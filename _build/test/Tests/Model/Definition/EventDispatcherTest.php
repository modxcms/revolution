<?php

namespace MODX\Revolution\Tests\Model\Definition;

use MODX\Revolution\Definition\DefinitionRegistry;
use MODX\Revolution\Definition\DiskListenerExecutionException;
use MODX\Revolution\Definition\EventDispatcher;
use MODX\Revolution\modElementPropertySet;
use MODX\Revolution\modAccessCategory;
use MODX\Revolution\modCategory;
use MODX\Revolution\modUserGroup;
use MODX\Revolution\modX;
use MODX\Revolution\modEvent;
use MODX\Revolution\modPlugin;
use MODX\Revolution\modPluginEvent;
use MODX\Revolution\modPropertySet;
use MODX\Revolution\MODxTestCase;

class EventDispatcherTest extends MODxTestCase
{
    private array $originalEventMap;
    private array $databasePlugins = [];
    private array $databaseEvents = [];
    private array $aclFixtures = [];
    private ?bool $originalSudo = null;
    private ?int $originalSessionState = null;
    private ?array $originalTrustedDefinitionConfig = null;

    /** @before */
    public function setUpFixtures()
    {
        parent::setUpFixtures();
        $this->originalEventMap = is_array($this->modx->eventMap) ? $this->modx->eventMap : [];
        $this->modx->eventMap = [];
    }

    /** @after */
    public function tearDownFixtures()
    {
        if ($this->originalTrustedDefinitionConfig !== null) {
            $config = new \ReflectionProperty($this->modx, '_config');
            $config->setAccessible(true);
            $config->setValue($this->modx, $this->originalTrustedDefinitionConfig);
            $this->originalTrustedDefinitionConfig = null;
        }
        if ($this->originalSudo !== null) {
            $this->modx->user->set('sudo', $this->originalSudo);
            $this->originalSudo = null;
        }
        if ($this->originalSessionState !== null) {
            $state = new \ReflectionProperty(modX::class, '_sessionState');
            $state->setAccessible(true);
            $state->setValue($this->modx, $this->originalSessionState);
            $this->originalSessionState = null;
        }
        foreach ($this->aclFixtures as $fixture) {
            if ($fixture && !$fixture->isNew()) {
                $fixture->remove();
            }
        }
        $this->aclFixtures = [];
        foreach ($this->databasePlugins as $plugin) {
            $bindings = $this->modx->getCollection(modPluginEvent::class, ['pluginid' => $plugin->get('id')]);
            foreach ((array) $bindings as $binding) {
                $binding->remove();
            }
            $plugin->remove();
        }
        foreach ($this->databaseEvents as $event) {
            $event->remove();
        }
        $this->modx->eventMap = $this->originalEventMap;
        $this->modx->setDefinitionRegistry(new DefinitionRegistry());
        parent::tearDownFixtures();
    }

    public function testRegisteredEmptyAndUnknownEventsRemainDistinct()
    {
        $this->installRegistry(['DiskRegistered' => ['name' => 'DiskRegistered']], []);

        $this->assertSame([], $this->modx->invokeEvent('DiskRegistered'));
        $this->assertFalse($this->modx->invokeEvent('DefinitelyUnknown'));
    }

    public function testDiskListenersUsePriorityStableKeysAndNormalEventOutput()
    {
        $pluginCache = $this->modx->pluginCache;
        $this->installRegistry([], [
            $this->listener('later', 20, 'later'),
            $this->listener('first-b', 10, 'first-b'),
            $this->listener('first-a', 10, 'first-a'),
        ]);

        $result = $this->modx->invokeEvent('DiskSequence', ['requestValue' => 'request']);

        $this->assertSame(['first-a:request', 'first-b:request', 'later:request'], $result);
        $this->assertSame('', $this->modx->event->activePlugin);
        $this->assertSame('', $this->modx->event->propertySet);
        $this->assertSame($pluginCache, $this->modx->pluginCache);
    }

    public function testPublicEventMapMutationCanSuppressDiskListener()
    {
        $listener = $this->listener('visible', 0, 'visible');
        $this->installRegistry([], [$listener]);
        $externalMap =& $this->modx->eventMap;

        unset($externalMap['DiskSequence'][$listener['key']]);

        $this->assertSame([], $this->modx->invokeEvent('DiskSequence'));
        $this->assertSame($externalMap, $this->modx->eventMap);
    }

    public function testRemoveEventListenerTargetsPluginIdsAndListenerKeys()
    {
        $this->modx->eventMap['DiskRemoval'] = [
            '7' => '7:',
            'disk:phase0/tests:listener:guard' => 'disk:phase0/tests:listener:guard',
        ];

        $this->assertTrue($this->modx->removeEventListener('DiskRemoval', 7));
        $this->assertArrayNotHasKey('7', $this->modx->eventMap['DiskRemoval']);

        $this->assertTrue($this->modx->removeEventListener('DiskRemoval', 'disk:phase0/tests:listener:guard'));
        $this->assertSame([], $this->modx->eventMap['DiskRemoval']);

        $this->modx->eventMap['DiskRemoval'] = ['9' => '9:'];
        $this->assertTrue($this->modx->removeEventListener('DiskRemoval', 'not-a-registered-listener'));
        $this->assertArrayNotHasKey('DiskRemoval', $this->modx->eventMap);

        $this->modx->eventMap['DiskRemoval'] = ['9' => '9:'];
        $this->assertFalse($this->modx->removeEventListener('DiskRemoval', 'disk:phase0/tests:listener:missing'));
        $this->assertArrayHasKey('DiskRemoval', $this->modx->eventMap);

        $listenerKey = 'disk:pkg/x:listener:foo';
        $this->modx->eventMap['DiskRemoval'] = [$listenerKey => $listenerKey . ':someSet'];
        $this->assertTrue($this->modx->removeEventListener('DiskRemoval', $listenerKey . ':someSet'));
        $this->assertSame([], $this->modx->eventMap['DiskRemoval']);

        $this->modx->eventMap['DiskRemoval'] = ['9' => '9:'];
        $this->assertFalse($this->modx->removeEventListener('DiskRemoval', 'disk:pkg/x:listener:foo:someSet'));
        $this->assertSame(['9' => '9:'], $this->modx->eventMap['DiskRemoval']);

        $this->assertTrue($this->modx->removeEventListener('DiskRemoval', 'disk:pkg:listener:foo:someSet'));
        $this->assertArrayNotHasKey('DiskRemoval', $this->modx->eventMap);

        $this->modx->eventMap['DiskRemoval'] = ['9' => '9:'];
        $this->assertTrue($this->modx->removeEventListener('DiskRemoval', 'disk:pkg/x:listener:foo:'));
        $this->assertArrayNotHasKey('DiskRemoval', $this->modx->eventMap);
    }

