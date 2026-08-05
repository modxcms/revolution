<?php

namespace MODX\Revolution\Definition;

use MODX\Revolution\modChunk;
use MODX\Revolution\modPlugin;
use MODX\Revolution\modSnippet;
use RuntimeException;

/**
 * Compiles explicitly configured, trusted PHP definition manifests.
 */
class DefinitionManifestCompiler
{
    private const ELEMENT_NAME_PATTERN =
        '/\A(?![0-9]+\z)(?=.{1,50}\z)[A-Za-z0-9](?:[A-Za-z0-9._ -]*[A-Za-z0-9_-])?\z/';

    /**
     * The authoritative disk-native element type map, keyed by manifest
     * collection. Deployment tooling derives its type-to-class map from here.
     */
    public const ELEMENT_TYPES = [
        'chunks' => ['class' => modChunk::class, 'type' => 'chunk', 'php' => false],
        'plugins' => ['class' => modPlugin::class, 'type' => 'plugin', 'php' => true],
        'snippets' => ['class' => modSnippet::class, 'type' => 'snippet', 'php' => true],
    ];

    private const ELEMENT_FIELDS = [
        'file',
        'properties',
        'property_sets',
        'media_source',
    ];

    private const LISTENER_FIELDS = [
        'key',
        'event',
        'file',
        'service',
        'plugin',
        'priority',
        'contexts',
        'properties',
        'property_set',
    ];

