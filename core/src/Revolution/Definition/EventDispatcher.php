<?php

namespace MODX\Revolution\Definition;

use MODX\Revolution\modPlugin;
use MODX\Revolution\modSystemEvent;
use MODX\Revolution\modX;

/**
 * Builds an executable listener view while retaining the legacy public maps.
 */
class EventDispatcher
{
    /**
     * Stock database event services active in manager and web contexts.
     */
    public const MGR_EVENT_SERVICES = [1, 2, 4, 5, 6];
    public const WEB_EVENT_SERVICES = [1, 3, 4, 5, 6];

    private const DATABASE_FACTS_CACHE_KEY = 'event-plugin-facts';
    private const DATABASE_FACTS_CACHE_PARTITION = 'definition_registry';

    private modX $modx;
    private DefinitionRegistry $registry;
    private DefinitionDatabaseFacts $databaseFacts;
    private array $priorityCache = [];
    private array $contentHashCache = [];
    private array $databaseEventCache = [];
    private array $databasePluginPresence = [];
    private bool $persistentDatabaseFactsLoaded = false;
    private array $persistentDatabaseFacts = [];
    private bool $persistentDatabaseFactsDegraded = false;
    private bool $strictValidation;
    private bool $persistentDatabaseFactsEnabled;
    private array $diagnostics = [];

    public function __construct(
        modX $modx,
        DefinitionRegistry $registry,
        bool $strictValidation = false,
        bool $persistentDatabaseFactsEnabled = true
    ) {
        $this->modx = $modx;
        $this->registry = $registry;
        $this->databaseFacts = new DefinitionDatabaseFacts($modx);
        $this->strictValidation = $strictValidation;
        $this->persistentDatabaseFactsEnabled = $persistentDatabaseFactsEnabled;
    }

    public function activateContext(string $contextKey, array &$eventMap): void
    {
        $this->deactivateContext($eventMap);
        if ($this->registry->isEmpty()) {
            return;
        }
        foreach ($this->registry->getEventNames($contextKey) as $eventName) {
            if ($this->isRowlessDiskEvent($eventName)) {
                $this->suppressRowlessDatabaseBindings($eventName, $eventMap);
            }
            if (!$this->shouldActivateDiskEvent($eventName, $contextKey)) {
                continue;
            }
            if (!isset($eventMap[$eventName])) {
                $eventMap[$eventName] = [];
            }
            $this->activateEventListeners($eventName, $contextKey, $eventMap);
        }
    }

    public function deactivateContext(array &$eventMap): void
    {
        $diskEvents = array_fill_keys($this->registry->getEventNames(), true);
        foreach ($eventMap as $eventName => &$legacyListeners) {
            if (!is_array($legacyListeners)) {
                continue;
            }
            foreach (array_keys($legacyListeners) as $listenerKey) {
                if (is_string($listenerKey) && DefinitionRegistry::isListenerKey($listenerKey)) {
                    unset($legacyListeners[$listenerKey]);
                }
            }
            if (!$legacyListeners && isset($diskEvents[$eventName])) {
                unset($eventMap[$eventName]);
            }
        }
        unset($legacyListeners);
    }

    public function getDiagnostics(): array
    {
        return array_values($this->diagnostics);
    }

    public function validateEventMetadata(): void
    {
        foreach ($this->registry->getEvents() as $eventName => $declaration) {
            $event = $this->getDatabaseEvent($eventName);
            if ($event) {
                $this->validateEventMetadataCollision($event, $declaration);
            }
        }
    }

