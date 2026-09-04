<?php

namespace MODX\Revolution;

use MODX\Revolution\Definition\DefinitionRegistry;
use MODX\Revolution\Definition\DefinitionManifestCompiler;
use MODX\Revolution\Definition\DefinitionRegistryArtifact;
use MODX\Revolution\Definition\DefinitionRegistryDeployment;
use MODX\Revolution\Definition\EventDispatcher;
use xPDO\xPDO;

require_once dirname(__DIR__) . '/MODxTestHarness.php';

$modx = MODxTestHarness::getFixture(modX::class, 'definition-registry-benchmark');
$modx->getParser();
$name = 'DefinitionBenchmark' . bin2hex(random_bytes(5));
$database = $modx->newObject(modSnippet::class);
$database->set('name', $name . 'Database');
$database->setContent('return "database";');
if (!$database->save()) {
    throw new \RuntimeException('Could not create the database benchmark snippet.');
}

$content = '<?php return "disk";';
$definition = [
    'key' => 'disk:phase0/benchmark:snippet:' . $name . 'Disk',
    'source' => 'disk',
    'package' => 'phase0/benchmark',
    'manifest' => __FILE__,
    'file' => __FILE__,
    'relative_file' => basename(__FILE__),
    'content_hash' => hash('sha256', $content),
    'content' => $content,
    'type' => 'snippet',
    'class' => modSnippet::class,
    'name' => $name . 'Disk',
    'normalized_name' => strtolower($name . 'Disk'),
    'properties' => [],
    'property_sets' => [],
    'media_source' => null,
];
$registry = new DefinitionRegistry([
    'schema' => 1,
    'release_hash' => hash('sha256', serialize($definition)),
    'definitions' => [
        modSnippet::class => [$definition['normalized_name'] => $definition],
    ],
    'events' => [],
    'listeners' => [],
    'inventory' => [],
]);
$modx->setDefinitionRegistry($registry);
$modx->sourceCache[modSnippet::class] = [];
$modx->getElement(modSnippet::class, $database->get('name'));
$modx->getElement(modSnippet::class, $definition['name']);
$compileRoot = sys_get_temp_dir() . '/modx-definition-benchmark-' . bin2hex(random_bytes(5));
mkdir($compileRoot . '/elements', 0777, true);
file_put_contents($compileRoot . '/elements/Benchmark.php', '<?php return "benchmark";');
file_put_contents(
    $compileRoot . '/modx.php',
    '<?php return ['
        . '"schema" => 1, "package" => "phase0/benchmark", "root" => __DIR__, '
        . '"elements" => ["snippets" => ["Benchmark" => ["file" => "elements/Benchmark.php"]]]'
        . '];'
);
$compiler = new DefinitionManifestCompiler();
$artifact = new DefinitionRegistryArtifact();
$compiledCatalog = $compiler->compile([$compileRoot . '/modx.php']);
$artifactPath = $compileRoot . '/' . $compiledCatalog['release_hash'] . '.php';
$artifact->write($artifactPath, $compiledCatalog);
$deployment = new DefinitionRegistryDeployment([
    'definition_manifests' => [$compileRoot . '/modx.php'],
    'definition_registry_artifact' => $artifactPath,
    'definition_registry_artifact_dir' => $compileRoot,
], $modx, $compiler, $artifact);

$measure = static function (callable $operation, int $iterations): array {
    $start = hrtime(true);
    for ($index = 0; $index < $iterations; $index++) {
        $operation();
    }
    $elapsed = hrtime(true) - $start;

    return [
        'iterations' => $iterations,
        'elapsed_ms' => round($elapsed / 1_000_000, 3),
        'microseconds_per_operation' => round($elapsed / $iterations / 1_000, 3),
    ];
};

/**
 * Repeat a timed loop and report the median per-operation wall time, plus the
 * total query count and net memory growth across every repeat.
 */
