<?php

namespace MODX\Revolution\Definition;

use MODX\Revolution\modContext;
use MODX\Revolution\modEvent;
use MODX\Revolution\modPlugin;
use MODX\Revolution\modScript;
use MODX\Revolution\modX;
use RuntimeException;
use Throwable;

class DefinitionRegistryDeployment
{
    private const REGISTRY_CACHE_PARTITION = 'definition_registry';
    private const RESET_CACHE_PARTITIONS = [
        self::REGISTRY_CACHE_PARTITION,
        'scripts',
        'default',
        'context_settings',
    ];

    private array $config;
    private modX $modx;
    private DefinitionManifestCompiler $compiler;
    private DefinitionRegistryArtifact $artifact;
    private DefinitionDatabaseFacts $databaseFacts;

    public function __construct(
        array $config,
        modX $modx,
        ?DefinitionManifestCompiler $compiler = null,
        ?DefinitionRegistryArtifact $artifact = null
    ) {
        $this->config = $config;
        $this->modx = $modx;
        $this->compiler = $compiler ?? new DefinitionManifestCompiler();
        $this->artifact = $artifact ?? new DefinitionRegistryArtifact();
        $this->databaseFacts = new DefinitionDatabaseFacts($modx);
    }

    public function validate(): array
    {
        $this->assertDatabaseAvailable();
        $catalog = $this->compileCatalog();
        $dispatcher = $this->validateRuntime($catalog, 3);

        return $this->releaseSummary($catalog) + ['diagnostics' => $this->diagnostics($catalog, $dispatcher)];
    }

    public function compile(): array
    {
        $this->assertDatabaseAvailable();
        $catalog = $this->compileCatalog();
        $dispatcher = $this->validateRuntime($catalog, 3);
        $directory = $this->artifactDirectory();
        $path = $directory . DIRECTORY_SEPARATOR . $catalog['release_hash'] . '.php';

        try {
            $created = $this->artifact->writeImmutable($path, $catalog);
        } catch (DefinitionRegistryArtifactConflictException $exception) {
            throw $this->failure($exception->getMessage(), 5, 'artifact-conflict');
        } catch (RuntimeException $exception) {
            throw $this->failure($exception->getMessage(), 4, 'artifact-write-failed');
        }

        return $this->releaseSummary($catalog) + [
            'artifact' => $path,
            'created' => $created,
            'diagnostics' => $this->diagnostics($catalog, $dispatcher),
        ];
    }

    public function hash(): array
    {
        $this->assertDatabaseAvailable();
        $catalog = $this->compileCatalog();
        $dispatcher = $this->validateRuntime($catalog, 3);
        $activeHash = null;
        $activeArtifact = $this->activeArtifact(false);
        if ($activeArtifact !== null) {
            $activeHash = $this->loadArtifact($activeArtifact)['release_hash'];
        }
        $warmedHash = $this->modx->getCacheManager()->get(
            'release-hash',
            $this->cachePartitionOptions(self::REGISTRY_CACHE_PARTITION)
        );
        if (!is_string($warmedHash)) {
            $warmedHash = null;
        }

        return [
            'active_artifact' => $activeArtifact,
            'active_hash' => $activeHash,
            'matches_active' => $activeHash === null ? null : hash_equals($catalog['release_hash'], $activeHash),
            'matches_warmed' => $warmedHash === null ? null : hash_equals($catalog['release_hash'], $warmedHash),
            'release_hash' => $catalog['release_hash'],
            'warmed_hash' => $warmedHash,
            'diagnostics' => $this->diagnostics($catalog, $dispatcher),
        ];
    }

