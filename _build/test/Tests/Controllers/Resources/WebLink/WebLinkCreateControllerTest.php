<?php
namespace MODX\Revolution\Tests\Controllers\Resources\WebLink;


use MODX\Revolution\modWebLink;
use MODX\Revolution\Tests\Controllers\Resources\ResourceCreateControllerTest;

class WebLinkCreateControllerTest extends ResourceCreateControllerTest
{
    /** @var \WebLinkCreateManagerController $controller */
    public $controller;

    public $controllerName = 'WebLinkCreateManagerController';
    public $controllerPath = 'resource/weblink/create';

    /**
     * @before
     */
    public function setUpFixtures()
    {
        parent::setUpFixtures();
        $this->controller->setProperties([
            'id' => 0,
            'class_key' => modWeblink::class,
            'parent' => 0,
            'context_key' => 'web',
        ]);
    }
}