    public function testStopPropagationMatchesDatabasePluginContract()
    {
        $stop = $this->listener('stop', 0, 'stop');
        $stop['content'] = '<?php $modx->event->output("stop"); $modx->event->stopPropagation();';
        $this->installRegistry([], [$stop, $this->listener('never', 10, 'never')]);

        $this->assertSame(['stop'], $this->modx->invokeEvent('DiskSequence'));
    }

    public function testMixedEqualPriorityRunsDatabaseBeforeDiskDeterministically()
    {
        $this->createDatabaseEvent('DiskSequence', 3, 'Phase2');
        $plugin = $this->createDatabasePlugin('return $modx->event->output("database");', false, 10);
        $disk = $this->listener('disk', 10, 'disk');
        $this->modx->eventMap['DiskSequence'] = [(string) $plugin->get('id') => (string) $plugin->get('id')];
        $this->installRegistry([], [$disk]);

        $this->assertSame(['database', 'disk:request'], $this->modx->invokeEvent('DiskSequence', [
            'requestValue' => 'request',
        ]));
    }

    public function testDisabledDatabasePluginSuppressesDiskTwinByDefault()
    {
        $plugin = $this->createDatabasePlugin('return $modx->event->output("disabled");', true);
        $disk = $this->listener('disk-twin', 0, 'disk');
        $disk['plugin'] = $plugin->get('name');

        $this->installRegistry(['DiskSequence' => ['name' => 'DiskSequence']], [$disk]);

        $this->assertSame([], $this->modx->invokeEvent('DiskSequence', ['requestValue' => 'request']));
    }

    public function testDatabaseFactsCacheReusesMatchingManifestReleaseAndRebuildsForReleaseChanges(): void
    {
        $originalCacheManager = $this->modx->cacheManager;
        $cacheManager = new RecordingCacheManager($this->modx);
        $this->modx->cacheManager = $cacheManager;

        try {
            $this->createDatabaseEvent('DiskSequence', 3, 'Phase2');
            $plugin = $this->createDatabasePlugin('return $modx->event->output("database");', false, 0);
            $listener = $this->listener('persistent-facts', 0, 'disk');
            $listener['plugin'] = $plugin->get('name');
            $pluginName = DefinitionRegistry::normalizeName($plugin->get('name'));
            $registry = $this->registry(['DiskSequence' => [
                'name' => 'DiskSequence', 'package' => 'phase0/tests', 'metadata' => [],
            ]], [$listener], [
                modPlugin::class => [$pluginName => $this->pluginDefinition($plugin->get('name'))],
            ]);
            $cacheKey = $this->modx->getOption(
                'cache_definition_registry_key',
                null,
                'definition_registry'
            ) . ':event-plugin-facts';

            $eventMap = ['DiskSequence' => [(string) $plugin->get('id') => (string) $plugin->get('id')]];
            (new EventDispatcher($this->modx, $registry))->activateContext('web', $eventMap);
            $this->assertArrayNotHasKey($listener['key'], $eventMap['DiskSequence']);
            $this->assertArrayHasKey($cacheKey, $cacheManager->entries);
            $cached = $cacheManager->entries[$cacheKey];
            $this->assertSame($registry->getReleaseHash(), $cached['release_hash']);
            $this->assertArrayHasKey('disksequence', $cached['events']);
            $this->assertTrue($cached['plugins'][DefinitionRegistry::normalizeName($listener['plugin'])]);
            $this->assertArrayHasKey('DiskSequence', $cached['priorities']);
            $this->assertSame([0], $cacheManager->lifetimes(), 'Facts are cached indefinitely.');

            $sets = $cacheManager->setCount();
            $eventMap = ['DiskSequence' => [(string) $plugin->get('id') => (string) $plugin->get('id')]];
            (new EventDispatcher($this->modx, $registry))
                ->activateContext('web', $eventMap);
            $this->assertArrayNotHasKey($listener['key'], $eventMap['DiskSequence']);
            $this->assertSame(
                $sets,
                $cacheManager->setCount(),
                'A fresh manifest-mode request must reuse matching persistent database facts.'
            );

            unset($cacheManager->entries[$cacheKey]['plugins'][$pluginName]);
            $sets = $cacheManager->setCount();
            $eventMap = ['DiskSequence' => [(string) $plugin->get('id') => (string) $plugin->get('id')]];
            (new EventDispatcher($this->modx, $registry))
                ->activateContext('web', $eventMap);
            $this->assertArrayNotHasKey($listener['key'], $eventMap['DiskSequence']);
            $this->assertSame(
                $sets,
                $cacheManager->setCount(),
                'A structurally valid partial entry is retained; a missing fact falls back to the database.'
            );

            $changedRegistry = $this->registry([
                'DiskSequence' => ['name' => 'DiskSequence', 'package' => 'phase0/tests', 'metadata' => []],
                'CacheReleaseChanged' => ['name' => 'CacheReleaseChanged', 'package' => 'phase0/tests', 'metadata' => []],
            ], [$listener], [
                modPlugin::class => [$pluginName => $this->pluginDefinition($plugin->get('name'))],
            ]);
            $sets = $cacheManager->setCount();
            $eventMap = ['DiskSequence' => [(string) $plugin->get('id') => (string) $plugin->get('id')]];
            (new EventDispatcher($this->modx, $changedRegistry))->activateContext('web', $eventMap);
            $this->assertGreaterThan(
                $sets,
                $cacheManager->setCount(),
                'A changed disk registry release rebuilds facts.'
            );
            $this->assertSame($changedRegistry->getReleaseHash(), $cacheManager->entries[$cacheKey]['release_hash']);

            unset($cacheManager->entries[$cacheKey]);
            $sets = $cacheManager->setCount();
            $pluginId = (string) $plugin->get('id');
            $eventMap = ['DiskSequence' => [$pluginId => $pluginId]];
            (new EventDispatcher($this->modx, $registry))
                ->activateContext('web', $eventMap);
            $this->assertGreaterThan($sets, $cacheManager->setCount());
            $this->assertArrayHasKey(
                $cacheKey,
                $cacheManager->entries,
                'An ordinary cache clear remains lazy: the next request rebuilds facts.'
            );
        } finally {
            $this->modx->cacheManager = $originalCacheManager;
        }
    }

