<?php
/*
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
    public $install = null;
    /** @var modInstallParser $parser */
    public $parser = null;
    public $action = '';

    /**
     * Initializes the modInstallRequest object.
     *
     * @constructor
     * @param modInstall &$installer A reference to the modInstall object.
     */
    function __construct(modInstall &$installer) {
        $this->install =& $installer;
        $this->loadParser();
    }

    /**
     * Handles the request and loads the appropriate controller.
     *
     * @return string
     */
    public function handle() {
        $install =& $this->install;
        $install->loadSettings();
        $install->loadDriver();
        $this->parser->set('config',$install->settings->fetch());

        $currentVersion = include MODX_CORE_PATH . 'docs/version.inc.php';

        $this->parser->set('app_name', 'MODX '.$currentVersion['code_name']);
        $this->parser->set('app_version', $currentVersion['full_version']);

        $agreed= isset ($_REQUEST['agreed']) ? true : false;
        $agreedChecked= $agreed ? ' checked="checked"' : '';

        $this->install->lexicon->load('default');
        $this->install->lexicon->load('drivers');
        $this->parser->set('_lang',$this->install->lexicon->fetch());

        $this->action= isset ($_REQUEST['action']) ? $_REQUEST['action'] : 'language';
        $this->parser->set('action',$this->action);


        $output = $this->parser->fetch('header.tpl');
        $parser =& $this->parser;
        $output .= include MODX_SETUP_PATH . 'controllers/' . $this->action . '.php';
        $output .= $this->parser->fetch('footer.tpl');

        return $output;
    }

    /**
     * Loads the Smarty parser
     *
     * @param string $class
     * @param string $path The path to the parser
     * @return boolean True if successful.
     */
    public function loadParser($class = 'parser.modInstallSmarty',$path = '') {
        $loaded = false;
        $className = $this->install->loadClass($class,$path);
        if (empty($className)) {
            if (!@include (MODX_SETUP_PATH . 'provisioner/bootstrap.php')) {
                die ('<html><head><title></title></head><body><h1>FATAL ERROR: MODX Setup cannot continue.</h1><p>Make sure all the files in the MODX setup package have been uploaded to your server.</p></body></html>');
            }
            $loaded = false;
        }
        $this->parser = new $className();
        return $loaded;
    }

    public function proceed($action) {
        $this->sendRedirect(MODX_SETUP_URL.'?action='.$action);
    }
    public function sendRedirect($url) {
        $header= 'Location: ' . $url;
        header($header);
        exit();
    }
}
