<?php

namespace MODX\Revolution\Definition;

use MODX\Revolution\modEvent;
use MODX\Revolution\modPlugin;
use MODX\Revolution\modPluginEvent;
use MODX\Revolution\modX;
use PDO;

/**
 * Answers "does a database twin reserve this identity?" for disk-native definitions.
 *
 * Every consumer of database collision facts — the runtime element resolver, the
 * event dispatcher, deployment tooling, and the Manager inspector — resolves
 * database identity through this collaborator so the answer is computed one way:
 *
 * - Policy-free: identities are read with raw prepared statements, never through
 *   policy-filtered object hydration, so ACLs cannot change collision precedence.
 * - Normalized matching: a database row matches a disk name exactly when both are
 *   equal under {@see DefinitionRegistry::normalizeName()} (ASCII lowercase).
 *   Queries compare `LOWER(name)` against `LOWER()` of the disk name and rows are
 *   re-filtered by the registry normalization in PHP, so binary and
 *   case-insensitive database collations behave identically and non-ASCII case
 *   variants remain distinct identities.
 * - Failure-aware: bulk methods return null and single-name methods return null
 *   when a query cannot be prepared or executed, so callers can distinguish an
 *   operational failure from a genuinely absent row. Absence is reported as
 *   `false`; presence carries row facts.
 * - Batched: bulk methods chunk their IN() lists so arbitrarily large registries
 *   never exceed placeholder limits, and never issue one query per definition.
 *
 * This class is stateless; memoization and cache lifecycles remain the
 * responsibility of each consumer.
 */
class DefinitionDatabaseFacts
{
    public const BULK_QUERY_CHUNK_SIZE = 500;

    private modX $modx;

    public function __construct(modX $modx)
    {
        $this->modx = $modx;
    }

    /**
     * Report database presence for the supplied element names.
     *
     * Presence is deliberately independent of disabled state: a disabled
     * database plugin still reserves its collision identity, so plugin rows
     * carry a `disabled` fact for diagnostics instead of being filtered out.
     *
     * @param class-string $class
     * @param array $names Raw (unnormalized) element names; non-strings are ignored.
     * @return array<string, false|array{name: string, disabled?: bool}>|null A
     * complete map keyed by normalized name — row facts when a twin exists,
     * false when it is genuinely absent — or null when a query itself failed.
     */
    public function elementPresence(string $class, array $names): ?array
    {
        $presence = [];
        $queryNames = [];
        foreach ($names as $name) {
            if (!is_string($name)) {
                continue;
            }
            $normalized = DefinitionRegistry::normalizeName($name);
            $presence[$normalized] ??= false;
            $queryNames[$normalized] ??= $name;
        }
        if (!$presence) {
            return [];
        }

        $withDisabled = $class === modPlugin::class || is_subclass_of($class, modPlugin::class);
        $columns = $withDisabled ? 'name, disabled' : 'name';
        $table = $this->modx->getTableName($class);
        foreach (array_chunk(array_values($queryNames), self::BULK_QUERY_CHUNK_SIZE) as $chunk) {
            [$expressions, $params] = $this->loweredPlaceholders('definition_name', $chunk);
            $statement = $this->executeQuery(
                "SELECT {$columns} FROM {$table} WHERE LOWER(name) IN (" . implode(', ', $expressions) . ')',
                $params
            );
            if ($statement === null) {
                return null;
            }
            while ($row = $statement->fetch(PDO::FETCH_ASSOC)) {
                $key = DefinitionRegistry::normalizeName((string) $row['name']);
                if (!array_key_exists($key, $presence)) {
                    // A database-collation superset match that the registry's
                    // ASCII normalization does not consider the same identity.
                    continue;
                }
                $disabled = $withDisabled && (bool) $row['disabled'];
                if ($presence[$key] === false) {
                    $facts = ['name' => (string) $row['name']];
                    if ($withDisabled) {
                        $facts['disabled'] = $disabled;
                    }
                    $presence[$key] = $facts;
                } elseif ($withDisabled) {
                    // Multiple rows can share one normalized identity on binary
                    // collations; the twin is only disabled when every row is.
                    $presence[$key]['disabled'] = $presence[$key]['disabled'] && $disabled;
                }
            }
        }

        return $presence;
    }

    /**
     * @param class-string $class
     * @return bool|null Whether a same-identity row exists, or null when the
     * query itself failed.
     */
    public function elementExists(string $class, string $name): ?bool
    {
        $table = $this->modx->getTableName($class);
        $statement = $this->executeQuery(
            "SELECT name FROM {$table} WHERE LOWER(name) = LOWER(:definition_name)",
            ['definition_name' => $name]
        );
        if ($statement === null) {
            return null;
        }

        return $this->matchingRows($statement, 'name', $name)->valid();
    }

