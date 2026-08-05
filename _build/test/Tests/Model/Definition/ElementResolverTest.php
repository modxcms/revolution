<?php

namespace MODX\Revolution\Tests\Model\Definition;

use MODX\Revolution\Definition\DefinitionManifestCompiler;
use MODX\Revolution\Definition\DefinitionRegistry;
use MODX\Revolution\Definition\DefinitionRegistryInspector;
use MODX\Revolution\Definition\ElementResolverInterface;
use MODX\Revolution\modAccessCategory;
use MODX\Revolution\modChunk;
use MODX\Revolution\modElement;
use MODX\Revolution\modElementPropertySet;
use MODX\Revolution\modCategory;
use MODX\Revolution\modPropertySet;
use MODX\Revolution\modSnippet;
use MODX\Revolution\modParser;
use MODX\Revolution\modUserGroup;
use MODX\Revolution\modX;
use MODX\Revolution\MODxTestCase;

class ElementResolverTest extends MODxTestCase
{
    private string $elementName;
    private array $aclFixtures = [];
    private ?bool $originalSudo = null;
    private ?int $originalSessionState = null;

    /** @before */
    public function setUpFixtures()
    {
        parent::setUpFixtures();
        $this->elementName = 'DiskNativeTest' . bin2hex(random_bytes(5));
        $this->modx->sourceCache = [];
    }

    /** @after */
    public function tearDownFixtures()
    {
        if ($this->originalSudo !== null) {
            $this->modx->user->set('sudo', $this->originalSudo);
            $this->originalSudo = null;
        }
        if ($this->originalSessionState !== null) {
            $sessionState = new \ReflectionProperty(modX::class, '_sessionState');
            $sessionState->setAccessible(true);
            $sessionState->setValue($this->modx, $this->originalSessionState);
            $this->originalSessionState = null;
        }
        foreach ((array) $this->modx->getCollection(modSnippet::class, ['name' => $this->elementName]) as $snippet) {
            $snippet->remove();
        }
        foreach ($this->aclFixtures as $fixture) {
            if ($fixture && !$fixture->isNew()) {
                $fixture->remove();
            }
        }
        $this->aclFixtures = [];
        $this->modx->setDefinitionRegistry(new DefinitionRegistry());
        $this->modx->sourceCache = [];
        parent::tearDownFixtures();
    }

    public function testDiskOnlyLookupUsesPublicApiButNotXpdo()
    {
        $this->installDiskSnippet('return "disk:" . $name;', ['name' => 'Ada']);

        $first = $this->modx->getElement(modSnippet::class, strtolower($this->elementName));
        $second = $this->modx->getElement(modSnippet::class, $this->elementName);

        $this->assertInstanceOf(modSnippet::class, $first);
        $this->assertNotSame($first, $second);
        $this->assertTrue($first->isNew());
        $this->assertSame('disk:Ada', $first->process());
        $this->assertNull($first->getSource());
        $this->assertSame([
            'source' => 'disk',
            'package' => 'phase0/tests',
            'definition_key' => 'disk:phase0/tests:snippet:' . $this->elementName,
            'collision' => false,
            'decision' => 'disk-only',
        ], array_intersect_key($first->getDefinitionMetadata(), array_flip([
            'source',
            'package',
            'definition_key',
            'collision',
            'decision',
        ])));
        $this->assertNull($this->modx->getObject(modSnippet::class, ['name' => $this->elementName]));
        $this->assertSame([], $this->modx->sourceCache, 'Disk elements must never populate sourceCache.');
        $presence = new \ReflectionProperty(\MODX\Revolution\Definition\ElementResolver::class, 'databasePresence');
        $presence->setAccessible(true);
        $this->assertCount(1, $presence->getValue($this->modx->getElementResolver()));
        $this->assertNotSame($first, $second, 'Disk lookups remain fresh while database presence is memoized.');
        $this->assertSame(
            'disk:phase0/tests:snippet:' . $this->elementName,
            $this->modx->getDefinitionRegistry()->getDefinition(modSnippet::class, $this->elementName)['key']
        );
    }

