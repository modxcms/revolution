<?php
namespace MODX\Revolution\Tests\Controllers\Resources\WebLink;


use MODX\Revolution\modWebLink;
use MODX\Revolution\Tests\Controllers\Resources\ResourceUpdateControllerTest;

class WebLinkUpdateControllerTest extends ResourceUpdateControllerTest
{
    /** @var \WebLinkUpdateManagerController $controller */
    public $controller;

    public $controllerName = 'WebLinkUpdateManagerController';
    public $controllerPath = 'resource/weblink/update';


    public function testNotFound()
    {
        $this->controller->setProperties([
            'id' => -1,
            'class_key' => modWeblink::class,
        ]);
        $response = $this->controller->getResource();
        $this->assertNotTrue($response);
    }


    public function testFound()
    {
        $this->controller->setProperties([
            'id' => 1,
            'class_key' => modWeblink::class,
        ]);
        $response = $this->controller->getResource();
        $this->assertTrue($response);
    }
}