    public function list(?string $package = null, ?string $type = null): array
    {
        $this->assertDatabaseAvailable();
        if ($type !== null && !isset(self::elementTypes()[$type])) {
            throw $this->failure("Unsupported definition type: {$type}", 2, 'invalid-type');
        }

        [$catalog, $dispatcher] = $this->selectedCatalog();
        $definitions = [];
        foreach ($catalog['definitions'] as $class => $byName) {
            foreach ($byName as $definition) {
                if (
                    ($package !== null && $definition['package'] !== $package)
                    || ($type !== null && $definition['type'] !== $type)
                ) {
                    continue;
                }
                $definitions[] = [
                    'content_hash' => $definition['content_hash'],
                    'file' => $definition['relative_file'],
                    'key' => $definition['key'],
                    'name' => $definition['name'],
                    'package' => $definition['package'],
                    'type' => $definition['type'],
                ];
            }
        }
        usort($definitions, static fn(array $left, array $right): int => strcmp($left['key'], $right['key']));

        $events = [];
        foreach ($catalog['events'] as $event) {
            if ($package === null || $event['package'] === $package) {
                $events[] = [
                    'metadata' => $event['metadata'],
                    'name' => $event['name'],
                    'package' => $event['package'],
                    'manifest' => basename($event['manifest']),
                ];
            }
        }
        usort($events, static function (array $left, array $right): int {
            $comparison = strcmp($left['package'], $right['package']);

            return $comparison !== 0 ? $comparison : strcmp($left['name'], $right['name']);
        });

        $listeners = [];
        foreach ($catalog['listeners'] as $listener) {
            if ($package === null || $listener['package'] === $package) {
                $listeners[] = [
                    'event' => $listener['event'],
                    'key' => $listener['key'],
                    'listener_key' => $listener['listener_key'],
                    'package' => $listener['package'],
                    'plugin' => $listener['plugin'],
                    'priority' => $listener['priority'],
                    'target' => $listener['service'] !== null ? 'service' : 'file',
                ];
            }
        }
        usort($listeners, static fn(array $left, array $right): int => strcmp($left['key'], $right['key']));

        $packages = array_keys($catalog['inventory']);
        if ($package !== null) {
            $packages = array_values(array_filter($packages, static fn(string $item): bool => $item === $package));
        }
        sort($packages, SORT_STRING);

        return [
            'definitions' => $definitions,
            'events' => $events,
            'listeners' => $listeners,
            'packages' => $packages,
            'release_hash' => $catalog['release_hash'],
            'diagnostics' => $this->diagnostics($catalog, $dispatcher),
        ];
    }

    public function explain(?string $key, ?string $type, ?string $name): array
    {
        $this->assertDatabaseAvailable();
        [$catalog, $dispatcher] = $this->selectedCatalog();
        $registry = new DefinitionRegistry($catalog);
        if ($key !== null) {
            $definition = $this->definitionByKey($catalog, $key);
            $class = $definition['class'];
            $name = $definition['name'];
        } else {
            [$class, $name, $definition] = $this->definitionByPublicIdentity($registry, $type, $name);
        }
        $databasePresence = $this->databasePresence($class, [$name]);
        $databaseExists = $databasePresence[DefinitionRegistry::normalizeName($name)] ?? false;
        $resolver = new ElementResolver($this->modx, $registry);
        $element = $resolver->getElement($class, $name);
        $decision = $resolver->getLastDecision();
        if ($databaseExists && ($decision['reason'] ?? '') === 'not-found') {
            $decision['reason'] = 'database-load-denied';
        }

        return [
            'candidates' => [
                'database' => $databaseExists,
                'disk' => $definition !== null,
            ],
            'decision' => $decision,
            'definition' => $definition === null ? null : $this->definitionProvenance($definition),
            'release_hash' => $catalog['release_hash'],
            'winner' => $element ? $element->getDefinitionMetadata()['source'] : null,
            'diagnostics' => $this->diagnostics($catalog, $dispatcher),
        ];
    }