    public function compile(array $manifestPaths): array
    {
        $definitions = [];
        $events = [];
        $listeners = [];
        $inventory = [];
        $knownPackages = [];
        $sourceCache = [];

        foreach ($manifestPaths as $manifestPath) {
            if (!is_string($manifestPath) || $manifestPath === '') {
                throw new RuntimeException('Every definition manifest path must be a non-empty string.');
            }

            $manifestFile = realpath($manifestPath);
            if ($manifestFile === false || !is_file($manifestFile) || !is_readable($manifestFile)) {
                throw new DefinitionManifestInputException("Definition manifest is not readable: {$manifestPath}");
            }

            $manifest = $this->loadManifest($manifestFile);
            if (!is_array($manifest)) {
                throw new RuntimeException("Definition manifest must return an array: {$manifestFile}");
            }

            if (($manifest['schema'] ?? null) !== 1) {
                throw new RuntimeException("Definition manifest must declare schema 1: {$manifestFile}");
            }
            $package = $manifest['package'] ?? '';
            if (!is_string($package) || !$this->isCanonicalPackage($package)) {
                throw new RuntimeException(
                    "Definition manifest must use a canonical package identifier: {$manifestFile}"
                );
            }
            if (isset($knownPackages[$package])) {
                throw new RuntimeException("A registry release allows one manifest per package identifier: {$package}");
            }
            $knownPackages[$package] = true;

            $root = isset($manifest['root']) && is_string($manifest['root']) ? realpath($manifest['root']) : false;
            if ($root === false || !is_dir($root)) {
                throw new DefinitionManifestInputException(
                    "Definition manifest has an invalid package root: {$manifestFile}"
                );
            }

            foreach (['elements', 'events', 'listeners'] as $collection) {
                if (isset($manifest[$collection]) && !is_array($manifest[$collection])) {
                    throw new RuntimeException("Definition manifest {$collection} must be an array: {$manifestFile}");
                }
            }
            $unknownManifestFields = array_diff(
                array_keys($manifest),
                ['schema', 'package', 'root', 'elements', 'events', 'listeners']
            );
            if ($unknownManifestFields) {
                throw new RuntimeException(
                    "Unsupported definition manifest field in {$manifestFile}: " . reset($unknownManifestFields)
                );
            }
            $unknownElementCollections = array_diff(
                array_keys($manifest['elements'] ?? []),
                array_keys(self::ELEMENT_TYPES)
            );
            if ($unknownElementCollections) {
                throw new RuntimeException(
                    "Unknown definition element collection in {$manifestFile}: " . reset($unknownElementCollections)
                );
            }

            $this->assertContained($manifestFile, $root, 'manifest');
            $manifestDigest = @hash_file('sha256', $manifestFile);
            if ($manifestDigest === false) {
                throw new DefinitionManifestInputException("Definition manifest could not be read: {$manifestFile}");
            }
            $inventory[$package]['manifest'] = [
                'path' => basename($manifestFile),
                'digest' => $manifestDigest,
            ];

            foreach (self::ELEMENT_TYPES as $manifestType => $type) {
                $items = $manifest['elements'][$manifestType] ?? [];
                if (!is_array($items)) {
                    throw new RuntimeException("Definition list {$manifestType} must be an array in {$manifestFile}");
                }

                foreach ($items as $name => $definition) {
                    if (!is_string($name) || !is_array($definition)) {
                        throw new RuntimeException(
                            "Every {$manifestType} definition must have a non-empty name and array body."
                        );
                    }
                    if (!preg_match(self::ELEMENT_NAME_PATTERN, $name)) {
                        throw new RuntimeException(
                            "Definition names must match the disk-native ASCII grammar, contain at most 50 bytes,"
                            . " start with a letter or digit, end without a space or dot,"
                            . " and must not be entirely numeric:"
                            . " {$manifestType}:{$name}"
                        );
                    }
                    $key = "disk:{$package}:{$type['type']}:{$name}";
                    $this->assertSupportedFields($definition, self::ELEMENT_FIELDS, 'element', $key);
                    $normalizedName = DefinitionRegistry::normalizeName($name);
                    if (isset($definitions[$type['class']][$normalizedName])) {
                        $existingPackage = $definitions[$type['class']][$normalizedName]['package'];
                        throw new RuntimeException(
                            "Duplicate disk definition for {$type['type']}:{$name} in packages"
                            . " {$existingPackage} and {$package}"
                        );
                    }

                    $sourceFile = $this->resolveSourceFile(
                        $definition['file'] ?? null,
                        $root,
                        "{$type['type']}:{$name}"
                    );
                    $extension = strtolower(pathinfo($sourceFile, PATHINFO_EXTENSION));
                    if ($type['php'] && $extension !== 'php') {
                        throw new RuntimeException(
                            "Trusted {$type['type']} source must use a .php file: {$sourceFile}"
                        );
                    }
                    if (!$type['php'] && $extension === 'php') {
                        throw new RuntimeException(
                            "Trusted {$type['type']} source must not use a .php file: {$sourceFile}"
                        );
                    }

                    $relativeFile = $this->relativePath($sourceFile, $root);
                    $source = $this->readSourceFile($sourceFile, $key, $sourceCache);
                    $content = $source['content'];
                    $digest = $source['digest'];
                    $inventory[$package]['files'][$relativeFile] = $digest;
                    $definitions[$type['class']][$normalizedName] = [
                        'key' => $key,
                        'source' => 'disk',
                        'package' => $package,
                        'manifest' => $manifestFile,
                        'root' => $root,
                        'file' => $sourceFile,
                        'relative_file' => $relativeFile,
                        'content_hash' => $digest,
                        'content' => $content,
                        'type' => $type['type'],
                        'class' => $type['class'],
                        'name' => $name,
                        'normalized_name' => $normalizedName,
                        'properties' => $this->validateProperties($definition['properties'] ?? [], $key),
                        'property_sets' => $this->validatePropertySets($definition['property_sets'] ?? [], $key),
                        'media_source' => $this->validateMediaSource($definition['media_source'] ?? null, $key),
                    ];
                }
            }

            foreach (($manifest['events'] ?? []) as $eventName => $metadata) {
                $this->assertEventName($eventName, $manifestFile);
                if (!is_array($metadata)) {
                    throw new RuntimeException("Event metadata must be an array for {$eventName}");
                }
                if (isset($events[$eventName])) {
                    throw new RuntimeException("Duplicate disk event declaration: {$eventName}");
                }
                $events[$eventName] = [
                    'name' => $eventName,
                    'package' => $package,
                    'manifest' => $manifestFile,
                    'metadata' => $this->validateEventMetadata($metadata, $eventName),
                ];
            }

            foreach (($manifest['listeners'] ?? []) as $listener) {
                if (!is_array($listener)) {
                    throw new RuntimeException("Every listener must be an array in {$manifestFile}");
                }
                $listenerKey = $listener['key'] ?? '';
                $eventName = $listener['event'] ?? '';
                if (!is_string($listenerKey) || !preg_match('/\A[A-Za-z0-9][A-Za-z0-9._-]*\z/', $listenerKey)) {
                    throw new RuntimeException("Every listener requires a stable key in {$manifestFile}");
                }
                $this->assertEventName($eventName, $manifestFile);
                $key = "disk:{$package}:listener:{$listenerKey}";
                if (isset($listeners[$key])) {
                    throw new RuntimeException("Duplicate disk listener key: {$key}");
                }
                $this->assertSupportedFields($listener, self::LISTENER_FIELDS, 'listener', $key);

                $hasFile = array_key_exists('file', $listener);
                $hasService = array_key_exists('service', $listener);
                if ($hasFile === $hasService) {
                    throw new RuntimeException("Listener {$key} must declare exactly one file or service target.");
                }
                if ($hasService && (!is_string($listener['service']) || $listener['service'] === '')) {
                    throw new RuntimeException("Listener {$key} must declare exactly one file or service target.");
                }
                $plugin = $listener['plugin'] ?? $listenerKey;
                if (!is_string($plugin) || $plugin === '') {
                    throw new RuntimeException("Listener {$key} plugin identity must be a non-empty string.");
                }
                $propertySet = $listener['property_set'] ?? null;
                if ($propertySet !== null && (!is_string($propertySet) || $propertySet === '')) {
                    throw new RuntimeException("Listener {$key} property_set must be a non-empty string.");
                }

                $file = null;
                $relativeFile = null;
                $content = null;
                if ($hasFile) {
                    $file = $this->resolveSourceFile($listener['file'], $root, "listener:{$listenerKey}");
                    if (strtolower(pathinfo($file, PATHINFO_EXTENSION)) !== 'php') {
                        throw new RuntimeException("Trusted listener source must use a .php file: {$file}");
                    }
                    $relativeFile = $this->relativePath($file, $root);
                    $source = $this->readSourceFile($file, $key, $sourceCache);
                    $content = $source['content'];
                    $inventory[$package]['files'][$relativeFile] = $source['digest'];
                }

                $priority = $listener['priority'] ?? 0;
                if (!is_int($priority) && !(is_string($priority) && preg_match('/^-?\d+$/', $priority))) {
                    throw new RuntimeException("Listener priority must be an integer for {$key}");
                }
                $contexts = $listener['contexts'] ?? [];
                if (!is_array($contexts)) {
                    throw new RuntimeException("Listener contexts must be non-empty strings for {$key}");
                }
                $invalidContexts = array_filter(
                    $contexts,
                    static fn($value) => !is_string($value) || $value === ''
                );
                if ($invalidContexts) {
                    throw new RuntimeException("Listener contexts must be non-empty strings for {$key}");
                }

                $listeners[$key] = [
                    'key' => $key,
                    'listener_key' => $listenerKey,
                    'source' => 'disk',
                    'package' => $package,
                    'manifest' => $manifestFile,
                    'event' => $eventName,
                    'priority' => (int) $priority,
                    'contexts' => array_values($contexts),
                    'file' => $file,
                    'relative_file' => $relativeFile,
                    'content' => $content,
                    'service' => $listener['service'] ?? null,
                    'plugin' => $plugin,
                    'property_set' => $propertySet,
                    'properties' => $this->validateProperties($listener['properties'] ?? [], $key),
                ];
            }
        }

        $this->validateListenerPropertySets($listeners, $definitions);

        ksort($definitions, SORT_STRING);
        foreach ($definitions as &$byName) {
            ksort($byName, SORT_STRING);
        }
        unset($byName);
        ksort($events, SORT_STRING);
        ksort($listeners, SORT_STRING);
        ksort($inventory, SORT_STRING);
        foreach ($inventory as &$packageInventory) {
            if (isset($packageInventory['files'])) {
                ksort($packageInventory['files'], SORT_STRING);
            }
        }
        unset($packageInventory);

        $hashInput = [
            'schema' => 1,
            'inventory' => $inventory,
            'definitions' => $this->portableDefinitions($definitions),
            'events' => $this->portableEvents($events),
            'listeners' => $this->portableListeners($listeners),
        ];
        $releaseHash = self::calculateReleaseHash($hashInput);

        return [
            'schema' => 1,
            'release_hash' => $releaseHash,
            'definitions' => $definitions,
            'events' => $events,
            'listeners' => $listeners,
            'inventory' => $inventory,
        ];
    }

