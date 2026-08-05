<?php

namespace MODX\Revolution\Definition;

use MODX\Revolution\modEvent;
use MODX\Revolution\modPlugin;
use MODX\Revolution\modX;
use InvalidArgumentException;

/**
 * Produces read-only Manager-safe records from the active disk definition registry.
 *
 * This deliberately reads database candidates through the policy-free
 * DefinitionDatabaseFacts collaborator. It neither resolves elements nor hydrates
 * policy-filtered objects, so collision reporting is independent of the requesting
 * user's element ACLs.
 */
class DefinitionRegistryInspector
{
    private const KINDS = [
        'elements' => 'definition',
        'events' => 'event',
        'listeners' => 'listener',
    ];
    private const SORT_FIELDS = ['key', 'kind', 'type', 'name', 'package', 'collision_state'];

    private modX $modx;
    private DefinitionRegistry $registry;
    private DefinitionDatabaseFacts $databaseFacts;

    public function __construct(modX $modx, ?DefinitionRegistry $registry = null)
    {
        $this->modx = $modx;
        $this->registry = $registry ?? $modx->getDefinitionRegistry();
        $this->databaseFacts = new DefinitionDatabaseFacts($modx);
    }

    /**
     * @return array{release_hash: string, total: int, results: array<int, array<string, mixed>>}
     */
    public function list(array $options = []): array
    {
        $kind = $options['kind'] ?? null;
        if (array_key_exists('kind', $options)) {
            if (!is_string($kind) || !isset(self::KINDS[$kind])) {
                throw new InvalidArgumentException('Unsupported definition registry kind');
            }
            $kind = self::KINDS[$kind];
        }
        $records = $this->records(is_string($kind) ? $kind : null);
        $type = $options['type'] ?? null;
        $package = $options['package'] ?? null;
        $query = DefinitionRegistry::normalizeName(trim((string) ($options['query'] ?? '')));
        foreach ($records as $index => $record) {
            if (!$this->matches($record, $type, $package, $query)) {
                unset($records[$index]);
            }
        }
        $records = $this->resolveCollisions(array_values($records));

        $sort = (string) ($options['sort'] ?? 'key');
        $sort = in_array($sort, self::SORT_FIELDS, true) ? $sort : 'key';
        $direction = strtoupper((string) ($options['dir'] ?? 'ASC')) === 'DESC' ? -1 : 1;
        usort($records, static function (array $left, array $right) use ($sort, $direction): int {
            $comparison = strcmp((string) $left[$sort], (string) $right[$sort]);
            if ($comparison === 0) {
                $comparison = strcmp($left['key'], $right['key']);
            }

            return $comparison * $direction;
        });

        $total = count($records);
        $start = max(0, (int) ($options['start'] ?? 0));
        $limit = (int) ($options['limit'] ?? 20);
        if ($limit < 1) {
            $limit = 20;
        }
        $records = array_slice($records, $start, $limit);

        return ['release_hash' => $this->registry->getReleaseHash(), 'total' => $total, 'results' => $records];
    }

    private function matches(array $record, $type, $package, string $query): bool
    {
        if (is_string($type) && $type !== '' && $record['type'] !== $type) {
            return false;
        }
        if (is_string($package) && $package !== '' && $record['package'] !== $package) {
            return false;
        }
        if ($query === '') {
            return true;
        }

        return str_contains(DefinitionRegistry::normalizeName(implode(' ', [
            $record['key'], $record['kind'], $record['type'], $record['name'], $record['package'],
        ])), $query);
    }

