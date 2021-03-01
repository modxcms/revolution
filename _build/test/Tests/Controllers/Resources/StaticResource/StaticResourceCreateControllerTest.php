<?php
namespace MODX\Revolution\Tests\Controllers\Resources\StaticResource;


use MODX\Revolution\modStaticResource;
use MODX\Revolution\Tests\Controllers\Resources\ResourceCreateControllerTest;

class StaticResourceCreateControllerTest extends ResourceCreateControllerTest
{
    /** @var \StaticResourceCreateManagerController $controller */
    public $controller;

    public $controllerName = 'StaticResourceCreateManagerController';
    public $controllerPath = 'resource/staticresource/create';

    /**
     * @before
     */
    public function setUpFixtures()
    {
        parent::setUpFixtures();
        $this->controller->setProperties([
            'id' => 0,
            'class_key' => modStaticResource::class,
            'parent' => 0,
            'context_key' => 'web',
        ]);
    }
}
