<?php

namespace MODX\Revolution\Tests\Model\Definition;

use MODX\Revolution\Definition\DefinitionRegistry;
use MODX\Revolution\Definition\DefinitionRegistryInspector;
use MODX\Revolution\modEvent;
use MODX\Revolution\modPlugin;
use MODX\Revolution\modSnippet;
use MODX\Revolution\modX;
use MODX\Revolution\MODxTestCase;
use MODX\Revolution\Processors\System\Definition\GetList;
use InvalidArgumentException;

class DefinitionRegistryInspectorTest extends MODxTestCase
{
    private string $name;
    private string $eventName;
    private string $suppressedName;
    private ?DefinitionRegistry $previousRegistry = null;

    /** @before */
    public function setUpFixtures()
    {
        parent::setUpFixtures();
        $this->name = 'UnitTestInspector' . bin2hex(random_bytes(4));
        $this->suppressedName = $this->name . 'Suppressed';
        $this->eventName = $this->name . 'Event';
        foreach ([$this->name, $this->suppressedName] as $pluginName) {
            $plugin = $this->modx->newObject(modPlugin::class);
            $plugin->set('name', $pluginName);
            $plugin->set('disabled', $pluginName === $this->suppressedName);
            $plugin->setContent('');
            $this->assertTrue($plugin->save());
        }
        $event = $this->modx->newObject(modEvent::class);
        $event->set('name', $this->eventName);
        $event->set('service', 6);
        $event->set('groupname', 'Unit Test');
        $this->assertTrue($event->save());
    }

    /** @after */
    public function tearDownFixtures()
    {
        foreach (
            $this->modx->getCollection(modPlugin::class, [
                'name:IN' => [$this->name, $this->suppressedName],
            ]) as $plugin
        ) {
            $plugin->remove();
        }
        foreach ($this->modx->getCollection(modEvent::class, ['name' => $this->eventName]) as $event) {
            $event->remove();
        }
        $this->modx->error->reset();
        if ($this->previousRegistry instanceof DefinitionRegistry) {
            $this->modx->setDefinitionRegistry($this->previousRegistry);
        }
        parent::tearDownFixtures();
    }