$benchmark = static function (callable $operation, int $iterations, int $repeats = 5) use ($modx): array {
    $operation();
    $queriesBefore = $modx->executedQueries;
    $memoryBefore = memory_get_usage();
    $samples = [];
    for ($repeat = 0; $repeat < $repeats; $repeat++) {
        $start = hrtime(true);
        for ($index = 0; $index < $iterations; $index++) {
            $operation();
        }
        $samples[] = (hrtime(true) - $start) / $iterations / 1_000;
    }
    sort($samples);

    return [
        'iterations_per_repeat' => $iterations,
        'repeats' => $repeats,
        'median_microseconds_per_operation' => round($samples[intdiv($repeats, 2)], 3),
        'min_microseconds_per_operation' => round($samples[0], 3),
        'max_microseconds_per_operation' => round($samples[$repeats - 1], 3),
        'queries_across_all_repeats' => $modx->executedQueries - $queriesBefore,
        'memory_delta_bytes' => memory_get_usage() - $memoryBefore,
    ];
};

/* Fabricated database plugins served from modX::$pluginCache, mirroring the
 * production warm path where plugin rows hydrate from the context cache. */
$fabricatedPluginIds = [];
for ($index = 0; $index < 20; $index++) {
    $pluginId = 900001 + $index;
    $pluginPrototype = $modx->newObject(modPlugin::class);
    $pluginPrototype->set('name', $name . 'EventPlugin' . $index);
    $pluginPrototype->setContent('return;');
    $modx->pluginCache[$pluginId] = array_merge(
        $pluginPrototype->toArray('', true),
        ['id' => $pluginId]
    );
    $fabricatedPluginIds[] = $pluginId;
}
$databaseEvent0 = $name . 'Dispatch0';
$databaseEvent1 = $name . 'Dispatch1';
$databaseEvent20 = $name . 'Dispatch20';
$diskEventName = $name . 'DiskDispatch';
$mixedEventName = $name . 'MixedDispatch';
$databaseBindings = [];
foreach ($fabricatedPluginIds as $pluginId) {
    $databaseBindings[(string) $pluginId] = (string) $pluginId;
}
$modx->eventMap = [
    $databaseEvent0 => [],
    $databaseEvent1 => array_slice($databaseBindings, 0, 1, true),
    $databaseEvent20 => $databaseBindings,
];
$diskListener = static fn(string $key, string $eventName, string $plugin): array => [
    'key' => 'disk:phase0/benchmark:listener:' . $key,
    'listener_key' => $key,
    'source' => 'disk',
    'package' => 'phase0/benchmark',
    'event' => $eventName,
    'priority' => 0,
    'contexts' => [],
    'file' => __FILE__,
    'relative_file' => basename(__FILE__),
    'content' => '<?php return;',
    'service' => null,
    'plugin' => $plugin,
    'properties' => [],
];
$diskListeners = [
    'disk:phase0/benchmark:listener:solo' => $diskListener('solo', $diskEventName, $name . 'DiskSolo'),
    'disk:phase0/benchmark:listener:mixed' => $diskListener('mixed', $mixedEventName, $name . 'DiskMixed'),
];
$diskRegistry = new DefinitionRegistry([
    'schema' => 1,
    'release_hash' => hash('sha256', serialize($diskListeners)),
    'definitions' => [],
    'events' => [
        $diskEventName => ['name' => $diskEventName, 'package' => 'phase0/benchmark', 'metadata' => []],
        $mixedEventName => ['name' => $mixedEventName, 'package' => 'phase0/benchmark', 'metadata' => []],
    ],
    'listeners' => $diskListeners,
    'inventory' => [],
]);
$mixedEvent = $modx->newObject(modEvent::class);
$mixedEvent->set('name', $mixedEventName);
$mixedEvent->set('service', 1);
$mixedEvent->set('groupname', 'Benchmark');
if (!$mixedEvent->save()) {
    throw new \RuntimeException('Could not create the mixed benchmark event row.');
}

