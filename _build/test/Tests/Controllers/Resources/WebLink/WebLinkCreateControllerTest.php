<?php
namespace MODX\Revolution\Tests\Controllers\Resources\WebLink;


use MODX\Revolution\Tests\Controllers\Resources\ResourceCreateControllerTest;

class WebLinkCreateControllerTest extends ResourceCreateControllerTest
{
    /** @var \WebLinkCreateManagerController $controller */
    public $controller;

    public $controllerName = 'WebLinkCreateManagerController';
    public $controllerPath = 'resource/weblink/create';


    public function setUp()
    {
        parent::setUp();
        $this->controller->setProperties([
            'id' => 0,
            'class_key' => 'modWeblink',
            'parent' => 0,
            'context_key' => 'web',
        ]);
    }
}
