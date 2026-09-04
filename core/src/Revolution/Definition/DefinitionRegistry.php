<?php

namespace MODX\Revolution\Definition;

use RuntimeException;

class DefinitionRegistry
{
    /**
     * Domain-separation prefix for every release hash computed for this schema.
     */
    public const HASH_DOMAIN = "modx-disk-native-registry-v2\0";

    /**
     * The release hash of a compiled empty catalog. Pinned so bootstrapping a
     * site without manifests never has to canonically encode and hash an empty
     * structure; a test asserts it equals DefinitionManifestCompiler::compile([]).
     */
    public const EMPTY_RELEASE_HASH = '75b4b9f96bff2bdf49a8884ada47eb4b640075410b2849056e1e35fd04200120';

    private array $catalog;
    private array $listenersByEvent = [];
    private ?array $allEventNames = null;
    private array $eventNamesByContext = [];

    public function __construct(array $catalog = [])
    {
        $this->catalog = $catalog + [
            'schema' => 1,
            'definitions' => [],
            'events' => [],
            'listeners' => [],
            'inventory' => [],
        ];
        if (!isset($this->catalog['release_hash'])) {
            $this->catalog['release_hash'] = self::EMPTY_RELEASE_HASH;
        }
        $this->catalog = self::normalizeCatalog($this->catalog);
        foreach ($this->catalog['listeners'] as $key => $listener) {
            $this->listenersByEvent[$listener['event']][] = $key;
        }
    }

    /**
     * Reject malformed external catalog arrays before runtime consumers can turn
     * a missing nested field into a misleading lookup or dispatch result.
     */
    public static function assertValidCatalog(array $catalog, bool $requireCompiledFields = false): void
    {
        foreach (['definitions', 'events', 'listeners', 'inventory'] as $collection) {
            if (!isset($catalog[$collection]) || !is_array($catalog[$collection])) {
                throw new RuntimeException("Definition registry {$collection} must be an array.");
            }
        }
        if (!is_int($catalog['schema'] ?? null) || $catalog['schema'] !== 1) {
            throw new RuntimeException('Definition registry must declare schema 1.');
        }
        if (!is_string($catalog['release_hash'] ?? null)) {
            throw new RuntimeException('Definition registry release_hash must be a string.');
        }
        foreach ($catalog['definitions'] as $class => $definitions) {
            if (!is_string($class) || !is_array($definitions)) {
                throw new RuntimeException('Definition registry definitions must be grouped by class arrays.');
            }
            foreach ($definitions as $name => $definition) {
                if (!is_string($name) || !is_array($definition)) {
                    throw new RuntimeException('Definition registry definition entries must be arrays keyed by name.');
                }
                self::assertStringFields(
                    $definition,
                    ['key', 'class', 'type', 'name', 'package'],
                    'definition'
                );
                if ($definition['class'] !== $class || self::normalizeName($definition['name']) !== $name) {
                    throw new RuntimeException(
                        'Definition registry definition indexes must match their class and name.'
                    );
                }
                if (($definition['source'] ?? null) === 'disk' || $requireCompiledFields) {
                    self::assertStringFields(
                        $definition,
                        [
                            'source',
                            'manifest',
                            'root',
                            'file',
                            'relative_file',
                            'content_hash',
                            'content',
                            'normalized_name',
                        ],
                        'compiled definition'
                    );
                    if (
                        $definition['source'] !== 'disk'
                        || $definition['normalized_name'] !== $name
                        || !is_array($definition['properties'] ?? null)
                        || !is_array($definition['property_sets'] ?? null)
                        || ($definition['media_source'] ?? null) !== null
                    ) {
                        throw new RuntimeException('Definition registry compiled definitions have an invalid shape.');
                    }
                }
            }
        }
        foreach ($catalog['events'] as $name => $event) {
            if (!is_string($name) || !is_array($event)) {
                throw new RuntimeException('Definition registry event entries must be arrays keyed by name.');
            }
            if (isset($event['name']) && (!is_string($event['name']) || $event['name'] !== $name)) {
                throw new RuntimeException('Definition registry event indexes must match event names.');
            }
            if (isset($event['metadata']) && !is_array($event['metadata'])) {
                throw new RuntimeException('Definition registry event metadata must be an array.');
            }
            if ($requireCompiledFields) {
                self::assertStringFields($event, ['name', 'package', 'manifest'], 'compiled event');
                if (!is_array($event['metadata'] ?? null)) {
                    throw new RuntimeException('Definition registry compiled event metadata must be an array.');
                }
            }
        }
        foreach ($catalog['listeners'] as $key => $listener) {
            if (
                !is_string($key)
                || !is_array($listener)
                || !is_string($listener['event'] ?? null)
                || !is_array($listener['contexts'] ?? null)
            ) {
                throw new RuntimeException(
                    'Definition registry listeners must have string events and array contexts.'
                );
            }
            self::assertStringFields($listener, ['key', 'listener_key', 'package', 'plugin'], 'listener');
            if (
                $listener['key'] !== $key
                || !is_int($listener['priority'] ?? null)
                || (!is_null($listener['service'] ?? null) && !is_string($listener['service']))
                || (
                    isset($listener['property_set'])
                    && !is_string($listener['property_set'])
                    && $listener['property_set'] !== null
                )
                || (isset($listener['properties']) && !is_array($listener['properties']))
            ) {
                throw new RuntimeException('Definition registry listener entries have an invalid shape.');
            }
            foreach ($listener['contexts'] as $context) {
                if (!is_string($context) || $context === '') {
                    throw new RuntimeException('Definition registry listener contexts must contain non-empty strings.');
                }
            }
            if ($requireCompiledFields) {
                self::assertStringFields($listener, ['source', 'manifest'], 'compiled listener');
                if (
                    $listener['source'] !== 'disk'
                    || !is_array($listener['properties'] ?? null)
                ) {
                    throw new RuntimeException('Definition registry compiled listeners have an invalid shape.');
                }
                self::assertCompiledListenerTarget($listener);
            }
        }
        foreach ($catalog['inventory'] as $package => $inventory) {
            if (!is_string($package) || !is_array($inventory)) {
                throw new RuntimeException('Definition registry inventory entries must be package arrays.');
            }
            if (
                $requireCompiledFields
                && (
                    !is_array($inventory['manifest'] ?? null)
                    || !is_string($inventory['manifest']['path'] ?? null)
                    || !is_string($inventory['manifest']['digest'] ?? null)
                )
            ) {
                throw new RuntimeException('Definition registry compiled inventory entries have an invalid shape.');
            }
        }
    }