    public function getOrderedListeners(string $eventName, string $contextKey, array $eventMap): array
    {
        if (!isset($eventMap[$eventName])) {
            return [];
        }

        $descriptors = [];
        $containsDisk = false;
        $registryEmpty = $this->registry->isEmpty();
        $rowlessDiskEvent = !$registryEmpty && $this->isRowlessDiskEvent($eventName);
        foreach ($eventMap[$eventName] as $listenerKey => $listenerValue) {
            $disk = $registryEmpty ? null : $this->registry->getListener((string) $listenerKey);
            $matchesContext = $disk && (!$disk['contexts'] || in_array($contextKey, $disk['contexts'], true));
            if ($disk && $disk['event'] === $eventName && $matchesContext) {
                $descriptor = $disk;
                $valuePrefix = (string) $listenerKey . ':';
                if (is_string($listenerValue) && strncmp($listenerValue, $valuePrefix, strlen($valuePrefix)) === 0) {
                    $descriptor['property_set'] = substr($listenerValue, strlen($valuePrefix));
                }
                $descriptors[] = $descriptor;
                $containsDisk = true;
                continue;
            }
            if (!is_numeric($listenerKey) || $rowlessDiskEvent) {
                continue;
            }
            $propertySet = '';
            if (is_string($listenerValue) && ($position = strpos($listenerValue, ':')) !== false) {
                $propertySet = substr($listenerValue, $position + 1);
            }
            $descriptors[] = [
                'key' => (string) $listenerKey,
                'source' => 'database',
                'plugin_id' => (int) $listenerKey,
                'property_set' => $propertySet,
                'priority' => 0,
            ];
        }

        if (!$containsDisk) {
            return $descriptors;
        }

        $priorities = $this->getDatabasePriorities($eventName);
        foreach ($descriptors as &$descriptor) {
            if ($descriptor['source'] === 'database') {
                $descriptor['priority'] = $priorities[$descriptor['plugin_id']] ?? 0;
            }
        }
        unset($descriptor);
        usort($descriptors, static function (array $left, array $right): int {
            $priority = $left['priority'] <=> $right['priority'];
            if ($priority !== 0) {
                return $priority;
            }
            $leftProvider = $left['source'] === 'database' ? 0 : 1;
            $rightProvider = $right['source'] === 'database' ? 0 : 1;
            $provider = $leftProvider <=> $rightProvider;
            if ($provider !== 0) {
                return $provider;
            }
            if ($leftProvider === 0) {
                return $left['plugin_id'] <=> $right['plugin_id'];
            }

            $package = strcmp((string) $left['package'], (string) $right['package']);
            return $package !== 0
                ? $package
                : strcmp((string) $left['listener_key'], (string) $right['listener_key']);
        });

        return $descriptors;
    }

    /**
     * Resolve the plugin behind an ordered listener descriptor, or null when the
     * database plugin is missing or disabled.
     */
    public function resolvePlugin(array $descriptor): ?modPlugin
    {
        return $descriptor['source'] === 'database'
            ? $this->getDatabasePlugin($descriptor)
            : $this->getDiskPlugin($descriptor);
    }

    /**
     * Return the property set an ordered listener descriptor binds, if any.
     */
    public function getListenerPropertySet(array $descriptor): string
    {
        return $descriptor['property_set'] ?? '';
    }

    /**
     * Execute one ordered listener descriptor against its resolved plugin.
     *
     * The caller owns the modSystemEvent lifecycle and must have wired the
     * event name, active plugin, and property set before calling: property-set
     * resolution inside modPlugin::getProperties() reads
     * modSystemEvent::$propertySet.
     */
    public function invoke(array $descriptor, modPlugin $plugin, array $params, modSystemEvent $event)
    {
        if ($descriptor['source'] === 'database') {
            return $plugin->process(array_merge($plugin->getProperties(), $params));
        }
        $eventParams = array_merge($plugin->getProperties(), $descriptor['properties'] ?? [], $params);
        if (!empty($descriptor['service'])) {
            $event->params = $eventParams;
            try {
                return $this->invokeService($descriptor, $eventParams, $event);
            } finally {
                $event->params = null;
            }
        }

        return $this->invokeDiskPlugin($descriptor, $plugin, $eventParams);
    }

