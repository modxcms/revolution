<?php

namespace MODX\Revolution\Definition;

/**
 * Immutable runtime view of one compiled disk-native definition release.
 */
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
            // Share the compiled empty-catalog hash so a default-constructed registry and a
            // compiled empty release describe the same identity and honor the same caches.
            $this->catalog['release_hash'] = self::EMPTY_RELEASE_HASH;
        }
        foreach ($this->catalog['listeners'] as $key => $listener) {
            $this->listenersByEvent[$listener['event']][] = $key;
        }
    }

    /**
     * Build the transient script name for a disk definition's compiled include.
     */
    public static function scriptName(string $definitionKey, string $contentHash): string
    {
        return preg_replace('/[^A-Za-z0-9_]/', '_', $definitionKey) . '_' . $contentHash;
    }

    /**
     * Build the script cache key for a disk definition's compiled include.
     */
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

    /**
     * Whether a string is a compiled disk listener key exactly as
     * DefinitionManifestCompiler produces them:
     * `disk:<canonical/package>:listener:<listener-key>`.
     */
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

    /** @return array<string, array> */
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
