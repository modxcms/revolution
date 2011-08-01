<?php
/**
 * MODX Revolution
 *
 * Copyright 2006-2011 by MODX, LLC.
 * All rights reserved.
 *
 * This program is free software; you can redistribute it and/or modify it under
 * the terms of the GNU General Public License as published by the Free Software
 * Foundation; either version 2 of the License, or (at your option) any later
 * version.
 *
 * This program is distributed in the hope that it will be useful, but WITHOUT
 * ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS
 * FOR A PARTICULAR PURPOSE. See the GNU General Public License for more
 * details.
 *
 * You should have received a copy of the GNU General Public License along with
 * this program; if not, write to the Free Software Foundation, Inc., 59 Temple
 * Place, Suite 330, Boston, MA 02111-1307 USA
 *
 * @package modx-test
 */
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
        $this->modx->loadClass('modManagerController',MODX_CORE_PATH.'model/modx/',true,true);
        require_once MODX_MANAGER_PATH.'controllers/default/'.$this->controllerPath.'.class.php';
        $className = $this->controllerName;

        if (!empty($className)) {
            $this->controller = new $className($this->modx,array(
                'namespace' => 'core',
                'namespace_name' => 'core',
                'namespace_path' => MODX_MANAGER_PATH,
                'controller' => $controllerPath,
            ));
        }
    }

    public function tearDown() {
        parent::tearDown();
        $this->controller = null;
    }
}