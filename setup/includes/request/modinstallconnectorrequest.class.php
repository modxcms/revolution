<?php
/*
 * This file is part of MODX Revolution.
 *
 * Copyright (c) MODX, LLC. All Rights Reserved.
 *
 * For complete copyright and license information, see the COPYRIGHT and LICENSE
 * files found in the top-level directory of this distribution.
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
        if ($this->install->isLocked()) {
            $this->error->failure('MODX setup is locked!');
        }
        if (empty($this->install->action)) {
            $this->error->failure('No processor specified!');
        }
        $this->action = $this->install->action;

        if($this->action !== 'database/connection') {
            $this->install->loadDriver();
        }

        $f = MODX_SETUP_PATH . 'processors/' . $this->action . '.php';
        if (!file_exists($f)) {
            $this->error->failure('Could not load requested processor for action ' . $this->action . '.');
        }

        $install =& $this->install;
        $install->loadSettings();
        $error =& $this->error;

        if (!@include($f)) $this->error->failure('Could not load requested processor for action ' . $this->action . '.');
    }
}