    public function testArtifactModeUsesTheSameDatabaseFactsCacheLifecycle(): void
    {
        $originalCacheManager = $this->modx->cacheManager;
        $cacheManager = new RecordingCacheManager($this->modx);
        $this->modx->cacheManager = $cacheManager;

        try {
            $this->enableArtifactDatabaseFactsCache();
            $this->createDatabaseEvent('DiskSequence', 3, 'Phase2');
            $plugin = $this->createDatabasePlugin('return "database";', false);
            $listener = $this->listener('artifact-facts', 0, 'disk');
            $listener['plugin'] = $plugin->get('name');
            $registry = $this->registry(['DiskSequence' => [
                'name' => 'DiskSequence', 'package' => 'phase0/tests', 'metadata' => [],
            ]], [$listener], [
                modPlugin::class => [
                    DefinitionRegistry::normalizeName($plugin->get('name')) => $this->pluginDefinition(
                        $plugin->get('name')
                    ),
                ],
            ]);
            $eventMap = ['DiskSequence' => [(string) $plugin->get('id') => (string) $plugin->get('id')]];

            (new EventDispatcher($this->modx, $registry))->activateContext('web', $eventMap);
            (new EventDispatcher($this->modx, $registry))->activateContext('web', $eventMap);

            $this->assertSame(
                [0],
                $cacheManager->lifetimes(),
                'Artifact mode uses the same indefinite facts-cache lifecycle.'
            );
        } finally {
            $this->modx->cacheManager = $originalCacheManager;
        }
    }

    public function testArtifactModeFallsBackToDatabaseLookupWhenFactsCacheFails(): void
    {
        $originalCacheManager = $this->modx->cacheManager;
        $cacheManager = new RecordingCacheManager($this->modx, true);
        $this->modx->cacheManager = $cacheManager;
        try {
            $this->enableArtifactDatabaseFactsCache();
            $plugin = $this->createDatabasePlugin('return $modx->event->output("database");', false, 0);
            $listener = $this->listener('cache-failure', 0, 'disk');
            $listener['plugin'] = $plugin->get('name');
            $pluginName = DefinitionRegistry::normalizeName($plugin->get('name'));
            $registry = $this->registry([], [$listener], [
                modPlugin::class => [$pluginName => $this->pluginDefinition($plugin->get('name'))],
            ]);
            $eventMap = ['DiskSequence' => [(string) $plugin->get('id') => (string) $plugin->get('id')]];

            (new EventDispatcher($this->modx, $registry))->activateContext('web', $eventMap);

            $this->assertArrayNotHasKey($listener['key'], $eventMap['DiskSequence']);
        } finally {
            $this->modx->cacheManager = $originalCacheManager;
        }
    }

    public function testTransientDatabaseFailureKeepsDatabaseFactsRequestLocal(): void
    {
        $originalCacheManager = $this->modx->cacheManager;
        $cacheManager = new RecordingCacheManager($this->modx);

        $this->createDatabaseEvent('DiskSequence', 3, 'Phase2');
        $plugin = $this->createDatabasePlugin('return $modx->event->output("database");', false, 0);
        $listener = $this->listener('transient-outage', 0, 'disk');
        $listener['plugin'] = $plugin->get('name');
        $pluginName = DefinitionRegistry::normalizeName($plugin->get('name'));
        $registry = $this->registry(['DiskSequence' => [
            'name' => 'DiskSequence', 'package' => 'phase0/tests', 'metadata' => [],
        ]], [$listener], [
            modPlugin::class => [$pluginName => $this->pluginDefinition($plugin->get('name'))],
        ]);

        $failingModx = $this->failingModx($cacheManager, static fn() => false);

        $pluginId = (string) $plugin->get('id');
        $eventMap = ['DiskSequence' => [$pluginId => $pluginId]];
        (new EventDispatcher($failingModx, $registry))->activateContext('web', $eventMap);

        $this->assertArrayHasKey(
            $listener['key'],
            $eventMap['DiskSequence'],
            'While the database is unavailable the degraded facts stay request-local.'
        );
        $this->assertSame(
            0,
            $cacheManager->setCount(),
            'A facts snapshot degraded by query failures must never be persisted.'
        );

        $this->modx->cacheManager = $cacheManager;
        try {
            $eventMap = ['DiskSequence' => [$pluginId => $pluginId]];
            (new EventDispatcher($this->modx, $registry))->activateContext('web', $eventMap);

            $this->assertArrayNotHasKey(
                $listener['key'],
                $eventMap['DiskSequence'],
                'The next request after a transient outage restores database-plugin suppression.'
            );
            $this->assertArrayHasKey($pluginId, $eventMap['DiskSequence']);
            $this->assertGreaterThan(0, $cacheManager->setCount(), 'Healthy facts are persisted after the outage.');
            $cacheKey = $this->modx->getOption(
                'cache_definition_registry_key',
                null,
                'definition_registry'
            ) . ':event-plugin-facts';
            $this->assertTrue($cacheManager->entries[$cacheKey]['plugins'][$pluginName]);
        } finally {
            $this->modx->cacheManager = $originalCacheManager;
        }
    }