    public static function calculateReleaseHash(array $catalog): string
    {
        $compiler = new self();
        $hashInput = [
            'schema' => $catalog['schema'] ?? null,
            'inventory' => $catalog['inventory'] ?? [],
            'definitions' => $compiler->portableDefinitions($catalog['definitions'] ?? []),
            'events' => $compiler->portableEvents($catalog['events'] ?? []),
            'listeners' => $compiler->portableListeners($catalog['listeners'] ?? []),
        ];

        return hash('sha256', DefinitionRegistry::HASH_DOMAIN . $compiler->canonicalEncode($hashInput));
    }

    private function isCanonicalPackage(string $package): bool
    {
        return (bool) preg_match('/\A[a-z0-9][a-z0-9._-]*(?:\/[a-z0-9][a-z0-9._-]*)+\z/', $package);
    }

    private function resolveSourceFile($relativePath, string $root, string $definition): string
    {
        if (!is_string($relativePath) || $relativePath === '' || $relativePath[0] === '/') {
            throw new RuntimeException("Definition source path must be relative for {$definition}");
        }
        $sourceFile = realpath($root . DIRECTORY_SEPARATOR . $relativePath);
        if ($sourceFile === false || !is_file($sourceFile) || !is_readable($sourceFile)) {
            throw new DefinitionManifestInputException(
                "Definition source is not readable for {$definition}: {$relativePath}"
            );
        }
        $this->assertContained($sourceFile, $root, $definition);

        return $sourceFile;
    }