    public function testDiskElementCannotEnterPersistenceOrRemovalPaths()
    {
        $this->installDiskSnippet('return "disk";');
        $element = $this->modx->getElement(modSnippet::class, $this->elementName);

        foreach (['save', 'remove'] as $method) {
            try {
                $element->{$method}();
                $this->fail("Disk element {$method}() must be rejected.");
            } catch (\LogicException $exception) {
                $this->assertStringContainsString('Disk-native definitions', $exception->getMessage());
            }
        }
    }

    public function testDatabaseWinsExactCollision()
    {
        $database = $this->createDatabaseSnippet('return "database";');
        $this->installDiskSnippet('return "disk";');

        $resolved = $this->modx->getElement(modSnippet::class, $this->elementName);

        $this->assertSame($database->get('id'), $resolved->get('id'));
        $this->assertSame('database', $resolved->process());
        $this->assertSame('database', $this->modx->getElementResolver()->getLastDecision()['winner']);
        $this->assertSame('database-default', $this->modx->getElementResolver()->getLastDecision()['reason']);
    }

    public function testDatabaseTwinStoredInDifferentAsciiCaseSuppressesDiskElement()
    {
        $database = $this->modx->newObject(modSnippet::class);
        $database->set('name', strtolower($this->elementName));
        $database->setContent('return "database";');
        $this->assertTrue($database->save());
        $this->installDiskSnippet('return "disk";');

        try {
            $resolved = $this->modx->getElement(modSnippet::class, $this->elementName);

            $this->assertInstanceOf(modSnippet::class, $resolved);
            $this->assertSame('database', $resolved->process());
            $this->assertSame(
                'database-default',
                $this->modx->getElementResolver()->getLastDecision()['reason'],
                'An ASCII case-variant database twin reserves the identity on any collation.'
            );
        } finally {
            $database->remove();
        }
    }

    public function testLowercaseLookupFindsMixedCaseDatabaseTwinBeforeDisk()
    {
        $this->createDatabaseSnippet('return "database";');
        $this->installDiskSnippet('return "disk";');

        $resolved = $this->modx->getElement(modSnippet::class, strtolower($this->elementName));

        $this->assertInstanceOf(modSnippet::class, $resolved);
        $this->assertSame('database', $resolved->process());
        $this->assertSame(
            'database-default',
            $this->modx->getElementResolver()->getLastDecision()['reason']
        );
    }

    /**
     * Twin matching follows DefinitionRegistry::normalizeName() (ASCII lowercase),
     * not the database collation: non-ASCII case variants such as É and é remain
     * distinct identities even where a case-insensitive collation equates them.
     */
    public function testNonAsciiCaseVariantDatabaseElementDoesNotSuppressDiskDefinition()
    {
        $suffix = bin2hex(random_bytes(5));
        $this->elementName = 'éSnippet' . $suffix;
        $database = $this->modx->newObject(modSnippet::class);
        $database->set('name', 'ÉSnippet' . $suffix);
        $database->setContent('return "database";');
        $this->assertTrue($database->save());
        $this->installDiskSnippet('return "disk";');

        try {
            $resolved = $this->modx->getElement(modSnippet::class, $this->elementName);

            $this->assertInstanceOf(modSnippet::class, $resolved);
            $this->assertSame('disk', $resolved->process());
            $this->assertSame('disk-only', $this->modx->getElementResolver()->getLastDecision()['reason']);
        } finally {
            $database->remove();
        }
    }

