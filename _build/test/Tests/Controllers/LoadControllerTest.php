<?php
namespace MODX\Revolution\Tests\Controllers;


use MODX\Revolution\Controllers\Exceptions\NotFoundException;
use MODX\Revolution\modManagerResponse;
use MODX\Revolution\modX;
use MODX\Revolution\MODxTestCase;
use MODX\Revolution\Processors\Resource\Create;
use MODX\Revolution\Smarty\modSmarty;

/**
 * Tests related to the modManagerResponse and modManagerController classes for loading controllers
 */
class LoadControllerTest extends MODxTestCase
{
    /** @var modManagerResponse */
    protected $response;

    public function setUp()
    {
        parent::setUp();

        /* load smarty template engine */
        $templatePath = $this->modx->getOption('manager_path') . 'templates/default/';
        $this->modx->getService('smarty', modSmarty::class, '', [
            'template_dir' => $templatePath,
        ]);
        $this->modx->smarty->setCachePath('mgr/smarty/default/');
        $this->modx->smarty->assign('_config', $this->modx->config);
        $this->modx->smarty->assignByRef('modx', $this->modx);

        $this->response = new modManagerResponse($this->modx);
    }


    /**
     * @dataProvider providerGetControllerClassName
     * @param string $action
     * @param string|bool $expected
     * @throws NotFoundException
     */
    public function testGetControllerClassName(string $action, $expected)
    {
        if ($expected === false) {
            $this->expectException(NotFoundException::class);
        }

        $this->assertEquals($expected, $this->response->getControllerClassName($action));

    }

    public function providerGetControllerClassName()
    {
        return [
            ['search', \SearchManagerController::class],
            ['resource/create', \ResourceCreateManagerController::class],
            ['security/access/policy/template/update', \SecurityAccessPolicyTemplateUpdateManagerController::class],
            ['context/view', \ContextViewManagerController::class],
            ['nopes', false], // make sure we get an exception for something invalid
        ];
    }

    /**
     * @param $className
     * @param $isController
     * @dataProvider providerIsControllerClass
     */
    public function testIsControllerClass($className, $isController)
    {
        $this->assertEquals($isController, modManagerResponse::isControllerClass($className));
    }

    public function providerIsControllerClass()
    {
        return [
            [modX::class, false],
            [\SearchManagerController::class, true], // would normally need a `require` first - but is already loaded thanks to the earlier test
            [Create::class, false]
        ];
    }
}