    private function assertContained(string $path, string $root, string $label): void
    {
        $rootPrefix = rtrim($root, DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR;
        if (strncmp($path, $rootPrefix, strlen($rootPrefix)) !== 0) {
            throw new RuntimeException("Definition source is outside its package root for {$label}: {$path}");
        }
    }

    private function relativePath(string $path, string $root): string
    {
        return substr($path, strlen(rtrim($root, DIRECTORY_SEPARATOR)) + 1);
    }

    private function readSourceFile(string $path, string $key, array &$sourceCache): array
    {
        if (!isset($sourceCache[$path])) {
            $content = @file_get_contents($path);
            if ($content === false) {
                throw new DefinitionManifestInputException("Definition source could not be read for {$key}: {$path}");
            }
            $sourceCache[$path] = [
                'content' => $content,
                'digest' => hash('sha256', $content),
            ];
        }

        return $sourceCache[$path];
    }

    private function validateProperties($properties, string $key): array
    {
        if (!is_array($properties)) {
            throw new RuntimeException("Properties must be an array for {$key}");
        }

        return $properties;
    }

    private function assertSupportedFields(array $entry, array $supportedFields, string $entryType, string $key): void
    {
        $unknownFields = array_diff(array_keys($entry), $supportedFields);
        if ($unknownFields) {
            throw new RuntimeException(
                "Unsupported definition {$entryType} field for {$key}: " . reset($unknownFields)
            );
        }
    }

    private function validatePropertySets($propertySets, string $key): array
    {
        if (!is_array($propertySets)) {
            throw new RuntimeException("Property sets must be an array for {$key}");
        }
        foreach ($propertySets as $name => $properties) {
            if (!is_string($name) || $name === '' || !is_array($properties)) {
                throw new RuntimeException("Every property set must have a name and array value for {$key}");
            }
        }
        $normalizedNames = [];
        foreach ($propertySets as $name => $properties) {
            $normalizedName = DefinitionRegistry::normalizeName($name);
            if (isset($normalizedNames[$normalizedName])) {
                throw new RuntimeException(
                    "Duplicate property set names for {$key}: {$normalizedNames[$normalizedName]} and {$name}"
                );
            }
            $normalizedNames[$normalizedName] = $name;
        }

        return $propertySets;
    }

    private function validateMediaSource($mediaSource, string $key)
    {
        if ($mediaSource !== null) {
            throw new RuntimeException("media_source must be null or omitted for {$key}");
        }

        return null;
    }

    private function validateEventMetadata(array $metadata, string $eventName): array
    {
        $unknown = array_diff(array_keys($metadata), ['service', 'group']);
        if ($unknown) {
            throw new RuntimeException("Invalid event metadata field for {$eventName}: " . reset($unknown));
        }
        if (
            array_key_exists('service', $metadata)
            && (!is_string($metadata['service']) || !in_array($metadata['service'], ['all', 'web', 'mgr'], true))
        ) {
            throw new RuntimeException("Invalid event metadata service for {$eventName}");
        }
        if (array_key_exists('group', $metadata) && (!is_string($metadata['group']) || $metadata['group'] === '')) {
            throw new RuntimeException("Invalid event metadata group for {$eventName}");
        }

        return $metadata;
    }

    private function validateListenerPropertySets(array $listeners, array $definitions): void
    {
        foreach ($listeners as $listener) {
            if ($listener['property_set'] === null) {
                continue;
            }
            $definition = $definitions[modPlugin::class][
                DefinitionRegistry::normalizeName($listener['plugin'])
            ] ?? null;
            if (!$definition || $definition['package'] !== $listener['package']) {
                throw new RuntimeException(
                    "Listener {$listener['key']} property_set requires a same-package disk plugin definition"
                );
            }
            if (
                DefinitionRegistry::findPropertySet(
                    $definition['property_sets'],
                    $listener['property_set']
                ) !== null
            ) {
                continue;
            }
            throw new RuntimeException(
                "Listener {$listener['key']} references unknown property_set: {$listener['property_set']}"
            );
        }
    }

    private function assertEventName($eventName, string $manifestFile): void
    {
        if (!is_string($eventName) || !preg_match('/\A[A-Za-z][A-Za-z0-9_.-]*\z/', $eventName)) {
            throw new RuntimeException("Invalid event name in {$manifestFile}");
        }
    }

    private function loadManifest(string $manifestFile): mixed
    {
        return (static function (string $path) {
            return require $path;
        })($manifestFile);
    }

    private function portableDefinitions(array $definitions): array
    {
        foreach ($definitions as &$byName) {
            foreach ($byName as &$definition) {
                unset($definition['manifest'], $definition['root'], $definition['file']);
            }
            unset($definition);
        }
        unset($byName);

        return $definitions;
    }

    private function portableListeners(array $listeners): array
    {
        foreach ($listeners as &$listener) {
            unset($listener['file'], $listener['manifest']);
        }
        unset($listener);

        return $listeners;
    }

    private function portableEvents(array $events): array
    {
        foreach ($events as &$event) {
            unset($event['manifest']);
        }
        unset($event);

        return $events;
    }

    /**
     * Encode manifest values with explicit type and byte-length boundaries.
     *
     * JSON cannot represent arbitrary PHP source bytes. This encoding remains deterministic for
     * non-UTF-8 strings while distinguishing lists, maps, scalar types, and map-key types.
     *
     * @param mixed $value
     */
    private function canonicalEncode($value): string
    {
        if ($value === null) {
            return 'n';
        }
        if (is_bool($value)) {
            return $value ? 'b1' : 'b0';
        }
        if (is_int($value)) {
            $integer = (string) $value;

            return 'i' . strlen($integer) . ':' . $integer;
        }
        if (is_float($value)) {
            if (!is_finite($value)) {
                throw new RuntimeException('Definition catalog cannot hash non-finite floating-point values.');
            }

            return 'f' . pack('E', $value);
        }
        if (is_string($value)) {
            return 's' . strlen($value) . ':' . $value;
        }
        if (!is_array($value)) {
            throw new RuntimeException('Definition catalog contains a value that cannot be release-hashed.');
        }

        if (array_is_list($value)) {
            $encoded = 'l' . count($value) . ':';
            foreach ($value as $item) {
                $encoded .= $this->canonicalEncode($item);
            }

            return $encoded;
        }

        $entries = [];
        foreach ($value as $key => $item) {
            $encodedKey = $this->canonicalEncode($key);
            $entries[] = [$encodedKey, $this->canonicalEncode($item)];
        }
        usort($entries, static function (array $left, array $right): int {
            return strcmp($left[0], $right[0]);
        });

        $encoded = 'm' . count($entries) . ':';
        foreach ($entries as [$key, $item]) {
            $encoded .= $key . $item;
        }

        return $encoded;
    }
}