    public function testBulkFactsQueryFailureStillPersistsPerNameResolvedFacts(): void
    {
        $cacheManager = new RecordingCacheManager($this->modx);

        $this->createDatabaseEvent('DiskSequence', 3, 'Phase2');
        $plugin = $this->createDatabasePlugin('return $modx->event->output("database");', false, 0);
        $listener = $this->listener('bulk-fallback', 0, 'disk');
        $listener['plugin'] = $plugin->get('name');
        $pluginName = DefinitionRegistry::normalizeName($plugin->get('name'));
        $registry = $this->registry(['DiskSequence' => [
            'name' => 'DiskSequence', 'package' => 'phase0/tests', 'metadata' => [],
        ]], [$listener], [
            modPlugin::class => [$pluginName => $this->pluginDefinition($plugin->get('name'))],
        ]);

        $bulkFailingModx = $this->failingModx(
            $cacheManager,
            fn($statement, $driverOptions = []) => strpos($statement, ' IN (') !== false
                ? false
                : $this->modx->prepare($statement, (array) $driverOptions)
        );

        $pluginId = (string) $plugin->get('id');
        $eventMap = ['DiskSequence' => [$pluginId => $pluginId]];
        (new EventDispatcher($bulkFailingModx, $registry))->activateContext('web', $eventMap);

        $this->assertArrayNotHasKey(
            $listener['key'],
            $eventMap['DiskSequence'],
            'Healthy per-name fallbacks keep database-plugin suppression accurate.'
        );
        $this->assertArrayHasKey($pluginId, $eventMap['DiskSequence']);
        $this->assertSame(
            1,
            $cacheManager->setCount(),
            'Facts resolved entirely by healthy per-name fallbacks are still persisted.'
        );
        $cacheKey = $this->modx->getOption(
            'cache_definition_registry_key',
            null,
            'definition_registry'
        ) . ':event-plugin-facts';
        $this->assertTrue($cacheManager->entries[$cacheKey]['plugins'][$pluginName]);
        $this->assertIsArray($cacheManager->entries[$cacheKey]['events']['disksequence']);
        $this->assertSame(
            [$plugin->get('id') => 0],
            $cacheManager->entries[$cacheKey]['priorities']['DiskSequence']
        );
    }

    /**
     * Twin matching follows DefinitionRegistry::normalizeName() (ASCII lowercase),
     * not the database collation: non-ASCII case variants such as É and é are
     * distinct identities even where a case-insensitive collation would equate
     * them, so this disk listener must keep running.
     */
    public function testPluginTwinMatchingFollowsRegistryNormalizationNotDatabaseCollation(): void
    {
        $suffix = bin2hex(random_bytes(5));
        $databaseName = 'ÉPlugin' . $suffix;
        $diskName = 'éPlugin' . $suffix;
        $plugin = $this->createDatabasePlugin('return "database";', false);
        $plugin->set('name', $databaseName);
        $this->assertTrue($plugin->save());

        $listener = $this->listener('unicode-collation', 0, 'disk');
        $listener['plugin'] = $diskName;
        $registry = $this->registry([], [$listener], [
            modPlugin::class => [
                DefinitionRegistry::normalizeName($diskName) => $this->pluginDefinition($diskName),
            ],
        ]);
        $eventMap = ['DiskSequence' => []];

        (new EventDispatcher($this->modx, $registry))->activateContext('web', $eventMap);

        $this->assertArrayHasKey(
            $listener['key'],
            $eventMap['DiskSequence'],
            'A non-ASCII case variant is a different identity under the normalization contract.'
        );
    }

    public function testAsciiCaseVariantDatabasePluginSuppressesDiskListenerOnAnyCollation(): void
    {
        foreach ([['mixedcaseplugin', 'MixedCasePlugin'], ['UPPERCASEPLUGIN', 'uppercaseplugin']] as $index => $pair) {
            [$databaseBase, $diskBase] = $pair;
            $suffix = bin2hex(random_bytes(5));
            $databaseName = $databaseBase . $suffix;
            $diskName = $diskBase . $suffix;
            $plugin = $this->createDatabasePlugin('return "database";', false);
            $plugin->set('name', $databaseName);
            $this->assertTrue($plugin->save());

            $listener = $this->listener('ascii-case-variant-' . $index, 0, 'disk');
            $listener['plugin'] = $diskName;
            $registry = $this->registry([], [$listener], [
                modPlugin::class => [
                    DefinitionRegistry::normalizeName($diskName) => $this->pluginDefinition($diskName),
                ],
            ]);
            $eventMap = ['DiskSequence' => []];

            (new EventDispatcher($this->modx, $registry))->activateContext('web', $eventMap);

            $this->assertArrayNotHasKey(
                $listener['key'],
                $eventMap['DiskSequence'],
                "A database plugin '{$databaseName}' must suppress the disk twin '{$diskName}'."
            );
        }
    }

    public function testDatabasePluginCollisionSuppressesDiskListenerEvenWhenDisabled()
    {
        $plugin = $this->createDatabasePlugin('return $modx->event->output("disabled");', true);
        $disk = $this->listener('disk-collision', 0, 'disk');
        $disk['plugin'] = $plugin->get('name');
        $definition = $this->pluginDefinition($plugin->get('name'));

        $this->installRegistry([], [$disk], [
            modPlugin::class => [strtolower($plugin->get('name')) => $definition],
        ]);

        $this->assertSame([], $this->modx->invokeEvent('DiskSequence', [
            'requestValue' => 'request',
        ]));
    }

    public function testDatabasePluginCollisionKeepsEnabledDatabaseBindings()
    {
        $this->createDatabaseEvent('DiskSequence', 3, 'Phase2');
        $plugin = $this->createDatabasePlugin('return $modx->event->output("database");', false, 0);
        $disk = $this->listener('enabled-collision', 10, 'listener');
        $disk['plugin'] = $plugin->get('name');
        $definition = $this->pluginDefinition($plugin->get('name'));
        $this->modx->eventMap['DiskSequence'] = [(string) $plugin->get('id') => (string) $plugin->get('id')];

        $this->installRegistry([], [$disk], [
            modPlugin::class => [strtolower($plugin->get('name')) => $definition],
        ]);

        $this->assertSame(['database'], $this->modx->invokeEvent('DiskSequence', [
            'requestValue' => 'request',
        ]));
        $this->assertArrayHasKey((string) $plugin->get('id'), $this->modx->eventMap['DiskSequence']);
    }

