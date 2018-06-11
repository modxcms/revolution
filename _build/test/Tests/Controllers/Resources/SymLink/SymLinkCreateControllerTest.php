<?php

class SymLinkCreateControllerTest extends ResourceCreateControllerTest
{
    /** @var SymLinkCreateManagerController $controller */
    public $controller;

    public $controllerName = 'SymLinkCreateManagerController';
    public $controllerPath = 'resource/symlink/create';


    public function setUp()
    {
        parent::setUp();
        $this->controller->setProperties([
            'id' => 0,
            'class_key' => 'modSymlink',
            'parent' => 0,
            'context_key' => 'web',
        ]);
    }
}
