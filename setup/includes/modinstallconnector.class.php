<?php
/*
 * MODx Revolution
 *
 * Copyright 2006, 2007, 2008, 2009, 2010 by the MODx Team.
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
class modInstallConnector {
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
     */
    public function loadError() {
        require_once MODX_SETUP_PATH . 'includes/modinstalljsonerror.class.php';
        $this->error = new modInstallJSONError($this->install);
    }

    /**
     * Handles connector requests.
     */
    public function handle($action = '') {
        if (empty($_REQUEST['action'])) $this->error->failure('No processor specified!');
        $this->action = $_REQUEST['action'];

        $this->install->loadDriver();
        
        $f = MODX_SETUP_PATH . 'processors/' . $this->action . '.php';
        if (!file_exists($f)) $this->error->failure('Could not load requested processor for action ' . $this->action . '.');

        $install =& $this->install;
        $install->loadSettings();
        $error =& $this->error;

        if (!@include($f)) $this->error->failure('Could not load requested processor for action ' . $this->action . '.');
    }
}