    public function warm(): array
    {
        $this->assertDatabaseAvailable();
        $activeArtifact = $this->activeArtifact(true);
        $expected = $this->compileCatalog();
        $active = $this->loadArtifact($activeArtifact);
        if (!hash_equals($expected['release_hash'], $active['release_hash'])) {
            throw $this->failure(
                'The active definition artifact does not match the configured release hash.',
                5,
                'active-hash-mismatch'
            );
        }
        if (!DefinitionRegistryArtifact::isContentAddressedBasename($activeArtifact, $active['release_hash'])) {
            throw $this->failure(
                'The active definition artifact path is not content-addressed by its release hash.',
                5,
                'active-path-not-content-addressed'
            );
        }

        $dispatcher = $this->validateRuntime($active, 5);
        $this->modx->setDefinitionRegistry(new DefinitionRegistry($active));
        $contexts = $this->contextKeys();
        $cacheManager = $this->modx->getCacheManager();
        $this->primePartitionForClear('resource');
        $partialResourceClear = $this->modx->getOption('cache_resource_clear_partial', null, false);
        $this->modx->setOption('cache_resource_clear_partial', false);
        try {
            $resourceResults = [];
            $resourceSuccess = $cacheManager->refresh([
                'resource' => ['contexts' => array_diff($contexts, ['mgr'])],
            ], $resourceResults);
        } finally {
            $this->modx->setOption('cache_resource_clear_partial', $partialResourceClear);
        }
        if (!$resourceSuccess || !array_key_exists('resource', $resourceResults)) {
            throw $this->failure('Could not clear the full resource cache.', 5, 'resource-cache-clear-failed');
        }

        foreach (self::RESET_CACHE_PARTITIONS as $partition) {
            $this->resetCachePartition($partition);
        }

        $contextResults = [];
        $contextSuccess = $cacheManager->refresh([
            'context_settings' => ['contexts' => $contexts],
        ], $contextResults);
        $warmedContexts = $contextResults['context_settings'] ?? null;
        if (!$contextSuccess || !is_array($warmedContexts) || in_array(false, $warmedContexts, true)) {
            throw $this->failure('Could not refresh definition runtime caches.', 5, 'runtime-cache-warm-failed');
        }
        $contextCacheOptions = $this->cachePartitionOptions('context_settings');
        foreach ($contexts as $context) {
            $cachedContext = $cacheManager->get("{$context}/context", $contextCacheOptions);
            if (!is_array($cachedContext)) {
                throw $this->failure(
                    "Could not verify the warmed context cache for {$context}.",
                    5,
                    'context-cache-warm-failed'
                );
            }
        }
        $warmedScripts = 0;
        foreach ($active['definitions'] as $class => $byName) {
            if (!is_a($class, modScript::class, true)) {
                continue;
            }
            foreach ($byName as $definition) {
                $element = $this->modx->getElement($class, $definition['name']);
                if (
                    !$element instanceof modScript
                    || ($element->getDefinitionMetadata()['definition_key'] ?? null) !== $definition['key']
                ) {
                    continue;
                }
                if ($element->loadScript() === false) {
                    throw $this->failure(
                        "Could not warm compiled script cache for {$definition['key']}",
                        5,
                        'script-cache-warm-failed'
                    );
                }
                $warmedScripts++;
            }
        }
        $releaseHash = $active['release_hash'];
        $hashStored = $cacheManager->set(
            'release-hash',
            $releaseHash,
            0,
            $this->cachePartitionOptions(self::REGISTRY_CACHE_PARTITION)
        );
        if (!$hashStored) {
            throw $this->failure('Could not record the warmed registry hash.', 5, 'registry-hash-cache-failed');
        }

        return [
            'active_artifact' => $activeArtifact,
            'contexts' => $contexts,
            'release_hash' => $active['release_hash'],
            'resource_cache_cleared' => true,
            'scripts_warmed' => $warmedScripts,
            'diagnostics' => $this->diagnostics($active, $dispatcher),
        ];
    }

    private function compileCatalog(): array
    {
        $manifests = $this->configArray('definition_manifests');
        try {
            return $this->compiler->compile($manifests);
        } catch (DefinitionManifestInputException $exception) {
            throw $this->failure($exception->getMessage(), 4, 'release-input-failed');
        } catch (Throwable $exception) {
            throw $this->failure($exception->getMessage(), 3, 'release-validation-failed');
        }
    }

    /**
     * @return array{0: array, 1: EventDispatcher} The selected catalog and its
     * runtime-validated dispatcher.
     */
    private function selectedCatalog(): array
    {
        $activeArtifact = $this->activeArtifact(false);
        $catalog = $activeArtifact === null ? $this->compileCatalog() : $this->loadArtifact($activeArtifact);
        $dispatcher = $this->validateRuntime($catalog, $activeArtifact === null ? 3 : 5);

        return [$catalog, $dispatcher];
    }

    private function loadArtifact(string $path): array
    {
        try {
            return $this->artifact->load($path);
        } catch (RuntimeException $exception) {
            throw $this->failure($exception->getMessage(), 5, 'active-artifact-invalid');
        }
    }

    /**
     * Validate the catalog's runtime event metadata once and return the
     * validated dispatcher so diagnostics can reuse it.
     */
    private function validateRuntime(array $catalog, int $exitStatus): EventDispatcher
    {
        $this->databasePresence(
            modEvent::class,
            array_merge(array_keys($catalog['events']), array_column($catalog['listeners'], 'event'))
        );
        try {
            $strictValidation = $this->modx->isDefinitionValidationStrict();
            $dispatcher = new EventDispatcher($this->modx, new DefinitionRegistry($catalog), $strictValidation, false);
            $dispatcher->validateEventMetadata();
        } catch (RuntimeException $exception) {
            throw $this->failure($exception->getMessage(), $exitStatus, 'runtime-validation-failed');
        }

        return $dispatcher;
    }