    private static function normalizeCatalog(array $catalog): array
    {
        self::assertValidCatalog($catalog);
        foreach ($catalog['events'] as $name => $event) {
            if (is_array($event)) {
                $catalog['events'][$name] = $event + ['name' => $name, 'metadata' => []];
            }
        }
        foreach ($catalog['listeners'] as $key => $listener) {
            if (is_array($listener)) {
                $catalog['listeners'][$key] = $listener + ['properties' => []];
            }
        }
        return $catalog;
    }

    private static function assertStringFields(array $entry, array $fields, string $entryType): void
    {
        foreach ($fields as $field) {
            if (!is_string($entry[$field] ?? null) || $entry[$field] === '') {
                throw new RuntimeException("Definition registry {$entryType} {$field} must be a non-empty string.");
            }
        }
    }

    private static function assertCompiledListenerTarget(array $listener): void
    {
        if (
            !array_key_exists('file', $listener)
            || !array_key_exists('relative_file', $listener)
            || !array_key_exists('content', $listener)
            || !array_key_exists('service', $listener)
        ) {
            throw new RuntimeException('Definition registry compiled listener target has an invalid shape.');
        }
        $fileTarget = is_string($listener['file'] ?? null)
            && $listener['file'] !== ''
            && is_string($listener['relative_file'] ?? null)
            && $listener['relative_file'] !== ''
            && is_string($listener['content'] ?? null);
        $serviceTarget = is_string($listener['service'] ?? null) && $listener['service'] !== '';
        $service = $listener['service'];
        $emptyFileTarget = ($listener['file'] ?? null) === null
            && ($listener['relative_file'] ?? null) === null
            && ($listener['content'] ?? null) === null;

        if (($fileTarget && $service === null) || ($emptyFileTarget && $serviceTarget)) {
            return;
        }

        throw new RuntimeException('Definition registry compiled listener target has an invalid shape.');
    }