try {
    $results = [
        'database_warm_named_lookup' => $measure(
            static fn() => $modx->getElement(modSnippet::class, $database->get('name')),
            1000
        ),
        'disk_warm_named_lookup' => $measure(
            static fn() => $modx->getElement(modSnippet::class, $definition['name']),
            1000
        ),
        'registry_keyed_lookup' => $measure(
            static fn() => $registry->getDefinition(modSnippet::class, $definition['name']),
            100000
        ),
        'resource_hash_comparison' => $measure(
            static fn() => $modx->isDefinitionRegistryCacheCompatible([
                'definitionRegistryHash' => $registry->getReleaseHash(),
            ]),
            100000
        ),
        'cold_manifest_compilation' => $measure(
            static fn() => $compiler->compile([$compileRoot . '/modx.php']),
            100
        ),
        'warm_artifact_load' => $measure(
            static fn() => $artifact->load($artifactPath),
            1000
        ),
        'deployment_hash_diagnostic' => $measure(
            static fn() => $deployment->hash(),
            100
        ),
        'deployment_registry_listing' => $measure(
            static fn() => $deployment->list(),
            100
        ),
    ];

    /* invokeEvent dispatch with the definition registry inactive: the
     * legacy-equivalent database-only path (an empty DefinitionRegistry). */
    $modx->setDefinitionRegistry(new DefinitionRegistry());
    $results['invoke_event_0_db_listeners_registry_inactive'] = $benchmark(
        static fn() => $modx->invokeEvent($databaseEvent0),
        20000
    );
    $results['invoke_event_1_db_listener_registry_inactive'] = $benchmark(
        static fn() => $modx->invokeEvent($databaseEvent1),
        5000
    );
    $results['invoke_event_20_db_listeners_registry_inactive'] = $benchmark(
        static fn() => $modx->invokeEvent($databaseEvent20),
        1000
    );

    /* The same database-only events with a non-empty definition registry active
     * (one disk snippet definition, no disk listeners on these events). */
    $modx->setDefinitionRegistry($registry);
    $results['invoke_event_0_db_listeners_registry_active'] = $benchmark(
        static fn() => $modx->invokeEvent($databaseEvent0),
        20000
    );
    $results['invoke_event_1_db_listener_registry_active'] = $benchmark(
        static fn() => $modx->invokeEvent($databaseEvent1),
        5000
    );
    $results['invoke_event_20_db_listeners_registry_active'] = $benchmark(
        static fn() => $modx->invokeEvent($databaseEvent20),
        1000
    );

    /* Disk-only and mixed listener sequences with a listener-bearing registry. */
    $modx->eventMap[$mixedEventName] = array_slice($databaseBindings, 0, 1, true);
    $modx->setDefinitionRegistry($diskRegistry);
    $results['invoke_event_1_disk_listener'] = $benchmark(
        static fn() => $modx->invokeEvent($diskEventName),
        5000
    );
    $results['invoke_event_mixed_1_db_1_disk'] = $benchmark(
        static fn() => $modx->invokeEvent($mixedEventName),
        5000
    );

    /* Context activation: empty registry, a fresh dispatcher per activation
     * (initial activation including the persistent facts-cache read), and
     * repeated switching on one dispatcher. */
    $activationBaseMap = $modx->eventMap;
    $emptyDispatcher = new EventDispatcher($modx, new DefinitionRegistry());
    $results['context_activation_empty_registry'] = $benchmark(
        static function () use ($emptyDispatcher, $activationBaseMap): void {
            $emptyDispatcher->activateContext('web', $activationBaseMap);
        },
        5000
    );
    $results['context_activation_fresh_dispatcher'] = $benchmark(
        static function () use ($modx, $diskRegistry, $activationBaseMap): void {
            (new EventDispatcher($modx, $diskRegistry))->activateContext('web', $activationBaseMap);
        },
        2000
    );
    $switchDispatcher = new EventDispatcher($modx, $diskRegistry);
    $results['context_activation_repeated_switch'] = $benchmark(
        static function () use ($switchDispatcher, $activationBaseMap): void {
            $switchDispatcher->activateContext('web', $activationBaseMap);
        },
        5000
    );

    $results['environment'] = [
        'php' => PHP_VERSION,
        'cache_handler' => $modx->getOption(xPDO::OPT_CACHE_HANDLER, null, 'xPDO\\Cache\\xPDOFileCache'),
        'database' => $modx->query('SELECT VERSION()')->fetchColumn(),
    ];
    echo json_encode($results, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES) . PHP_EOL;
} finally {
    $mixedEvent->remove();
    $database->remove();
    unlink($compileRoot . '/elements/Benchmark.php');
    unlink($compileRoot . '/modx.php');
    unlink($artifactPath);
    rmdir($compileRoot . '/elements');
    rmdir($compileRoot);
}
