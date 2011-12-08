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
    /** @var modInstallParser|modInstallSmarty $parser */
    public $parser = null;
    public $action = '';
    /** @var modConfigReader $reader */
    public $configReader;

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
     * Get the existing or create a new configuration.
     *
     * @param integer $mode The install mode.
     * @param array $config An array of config attributes.
     * @return array A copy of the config attributes array.
     */
    public function getConfig($mode = 0, array $config = array ()) {
        switch ($mode) {
            case modInstall::MODE_UPGRADE_EVO :
                $this->loadConfigReader('config.modEvolutionConfigReader');
                $config = $this->configReader->read($config);
                break;

            case modInstall::MODE_UPGRADE_REVO :
            case modInstall::MODE_UPGRADE_REVO_ADVANCED :
                $this->loadConfigReader('config.modRevolutionConfigReader');
                $config = $this->configReader->read($config);
                break;

            default :
                $this->loadConfigReader('config.modRevolutionConfigReader');
                $config = $this->configReader->loadDefaults($config);
                $config = $this->setDefaultPaths($config);
                break;
        }
        $this->install->config = array_merge($this->install->config, $config);
        $this->install->config['database_dsn'] = $this->getDatabaseDSN($this->install->config['database_type'],$this->install->config['database_server'],$this->install->config['dbase'],$this->install->config['database_connection_charset']);
        return $this->install->config;
    }

    /**
     * Ensure default paths are set for the server on new installations
     * @param array $config
     * @return array
     */
    public function setDefaultPaths(array $config = array()) {
        $webUrl= substr($_SERVER['PHP_SELF'], 0, strpos($_SERVER['PHP_SELF'], 'setup/'));
        $webUrl= rtrim($webUrl,'/').'/';
        $defaults = array();
        $defaults['context_web_path'] = rtrim(MODX_INSTALL_PATH,'/').'/';
        $defaults['context_web_url'] = $webUrl;
        $defaults['context_mgr_path'] = rtrim(MODX_INSTALL_PATH,'/') . '/manager/';
        $defaults['context_mgr_url'] = $webUrl . 'manager/';
        $defaults['context_connectors_path'] = rtrim(MODX_INSTALL_PATH,'/') . '/connectors/';
        $defaults['context_connectors_url'] = $webUrl . 'connectors/';
        $defaults['core_path'] = MODX_CORE_PATH;

        /* first allow overwriting of defaults from config.xml for CLI installs if found */
        foreach ($defaults as $k => $v) {
            if (array_key_exists($k,$config)) {
                $defaults[$k] = $config[$k];
            }
        }

        $defaults['web_path'] = $defaults['context_web_path'];
        $defaults['web_url'] = $defaults['context_web_url'];
        $defaults['mgr_path'] = $defaults['context_mgr_path'];
        $defaults['mgr_url'] = $defaults['context_mgr_url'];
        $defaults['connectors_path'] = $defaults['context_connectors_path'];
        $defaults['connectors_url'] = $defaults['context_connectors_url'];
        $defaults['web_path_auto'] = 0;
        $defaults['web_url_auto'] = 0;
        $defaults['mgr_path_auto'] = 0;
        $defaults['mgr_url_auto'] = 0;
        $defaults['connectors_path_auto'] = 0;
        $defaults['connectors_url_auto'] = 0;
        $defaults['processors_path'] = MODX_CORE_PATH . 'model/modx/processors/';
        $defaults['assets_path'] = $defaults['web_path'] . 'assets/';
        $defaults['assets_url'] = $defaults['web_url'] . 'assets/';

        foreach ($defaults as $k => $v) {
            if (!array_key_exists($k,$config)) {
                $config[$k] = $defaults[$k];
            }
        }
        return $config;
    }

    /**
     * Get the database DSN from the passed parameters
     * @param string $databaseType
     * @param string $databaseServer
     * @param string $database
     * @param string $databaseConnectionCharset
     * @return string
     */
    public function getDatabaseDSN($databaseType,$databaseServer,$database,$databaseConnectionCharset = '') {
        $dsn = '';
        switch ($databaseType) {
            case 'sqlsrv':
                $dsn = "{$databaseType}:server={$databaseServer};database={$database}";
                break;
            case 'mysql':
                $dsn = "{$databaseType}:host={$databaseServer};dbname={$database};charset={$databaseConnectionCharset}";
                break;
            default:
                break;
        }
        return $dsn;
    }

    /**
     * Loads the Config Reader
     *
     * @param string $class
     * @param string $path The path to the parser
     * @return boolean True if successful.
     */
    public function loadConfigReader($class = 'config.modRevolutionConfigReader',$path = '') {
        $loaded = false;
        $className = $this->install->loadClass($class,$path);
        if (empty($className)) {
            die ('<html><head><title></title></head><body><h1>FATAL ERROR: MODX Setup cannot continue.</h1><p>Make sure all the files in the MODX setup package have been uploaded to your server.</p></body></html>');
        }
        $this->configReader = new $className($this->install);
        return $loaded;
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