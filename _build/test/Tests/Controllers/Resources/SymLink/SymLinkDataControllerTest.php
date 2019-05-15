<?php
namespace MODX\Revolution\Tests\Controllers\Resources\SymLink;


use MODX\Revolution\MODxControllerTestCase;

class SymLinkDataControllerTest extends MODxControllerTestCase
{
    /** @var \SymLinkDataManagerController $controller */
    public $controller;

    public $controllerName = 'SymLinkDataManagerController';
    public $controllerPath = 'resource/symlink/data';


    public function testNotFound()
    {
        $this->controller->setProperties([
            'id' => -1,
            'class_key' => 'modSymlink',
        ]);
        $this->controller->process();
        $this->assertEmpty($this->controller->resource);
    }


    public function testFound()
    {
        $this->controller->setProperties([
            'id' => 1,
            'class_key' => 'modSymlink',
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