    public function testAclDeniedDatabasePluginCollisionDoesNotRunDiskListener()
    {
        $group = $this->modx->newObject(modUserGroup::class);
        $group->set('name', 'DeniedPluginGroup' . bin2hex(random_bytes(3)));
        $this->assertTrue($group->save());
        $category = $this->modx->newObject(modCategory::class);
        $category->set('category', 'DeniedPluginCategory' . bin2hex(random_bytes(3)));
        $this->assertTrue($category->save());
        $acl = $this->modx->newObject(modAccessCategory::class);
        $acl->fromArray(['target' => $category->get('id'), 'principal_class' => modUserGroup::class,
            'principal' => $group->get('id'), 'authority' => 9999, 'policy' => 0,
            'context_key' => $this->modx->context->get('key')]);
        $this->assertTrue($acl->save());
        $this->aclFixtures = [$acl, $category, $group];
        $plugin = $this->createDatabasePlugin('return $modx->event->output("database");', false, 0);
        $plugin->set('category', $category->get('id'));
        $this->assertTrue($plugin->save());
        $this->originalSudo = (bool) $this->modx->user->get('sudo');
        $this->modx->user->set('sudo', false);
        $state = new \ReflectionProperty(modX::class, '_sessionState');
        $state->setAccessible(true);
        $this->originalSessionState = $state->getValue($this->modx);
        $state->setValue($this->modx, modX::SESSION_STATE_INITIALIZED);
        $disk = $this->listener('acl-collision', 10, 'disk');
        $disk['plugin'] = $plugin->get('name');
        $this->modx->eventMap['DiskSequence'] = [(string) $plugin->get('id') => (string) $plugin->get('id')];
        $normalizedName = DefinitionRegistry::normalizeName($plugin->get('name'));
        $this->installRegistry([], [$disk], [
            modPlugin::class => [
                $normalizedName => $this->pluginDefinition($plugin->get('name')),
            ],
        ]);

        $this->assertNotContains(
            'disk:request',
            (array) $this->modx->invokeEvent('DiskSequence', ['requestValue' => 'request'])
        );
    }

    public function testUnregisteredListenerServicesAreFreshForEveryInvocation()
    {
        $service = new class {
            private int $invocations = 0;

            public function handle($modx, array $params, $event): void
            {
                $event->output((string) ++$this->invocations);
            }
        };
        $listener = $this->listener('fresh-service', 0, 'unused');
        $listener['file'] = null;
        $listener['relative_file'] = null;
        $listener['content'] = null;
        $listener['service'] = get_class($service);
        $this->installRegistry([], [$listener]);

        $this->assertSame(['1'], $this->modx->invokeEvent('DiskSequence'));
        $this->assertSame(['1'], $this->modx->invokeEvent('DiskSequence'));
    }

    public function testRegisteredListenerServiceRetainsContainerLifecycle()
    {
        $service = new class {
            private int $invocations = 0;
            public function handle($modx, array $params, $event): void
            {
                $event->output((string) ++$this->invocations);
            }
        };
        $class = get_class($service);
        $this->modx->services->add($class, $service);
        $listener = $this->listener('container-service', 0, 'unused');
        $listener['file'] = null;
        $listener['relative_file'] = null;
        $listener['content'] = null;
        $listener['service'] = $class;
        $this->installRegistry([], [$listener]);

        $this->assertSame(['1'], $this->modx->invokeEvent('DiskSequence'));
        $this->assertSame(['2'], $this->modx->invokeEvent('DiskSequence'));
    }

    public function testInvokableListenerServiceReceivesNormalEventArguments()
    {
        $service = new class {
            public function __invoke($modx, array $params, $event): string
            {
                $event->output($params['marker'] . ':' . $event->name);

                return 'service-result';
            }
        };
        $listener = $this->listener('invokable-service', 0, 'unused');
        $listener['file'] = null;
        $listener['relative_file'] = null;
        $listener['content'] = null;
        $listener['service'] = get_class($service);
        $this->installRegistry([], [$listener]);

        $this->assertSame(['unused:DiskSequence'], $this->modx->invokeEvent('DiskSequence'));
    }

    public function testFileListenerReceivesModxScriptPropertiesAndExtractedValues()
    {
        $listener = $this->listener('file-variables', 0, 'unused');
        $listener['properties'] = ['inline' => 'listener'];
        $listener['content'] = '<?php $modx->event->output(json_encode(['
            . '$modx instanceof \\MODX\\Revolution\\modX, $scriptProperties["inline"], $inline]));';
        $this->installRegistry([], [$listener]);

        $this->assertSame(['[true,"listener","listener"]'], $this->modx->invokeEvent('DiskSequence'));
    }

    public function testListenerTargetErrorsCarryPackageListenerAndSourceContext()
    {
        $service = new class {
        };
        $listener = $this->listener('invalid-service', 0, 'unused');
        $listener['file'] = null;
        $listener['relative_file'] = null;
        $listener['content'] = null;
        $listener['service'] = get_class($service);
        $this->modx->services->add($listener['service'], $service);
        $this->installRegistry([], [$listener]);

        $this->expectException(DiskListenerExecutionException::class);
        $this->expectExceptionMessage(
            'Disk listener disk:phase0/tests:listener:invalid-service from package phase0/tests and source'
        );
        $this->modx->invokeEvent('DiskSequence');
    }

    public function testServiceListenerExceptionRestoresOutputBufferLevel()
    {
        $service = new class {
            public function handle($modx, array $params, $event): void
            {
                ob_start();
                ob_start();
                throw new \RuntimeException('service exploded mid-buffer');
            }
        };
        $listener = $this->listener('buffered-service', 0, 'unused');
        $listener['file'] = null;
        $listener['relative_file'] = null;
        $listener['content'] = null;
        $listener['service'] = get_class($service);
        $this->installRegistry([], [$listener]);
        $bufferLevel = ob_get_level();

        try {
            $this->modx->invokeEvent('DiskSequence');
            $this->fail('The failing service listener did not raise a DiskListenerExecutionException.');
        } catch (DiskListenerExecutionException $exception) {
            $this->assertSame(
                $bufferLevel,
                ob_get_level(),
                'A service listener failure must not leak unbalanced output buffers.'
            );
        } finally {
            while (ob_get_level() > $bufferLevel) {
                ob_end_clean();
            }
        }
    }

