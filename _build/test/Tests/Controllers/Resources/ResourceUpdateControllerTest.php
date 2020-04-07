<?php
namespace MODX\Revolution\Tests\Controllers\Resources;


use MODX\Revolution\modDocument;
use MODX\Revolution\MODxControllerTestCase;

class ResourceUpdateControllerTest extends MODxControllerTestCase
{
    /** @var \ResourceUpdateManagerController $controller */
    public $controller;

    public $controllerName = 'ResourceUpdateManagerController';
    public $controllerPath = 'resource/update';


    public function testNotFound()
    {
        $this->controller->setProperties([
            'id' => -1,
            'class_key' => modDocument::class,
        ]);
        $response = $this->controller->getResource();
        $this->assertNotTrue($response);
    }


    public function testFound()
    {
        $this->controller->setProperties([
            'id' => 1,
            'class_key' => modDocument::class,
        ]);
        $response = $this->controller->getResource();
        $this->assertTrue($response);
    }


    /**
     * @depends testFound
     */
    public function testRender()
    {
        $this->controller->setProperties([
            'id' => 1,
        ]);
        $this->controller->initialize();
        $this->controller->render();
        $this->assertNotEmpty($this->controller->head['js']);
        $this->assertNotEmpty($this->controller->head['html']);
    }


    public function testGetTemplateFile()
    {
        $templateFile = $this->controller->getTemplateFile();
        $this->assertNotEmpty($templateFile);
    }


    /**
     * @depends testFound
     */
    public function testGetPageTitle()
    {
        $pageTitle = $this->controller->getPageTitle();
        $this->assertNotEmpty($pageTitle);
    }
}
