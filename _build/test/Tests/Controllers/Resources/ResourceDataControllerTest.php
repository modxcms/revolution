<?php
namespace MODX\Revolution\Tests\Controllers\Resources;


use MODX\Revolution\modDocument;
use MODX\Revolution\MODxControllerTestCase;

class ResourceDataControllerTest extends MODxControllerTestCase
{
    /** @var \ResourceDataManagerController $controller */
    public $controller;

    public $controllerName = 'ResourceDataManagerController';
    public $controllerPath = 'resource/data';


    public function testNotFound()
    {
        $this->controller->setProperties([
            'id' => -1,
            'class_key' => modDocument::class,
        ]);
        $this->controller->process();
        $this->assertEmpty($this->controller->resource);
    }


    public function testFound()
    {
        $this->controller->setProperties([
            'id' => 1,
            'class_key' => modDocument::class,
        ]);
        $this->controller->process();
        $this->assertNotEmpty($this->controller->resource);
    }


    public function testRender()
    {
        $this->controller->setProperties([
            'id' => 1,
            'class_key' => modDocument::class,
        ]);
        $this->controller->render();
        $this->assertNotEmpty($this->controller->head['js']);
        $this->assertNotEmpty($this->controller->head['html']);

        $pageTitle = $this->controller->getPageTitle();
        $this->assertNotEmpty($pageTitle);
    }


    public function testGetTemplateFile()
    {
        $templateFile = $this->controller->getTemplateFile();
        $this->assertEmpty($templateFile);
    }
}
