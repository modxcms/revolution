<?php
namespace MODX\Revolution\Tests\Controllers\Resources\WebLink;


use MODX\Revolution\modStaticResource;
use MODX\Revolution\modWebLink;
use MODX\Revolution\MODxControllerTestCase;

class WebLinkDataControllerTest extends MODxControllerTestCase
{
    /** @var \WebLinkDataManagerController $controller */
    public $controller;

    public $controllerName = 'WebLinkDataManagerController';
    public $controllerPath = 'resource/weblink/data';


    public function testNotFound()
    {
        $this->controller->setProperties([
            'id' => -1,
            'class_key' => modWeblink::class,
        ]);
        $this->controller->process();
        $this->assertEmpty($this->controller->resource);
    }


    public function testFound()
    {
        $this->controller->setProperties([
            'id' => 1,
            'class_key' => modWeblink::class,
        ]);
        $this->controller->process();
        $this->assertNotEmpty($this->controller->resource);
    }


    public function testRender()
    {
        $this->controller->setProperties([
            'id' => 1,
            'class_key' => modStaticResource::class,
        ]);
        $this->controller->render();
        $this->assertNotEmpty($this->controller->head['js']);
        $this->assertNotEmpty($this->controller->head['html']);

        $pageTitle = $this->controller->getPageTitle();
        $this->assertNotEmpty($pageTitle);
    }
}
