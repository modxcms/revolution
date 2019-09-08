<?php
namespace MODX\Revolution\Tests\Controllers;


use MODX\Revolution\modManagerControllerDeprecated;
use MODX\Revolution\MODxTestCase;
use MODX\Revolution\Smarty\modSmarty;

/**
 * Tests related to the modManagerControllerDeprecated controller class
 */
class DeprecatedControllerTest extends MODxTestCase {
    /**
     * @var modManagerControllerDeprecated
     */
    public $controller;

    public function setUp() {
        parent::setUp();

        /* load smarty template engine */
        $templatePath = $this->modx->getOption('manager_path') . 'templates/default/';
        $this->modx->getService('smarty', modSmarty::class, '', array(
            'template_dir' => $templatePath,
        ));
        $this->modx->smarty->setCachePath('mgr/smarty/default/');
        $this->modx->smarty->assign('_config',$this->modx->config);
        $this->modx->smarty->assignByRef('modx',$this->modx);

        $this->controller = new modManagerControllerDeprecated($this->modx);
    }
    public function tearDown() {
        parent::tearDown();
        $this->controller = null;
    }

    public function testCustomAssets() {
        $data = <<<HTML
<script>console.log('exists!');</script>
HTML;

        $this->controller->addHtml($data);
        $output = $this->controller->render();

        $this->assertContains($data, $output, 'Deprecated controller did not process additionally added assets/HTML');
    }
}
