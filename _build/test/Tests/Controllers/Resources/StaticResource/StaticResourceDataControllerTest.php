<?php
namespace MODX\Revolution\Tests\Controllers\Resources\StaticResource;


use MODX\Revolution\MODxControllerTestCase;

class StaticResourceDataControllerTest extends MODxControllerTestCase
{
    /** @var \StaticResourceDataManagerController $controller */
    public $controller;

    public $controllerName = 'StaticResourceDataManagerController';
    public $controllerPath = 'resource/staticresource/data';


    public function testNotFound()
    {
        $this->controller->setProperties([
            'id' => -1,
            'class_key' => 'modStaticResource',
        ]);
        $this->controller->process();
        $this->assertEmpty($this->controller->resource);
    }


    public function testFound()
    {
        $this->controller->setProperties([
            'id' => 1,
            'class_key' => 'modStaticResource',
        ]);
        $this->controller->process();
        $this->assertNotEmpty($this->controller->resource);
    }


    public function testRender()
    {
        $this->controller->setProperties([
            'id' => 1,
            'class_key' => 'modStaticResource',
        ]);
        $this->controller->render();
        $this->assertNotEmpty($this->controller->head['js']);
        $this->assertNotEmpty($this->controller->head['html']);

        $pageTitle = $this->controller->getPageTitle();
        $this->assertNotEmpty($pageTitle);
    }
}