    public function testItReturnsStableReadOnlyRecordsAndCollisionStates()
    {
        $registry = new DefinitionRegistry([
            'release_hash' => hash('sha256', 'inspector'),
            'definitions' => [modPlugin::class => [strtolower($this->name) => [
                'key' => 'disk:test/inspector:plugin:' . $this->name,
                'class' => modPlugin::class,
                'type' => 'plugin', 'name' => $this->name, 'package' => 'test/inspector',
                'manifest' => '/release/definitions.php', 'relative_file' => 'plugin.php',
            ]], modSnippet::class => ['diskonly' => [
                'key' => 'disk:test/inspector:snippet:DiskOnly', 'class' => modSnippet::class,
                'type' => 'snippet', 'name' => 'DiskOnly', 'package' => 'test/inspector',
                'manifest' => '/release/definitions.php', 'relative_file' => 'snippet.php',
            ]]],
            'events' => [$this->eventName => [
                'name' => $this->eventName, 'package' => 'test/inspector',
                'manifest' => '/release/definitions.php', 'metadata' => [],
            ]],
            'listeners' => ['disk:test/inspector:listener:probe' => [
                'key' => 'disk:test/inspector:listener:probe', 'listener_key' => 'probe',
                'package' => 'test/inspector', 'event' => $this->eventName, 'contexts' => [],
                'plugin' => $this->name, 'relative_file' => 'listener.php',
                'priority' => 0, 'service' => null,
            ], 'disk:test/inspector:listener:suppressed' => [
                'key' => 'disk:test/inspector:listener:suppressed', 'listener_key' => 'suppressed',
                'package' => 'test/inspector', 'event' => $this->eventName, 'contexts' => [],
                'plugin' => $this->suppressedName, 'relative_file' => 'suppressed.php',
                'priority' => 1, 'service' => null,
            ]],
            'inventory' => ['test/inspector' => ['manifest' => ['path' => 'definitions.php']]],
        ]);

        $data = (new DefinitionRegistryInspector($this->modx, $registry))->list(['limit' => 0]);
        $this->assertSame($registry->getReleaseHash(), $data['release_hash']);
        $this->assertSame(5, $data['total']);
        $this->assertSame([
            'disk:test/inspector:event:' . $this->eventName,
            'disk:test/inspector:listener:probe',
            'disk:test/inspector:listener:suppressed',
            'disk:test/inspector:plugin:' . $this->name,
            'disk:test/inspector:snippet:DiskOnly',
        ], array_column($data['results'], 'key'));
        $byKey = array_column($data['results'], null, 'key');
        $this->assertSame(
            'database-default',
            $byKey['disk:test/inspector:plugin:' . $this->name]['collision_state']
        );
        $this->assertSame(
            'disk-suppressed-by-database',
            $byKey['disk:test/inspector:listener:probe']['collision_state']
        );
        $this->assertSame(
            'disk-suppressed-by-database',
            $byKey['disk:test/inspector:listener:suppressed']['collision_state']
        );
        $this->assertFalse($byKey['disk:test/inspector:listener:probe']['database_disabled']);
        $this->assertTrue($byKey['disk:test/inspector:listener:suppressed']['database_disabled']);
        $this->assertSame(
            'database-shared',
            $byKey['disk:test/inspector:event:' . $this->eventName]['collision_state']
        );
        $this->assertSame('definitions.php', $byKey['disk:test/inspector:snippet:DiskOnly']['manifest']);
        $this->assertArrayNotHasKey('content', $byKey['disk:test/inspector:snippet:DiskOnly']);
        $this->assertArrayNotHasKey('properties', $byKey['disk:test/inspector:snippet:DiskOnly']);
        $this->assertSame('disk', $byKey['disk:test/inspector:snippet:DiskOnly']['source']);
        $this->assertSame('disk-only', $byKey['disk:test/inspector:snippet:DiskOnly']['collision_state']);

        $page = (new DefinitionRegistryInspector($this->modx, $registry))->list([
            'kind' => 'elements', 'sort' => 'name', 'start' => 1, 'limit' => 1,
        ]);
        $this->assertSame(2, $page['total']);
        $this->assertCount(1, $page['results']);
        $this->assertSame($this->name, $page['results'][0]['name']);
    }

    public function testDirectZeroLimitUsesTheDefaultFinitePageSize()
    {
        $definitions = [];
        for ($index = 0; $index < 21; $index++) {
            $name = 'Paged' . $index;
            $definitions[strtolower($name)] = [
                'key' => 'disk:test/paging:snippet:' . $name,
                'class' => modSnippet::class,
                'type' => 'snippet',
                'name' => $name,
                'package' => 'test/paging',
            ];
        }
        $registry = new DefinitionRegistry(['definitions' => [modSnippet::class => $definitions]]);

        $data = (new DefinitionRegistryInspector($this->modx, $registry))->list(['limit' => 0]);

        $this->assertSame(21, $data['total']);
        $this->assertCount(20, $data['results']);
    }