    public function testFileListenerExceptionsCarryPackageListenerAndSourceContext()
    {
        $listener = $this->listener('invalid-file', 0, 'unused');
        $listener['content'] = '<?php throw new \\RuntimeException("file exploded");';
        $this->installRegistry([], [$listener]);

        $this->expectException(DiskListenerExecutionException::class);
        $this->expectExceptionMessage(
            'Disk listener disk:phase0/tests:listener:invalid-file from package phase0/tests and source'
        );
        $this->modx->invokeEvent('DiskSequence');
    }

    public function testDiskPluginPropertyPrecedenceIncludesNamedSetListenerPropertiesAndInvocationParams()
    {
        $listener = $this->listener('precedence', 0, 'unused');
        $listener['plugin'] = 'UnifiedPlugin';
        $listener['property_set'] = 'FEATURED';
        $listener['properties'] = ['listener' => 'listener'];
        $listener['content'] = '<?php $modx->event->output(json_encode([$default ?? null, $named ?? null, $listener ?? null, $invoke ?? null]));';
        $definition = $this->pluginDefinition('UnifiedPlugin');
        $definition['properties'] = ['default' => 'default', 'named' => 'default', 'listener' => 'default', 'invoke' => 'default'];
        $definition['property_sets'] = ['Featured' => ['named' => 'named']];
        $this->installRegistry([], [$listener], [modPlugin::class => ['unifiedplugin' => $definition]]);

        $this->assertSame(
            $listener['key'] . ':FEATURED',
            $this->modx->eventMap['DiskSequence'][$listener['key']]
        );
        $this->assertSame(['["default","named","listener","invoke"]'], $this->modx->invokeEvent('DiskSequence', [
            'invoke' => 'invoke',
        ]));
    }

    public function testDatabasePluginPropertySetEncodingAndResolutionRemainUnchanged()
    {
        $eventName = 'Phase2DatabaseSet' . bin2hex(random_bytes(5));
        $setName = 'Phase2Set' . bin2hex(random_bytes(5));
        $this->createDatabaseEvent($eventName, 3, 'Phase2');
        $plugin = $this->createDatabasePlugin('return $modx->event->output($value);', false);
        $plugin->setProperties(['value' => 'default']);
        $this->assertTrue($plugin->save());
        $propertySet = $this->modx->newObject(modPropertySet::class);
        $propertySet->set('name', $setName);
        $propertySet->setProperties(['value' => 'database-set']);
        $this->assertTrue($propertySet->save());
        $link = $this->modx->newObject(modElementPropertySet::class);
        $link->fromArray([
            'element' => $plugin->get('id'),
            'element_class' => $plugin->_class,
            'property_set' => $propertySet->get('id'),
        ], '', true);
        $this->assertTrue($link->save());
        $binding = $this->modx->newObject(modPluginEvent::class);
        $binding->set('pluginid', $plugin->get('id'));
        $binding->set('event', $eventName);
        $binding->set('propertyset', $propertySet->get('id'));
        $this->assertTrue($binding->save());

        try {
            $this->modx->eventMap = $this->modx->getEventMap('web');
            $this->modx->setDefinitionRegistry(new DefinitionRegistry());
            $pluginId = (string) $plugin->get('id');
            $this->assertSame($pluginId . ':' . $setName, $this->modx->eventMap[$eventName][$pluginId]);
            $this->assertSame(['database-set'], $this->modx->invokeEvent($eventName));
        } finally {
            $link->remove();
            $propertySet->remove();
        }
    }

    public function testKnownDiskListenerCanBeRemovedAndReaddedThroughPublicApi()
    {
        $listener = $this->listener('readd', 0, 'readd');
        $listener['plugin'] = 'ReaddPlugin';
        $definition = $this->pluginDefinition('ReaddPlugin');
        $definition['property_sets'] = ['Featured' => ['mode' => 'featured']];
        $this->installRegistry([], [$listener], [modPlugin::class => ['readdplugin' => $definition]]);
        $key = $listener['key'];

        $this->assertTrue($this->modx->removeEventListener('DiskSequence', $key));
        $this->assertTrue($this->modx->addEventListener('DiskSequence', $key, 'FEATURED'));
        $this->assertSame(['readd:request'], $this->modx->invokeEvent('DiskSequence', ['requestValue' => 'request']));
    }

    public function testDiskListenerPublicApiRejectsWrongEventUnknownKeyAndUnknownSet()
    {
        $listener = $this->listener('guarded-add', 0, 'unused');
        $listener['plugin'] = 'GuardedPlugin';
        $definition = $this->pluginDefinition('GuardedPlugin');
        $definition['property_sets'] = ['Featured' => ['mode' => 'featured']];
        $this->installRegistry([], [$listener], [modPlugin::class => ['guardedplugin' => $definition]]);

        $this->assertFalse($this->modx->addEventListener('WrongEvent', $listener['key']));
        $this->assertFalse($this->modx->addEventListener('DiskSequence', 'disk:phase0/tests:listener:missing'));
        $this->assertFalse($this->modx->addEventListener('DiskSequence', $listener['key'], 'Missing'));
    }

    public function testPublicApiStillAddsNumericPluginForDatabaseBackedEvent()
    {
        $eventName = 'Phase2PublicDatabase' . bin2hex(random_bytes(5));
        $this->createDatabaseEvent($eventName, 3, 'Phase2');
        $plugin = $this->createDatabasePlugin('return "database";', false);

        $this->assertTrue($this->modx->addEventListener($eventName, $plugin->get('id'), 'Named'));
        $pluginId = (string) $plugin->get('id');
        $this->assertSame($pluginId . ':Named', $this->modx->eventMap[$eventName][$pluginId]);
    }

    public function testPublicApiStillAddsNumericPluginForOrdinaryLegacyRuntimeEvent()
    {
        $eventName = 'Phase2LegacyRuntime' . bin2hex(random_bytes(5));
        $this->modx->setDefinitionRegistry(new DefinitionRegistry());

        $this->assertTrue($this->modx->addEventListener($eventName, 42, 'Named'));
        $this->assertSame('42:Named', $this->modx->eventMap[$eventName][42]);
    }

