<?php
/*
 * This file is part of the MODX Revolution package.
 *
 * Copyright (c) MODX, LLC
 *
 * For complete copyright and license information, see the COPYRIGHT and LICENSE
 * files found in the top-level directory of this distribution.
 *
 * @package modx-test
*/
namespace MODX\Revolution;

use MODX\Revolution\Smarty\modSmarty;

/**
 * Abstract class extending MODxTestCase for controller-specific testing
 *
 * @package modx-test
 * @subpackage modx
 */
abstract class MODxControllerTestCase extends MODxTestCase {
    /** @var modManagerController $controller */
    public $controller;
    /**
     * The short path to the controller, ie, "context/update"
     * @var string $controllerPath
     */
    public $controllerPath;
    /**
     * The name of the controller class to load, ie, "ContextUpdateManagerController"
     * @var string $controllerName
     */
    public $controllerName;

    public function setUp() {
        parent::setUp();

        /* load smarty template engine */
        $templatePath = $this->modx->getOption('manager_path') . 'templates/default/';
        $this->modx->getService('smarty', modSmarty::class, '', [
            'template_dir' => $templatePath,
        ]);
        $this->modx->smarty->setCachePath('mgr/smarty/default/');
        $this->modx->smarty->assign('_config',$this->modx->config);
        $this->modx->smarty->assignByRef('modx',$this->modx);

        require_once MODX_MANAGER_PATH.'controllers/default/'.$this->controllerPath.'.class.php';
        $className = $this->controllerName;

        if (!empty($className)) {
            $this->controller = new $className($this->modx, [
                'namespace' => 'core',
                'namespace_name' => 'core',
                'namespace_path' => MODX_MANAGER_PATH,
                'controller' => $this->controllerPath,
            ]);
            $this->controller->setProperties($_REQUEST);
        }
        $this->modx->controller = $this->controller;
    }

    public function tearDown() {
        parent::tearDown();
        $this->controller = null;
    }
}
