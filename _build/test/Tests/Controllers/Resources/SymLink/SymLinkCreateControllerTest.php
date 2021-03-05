<?php
namespace MODX\Revolution\Tests\Controllers\Resources\SymLink;


use MODX\Revolution\modSymLink;
use MODX\Revolution\Tests\Controllers\Resources\ResourceCreateControllerTest;

class SymLinkCreateControllerTest extends ResourceCreateControllerTest
{
    /** @var \SymLinkCreateManagerController $controller */
    public $controller;

    public $controllerName = 'SymLinkCreateManagerController';
    public $controllerPath = 'resource/symlink/create';

    /**
     * @before
     */
    public function setUpFixtures()
    {
        parent::setUpFixtures();
        $this->controller->setProperties([
            'id' => 0,
            'class_key' => modSymlink::class,
            'parent' => 0,
            'context_key' => 'web',
        ]);
    }
}
