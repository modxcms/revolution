<?php
/*
 * MODx Revolution
 * 
 * Copyright 2006, 2007, 2008, 2009 by the MODx Team.
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
 * modInstallRequest
 *
 * @package setup
 */
/**
 * The Installation Request handler.
 *
 * @package setup
 */
class modInstallRequest {
    /**
     * @var modInstall $install A reference to the modInstall object.
     * @access public
     */
    var $install = null;

    /**#@+
     * Initializes the modInstallRequest object.
     *
     * @constructor
     * @param modInstall &$modInstall A reference to the modInstall object.
     */
    function modInstallRequest(&$modInstall) {
        $this->__construct($modInstall);
    }
    /** @ignore */
    function __construct(&$modInstall) {
        $this->install =& $modInstall;
    }
    /**@#- */

    /**
     * Handles the request and loads the appropriate controller.
     */
    function handle() {
        $install =& $this->install;

        $this->parser->assign('app_name', 'MODx Revolution');
        $this->parser->assign('app_version', '2.0.0-alpha');

        $agreed= isset ($_REQUEST['agreed']) ? true : false;
        $agreedChecked= $agreed ? ' checked="checked"' : '';

        $this->action= isset ($_REQUEST['action']) ? $_REQUEST['action'] : 'language';
        $this->parser->assign('action',$this->action);
        $this->parser->assign('_lang',$this->install->lexicon);
        @include (MODX_SETUP_PATH . 'controllers/' . $this->action . '.php');
        exit;
    }

    /**
     * Loads the Smarty parser
     * @return boolean True if successful.
     */
    function loadParser() {
        $loaded = false;
        if (!@require_once (MODX_SETUP_PATH . 'includes/modinstallsmarty.class.php')) {
            if (!@include (MODX_SETUP_PATH . 'provisioner/bootstrap.php')) {
                die ('<html><head><title></title></head><body><h1>FATAL ERROR: MODx Setup cannot continue.</h1><p>Make sure all the files in the MODx setup package have been uploaded to your server.</p></body></html>');
            }
        }
        $this->parser = new modInstallSmarty();
        $this->parser->caching= false;
        return $loaded;
    }
}