    private function getDatabasePlugin(array $descriptor): ?modPlugin
    {
        $pluginId = $descriptor['plugin_id'];
        if (isset($this->modx->pluginCache[$pluginId])) {
            $plugin = $this->modx->newObject(modPlugin::class);
            $plugin->fromArray($this->modx->pluginCache[$pluginId], '', true, true);
            $plugin->_processed = false;

            return $plugin->get('disabled') ? null : $plugin;
        }

        $plugin = $this->modx->getObject(modPlugin::class, ['id' => $pluginId, 'disabled' => '0'], true);

        return $plugin instanceof modPlugin ? $plugin : null;
    }

    private function getDiskPlugin(array $descriptor): modPlugin
    {
        $definition = $this->registry->getDefinition(modPlugin::class, $descriptor['plugin']);
        $plugin = $this->modx->newObject(modPlugin::class);
        $plugin->set('name', $descriptor['plugin']);
        $plugin->setContent($descriptor['content'] ?? $definition['content'] ?? '');
        $plugin->setProperties($definition['properties'] ?? []);
        $plugin->set('disabled', false);
        $plugin->set('static', false);
        $plugin->setDefinitionMetadata([
            'source' => 'disk',
            'package' => $descriptor['package'],
            'manifest' => $definition['manifest']
                ?? $descriptor['manifest']
                ?? $this->registry->getManifestPath($descriptor['package']),
            'source_file' => $descriptor['file'] ?? ($definition['file'] ?? null),
            'normalized_key' => DefinitionRegistry::normalizeName($descriptor['plugin']),
            'definition_key' => $definition['key'] ?? $descriptor['key'],
            'collision' => null,
            'decision' => 'listener-binding',
            'property_sets' => $definition['property_sets'] ?? [],
            'media_source' => null,
        ]);
        $hash = $this->contentHashCache[$descriptor['key']]
            ??= hash('sha256', $plugin->getContent());
        $identity = $definition['key'] ?? $descriptor['key'];
        $plugin->_scriptName = DefinitionRegistry::scriptName($identity, $hash);
        $plugin->_scriptCacheKey = DefinitionRegistry::scriptCacheKey($identity, $hash);

        return $plugin;
    }

    private function invokeService(array $descriptor, array $eventParams, modSystemEvent $event)
    {
        $outputBufferLevel = ob_get_level();
        try {
            $class = $descriptor['service'];
            if ($this->modx->services->has($class)) {
                $service = $this->modx->services->get($class);
            } else {
                $service = new $class();
            }
            if (is_callable($service)) {
                return $service($this->modx, $eventParams, $event);
            }
            if (method_exists($service, 'handle')) {
                return $service->handle($this->modx, $eventParams, $event);
            }

            throw new \RuntimeException("Service class {$class} is not callable");
        } catch (\Throwable $exception) {
            while (ob_get_level() > $outputBufferLevel) {
                ob_end_clean();
            }
            throw new DiskListenerExecutionException(
                "Disk listener {$descriptor['key']} from package {$descriptor['package']}"
                . " and source {$descriptor['service']} failed: {$exception->getMessage()}",
                0,
                $exception
            );
        }
    }

    private function invokeDiskPlugin(array $descriptor, modPlugin $plugin, array $eventParams)
    {
        $outputBufferLevel = ob_get_level();
        try {
            return $plugin->process($eventParams);
        } catch (\Throwable $exception) {
            while (ob_get_level() > $outputBufferLevel) {
                ob_end_clean();
            }
            $source = $descriptor['file'] ?? $descriptor['relative_file'] ?? 'inline content';
            throw new DiskListenerExecutionException(
                "Disk listener {$descriptor['key']} from package {$descriptor['package']}"
                . " and source {$source} failed: {$exception->getMessage()}",
                0,
                $exception
            );
        }
    }

    public function supportsListenerPropertySet(array $listener, string $propertySet): bool
    {
        if ($propertySet === '') {
            return true;
        }
        $definition = $this->registry->getDefinition(modPlugin::class, $listener['plugin']);
        if (!$definition || $definition['package'] !== $listener['package']) {
            return false;
        }
        return DefinitionRegistry::findPropertySet($definition['property_sets'] ?? [], $propertySet) !== null;
    }

