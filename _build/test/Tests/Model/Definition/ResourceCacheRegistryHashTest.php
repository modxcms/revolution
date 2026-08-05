<?php

namespace MODX\Revolution\Tests\Model\Definition;

use MODX\Revolution\Definition\DefinitionRegistry;
use MODX\Revolution\modCacheManager;
use MODX\Revolution\modDocument;
use MODX\Revolution\modRequest;
use MODX\Revolution\modResource;
use MODX\Revolution\MODxTestCase;
use MODX\Revolution\Processors\Resource\Data;
use MODX\Revolution\Processors\System\ClearCache;
use xPDO\xPDO;

class ResourceCacheRegistryHashTest extends MODxTestCase
{
    public function testResourceCacheCompatibilityRequiresExactRegistryHash()
    {
        $this->modx->setDefinitionRegistry($this->registry('release-a'));

        $this->assertTrue($this->modx->isDefinitionRegistryCacheCompatible([
            'definitionRegistryHash' => hash('sha256', 'release-a'),
        ]));
        $this->assertFalse($this->modx->isDefinitionRegistryCacheCompatible([]));
        $this->assertFalse($this->modx->isDefinitionRegistryCacheCompatible([
            'definitionRegistryHash' => hash('sha256', 'release-b'),
        ]));
    }

    public function testGeneratedResourceCacheCarriesRegistryHash()
    {
        $registry = $this->registry('release-a');
        $this->modx->setDefinitionRegistry($registry);
        $modx =& $this->modx;
        $cacheManager = new class ($modx) extends modCacheManager {
            public function set($key, &$var, $lifetime = 0, $options = [])
            {
                return true;
            }
        };
        $resource = $this->modx->newObject(modResource::class);
        $resource->set('id', 987654321);
        $resource->set('cacheable', true);
        $resource->setProcessed(true);
        $resource->_content = 'cached';

        $cached = $cacheManager->generateResource($resource);

        $this->assertSame($registry->getReleaseHash(), $cached['definitionRegistryHash']);
    }

    public function testNormalRequestRejectsStaleResourceAndElementSnapshots()
    {
        $this->modx->setDefinitionRegistry($this->registry('release-current'));
        $resource = $this->modx->newObject(modDocument::class);
        $resource->fromArray([
            'pagetitle' => 'Definition cache guard',
            'alias' => 'definition-cache-' . bin2hex(random_bytes(5)),
            'content' => 'fresh database content',
            'published' => 1,
            'cacheable' => 1,
            'context_key' => 'web',
            'template' => 0,
        ]);
        $this->assertTrue($resource->save());
        $cachedFields = $resource->toArray('', true);
        $cachedFields['_content'] = 'stale cached content';
        $cachedFields['_processed'] = true;
        $cached = [
            'definitionRegistryHash' => hash('sha256', 'release-old'),
            'resourceClass' => modDocument::class,
            'resource' => $cachedFields,
            'elementCache' => ['stale-tag' => 'stale output'],
            'sourceCache' => ['stale-class' => ['stale-name' => ['fields' => []]]],
        ];
        $originalCacheManager = $this->modx->cacheManager;
        $modx =& $this->modx;
        $cacheManager = new class ($modx, $cached) extends modCacheManager {
            private array $cached;

            public function __construct(&$xpdo, array $cached)
            {
                parent::__construct($xpdo);
                $this->cached = $cached;
            }

            public function get($key, $options = [])
            {
                return $this->cached;
            }
        };
        $this->modx->cacheManager = $cacheManager;
        $this->modx->elementCache = [];
        $this->modx->sourceCache = [];

        try {
            $loaded = (new modRequest($this->modx))->getResource('id', $resource->get('id'));
            $this->assertSame('fresh database content', $loaded->getContent());
            $this->assertSame([], $this->modx->elementCache);
            $this->assertSame([], $this->modx->sourceCache);
        } finally {
            $this->modx->cacheManager = $originalCacheManager;
            $resource->remove();
        }
    }

    public function testManagerCachedContentPreviewRejectsAStaleRegistryRelease()
    {
        $this->modx->setDefinitionRegistry($this->registry('release-current'));
        $resource = $this->modx->newObject(modResource::class);
        $resource->set('id', 987654321);
        $resource->set('context_key', 'web');
        $cached = [
            'definitionRegistryHash' => hash('sha256', 'release-old'),
            'resource' => ['_content' => 'stale cached content'],
        ];
        $originalCacheManager = $this->modx->cacheManager;
        $modx =& $this->modx;
        $this->modx->cacheManager = new class ($modx, $cached) extends modCacheManager {
            private array $cached;

            public function __construct(&$xpdo, array $cached)
            {
                parent::__construct($xpdo);
                $this->cached = $cached;
            }

            public function get($key, $options = [])
            {
                return $this->cached;
            }
        };
        $processor = new Data($this->modx);
        $processor->resource = $resource;

        try {
            $this->assertNotSame('stale cached content', $processor->getCacheSource());
        } finally {
            $this->modx->cacheManager = $originalCacheManager;
        }
    }

    public function testNormalFullRefreshClearsRegistryAndResourcePartitions()
    {
        $modx =& $this->modx;
        $cacheManager = new class ($modx) extends modCacheManager {
            public array $cleaned = [];

            public function autoPublish(array $options = [])
            {
                return [];
            }

            public function generateConfig(array $options = [])
            {
                return true;
            }

            public function generateContext($key, array $options = [])
            {
                return true;
            }

            public function clean($options = [])
            {
                $this->cleaned[] = $options[xPDO::OPT_CACHE_KEY] ?? null;

                return true;
            }

            public function deleteTree(
                $dirname,
                $options = ['deleteTop' => false, 'skipDirs' => false, 'extensions' => ['.cache.php']]
            ) {
                return true;
            }
        };
        $results = [];

        $this->assertTrue($cacheManager->refresh([], $results));
        $this->assertArrayHasKey('definition_registry', $results);
        $this->assertArrayHasKey('resource', $results);
        $this->assertContains('definition_registry', $cacheManager->cleaned);
        $this->assertContains('resource', $cacheManager->cleaned);
    }

    public function testManagerClearCacheClearsRegistryAndResourcePartitionsOnADatabaseOnlySite()
    {
        $this->assertTrue($this->modx->getDefinitionRegistry()->isEmpty());
        $processor = new ClearCache($this->modx, [
            'contexts' => ['web', 'mgr'],
        ]);
        $modx =& $this->modx;
        $cacheManager = new class ($modx) extends modCacheManager {
            public array $cleaned = [];

            public function clean($options = [])
            {
                $this->cleaned[] = $options[xPDO::OPT_CACHE_KEY] ?? null;

                return true;
            }
        };
        $results = [];

        $this->assertTrue($cacheManager->refresh($processor->getPartitions(), $results));
        $this->assertArrayHasKey('definition_registry', $results);
        $this->assertArrayHasKey('resource', $results);
        $this->assertContains('definition_registry', $cacheManager->cleaned);
        $this->assertContains('resource', $cacheManager->cleaned);
        $this->assertTrue($this->modx->getDefinitionRegistry()->isEmpty());
    }

    private function registry(string $release): DefinitionRegistry
    {
        return new DefinitionRegistry([
            'schema' => 1,
            'release_hash' => hash('sha256', $release),
            'definitions' => [],
            'events' => [],
            'listeners' => [],
            'inventory' => [],
        ]);
    }
}