    public function testCompiledManifestResolvesAndProcessesEndToEnd()
    {
        $root = sys_get_temp_dir() . '/modx-definition-e2e-' . bin2hex(random_bytes(6));
        mkdir($root . '/elements', 0777, true);
        file_put_contents($root . '/elements/snippet.php', '<?php return "compiled:" . $name;');
        $manifestPath = $root . '/manifest.php';
        file_put_contents($manifestPath, '<?php return ' . var_export([
            'schema' => 1,
            'package' => 'phase0/e2e',
            'root' => $root,
            'elements' => [
                'snippets' => [
                    $this->elementName => [
                        'file' => 'elements/snippet.php',
                        'properties' => ['name' => 'Ada'],
                    ],
                ],
            ],
        ], true) . ';');

        try {
            $catalog = (new DefinitionManifestCompiler())->compile([$manifestPath]);
            $this->modx->setDefinitionRegistry(new DefinitionRegistry($catalog));

            $resolved = $this->modx->getElement(modSnippet::class, $this->elementName);

            $this->assertInstanceOf(modSnippet::class, $resolved);
            $this->assertSame('compiled:Ada', $resolved->process());
            $this->assertSame(
                'disk:phase0/e2e:snippet:' . $this->elementName,
                $resolved->getDefinitionMetadata()['definition_key']
            );
        } finally {
            unlink($root . '/elements/snippet.php');
            unlink($manifestPath);
            rmdir($root . '/elements');
            rmdir($root);
        }
    }

    public function testManifestPropertySetsApplyToSnippetsAndChunksWithExpectedPrecedence()
    {
        $root = sys_get_temp_dir() . '/modx-definition-properties-' . bin2hex(random_bytes(6));
        mkdir($root . '/elements', 0777, true);
        $snippetName = $this->elementName . 'PropertySnippet';
        $chunkName = $this->elementName . 'PropertyChunk';
        file_put_contents(
            $root . '/elements/snippet.php',
            '<?php return json_encode(['
                . "'default' => \$default ?? null, 'selected' => \$selected ?? null, 'call' => \$call ?? null"
                . ']);'
        );
        file_put_contents($root . '/elements/chunk.tpl', '[[+default]]|[[+selected]]|[[+call]]');
        $manifestPath = $root . '/manifest.php';
        file_put_contents($manifestPath, '<?php return ' . var_export([
            'schema' => 1,
            'package' => 'phase1/properties',
            'root' => $root,
            'elements' => [
                'snippets' => [
                    $snippetName => [
                        'file' => 'elements/snippet.php',
                        'properties' => ['default' => 'default', 'selected' => 'default', 'call' => 'default'],
                        'property_sets' => [
                            'Named' => ['selected' => 'set', 'call' => 'set'],
                        ],
                    ],
                ],
                'chunks' => [
                    $chunkName => [
                        'file' => 'elements/chunk.tpl',
                        'properties' => ['default' => 'default', 'selected' => 'default', 'call' => 'default'],
                        'property_sets' => [
                            'Named' => ['selected' => 'set', 'call' => 'set'],
                        ],
                    ],
                ],
            ],
        ], true) . ';');

        $database = $this->modx->newObject(modSnippet::class);
        $database->set('name', $snippetName);
        $database->setContent('return $selected ?? "missing";');
        $this->assertTrue($database->save());
        $databaseSet = $this->modx->newObject(modPropertySet::class);
        $databaseSet->set('name', 'DatabaseOnly');
        $databaseSet->setProperties(['selected' => 'database']);
        $this->assertTrue($databaseSet->save());
        $databaseLink = $this->modx->newObject(modElementPropertySet::class);
        $databaseLink->fromArray([
            'element' => $database->get('id'),
            'element_class' => $database->_class,
            'property_set' => $databaseSet->get('id'),
        ], '', true);
        $this->assertTrue($databaseLink->save());

        try {
            $databaseCatalog = (new DefinitionManifestCompiler())->compile([$manifestPath]);
            $this->modx->setDefinitionRegistry(new DefinitionRegistry($databaseCatalog));
            $this->assertSame('database', $this->modx->runSnippet($snippetName . '@DatabaseOnly'));

            $catalog = (new DefinitionManifestCompiler())->compile([$manifestPath]);
            $this->modx->setDefinitionRegistry(new DefinitionRegistry($catalog));

            $snippet = $this->modx->getElement(modSnippet::class, $snippetName);
            $this->assertInstanceOf(modSnippet::class, $snippet);
            $this->assertSame($database->get('id'), $snippet->get('id'));
            $this->assertSame(['selected' => 'database'], $snippet->getPropertySet('DatabaseOnly'));
            $this->assertNull($snippet->getPropertySet('Named'));
            $this->assertSame('database', $this->modx->runSnippet($snippetName . '@DatabaseOnly'));

            $this->assertSame(
                'default|call|call-site',
                $this->modx->getChunk($chunkName . '@named', ['call' => 'call-site', 'selected' => 'call'])
            );
            $chunk = $this->modx->getElement(modChunk::class, $chunkName);
            $this->assertSame(['selected' => 'set', 'call' => 'set'], $chunk->getPropertySet('NAMED'));
        } finally {
            $databaseLink->remove();
            $databaseSet->remove();
            $database->remove();
            unlink($root . '/elements/snippet.php');
            unlink($root . '/elements/chunk.tpl');
            unlink($manifestPath);
            rmdir($root . '/elements');
            rmdir($root);
        }
    }