    private function activateEventListeners(string $eventName, string $contextKey, array &$eventMap): void
    {
        foreach ($this->registry->getListeners($eventName, $contextKey) as $key => $listener) {
            if (!$this->isSuppressedByDatabasePlugin($listener)) {
                $propertySet = $listener['property_set'] ?? '';
                $eventMap[$eventName][$key] = $key . ($propertySet !== '' ? ':' . $propertySet : '');
            }
        }
    }

    private function shouldActivateDiskEvent(string $eventName, string $contextKey): bool
    {
        $event = $this->getDatabaseEvent($eventName);
        $declaration = $this->registry->getEvents()[$eventName] ?? null;
        if ($event !== null) {
            if ($declaration) {
                $this->validateEventMetadataCollision($event, $declaration);
            }

            return $this->databaseEventMatchesContext($event, $contextKey);
        }
        $service = $declaration['metadata']['service'] ?? 'all';

        return $this->serviceMatchesContext($service, $contextKey);
    }

    public function isRowlessDiskEvent(string $eventName): bool
    {
        return $this->registry->hasDiskEvent($eventName)
            && $this->getDatabaseEvent($eventName) === null;
    }

    private function suppressRowlessDatabaseBindings(string $eventName, array &$eventMap): void
    {
        if (!isset($eventMap[$eventName]) || !is_array($eventMap[$eventName])) {
            return;
        }
        foreach (array_keys($eventMap[$eventName]) as $listenerKey) {
            if (is_numeric($listenerKey)) {
                unset($eventMap[$eventName][$listenerKey]);
            }
        }
    }

    /**
     * @param array{name: string, service: mixed, groupname: string} $event
     */
    private function validateEventMetadataCollision(array $event, array $declaration): void
    {
        $disk = $declaration['metadata'];
        $database = [
            'service' => $this->databaseEventService($event),
            'group' => (string) $event['groupname'],
        ];
        foreach (['service', 'group'] as $field) {
            if (!array_key_exists($field, $disk) || $disk[$field] === $database[$field]) {
                continue;
            }
            $key = $event['name'] . ':' . $field;
            $diagnostic = [
                'code' => 'event-metadata-conflict',
                'event' => $event['name'],
                'package' => $declaration['package'],
                'field' => $field,
                'database' => $database[$field],
                'disk' => $disk[$field],
            ];
            if (!isset($this->diagnostics[$key])) {
                $this->diagnostics[$key] = $diagnostic;
                $this->modx->log(
                    modX::LOG_LEVEL_WARN,
                    "Disk event metadata conflict for {$diagnostic['event']} ({$field}): database wins"
                );
            }
            if ($this->strictValidation) {
                throw new \RuntimeException(
                    "Disk event metadata conflict for {$diagnostic['event']} from package"
                    . " {$diagnostic['package']} on {$field}"
                );
            }
        }
    }

    private function databaseEventService(array $event): string
    {
        $service = (int) $event['service'];
        if ($service === 2) {
            return 'mgr';
        }
        if ($service === 3) {
            return 'web';
        }

        return 'all';
    }

    private function databaseEventMatchesContext(array $event, string $contextKey): bool
    {
        $service = (int) $event['service'];
        $activeServices = $contextKey === 'mgr' ? self::MGR_EVENT_SERVICES : self::WEB_EVENT_SERVICES;

        return in_array($service, $activeServices, true);
    }

    private function serviceMatchesContext(string $service, string $contextKey): bool
    {
        if ($service === 'mgr') {
            return $contextKey === 'mgr';
        }
        if ($service === 'web') {
            return $contextKey !== 'mgr';
        }

        return true;
    }