    public function testDatabaseEventMetadataWinsAndProducesDeterministicDiagnostics()
    {
        $eventName = 'Phase2Metadata' . bin2hex(random_bytes(5));
        $this->createDatabaseEvent($eventName, 3, 'Database');
        $listener = $this->listener('metadata', 0, 'metadata');
        $listener['event'] = $eventName;
        $this->installRegistry([
            $eventName => [
                'name' => $eventName,
                'package' => 'phase0/tests',
                'metadata' => ['service' => 'mgr', 'group' => 'Disk'],
            ],
        ], [$listener]);

        $diagnostics = $this->modx->getDefinitionEventDispatcher()->getDiagnostics();
        $this->assertSame(['service', 'group'], array_column($diagnostics, 'field'));
        $this->assertSame(['web', 'Database'], array_column($diagnostics, 'database'));
        $this->assertArrayHasKey($listener['key'], $this->modx->eventMap[$eventName]);
    }

    public function testStrictMetadataValidationRejectsConflictBeforeRegistryReplacement()
    {
        $eventName = 'Phase2Strict' . bin2hex(random_bytes(5));
        $this->createDatabaseEvent($eventName, 3, 'Database');
        $activeListener = $this->listener('active-before-strict-rejection', 0, 'active');
        $this->installRegistry([], [$activeListener]);
        $activeRegistry = $this->modx->getDefinitionRegistry();
        $activeMap = $this->modx->eventMap;
        $candidate = $this->registry([
            $eventName => [
                'name' => $eventName,
                'package' => 'phase0/tests',
                'metadata' => ['service' => 'mgr'],
            ],
        ], []);
        $config = new \ReflectionProperty($this->modx, '_config');
        $config->setAccessible(true);
        $original = $config->getValue($this->modx);
        $changed = $original;
        $changed['definition_strict_validation'] = true;
        $config->setValue($this->modx, $changed);

        try {
            $this->modx->setDefinitionRegistry($candidate);
            $this->fail('Strict metadata validation did not reject the conflicting registry.');
        } catch (\RuntimeException $exception) {
            $this->assertStringContainsString('from package phase0/tests on service', $exception->getMessage());
            $this->assertSame($activeRegistry, $this->modx->getDefinitionRegistry());
            $this->assertSame($activeMap, $this->modx->eventMap);
        } finally {
            $config->setValue($this->modx, $original);
        }
    }

    public function testDiskOnlyEventServiceFiltersManagerAndWebContexts()
    {
        $eventName = 'Phase2DiskService' . bin2hex(random_bytes(5));
        $listener = $this->listener('service-filter', 0, 'service');
        $listener['event'] = $eventName;
        $registry = $this->registry([
            $eventName => [
                'name' => $eventName,
                'package' => 'phase0/tests',
                'metadata' => ['service' => 'mgr'],
            ],
        ], [$listener]);
        $dispatcher = new EventDispatcher($this->modx, $registry);
        $managerMap = [];
        $webMap = [];

        $dispatcher->activateContext('mgr', $managerMap);
        $dispatcher->activateContext('web', $webMap);

        $this->assertArrayHasKey($listener['key'], $managerMap[$eventName]);
        $this->assertArrayNotHasKey($eventName, $webMap);
    }

    public function testDatabaseEventServiceTwoThreeAndOtherFilterBindingsByContext()
    {
        $plugin = $this->createDatabasePlugin('return "database";', false);
        $services = [0 => [false, false], 2 => [true, false], 3 => [false, true], 7 => [false, false]];
        foreach ($services as $service => [$managerExpected, $webExpected]) {
            $eventName = 'Phase2Service' . $service . bin2hex(random_bytes(5));
            $this->createDatabaseEvent($eventName, $service, 'Phase2');
            $binding = $this->modx->newObject(modPluginEvent::class);
            $binding->set('pluginid', $plugin->get('id'));
            $binding->set('event', $eventName);
            $this->assertTrue($binding->save());

            $this->assertSame($managerExpected, isset($this->modx->getEventMap('mgr')[$eventName]));
            $this->assertSame($webExpected, isset($this->modx->getEventMap('web')[$eventName]));
        }
    }

    public function testDiskListenersOnDatabaseEventsUseStockDatabaseServiceActivation()
    {
        foreach ([0, 7] as $service) {
            $eventName = 'Phase2DiskCollisionService' . $service . bin2hex(random_bytes(5));
            $this->createDatabaseEvent($eventName, $service, 'Phase2');
            $listener = $this->listener('collision-service-' . $service, 0, 'disk');
            $listener['event'] = $eventName;
            $registry = $this->registry([$eventName => [
                'name' => $eventName,
                'package' => 'phase0/tests',
                'metadata' => [],
            ]], [$listener]);
            $dispatcher = new EventDispatcher($this->modx, $registry);
            $managerMap = [];
            $webMap = [];

            $dispatcher->activateContext('mgr', $managerMap);
            $dispatcher->activateContext('web', $webMap);

            $this->assertArrayNotHasKey($eventName, $managerMap);
            $this->assertArrayNotHasKey($eventName, $webMap);
        }
    }

    public function testRowlessEventDoesNotLoadDatabaseBindingButDoesLoadDiskListener()
    {
        $eventName = 'Phase2Rowless' . bin2hex(random_bytes(5));
        $plugin = $this->createDatabasePlugin('return "database";', false);
        $binding = $this->modx->newObject(modPluginEvent::class);
        $binding->set('pluginid', $plugin->get('id'));
        $binding->set('event', $eventName);
        $this->assertTrue($binding->save());
        $this->assertArrayNotHasKey($eventName, $this->modx->getEventMap('web'));
        $pluginId = (string) $plugin->get('id');
        $this->modx->eventMap[$eventName] = [$pluginId => $pluginId];

        $listener = $this->listener('rowless', 0, 'disk');
        $listener['event'] = $eventName;
        $this->installRegistry([], [$listener]);

        $this->assertFalse($this->modx->addEventListener($eventName, $plugin->get('id')));
        $this->assertSame([$listener['key']], array_keys($this->modx->eventMap[$eventName]));
        $this->modx->eventMap[$eventName][$pluginId] = $pluginId;
        $this->assertSame(['disk:request'], $this->modx->invokeEvent($eventName, ['requestValue' => 'request']));
    }