    public function testCoreParserDelegatesNamedLookupToPublicResolver()
    {
        $this->installDiskSnippet('return "parser";');

        $resolved = $this->modx->getParser()->getElement(modSnippet::class, $this->elementName);

        $this->assertInstanceOf(modSnippet::class, $resolved);
        $this->assertSame('parser', $resolved->process());
    }

    public function testDiskChunkUsesTheNormalPublicApiWithoutAnXpdoRow()
    {
        $name = $this->elementName . 'Chunk';
        $content = 'Hello [[+name]]';
        $definition = [
            'key' => 'disk:phase0/tests:chunk:' . $name,
            'source' => 'disk',
            'package' => 'phase0/tests',
            'manifest' => __FILE__,
            'file' => __FILE__,
            'relative_file' => basename(__FILE__),
            'content_hash' => hash('sha256', $content),
            'content' => $content,
            'type' => 'chunk',
            'class' => modChunk::class,
            'name' => $name,
            'normalized_name' => strtolower($name),
            'properties' => [],
            'property_sets' => [],
            'media_source' => null,
        ];
        $this->modx->setDefinitionRegistry(new DefinitionRegistry([
            'schema' => 1,
            'release_hash' => hash('sha256', serialize($definition)),
            'definitions' => [modChunk::class => [strtolower($name) => $definition]],
            'events' => [],
            'listeners' => [],
            'inventory' => [],
        ]));

        $this->assertSame('Hello Ada', $this->modx->getChunk($name, ['name' => 'Ada']));
        $this->assertNull($this->modx->getObject(modChunk::class, ['name' => $name]));
    }

    public function testExistingDatabaseTwinThatCannotHydrateNeverFallsThroughToDisk()
    {
        $group = $this->modx->newObject(modUserGroup::class);
        $group->set('name', $this->elementName . 'AllowedGroup');
        $this->assertTrue($group->save());

        $category = $this->modx->newObject(modCategory::class);
        $category->set('category', $this->elementName . 'Category');
        $this->assertTrue($category->save());

        $acl = $this->modx->newObject(modAccessCategory::class);
        $acl->fromArray([
            'target' => $category->get('id'),
            'principal_class' => modUserGroup::class,
            'principal' => $group->get('id'),
            'authority' => 9999,
            'policy' => 0,
            'context_key' => $this->modx->context->get('key'),
        ]);
        $this->assertTrue($acl->save());

        $database = $this->createDatabaseSnippet('return "database";');
        $database->set('category', $category->get('id'));
        $this->assertTrue($database->save());
        $this->installDiskSnippet('return "disk";');

        $this->aclFixtures = [$acl, $category, $group];
        $this->originalSudo = (bool) $this->modx->user->get('sudo');
        $this->modx->user->set('sudo', false);
        $sessionState = new \ReflectionProperty(modX::class, '_sessionState');
        $sessionState->setAccessible(true);
        $this->originalSessionState = $sessionState->getValue($this->modx);
        $sessionState->setValue($this->modx, modX::SESSION_STATE_INITIALIZED);
        $this->modx->sourceCache = [];

        $this->assertSame(1, $this->modx->getCount(modSnippet::class, ['name' => $this->elementName]));
        $this->assertNull($this->modx->getObjectGraph(
            modSnippet::class,
            ['Source' => []],
            ['name' => $this->elementName],
            true
        ));
        $this->assertNull($this->modx->getElement(modSnippet::class, $this->elementName));
        $this->assertSame('database-load-denied', $this->modx->getElementResolver()->getLastDecision()['reason']);
        $inspection = (new DefinitionRegistryInspector(
            $this->modx,
            $this->modx->getDefinitionRegistry()
        ))->list(['kind' => 'elements', 'limit' => 0]);
        $this->assertTrue($inspection['results'][0]['collision']);
        $this->assertSame('database-default', $inspection['results'][0]['collision_state']);
    }