    private function isSuppressedByDatabasePlugin(array $listener): bool
    {
        $pluginName = DefinitionRegistry::normalizeName($listener['plugin']);
        if (!array_key_exists($pluginName, $this->databasePluginPresence)) {
            $facts = $this->loadPersistentDatabaseFacts();
            if (array_key_exists($pluginName, $facts['plugins'])) {
                $this->databasePluginPresence[$pluginName] = $facts['plugins'][$pluginName];
            } else {
                $this->databasePluginPresence[$pluginName] = $this->databaseFacts->elementExists(
                    modPlugin::class,
                    $listener['plugin']
                ) ?? false;
            }
        }
        return $this->databasePluginPresence[$pluginName];
    }

    /**
     * @return array{name: string, service: mixed, groupname: string}|null
     */
    private function getDatabaseEvent(string $eventName): ?array
    {
        if (!array_key_exists($eventName, $this->databaseEventCache)) {
            $eventKey = DefinitionRegistry::normalizeName($eventName);
            $facts = $this->loadPersistentDatabaseFacts();
            $snapshot = array_key_exists($eventKey, $facts['events'])
                ? $facts['events'][$eventKey]
                : $this->databaseFacts->eventSnapshot($eventName);
            $this->databaseEventCache[$eventName] = is_array($snapshot) ? $snapshot : null;
        }

        return $this->databaseEventCache[$eventName];
    }

    private function getDatabasePriorities(string $eventName): array
    {
        if (isset($this->priorityCache[$eventName])) {
            return $this->priorityCache[$eventName];
        }
        $facts = $this->loadPersistentDatabaseFacts();
        if (array_key_exists($eventName, $facts['priorities'])) {
            return $this->priorityCache[$eventName] = $facts['priorities'][$eventName];
        }

        return $this->priorityCache[$eventName] = $this->databaseFacts->eventPrioritiesForEvent($eventName) ?? [];
    }

    /**
     * Load the release-stamped, policy-free database facts snapshot.
     *
     * Event metadata is retained because it determines context activation; plugin
     * entries need only retain their presence bit. The cache is intentionally one
     * entry per cache partition so normal MODX cache clearing invalidates every
     * release snapshot.
     */
    private function loadPersistentDatabaseFacts(): array
    {
        if ($this->persistentDatabaseFactsLoaded) {
            return $this->persistentDatabaseFacts;
        }

        $this->persistentDatabaseFactsLoaded = true;
        $this->persistentDatabaseFacts = $this->emptyPersistentDatabaseFacts();
        if (!$this->persistentDatabaseFactsEnabled) {
            return $this->persistentDatabaseFacts;
        }
        try {
            $cached = $this->modx->getCacheManager()->get(
                self::DATABASE_FACTS_CACHE_KEY,
                $this->databaseFactsCacheOptions()
            );
        } catch (\Throwable) {
            $cached = null;
        }
        if (
            is_array($cached)
            && ($cached['release_hash'] ?? null) === $this->registry->getReleaseHash()
            && $this->isValidPersistentDatabaseFacts($cached)
        ) {
            $this->persistentDatabaseFacts = $cached;
            return $this->persistentDatabaseFacts;
        }

        $this->persistentDatabaseFacts = $this->resolvePersistentDatabaseFacts();
        if (!$this->persistentDatabaseFactsDegraded) {
            $this->storePersistentDatabaseFacts();
        }

        return $this->persistentDatabaseFacts;
    }