    private function assertDatabaseAvailable(): void
    {
        if (!$this->modx->connect()) {
            throw $this->failure('Could not connect to the MODX database.', 5, 'database-unavailable');
        }
    }

    private function diagnostics(array $catalog, EventDispatcher $dispatcher): array
    {
        $diagnostics = $dispatcher->getDiagnostics();
        foreach ($catalog['definitions'] as $class => $definitions) {
            $databasePresence = $this->databasePresence(
                $class,
                array_column($definitions, 'name')
            );
            foreach ($definitions as $definition) {
                $databaseExists = $databasePresence[DefinitionRegistry::normalizeName($definition['name'])] ?? false;
                if (!$databaseExists) {
                    continue;
                }
                $diagnostics[] = [
                    'code' => 'database-element-collision',
                    'decision' => 'database-default',
                    'key' => $definition['key'],
                    'message' => 'A database element with the same name takes precedence.',
                    'severity' => 'warning',
                ];
            }
        }
        $databasePluginPresence = $this->databasePresence(
            modPlugin::class,
            array_column($catalog['listeners'], 'plugin')
        );
        foreach ($catalog['listeners'] as $listener) {
            if (!($databasePluginPresence[DefinitionRegistry::normalizeName($listener['plugin'])] ?? false)) {
                continue;
            }
            $diagnostics[] = [
                'code' => 'database-plugin-collision',
                'decision' => 'disk-suppressed-by-database',
                'key' => $listener['key'],
                'message' => 'A database plugin suppresses this disk listener.',
                'severity' => 'warning',
            ];
        }

        foreach ($diagnostics as &$diagnostic) {
            $diagnostic['key'] = $diagnostic['key'] ?? 'disk:' . ($diagnostic['package'] ?? '')
                . ':event:' . ($diagnostic['event'] ?? '');
            $diagnostic['severity'] = $diagnostic['severity'] ?? 'warning';
            unset($diagnostic['package']);
        }
        unset($diagnostic);
        usort($diagnostics, static function (array $left, array $right): int {
            $severity = ['error' => 0, 'warning' => 1, 'info' => 2];
            return ($severity[$left['severity']] ?? 3) <=> ($severity[$right['severity']] ?? 3)
                ?: strcmp($left['key'], $right['key'])
                ?: strcmp($left['code'], $right['code']);
        });

        return $diagnostics;
    }

    /**
     * Return policy-free database presence for the supplied element names.
     *
     * Deployment validation must distinguish an operational query failure from an
     * absent database twin, so the collaborator's failure result becomes the
     * operational exit rather than an empty collision report.
     */
    private function databasePresence(string $class, array $names): array
    {
        $presence = $this->databaseFacts->elementPresence($class, $names);
        if ($presence === null) {
            throw $this->failure(
                'Could not inspect database definition presence.',
                5,
                'database-presence-unavailable'
            );
        }

        return array_map(static fn($facts): bool => $facts !== false, $presence);
    }

    private function configArray(string $key): array
    {
        $value = $this->config[$key] ?? [];
        if (!is_array($value)) {
            throw $this->failure("Release-owned {$key} must be an array.", 4, 'invalid-release-config');
        }

        return $value;
    }

    private function artifactDirectory(bool $writable = true): string
    {
        $directory = $this->config['definition_registry_artifact_dir'] ?? '';
        if (!is_string($directory) || $directory === '') {
            throw $this->failure(
                'Release-owned definition_registry_artifact_dir must be configured.',
                4,
                'artifact-directory-not-configured'
            );
        }
        $realDirectory = realpath($directory);
        if ($realDirectory === false || !is_dir($realDirectory) || ($writable && !is_writable($realDirectory))) {
            throw $this->failure(
                "Compiled definition registry directory is not available: {$directory}",
                4,
                'artifact-directory-unavailable'
            );
        }

        return $realDirectory;
    }

