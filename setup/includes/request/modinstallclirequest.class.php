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
require_once strtr(realpath(MODX_SETUP_PATH.'includes/request/modinstallrequest.class.php'),'\\','/');
/**
 * modInstallCLIRequest
 *
 * @package setup
 */
/**
 * Handles CLI installs.
 *
 * @package setup
 */
class modInstallCLIRequest extends modInstallRequest {
    /** @var modInstall $install */
    public $install;
    
    /**
     * Constructor for modInstallConnector object.
     *
     * @constructor
     * @param modInstall &$modInstall A reference to the modInstall object.
     */
    function __construct(modInstall &$modInstall) {
        $this->install =& $modInstall;
        $this->install->loadSettings();
        $this->settings =& $this->install->settings;
    }

    /**
     * Parse the install mode from a string or int
     * @param string|int $mode
     * @return int
     */
    public function getInstallMode($mode) {
        if (!is_int($mode)) {
            switch ($mode) {
                case 'new':
                    $mode = modInstall::MODE_NEW;
                    break;
                case 'upgrade-evo':
                    $mode = modInstall::MODE_UPGRADE_EVO;
                    break;
                case 'upgrade-revo-advanced':
                case 'upgrade-advanced':
                    $mode = modInstall::MODE_UPGRADE_REVO_ADVANCED;
                    break;
                case 'upgrade-revo':
                case 'upgrade':
                default:
                    $mode = modInstall::MODE_UPGRADE_REVO;
                    break;
            }
        }
        return $mode;
    }

    /**
     * Handles connector requests.
     *
     * @param string $action
     */
    public function handle($action = '') {
        /* prepare the settings */
        $settings = $_REQUEST;
        if (empty($settings['installmode'])) $settings['installmode'] = modInstall::MODE_NEW;
        $settings['installmode'] = $this->getInstallMode($settings['installmode']);
        $config = $this->install->getConfig($settings['installmode']);
        if (!empty($config)) {
            $this->settings->fromArray($config);
        }
        $this->settings->fromArray($settings);

        /* load the config file */
        if (!$this->loadConfigFile()) {
            $this->end('No config file!');
        }

        /* load the driver */
        $this->install->loadDriver();

        /* Run tests */
        $mode = (int)$this->install->settings->get('installmode');
        $results= $this->install->test($mode);
        if (!$this->install->test->success) {
            $msg = 'Failed tests: '."\n";
            foreach ($results['fail'] as $field => $result) {
                $msg .= $field.': '.$result['title'].' - '.$result['message'];
            }
            $this->end($msg);
        }

        /* Run installer */
        $this->install->getService('runner','runner.modInstallRunnerWeb');
        $failed = true;
        $errors = array();
        if ($this->install->runner) {
            $success = $this->install->runner->run($mode);
            $results = $this->install->runner->getResults();

            $failed= false;
            foreach ($results as $item) {
                if ($item['class'] === 'failed') {
                    $failed= true;
                    $errors[] = $item;
                    break;
                }
            }
        }

        if ($failed) {
            $msg = 'Failed install! '."\n";
            foreach ($errors as $field => $result) {
                $msg .= $result['msg'];
            }
            $this->end($msg);
        }

        /* cleanup */
        $errors= $this->install->verify();
        $cleanupErrors = $this->install->cleanup();
        $this->end('Installation finished.');
        
    }

    /**
     * Attempt to load the config.xml (or other config file) to use when installing. One must be present to run
     * MODX Setup in CLI mode.
     * 
     * @return boolean
     */
    public function loadConfigFile() {
        $loaded = false;
        $configFile = $this->settings->get('config');
        if (!empty($configFile)) {
            if (!file_exists($configFile) && file_exists(MODX_SETUP_PATH.$configFile)) {
                $configFile = MODX_SETUP_PATH.$configFile;
            }
        } elseif (file_exists(MODX_SETUP_PATH.'config.xml')) {
            $configFile = MODX_SETUP_PATH.'config.xml';
        }
        if (!empty($configFile) && file_exists($configFile)) {
            $settings = $this->parseConfigFile($configFile);
            if (!empty($settings)) {
                if (empty($settings['site_sessionname'])) {
                    $settings['site_sessionname'] = 'SN' . uniqid('');
                }
                if (empty($settings['config_options'])) {
                    $settings['config_options'] = array();
                }

                $this->settings->fromArray($settings);
                $settings = array_merge($this->settings->fetch());
                $dsn = $this->install->getDatabaseDSN($settings['database_type'],$settings['database_server'],$settings['dbase'],$settings['database_connection_charset']);
                $this->settings->set('database_dsn',$dsn);
                $loaded = true;
            }
        }
        return $loaded;
    }

    /**
     * Parse the config XML file
     *
     * @param string $file
     * @return array
     */
    public function parseConfigFile($file) {
        $contents = file_get_contents($file);
        $xml = new SimpleXMLElement($contents);

        $settings = array();
        foreach ($xml as $k => $v) {
            $settings[(string)$k] = (string)$v;
        }

        return $settings;
    }

    /**
     * End the PHP session and output a message
     *
     * @param string $message
     * @return void
     */
    public function end($message = '') {
        @session_write_close();
        die($message."\n");
    }
}