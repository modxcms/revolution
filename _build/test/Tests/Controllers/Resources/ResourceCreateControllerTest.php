<?php
namespace MODX\Revolution\Tests\Controllers\Resources;


use MODX\Revolution\MODxControllerTestCase;

class ResourceCreateControllerTest extends MODxControllerTestCase
{
    /** @var \ResourceCreateManagerController $controller */
    public $controller;

    public $controllerName = 'ResourceCreateManagerController';
    public $controllerPath = 'resource/create';


    public function setUp()
    {
        parent::setUp();
        $this->controller->setProperties([
            'id' => 0,
            'class_key' => 'modDocument',
            'parent' => 0,
            'context_key' => 'web',
        ]);
    }


    public function testRender()
    {
        $this->controller->render();
        $this->assertNotEmpty($this->controller->head['js']);
        $this->assertNotEmpty($this->controller->head['html']);
        $this->assertNotEmpty($this->controller->resourceArray);
    }


    public function testGetTemplateFile()
    {
        $templateFile = $this->controller->getTemplateFile();
        $this->assertNotEmpty($templateFile);
    }


    public function testGetPageTitle()
    {
        $pageTitle = $this->controller->getPageTitle();
        $this->assertNotEmpty($pageTitle);
    }


    public function testProcess()
    {
        $this->controller->process();
        $this->assertNotEmpty($this->controller->resource);
    }
}