    /** @return array<int, array<string, mixed>> */
    private function records(?string $kind): array
    {
        $records = [];
        if ($kind === null || $kind === 'definition') {
            foreach ($this->registry->getDefinitions() as $definitions) {
                foreach ($definitions as $definition) {
                    $records[] = [
                        'key' => $definition['key'],
                        'kind' => 'definition',
                        'type' => $definition['type'],
                        'name' => $definition['name'],
                        'package' => $definition['package'],
                        'manifest' => $this->registry->getManifestPath($definition['package']),
                        'source_file' => $definition['relative_file'] ?? null,
                        'event' => null,
                        'priority' => null,
                        'contexts' => [],
                        'target' => null,
                        '_database_class' => $definition['class'],
                        '_database_name' => $definition['name'],
                    ];
                }
            }
        }
        if ($kind === null || $kind === 'event') {
            $events = [];
            foreach ($this->registry->getEvents() as $event) {
                $events[$event['package'] . ':' . $event['name']] = $event;
            }
            foreach ($this->registry->getAllListeners() as $listener) {
                $eventKey = $listener['package'] . ':' . $listener['event'];
                $events[$eventKey] ??= [
                    'name' => $listener['event'],
                    'package' => $listener['package'],
                ];
            }
            foreach ($events as $event) {
                $records[] = [
                    'key' => 'disk:' . $event['package'] . ':event:' . $event['name'],
                    'kind' => 'event',
                    'type' => 'event',
                    'name' => $event['name'],
                    'package' => $event['package'],
                    'manifest' => $this->registry->getManifestPath($event['package']),
                    'source_file' => null,
                    'event' => $event['name'],
                    'priority' => null,
                    'contexts' => [],
                    'target' => null,
                    '_database_class' => modEvent::class,
                    '_database_name' => $event['name'],
                ];
            }
        }
        if ($kind === null || $kind === 'listener') {
            foreach ($this->registry->getAllListeners() as $listener) {
                $records[] = [
                    'key' => $listener['key'],
                    'kind' => 'listener',
                    'type' => 'listener',
                    'name' => $listener['listener_key'],
                    'package' => $listener['package'],
                    'manifest' => $this->registry->getManifestPath($listener['package']),
                    'source_file' => $listener['relative_file'] ?? null,
                    'event' => $listener['event'],
                    'priority' => $listener['priority'] ?? 0,
                    'contexts' => $listener['contexts'] ?? [],
                    'target' => $listener['service'] ?? ($listener['relative_file'] ?? null),
                    '_database_class' => modPlugin::class,
                    '_database_name' => $listener['plugin'],
                ];
            }
        }

        return $records;
    }

    /** @return array<int, array<string, mixed>> */
    private function resolveCollisions(array $records): array
    {
        $databasePresence = [];
        $namesByClass = [];
        foreach ($records as $record) {
            $name = $record['_database_name'];
            $namesByClass[$record['_database_class']][DefinitionRegistry::normalizeName($name)] = $name;
        }
        foreach ($namesByClass as $class => $names) {
            $classPresence = $this->databaseFacts->elementPresence($class, array_values($names));
            if ($classPresence === null) {
                throw new \RuntimeException('Could not inspect database definition collisions.');
            }
            foreach ($classPresence as $normalizedName => $facts) {
                if ($facts !== false) {
                    $databasePresence[$class . ':' . $normalizedName] = [
                        'disabled' => (bool) ($facts['disabled'] ?? false),
                    ];
                }
            }
        }

        foreach ($records as &$record) {
            $key = $record['_database_class'] . ':'
                . DefinitionRegistry::normalizeName($record['_database_name']);
            $databaseExists = isset($databasePresence[$key]);
            if (!$databaseExists) {
                $state = 'disk-only';
            } elseif ($record['kind'] === 'event') {
                $state = 'database-shared';
            } elseif ($record['kind'] === 'listener') {
                $state = 'disk-suppressed-by-database';
            } else {
                $state = 'database-default';
            }
            unset($record['_database_class'], $record['_database_name']);
            $record['collision'] = $databaseExists;
            if ($record['kind'] === 'listener') {
                $record['database_disabled'] = $databaseExists
                    ? $databasePresence[$key]['disabled']
                    : null;
            }
            $record['collision_state'] = $state;
            $record += [
                'source' => 'disk',
                'release_hash' => $this->registry->getReleaseHash(),
            ];
        }
        unset($record);

        return $records;
    }
}