    /**
     * Resolve one complete, release-scoped projection before publishing it. This
     * avoids request races replacing a complete cache entry with a partial one.
     *
     * Every per-name fallback distinguishes a failed query from a genuinely
     * absent row. A failure marks the snapshot as degraded so it stays
     * request-local: persisting it would freeze a transient database error as
     * authoritative absence (inverting collision precedence) until the next
     * manual cache clear.
     */
    private function resolvePersistentDatabaseFacts(): array
    {
        $this->persistentDatabaseFactsDegraded = false;
        $facts = $this->emptyPersistentDatabaseFacts();
        $eventNames = array_values(array_unique($this->registry->getEventNames()));
        $events = $this->databaseFacts->eventSnapshots($eventNames);
        foreach ($eventNames as $eventName) {
            $eventKey = DefinitionRegistry::normalizeName($eventName);
            $facts['events'][$eventKey] = $this->resolveFact(
                $events,
                $eventKey,
                fn() => $this->databaseFacts->eventSnapshot($eventName),
                false
            );
        }
        $priorities = $this->databaseFacts->eventPriorities($eventNames);
        foreach ($eventNames as $eventName) {
            $facts['priorities'][$eventName] = $this->resolveFact(
                $priorities,
                DefinitionRegistry::normalizeName($eventName),
                fn() => $this->databaseFacts->eventPrioritiesForEvent($eventName),
                []
            );
        }

        $pluginNames = [];
        foreach ($this->registry->getAllListeners() as $listener) {
            $pluginNames[DefinitionRegistry::normalizeName($listener['plugin'])] = $listener['plugin'];
        }
        $plugins = $this->databaseFacts->elementPresence(modPlugin::class, array_values($pluginNames));
        foreach ($pluginNames as $pluginKey => $pluginName) {
            $facts['plugins'][$pluginKey] = $this->resolveFact(
                $plugins,
                (string) $pluginKey,
                fn() => $this->databaseFacts->elementExists(modPlugin::class, $pluginName),
                false
            ) !== false;
        }

        return $facts;
    }

    /**
     * Resolve one per-key database fact: the bulk map wins when its query
     * succeeded, otherwise a per-name query answers. A failed per-name query
     * marks the snapshot degraded and yields the conservative absent default.
     *
     * @param array|null $bulk The bulk query result, or null when it failed.
     * @param mixed $absentDefault
     * @return mixed
     */
    private function resolveFact(?array $bulk, string $key, callable $resolvePerName, $absentDefault)
    {
        if ($bulk !== null) {
            return $bulk[$key] ?? $absentDefault;
        }
        $value = $resolvePerName();
        if ($value === null) {
            $this->persistentDatabaseFactsDegraded = true;

            return $absentDefault;
        }

        return $value;
    }

    private function isValidPersistentDatabaseFacts(array $facts): bool
    {
        if (
            !is_array($facts['events'] ?? null)
            || !is_array($facts['plugins'] ?? null)
            || !is_array($facts['priorities'] ?? null)
        ) {
            return false;
        }
        foreach ($facts['events'] as $event) {
            if ($event === false) {
                continue;
            }
            if (
                !is_array($event)
                || !is_string($event['name'] ?? null)
                || $event['name'] === ''
                || !is_numeric($event['service'] ?? null)
                || !is_string($event['groupname'] ?? null)
            ) {
                return false;
            }
        }
        foreach ($facts['plugins'] as $pluginExists) {
            if (!is_bool($pluginExists)) {
                return false;
            }
        }
        foreach ($facts['priorities'] as $priorities) {
            if (!is_array($priorities)) {
                return false;
            }
            foreach ($priorities as $pluginId => $priority) {
                if (!is_int($pluginId) || !is_int($priority)) {
                    return false;
                }
            }
        }

        return true;
    }

    private function emptyPersistentDatabaseFacts(): array
    {
        return [
            'release_hash' => $this->registry->getReleaseHash(),
            'events' => [],
            'plugins' => [],
            'priorities' => [],
        ];
    }

    private function storePersistentDatabaseFacts(): bool
    {
        if (!$this->persistentDatabaseFactsEnabled) {
            return true;
        }
        try {
            return (bool) $this->modx->getCacheManager()->set(
                self::DATABASE_FACTS_CACHE_KEY,
                $this->persistentDatabaseFacts,
                0,
                $this->databaseFactsCacheOptions()
            );
        } catch (\Throwable) {
            // The dispatcher remains correct with its request-local database results.
            return false;
        }
    }

    private function databaseFactsCacheOptions(): array
    {
        return $this->modx->getCacheManager()->getPartitionOptions(self::DATABASE_FACTS_CACHE_PARTITION);
    }
}
