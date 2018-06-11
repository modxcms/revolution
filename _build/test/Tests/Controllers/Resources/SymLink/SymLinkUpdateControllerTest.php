<?php

class SymLinkUpdateControllerTest extends ResourceUpdateControllerTest
{
    /** @var SymLinkUpdateManagerController $controller */
    public $controller;

    public $controllerName = 'SymLinkUpdateManagerController';
    public $controllerPath = 'resource/symlink/update';


    public function testNotFound()
    {
        $this->controller->setProperties([
            'id' => -1,
            'class_key' => 'modSymlink',
        ]);
        $response = $this->controller->getResource();
        $this->assertNotTrue($response);
    }


    public function testFound()
    {
        $this->controller->setProperties([
            'id' => 1,
            'class_key' => 'modSymlink',
        ]);
        $response = $this->controller->getResource();
        $this->assertTrue($response);
    }
}