    public static function scriptName(string $definitionKey, string $contentHash): string
    {
        return preg_replace('/[^A-Za-z0-9_]/', '_', $definitionKey) . '_' . $contentHash;
    }

    public static function scriptCacheKey(string $definitionKey, string $contentHash): string
    {
        return 'disk-native/' . str_replace(':', '/', $definitionKey) . '/' . $contentHash;
    }

    public static function findPropertySet(array $propertySets, string $setName): ?array
    {
        $normalizedName = self::normalizeName($setName);
        foreach ($propertySets as $declaredName => $properties) {
            if (self::normalizeName((string) $declaredName) === $normalizedName) {
                return $properties;
            }
        }

        return null;
    }

    public static function normalizeName(string $name): string
    {
        return strtr($name, 'ABCDEFGHIJKLMNOPQRSTUVWXYZ', 'abcdefghijklmnopqrstuvwxyz');
    }

    public static function isListenerKey(string $key): bool
    {
        return (bool) preg_match(
            '/\Adisk:[a-z0-9][a-z0-9._-]*(?:\/[a-z0-9][a-z0-9._-]*)+:listener:[A-Za-z0-9][A-Za-z0-9._-]*\z/',
            $key
        );
    }

    public function isEmpty(): bool
    {
        return !$this->catalog['definitions']
            && !$this->catalog['events']
            && !$this->catalog['listeners'];
    }

    public function getReleaseHash(): string
    {
        return $this->catalog['release_hash'];
    }

    public function getDefinition(string $class, string $name): ?array
    {
        return $this->catalog['definitions'][$class][self::normalizeName($name)] ?? null;
    }

    public function getDefinitions(): array
    {
        return $this->catalog['definitions'];
    }

    public function getEvents(): array
    {
        return $this->catalog['events'];
    }

    public function getManifestPath(string $package): ?string
    {
        $path = $this->catalog['inventory'][$package]['manifest']['path'] ?? null;

        return is_string($path) ? $path : null;
    }

    public function getListeners(string $eventName, string $contextKey): array
    {
        $listeners = [];
        foreach (($this->listenersByEvent[$eventName] ?? []) as $key) {
            $listener = $this->catalog['listeners'][$key];
            if ($listener['contexts'] && !in_array($contextKey, $listener['contexts'], true)) {
                continue;
            }
            $listeners[$key] = $listener;
        }

        return $listeners;
    }

    public function getListener(string $key): ?array
    {
        return $this->catalog['listeners'][$key] ?? null;
    }

    public function getAllListeners(): array
    {
        return $this->catalog['listeners'];
    }

    public function getEventNames(?string $contextKey = null): array
    {
        if ($contextKey === null) {
            return $this->allEventNames ??= $this->buildEventNames(null);
        }

        return $this->eventNamesByContext[$contextKey] ??= $this->buildEventNames($contextKey);
    }

    private function buildEventNames(?string $contextKey): array
    {
        $events = array_fill_keys(array_keys($this->catalog['events']), true);
        foreach ($this->listenersByEvent as $eventName => $listenerKeys) {
            if ($contextKey === null) {
                $events[$eventName] = true;
                continue;
            }
            foreach ($listenerKeys as $key) {
                $listener = $this->catalog['listeners'][$key];
                if (!$listener['contexts'] || in_array($contextKey, $listener['contexts'], true)) {
                    $events[$eventName] = true;
                    break;
                }
            }
        }

        return array_keys($events);
    }

    public function hasDiskEvent(string $eventName): bool
    {
        if (isset($this->catalog['events'][$eventName])) {
            return true;
        }
        return isset($this->listenersByEvent[$eventName]);
    }
}