    public function testManagerGetListUsesTheActiveRegistryAndTheStandardEnvelope()
    {
        $registry = new DefinitionRegistry([
            'release_hash' => hash('sha256', 'processor-inspector'),
            'definitions' => [modSnippet::class => ['diskonly' => [
                'key' => 'disk:test/processor:snippet:DiskOnly', 'class' => modSnippet::class,
                'type' => 'snippet', 'name' => 'DiskOnly', 'package' => 'test/processor',
            ]]],
            'events' => ['ProcessorEvent' => [
                'name' => 'ProcessorEvent', 'package' => 'test/processor', 'metadata' => [],
            ]],
            'listeners' => ['disk:test/processor:listener:probe' => [
                'key' => 'disk:test/processor:listener:probe', 'listener_key' => 'probe',
                'package' => 'test/processor', 'event' => 'ProcessorEvent', 'contexts' => [],
                'plugin' => 'ProcessorPlugin', 'relative_file' => 'listener.php',
                'priority' => 0, 'service' => null,
            ]],
            'inventory' => ['test/processor' => ['manifest' => ['path' => 'modx.php']]],
        ]);
        $this->previousRegistry = $this->modx->getDefinitionRegistry();
        $this->modx->setDefinitionRegistry($registry);
        $response = $this->modx->runProcessor(GetList::class, ['kind' => 'elements', 'limit' => 1]);

        $this->assertTrue($this->checkForSuccess($response), $response->getMessage());
        $results = $this->getResults($response);
        $this->assertCount(1, $results);
        $this->assertSame($registry->getReleaseHash(), $results[0]['release_hash']);
        $this->assertSame('definition', $results[0]['kind']);
        foreach (['events' => 'event', 'listeners' => 'listener'] as $kind => $expectedKind) {
            $response = $this->modx->runProcessor(GetList::class, ['kind' => $kind, 'limit' => 1]);
            $this->assertTrue($this->checkForSuccess($response), $response->getMessage());
            $this->assertSame($expectedKind, $this->getResults($response)[0]['kind']);
        }
    }

    /**
     * @dataProvider invalidKindProvider
     */
    public function testInvalidKindIsRejected($kind)
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Unsupported definition registry kind');

