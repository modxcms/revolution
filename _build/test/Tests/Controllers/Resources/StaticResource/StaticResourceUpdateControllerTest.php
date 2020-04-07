<?php
namespace MODX\Revolution\Tests\Controllers\Resources\StaticResource;


use MODX\Revolution\modStaticResource;
use MODX\Revolution\Tests\Controllers\Resources\ResourceUpdateControllerTest;

class StaticResourceUpdateControllerTest extends ResourceUpdateControllerTest
{
    /** @var \StaticResourceUpdateManagerController $controller */
    public $controller;

    public $controllerName = 'StaticResourceUpdateManagerController';
    public $controllerPath = 'resource/staticresource/update';


    public function testNotFound()
    {
        $this->controller->setProperties([
            'id' => -1,
            'class_key' => modStaticResource::class,
        ]);
        $response = $this->controller->getResource();
        $this->assertNotTrue($response);
    }


    public function testFound()
    {
        $this->controller->setProperties([
            'id' => 1,
            'class_key' => modStaticResource::class,
        ]);
        $response = $this->controller->getResource();
        $this->assertTrue($response);
    }
}