    public function testDatabaseBindingUsesPluginAndEventAsItsUniquenessKey()
    {
        $plugin = $this->createDatabasePlugin('return "database";', false);
        $eventName = 'Phase2Unique' . bin2hex(random_bytes(5));
        $first = $this->modx->newObject(modPluginEvent::class);
        $first->set('pluginid', $plugin->get('id'));
        $first->set('event', $eventName);
        $this->assertTrue($first->save());
        $duplicate = $this->modx->newObject(modPluginEvent::class);
        $duplicate->set('pluginid', $plugin->get('id'));
        $duplicate->set('event', $eventName);

        $this->assertFalse($duplicate->save());
    }

    public function testStrictValidationCoreConfigMustBeBoolean()
    {
        $config = new \ReflectionProperty($this->modx, '_config');
        $config->setAccessible(true);
        $original = $config->getValue($this->modx);
        $changed = $original;
        $changed['definition_strict_validation'] = 'true';
        $config->setValue($this->modx, $changed);

        try {
            $this->expectException(\RuntimeException::class);
            $this->expectExceptionMessage('definition_strict_validation must be a Boolean');
            $this->modx->setDefinitionRegistry(new DefinitionRegistry());
        } finally {
            $config->setValue($this->modx, $original);
        }
    }

    public function testReplacingRegistryRemovesProjectedDiskKeysAndPreservesEventMapReference()
    {
        $listener = $this->listener('stale', 0, 'stale');
        $this->installRegistry(['DiskSequence' => ['name' => 'DiskSequence']], [$listener]);
        $eventMap =& $this->modx->eventMap;
        $this->assertArrayHasKey($listener['key'], $eventMap['DiskSequence']);

        $this->modx->setDefinitionRegistry(new DefinitionRegistry());
        $this->assertSame($eventMap, $this->modx->eventMap);
        $this->assertArrayNotHasKey('DiskSequence', $eventMap);
        $this->assertFalse($this->modx->invokeEvent('DiskSequence'));
    }

    private function listener(string $key, int $priority, string $marker): array
    {
        return [
            'key' => 'disk:phase0/tests:listener:' . $key,
            'listener_key' => $key,
            'source' => 'disk',
            'package' => 'phase0/tests',
            'event' => 'DiskSequence',
            'priority' => $priority,
            'contexts' => [],
            'file' => __FILE__,
            'relative_file' => basename(__FILE__),
            'content' => '<?php $modx->event->output($marker . ":" . $requestValue);',
            'service' => null,
            'plugin' => $key,
            'properties' => ['marker' => $marker],
        ];
    }

    /**
     * Build a modX mock whose prepare() is controlled by $prepare while cache
     * manager access and option/table lookups keep working.
     */
    private function failingModx(RecordingCacheManager $cacheManager, callable $prepare): modX
    {
        $modx = $this->modx;
        $failingModx = $this->getMockBuilder(modX::class)
            ->disableOriginalConstructor()
            ->onlyMethods(['getCacheManager', 'getOption', 'getTableName', 'prepare', 'log'])
            ->getMock();
        $failingModx->method('getCacheManager')->willReturn($cacheManager);
        $failingModx->method('getOption')->willReturnCallback(
            static fn($key, $options = null, $default = null, $skipEmpty = false) => $modx->getOption(
                $key,
                $options,
                $default,
                $skipEmpty
            )
        );
        $failingModx->method('getTableName')->willReturnCallback(
            static fn($className) => $modx->getTableName($className)
        );
        $failingModx->method('prepare')->willReturnCallback($prepare);

        return $failingModx;
    }

    private function enableArtifactDatabaseFactsCache(): void
    {
        $config = new \ReflectionProperty($this->modx, '_config');
        $config->setAccessible(true);
        $current = $config->getValue($this->modx);
        if (!is_array($current)) {
            $current = [];
        }
        $this->originalTrustedDefinitionConfig ??= $current;
        $current['definition_registry_artifact'] = '/release/immutable-registry.php';
        $config->setValue($this->modx, $current);
    }

    private function installRegistry(array $events, array $listeners, array $definitions = []): void
    {
        $this->modx->setDefinitionRegistry($this->registry($events, $listeners, $definitions));
    }

    private function registry(array $events, array $listeners, array $definitions = []): DefinitionRegistry
    {
        $indexedListeners = [];
        foreach ($listeners as $listener) {
            $indexedListeners[$listener['key']] = $listener;
        }
        return new DefinitionRegistry([
            'schema' => 1,
            'release_hash' => hash('sha256', serialize([$events, $indexedListeners])),
            'definitions' => $definitions,
            'events' => $events,
            'listeners' => $indexedListeners,
            'inventory' => [],
        ]);
    }

    private function createDatabaseEvent(string $name, int $service, string $group): modEvent
    {
        $event = $this->modx->newObject(modEvent::class);
        $event->set('name', $name);
        $event->set('service', $service);
        $event->set('groupname', $group);
        $this->assertTrue($event->save());
        $this->databaseEvents[] = $event;

        return $event;
    }

    private function createDatabasePlugin(string $code, bool $disabled, ?int $priority = null): modPlugin
    {
        $plugin = $this->modx->newObject(modPlugin::class);
        $plugin->set('name', 'Phase0Plugin' . bin2hex(random_bytes(5)));
        $plugin->setContent($code);
        $plugin->set('disabled', $disabled);
        $this->assertTrue($plugin->save());
        $this->databasePlugins[] = $plugin;

        if ($priority !== null) {
            $binding = $this->modx->newObject(modPluginEvent::class);
            $binding->set('pluginid', $plugin->get('id'));
            $binding->set('event', 'DiskSequence');
            $binding->set('priority', $priority);
            $this->assertTrue($binding->save());
        }

        return $plugin;
    }

    private function pluginDefinition(string $name): array
    {
        $content = '<?php return $modx->event->output("definition");';

        return [
            'key' => 'disk:phase0/tests:plugin:' . $name,
            'source' => 'disk',
            'package' => 'phase0/tests',
            'manifest' => __FILE__,
            'file' => __FILE__,
            'relative_file' => basename(__FILE__),
            'content_hash' => hash('sha256', $content),
            'content' => $content,
            'type' => 'plugin',
            'class' => modPlugin::class,
            'name' => $name,
            'normalized_name' => strtolower($name),
            'properties' => [],
            'property_sets' => [],
            'media_source' => null,
        ];
    }
}