    /**
     * @param array $eventNames Raw event names; non-strings are ignored.
     * @return array<string, false|array{name: string, service: mixed, groupname: string}>|null
     * A complete map keyed by normalized event name — a metadata snapshot when
     * the event row exists, false when it is genuinely absent — or null when a
     * query itself failed.
     */
    public function eventSnapshots(array $eventNames): ?array
    {
        $snapshots = [];
        $queryNames = [];
        foreach ($eventNames as $name) {
            if (!is_string($name)) {
                continue;
            }
            $normalized = DefinitionRegistry::normalizeName($name);
            $snapshots[$normalized] ??= false;
            $queryNames[$normalized] ??= $name;
        }
        if (!$snapshots) {
            return [];
        }

        $table = $this->modx->getTableName(modEvent::class);
        foreach (array_chunk(array_values($queryNames), self::BULK_QUERY_CHUNK_SIZE) as $chunk) {
            [$expressions, $params] = $this->loweredPlaceholders('event', $chunk);
            $statement = $this->executeQuery(
                "SELECT name, service, groupname FROM {$table} WHERE LOWER(name) IN ("
                    . implode(', ', $expressions) . ')',
                $params
            );
            if ($statement === null) {
                return null;
            }
            while ($row = $statement->fetch(PDO::FETCH_ASSOC)) {
                $key = DefinitionRegistry::normalizeName((string) $row['name']);
                if (!array_key_exists($key, $snapshots) || $snapshots[$key] !== false) {
                    continue;
                }
                $snapshots[$key] = $this->eventSnapshotFromRow($row);
            }
        }

        return $snapshots;
    }

    /**
     * @return array|false|null The event metadata snapshot, false when the row
     * is genuinely absent, or null when the query itself failed.
     */
    public function eventSnapshot(string $eventName): array|false|null
    {
        $table = $this->modx->getTableName(modEvent::class);
        $statement = $this->executeQuery(
            "SELECT name, service, groupname FROM {$table} WHERE LOWER(name) = LOWER(:event)",
            ['event' => $eventName]
        );
        if ($statement === null) {
            return null;
        }
        foreach ($this->matchingRows($statement, 'name', $eventName) as $row) {
            return $this->eventSnapshotFromRow($row);
        }

        return false;
    }

    /**
     * @param array $eventNames Raw event names; non-strings are ignored.
     * @return array<string, array<int, int>>|null A complete map of listener
     * priorities keyed by normalized event name then plugin id — empty for
     * events without bindings — or null when a query itself failed.
     */
    public function eventPriorities(array $eventNames): ?array
    {
        $priorities = [];
        $queryNames = [];
        foreach ($eventNames as $name) {
            if (!is_string($name)) {
                continue;
            }
            $normalized = DefinitionRegistry::normalizeName($name);
            $priorities[$normalized] ??= [];
            $queryNames[$normalized] ??= $name;
        }
        if (!$priorities) {
            return [];
        }

        $table = $this->modx->getTableName(modPluginEvent::class);
        foreach (array_chunk(array_values($queryNames), self::BULK_QUERY_CHUNK_SIZE) as $chunk) {
            [$expressions, $params] = $this->loweredPlaceholders('event', $chunk);
            $statement = $this->executeQuery(
                "SELECT event, pluginid, priority FROM {$table} WHERE LOWER(event) IN ("
                    . implode(', ', $expressions) . ')',
                $params
            );
            if ($statement === null) {
                return null;
            }
            while ($row = $statement->fetch(PDO::FETCH_ASSOC)) {
                $key = DefinitionRegistry::normalizeName((string) $row['event']);
                if (!array_key_exists($key, $priorities)) {
                    continue;
                }
                $priorities[$key][(int) $row['pluginid']] = (int) $row['priority'];
            }
        }

        return $priorities;
    }

    /**
     * @return array<int, int>|null The priorities keyed by plugin id, or null
     * when the query itself failed.
     */
    public function eventPrioritiesForEvent(string $eventName): ?array
    {
        $table = $this->modx->getTableName(modPluginEvent::class);
        $statement = $this->executeQuery(
            "SELECT event, pluginid, priority FROM {$table} WHERE LOWER(event) = LOWER(:event)",
            ['event' => $eventName]
        );
        if ($statement === null) {
            return null;
        }
        $priorities = [];
        foreach ($this->matchingRows($statement, 'event', $eventName) as $row) {
            $priorities[(int) $row['pluginid']] = (int) $row['priority'];
        }

        return $priorities;
    }

    /**
     * Prepare and execute one collision-facts query.
     *
     * @return object|null The executed statement, or null when preparing or
     * executing failed — the shared three-state failure signal.
     */
    private function executeQuery(string $sql, array $params)
    {
        $statement = $this->modx->prepare($sql);
        if (!$statement || !$statement->execute($params)) {
            return null;
        }

        return $statement;
    }

    /**
     * Yield only the rows whose {$column} value shares the disk name's identity
     * under {@see DefinitionRegistry::normalizeName()}, discarding
     * database-collation superset matches.
     *
     * @param object $statement An executed statement.
     * @return \Generator<int, array>
     */
    private function matchingRows($statement, string $column, string $name): \Generator
    {
        $normalized = DefinitionRegistry::normalizeName($name);
        while ($row = $statement->fetch(PDO::FETCH_ASSOC)) {
            if (DefinitionRegistry::normalizeName((string) $row[$column]) === $normalized) {
                yield $row;
            }
        }
    }

    /**
     * @return array{name: string, service: mixed, groupname: string}
     */
    private function eventSnapshotFromRow(array $row): array
    {
        return [
            'name' => $row['name'],
            'service' => $row['service'],
            'groupname' => (string) $row['groupname'],
        ];
    }

    /**
     * @return array{0: array<int, string>, 1: array<string, string>} LOWER()-wrapped
     * placeholder expressions and their raw-name bindings.
     */
    private function loweredPlaceholders(string $prefix, array $values): array
    {
        $expressions = [];
        $params = [];
        foreach ($values as $index => $value) {
            $placeholder = ':' . $prefix . '_' . $index;
            $expressions[] = 'LOWER(' . $placeholder . ')';
            $params[substr($placeholder, 1)] = $value;
        }

        return [$expressions, $params];
    }
}
