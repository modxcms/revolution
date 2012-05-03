<?php
/*
 * MODX Revolution
 *
 * Copyright 2006-2012 by MODX, LLC.
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
 */
require_once strtr(realpath(MODX_SETUP_PATH.'includes/request/modinstallrequest.class.php'),'\\','/');
/**
 * modInstallConnector
 *
 * @package setup
 */
/**
 * Handles all connector requests to processors.
 *
 * @package setup
 */
class modInstallConnectorRequest extends modInstallRequest {
    /** @var modInstall $install */
    public $install;
    /** @var modInstallJSONError $error */
    public $error;
    public $action = '';
    
    /**
     * Constructor for modInstallConnector object.
     *
     * @constructor
     * @param modInstall &$modInstall A reference to the modInstall object.
     */
    function __construct(modInstall &$modInstall) {
        $this->install =& $modInstall;
        $this->loadError();
    }

    /**
     * Loads error processing tool
     *
     * @param string $class
     * @param string $path
     * @param array $config
     * @return modInstallError
     */
    public function loadError($class = 'error.modInstallJSONError',$path = '',array $config = array()) {
        $className = $this->install->loadClass($class,$path);
        if (!empty($className)) {
            $this->error = new $className($this->install,$config);
        } else {
            die('Failure to load '.$class.' from '.$path);
        }
        return $this->error;
    }

    /**
     * Handles connector requests.
     *
     * @param string $action
     */
    public function handle($action = '') {
        if (empty($_REQUEST['action'])) $this->error->failure('No processor specified!');
        $this->action = $_REQUEST['action'];

        if($this->action !== 'database/connection') {
            $this->install->loadDriver();
        }
        
        $f = MODX_SETUP_PATH . 'processors/' . $this->action . '.php';
        if (!file_exists($f)) $this->error->failure('Could not load requested processor for action ' . $this->action . '.');

        $install =& $this->install;
        $install->loadSettings();
        $error =& $this->error;

        if (!@include($f)) $this->error->failure('Could not load requested processor for action ' . $this->action . '.');
    }
}