    public function testCustomParserCanAdoptTheDocumentedResolverFacade()
    {
        $this->installDiskSnippet('return "custom-parser";');
        $parser = new class ($this->modx) extends modParser {
            public function getElement($class, $name)
            {
                return $this->modx->getElement($class, $this->realname($name));
            }
        };

        $resolved = $parser->getElement(modSnippet::class, $this->elementName . ':default=`missing`');

        $this->assertInstanceOf(modSnippet::class, $resolved);
        $this->assertSame('custom-parser', $resolved->process());
    }

    public function testResolverCanBeReplacedThroughTheServiceContainer()
    {
        $modx = modX::getInstance('definition-resolver-service-' . bin2hex(random_bytes(5)), [], true);
        $resolver = new class implements ElementResolverInterface {
            public function getElement(string $class, string $name): ?modElement
            {
                return null;
            }

            public function getLastDecision(): array
            {
                return ['winner' => null, 'reason' => 'custom-resolver'];
            }
        };
        $modx->services->add(ElementResolverInterface::class, $resolver);

        $this->assertSame($resolver, $modx->getElementResolver());
        $this->assertNull($modx->getElement(modSnippet::class, $this->elementName));
        $this->assertSame('custom-resolver', $modx->getElementResolver()->getLastDecision()['reason']);
    }

    public function testFacadeFiresLegacyNotFoundEventForAnyNullResolverDecision()
    {
        $resolver = new class implements ElementResolverInterface {
            public function getElement(string $class, string $name): ?modElement
            {
                return null;
            }

            public function getLastDecision(): array
            {
                return ['winner' => null, 'reason' => 'custom-resolver'];
            }
        };
        $modx = $this->getMockBuilder(modX::class)
            ->disableOriginalConstructor()
            ->onlyMethods(['getElementResolver', 'invokeEvent'])
            ->getMock();
        $modx->method('getElementResolver')->willReturn($resolver);
        $modx->expects($this->once())
            ->method('invokeEvent')
            ->with('OnElementNotFound', ['class' => modSnippet::class, 'name' => $this->elementName])
            ->willReturn(false);

        $this->assertNull($modx->getElement(modSnippet::class, $this->elementName));
    }

    private function createDatabaseSnippet(string $code): modSnippet
    {
        $snippet = $this->modx->newObject(modSnippet::class);
        $snippet->set('name', $this->elementName);
        $snippet->setContent($code);
        $this->assertTrue($snippet->save());

        return $snippet;
    }

    private function installDiskSnippet(string $code, array $properties = []): void
    {
        $definition = $this->diskDefinition($code, $properties);
        $catalog = [
            'schema' => 1,
            'release_hash' => hash('sha256', serialize($definition)),
            'definitions' => [
                modSnippet::class => [strtolower($this->elementName) => $definition],
            ],
            'events' => [],
            'listeners' => [],
            'inventory' => [],
        ];

        $this->modx->setDefinitionRegistry(new DefinitionRegistry($catalog));
    }

    private function diskDefinition(string $code, array $properties = []): array
    {
        $key = 'disk:phase0/tests:snippet:' . $this->elementName;

        return [
            'key' => $key,
            'source' => 'disk',
            'package' => 'phase0/tests',
            'manifest' => __FILE__,
            'file' => __FILE__,
            'relative_file' => basename(__FILE__),
            'content_hash' => hash('sha256', $code),
            'content' => '<?php ' . $code,
            'type' => 'snippet',
            'class' => modSnippet::class,
            'name' => $this->elementName,
            'normalized_name' => strtolower($this->elementName),
            'properties' => $properties,
            'property_sets' => [],
            'media_source' => null,
        ];
    }
}
