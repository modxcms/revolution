<?php

namespace MODX\Revolution\Tests\Controllers\Resources;

use MODX\Revolution\modDocument;
use MODX\Revolution\modResource;
use MODX\Revolution\modWebLink;
use MODX\Revolution\MODxTestCase;

/**
 * @covers ResourceManagerController::getInstance
 */
class ResourceManagerControllerGetInstanceTest extends MODxTestCase
{
    /** @var array<int, int> */
    private $createdResourceIds = [];


    /**
     * @before
     */
    public function setUpFixtures()
    {
        parent::setUpFixtures();
        require_once MODX_MANAGER_PATH . 'controllers/default/resource/resource.class.php';
        require_once MODX_MANAGER_PATH . 'controllers/default/resource/data.class.php';
        require_once MODX_MANAGER_PATH . 'controllers/default/resource/create.class.php';
        require_once MODX_MANAGER_PATH . 'controllers/default/resource/weblink/data.class.php';
    }


    /**
     * @after
     */
    public function tearDownFixtures()
    {
        foreach ($this->createdResourceIds as $id) {
            $resource = $this->modx->getObject(modResource::class, $id);
            if ($resource) {
                $resource->remove();
            }
        }
        $this->createdResourceIds = [];
        $_REQUEST = [];
        $_GET = [];
        parent::tearDownFixtures();
    }


    private function createResourceWithClassKey(string $classKey, string $objectClass = modDocument::class): int
    {
        $resource = $this->modx->newObject($objectClass);
        $resource->fromArray([
            'pagetitle' => '15080-getinstance-' . uniqid('', true),
            'alias' => '15080-gi-' . uniqid(),
            'parent' => 0,
            'template' => 0,
            'context_key' => 'web',
            'class_key' => $classKey,
            'published' => 0,
        ]);
        $this->assertTrue($resource->save(), 'Failed to save test resource');
        if ($resource->get('class_key') !== $classKey) {
            $resource->set('class_key', $classKey);
            $this->assertTrue($resource->save(), 'Failed to force class_key on test resource');
        }
        $id = (int)$resource->get('id');
        $this->createdResourceIds[] = $id;

        return $id;
    }


    private function getDataController(array $request)
    {
        $_REQUEST = $request;
        $_GET = $request;

        return \ResourceManagerController::getInstance($this->modx, 'ResourceDataManagerController', [
            'namespace' => 'core',
            'namespace_path' => MODX_MANAGER_PATH,
            'action' => 'Resource/Data',
        ]);
    }


    public function testDocumentDataManagerControllerAlias()
    {
        $this->assertTrue(class_exists('DocumentDataManagerController'));
        $this->assertTrue(is_a('DocumentDataManagerController', \ResourceDataManagerController::class, true));
    }


    public function testGetInstanceWithShortClassKeyInDatabase()
    {
        $id = $this->createResourceWithClassKey('modDocument');
        $stored = $this->modx->getObject(modResource::class, $id);
        $this->assertSame('modDocument', $stored->get('class_key'));

        $controller = $this->getDataController([
            'id' => (string)$id,
            'a' => 'resource/data',
        ]);

        $this->assertInstanceOf(\ResourceDataManagerController::class, $controller);
        $this->assertSame(modDocument::class, $controller->resourceClass);

        $stored = $this->modx->getObject(modResource::class, $id);
        $this->assertSame(
            'modDocument',
            $stored->get('class_key'),
            'Overview must not rewrite short modDocument on GET'
        );
    }


    public function testGetInstanceWithShortModResourceClassKeyInDatabase()
    {
        $id = $this->createResourceWithClassKey('modResource');
        $controller = $this->getDataController([
            'id' => (string)$id,
            'a' => 'resource/data',
        ]);

        $this->assertInstanceOf(\ResourceDataManagerController::class, $controller);
        $this->assertSame(modDocument::class, $controller->resourceClass);

        $stored = $this->modx->getObject(modResource::class, $id);
        $this->assertSame(modDocument::class, $stored->get('class_key'));
    }


    public function testOverviewLoadsResourceWithShortClassKey()
    {
        $id = $this->createResourceWithClassKey('modDocument');
        $controller = $this->getDataController([
            'id' => (string)$id,
            'a' => 'resource/data',
        ]);
        $controller->setProperties(['id' => $id]);

        $loaded = $this->modx->getObject(modResource::class, $id);
        $this->assertNotNull($loaded);
        $this->assertSame('modDocument', $loaded->get('class_key'));

        // Mirror ResourceDataManagerController::process load path
        $fromProcessPath = $this->modx->getObject(modResource::class, $id);
        $this->assertNotNull($fromProcessPath);
        $this->assertSame($id, (int)$fromProcessPath->get('id'));
    }


    public function testGetInstanceWithFqcnClassKeyRequest()
    {
        $controller = $this->getDataController([
            'a' => 'resource/data',
            'class_key' => modDocument::class,
        ]);

        $this->assertInstanceOf(\ResourceDataManagerController::class, $controller);
        $this->assertSame(modDocument::class, $controller->resourceClass);
    }


    public function testGetInstanceWithShortClassKeyRequest()
    {
        $controller = $this->getDataController([
            'a' => 'resource/data',
            'class_key' => 'modDocument',
        ]);

        $this->assertInstanceOf(\ResourceDataManagerController::class, $controller);
        $this->assertSame(modDocument::class, $controller->resourceClass);
    }


    public function testGetInstanceCreateWithFqcnClassKeyRequest()
    {
        $_REQUEST = [
            'a' => 'resource/create',
            'class_key' => modDocument::class,
            'parent' => 1,
            'context_key' => 'web',
        ];
        $_GET = $_REQUEST;

        $controller = \ResourceManagerController::getInstance($this->modx, 'ResourceCreateManagerController', [
            'namespace' => 'core',
            'namespace_path' => MODX_MANAGER_PATH,
            'action' => 'Resource/Create',
        ]);

        $this->assertInstanceOf(\ResourceCreateManagerController::class, $controller);
        $this->assertSame(modDocument::class, $controller->resourceClass);
    }


    public function testGetInstanceKeepsWeblinkDerivative()
    {
        $id = $this->createResourceWithClassKey(modWebLink::class, modWebLink::class);

        $controller = $this->getDataController([
            'id' => (string)$id,
            'a' => 'resource/data',
        ]);

        $this->assertInstanceOf(\WebLinkDataManagerController::class, $controller);
        $this->assertSame(modWebLink::class, $controller->resourceClass);
    }


    public function testGetInstanceWeblinkClassKeyRequest()
    {
        $controller = $this->getDataController([
            'a' => 'resource/data',
            'class_key' => modWebLink::class,
        ]);

        $this->assertInstanceOf(\WebLinkDataManagerController::class, $controller);
        $this->assertSame(modWebLink::class, $controller->resourceClass);
    }
}