    private function activeArtifact(bool $required): ?string
    {
        $path = $this->config['definition_registry_artifact'] ?? '';
        if ($path === '' && !$required) {
            return null;
        }
        if (!is_string($path) || $path === '') {
            throw $this->failure(
                'Release-owned definition_registry_artifact must identify the active artifact.',
                $required ? 5 : 4,
                'active-artifact-not-configured'
            );
        }

        if (!DefinitionRegistryArtifact::isContentAddressedBasename($path)) {
            throw $this->failure(
                'The configured definition artifact path is not content-addressed by a release hash.',
                5,
                'active-path-not-content-addressed'
            );
        }
        if (is_link($path)) {
            throw $this->failure(
                'The configured definition artifact path must not be a symlink.',
                5,
                'active-artifact-symlink'
            );
        }
        $realPath = realpath($path);
        if ($realPath === false) {
            throw $this->failure(
                "The configured definition artifact was not found: {$path}",
                5,
                'active-artifact-not-found'
            );
        }
        if (dirname($realPath) !== $this->artifactDirectory(false)) {
            throw $this->failure(
                'The active definition artifact is outside definition_registry_artifact_dir.',
                5,
                'active-artifact-outside-directory'
            );
        }

        return $realPath;
    }

    private function definitionByKey(array $catalog, string $key): array
    {
        foreach ($catalog['definitions'] as $byName) {
            foreach ($byName as $definition) {
                if ($definition['key'] === $key) {
                    return $definition;
                }
            }
        }
        throw $this->failure("Unknown source-qualified definition key: {$key}", 2, 'definition-not-found');
    }

    private function definitionByPublicIdentity(
        DefinitionRegistry $registry,
        ?string $type,
        ?string $name
    ): array {
        if ($type === null || $name === null || !isset(self::elementTypes()[$type])) {
            throw $this->failure('explain requires --key or both --type and --name.', 2, 'invalid-selector');
        }
        $class = self::elementTypes()[$type];
        $definition = $registry->getDefinition($class, $name);

        return [$class, $name, $definition];
    }

    private function definitionProvenance(array $definition): array
    {
        return [
            'content_hash' => $definition['content_hash'],
            'file' => $definition['relative_file'],
            'key' => $definition['key'],
            'manifest' => basename($definition['manifest']),
            'name' => $definition['name'],
            'package' => $definition['package'],
            'type' => $definition['type'],
        ];
    }

    private function releaseSummary(array $catalog): array
    {
        return [
            'definitions' => array_sum(array_map('count', $catalog['definitions'])),
            'events' => count($catalog['events']),
            'listeners' => count($catalog['listeners']),
            'packages' => count($catalog['inventory']),
            'release_hash' => $catalog['release_hash'],
        ];
    }

    private function contextKeys(): array
    {
        // modContext's primary key is its context key string, so the collection keys
        // are the context keys. A working install always has web and mgr, so an
        // empty result can only mean the context query failed.
        $contexts = array_keys($this->modx->getCollection(modContext::class));
        if (!$contexts) {
            throw $this->failure('Could not load contexts for cache warming.', 5, 'context-list-failed');
        }
        sort($contexts, SORT_STRING);

        return $contexts;
    }

    /**
     * The public element type map, keyed by type, derived from the
     * compiler-owned element type definitions.
     *
     * @return array<string, class-string>
     */
    private static function elementTypes(): array
    {
        return array_column(DefinitionManifestCompiler::ELEMENT_TYPES, 'class', 'type');
    }

    private function resetCachePartition(string $partition): void
    {
        $this->primePartitionForClear($partition);
        if ($partition === 'context_settings') {
            if (!$this->modx->getCacheManager()->clean($this->cachePartitionOptions($partition))) {
                throw $this->failure(
                    "Could not clear the {$partition} cache partition.",
                    5,
                    'cache-partition-clear-failed'
                );
            }

            return;
        }
        $results = [];
        if (!$this->modx->getCacheManager()->refresh([$partition => []], $results)) {
            throw $this->failure(
                "Could not clear the {$partition} cache partition.",
                5,
                'cache-partition-clear-failed'
            );
        }
    }

    private function primePartitionForClear(string $partition): void
    {
        $probe = true;
        $probeStored = $this->modx->getCacheManager()->set(
            '.definition-registry-clear-probe',
            $probe,
            0,
            $this->cachePartitionOptions($partition)
        );
        if (!$probeStored) {
            throw $this->failure(
                "Could not access the {$partition} cache partition.",
                5,
                'cache-partition-unavailable'
            );
        }
    }

    private function cachePartitionOptions(string $partition): array
    {
        return $this->modx->getCacheManager()->getPartitionOptions($partition);
    }

    private function failure(string $message, int $exitStatus, string $errorCode): DefinitionRegistryToolException
    {
        return new DefinitionRegistryToolException($message, $exitStatus, $errorCode);
    }
}