        (new DefinitionRegistryInspector($this->modx))->list(['kind' => $kind]);
    }

    public function invalidKindProvider(): array
    {
        return [
            'unknown' => ['unknown'],
            'empty' => [''],
            'non-string' => [[]],
        ];
    }

    public function testManagerGetListRejectsInvalidKinds()
    {
        foreach (['unknown', '', []] as $kind) {
            $response = $this->modx->runProcessor(GetList::class, ['kind' => $kind]);

            $this->assertFalse($this->checkForSuccess($response));
            $this->assertStringContainsString('Unsupported definition registry kind', $response->getMessage());
            $this->modx->error->reset();
        }
    }

    public function testEveryValidKindReturnsAnEmptySuccessEnvelope()
    {
        $this->previousRegistry = $this->modx->getDefinitionRegistry();
        $this->modx->setDefinitionRegistry(new DefinitionRegistry());

        foreach (['elements', 'events', 'listeners'] as $kind) {
            $response = $this->modx->runProcessor(GetList::class, ['kind' => $kind]);

            $this->assertTrue($this->checkForSuccess($response), $response->getMessage());
            $this->assertSame([], $this->getResults($response));
        }
    }

    public function testListenerImpliedEventsAreVisible()
    {
        $eventName = 'ImpliedEvent' . bin2hex(random_bytes(4));
        $registry = new DefinitionRegistry([
            'listeners' => ['disk:test/implied:listener:probe' => [
                'key' => 'disk:test/implied:listener:probe',
                'listener_key' => 'probe',
                'package' => 'test/implied',
                'event' => $eventName,
                'contexts' => [],
                'plugin' => 'ImpliedPlugin',
                'priority' => 0,
                'service' => null,
            ]],
        ]);

        $data = (new DefinitionRegistryInspector($this->modx, $registry))->list([
            'kind' => 'events',
            'limit' => 0,
        ]);

        $this->assertSame(1, $data['total']);
        $this->assertSame('disk:test/implied:event:' . $eventName, $data['results'][0]['key']);
    }

    public function testCollisionChecksAreBatchedAfterFiltering()
    {
        $registry = new DefinitionRegistry([
            'definitions' => [modSnippet::class => [
                'matchedone' => [
                    'key' => 'disk:test/batch:snippet:MatchedOne',
                    'class' => modSnippet::class,
                    'type' => 'snippet',
                    'name' => 'MatchedOne',
                    'package' => 'test/batch',
                ],
                'matchedtwo' => [
                    'key' => 'disk:test/batch:snippet:MatchedTwo',
                    'class' => modSnippet::class,
                    'type' => 'snippet',
                    'name' => 'MatchedTwo',
                    'package' => 'test/batch',
                ],
                'excluded' => [
                    'key' => 'disk:test/batch:snippet:Excluded',
                    'class' => modSnippet::class,
                    'type' => 'snippet',
                    'name' => 'Excluded',
                    'package' => 'test/batch',
                ],
            ]],
        ]);
        $modx = $this->getMockBuilder(modX::class)
            ->disableOriginalConstructor()
            ->onlyMethods(['getTableName', 'prepare'])
            ->getMock();
        $modx->method('getTableName')->with(modSnippet::class)->willReturn('modx_site_snippets');
        $statement = new class {
            private array $rows = [];
            public function execute(array $bindings): bool
            {
                $this->rows = array_map(static fn($name) => ['name' => $name], array_values($bindings));
                return true;
            }
            public function fetch(int $mode)
            {
                return array_shift($this->rows) ?: false;
            }
        };
        $modx->expects($this->once())->method('prepare')->willReturn($statement);

        $data = (new DefinitionRegistryInspector($modx, $registry))->list([
            'kind' => 'elements',
            'query' => 'matched',
            'limit' => 0,
        ]);

        $this->assertSame(2, $data['total']);
    }

    public function testCollisionChecksChunkLargeNameSetsWithNormalizedMatching()
    {
        $definitions = [];
        for ($index = 0; $index < 501; $index++) {
            $name = 'Chunked' . $index;
            $definitions[strtolower($name)] = [
                'key' => 'disk:test/chunk:snippet:' . $name,
                'class' => modSnippet::class,
                'type' => 'snippet',
                'name' => $name,
                'package' => 'test/chunk',
            ];
        }
        $registry = new DefinitionRegistry(['definitions' => [modSnippet::class => $definitions]]);
        $modx = $this->getMockBuilder(modX::class)
            ->disableOriginalConstructor()
            ->onlyMethods(['getTableName', 'prepare'])
            ->getMock();
        $modx->method('getTableName')->with(modSnippet::class)->willReturn('modx_site_snippets');
        $statement = new class {
            private array $rows = [];
            public function execute(array $bindings): bool
            {
                // The database rows come back in a different ASCII case than the
                // disk names; normalized matching must still recognize the twins.
                $this->rows = array_map(
                    static fn($name) => ['name' => strtoupper($name)],
                    array_values($bindings)
                );
                return true;
            }
            public function fetch(int $mode)
            {
                return array_shift($this->rows) ?: false;
            }
        };
        $prepared = [];
        $modx->expects($this->exactly(2))->method('prepare')->willReturnCallback(
            static function (string $query, $options = []) use (&$prepared, $statement) {
                $prepared[] = $query;
                return $statement;
            }
        );

        $data = (new DefinitionRegistryInspector($modx, $registry))->list(['kind' => 'elements', 'limit' => 0]);

        $this->assertSame(501, $data['total']);
        $this->assertSame(
            [500, 1],
            array_map(static fn(string $query): int => substr_count($query, 'LOWER(:'), $prepared),
            'Presence checks must chunk at the shared bulk-query size.'
        );
        $this->assertStringContainsString(
            'LOWER(name) IN (LOWER(:',
            $prepared[0],
            'Matching is explicitly case-folded on both sides so binary and '
            . 'case-insensitive collations resolve identically.'
        );
        foreach ($data['results'] as $record) {
            $this->assertTrue($record['collision']);
            $this->assertSame('database-default', $record['collision_state']);
        }
    }

    public function testManagerGetListChecksViewElementPermission()
    {
        $modx = $this->getMockBuilder(modX::class)
            ->disableOriginalConstructor()
            ->onlyMethods(['hasPermission'])
            ->getMock();
        $modx->expects($this->once())
            ->method('hasPermission')
            ->with('view_element')
            ->willReturn(false);

        $this->assertFalse((new GetList($modx))->checkPermissions());
    }
}
