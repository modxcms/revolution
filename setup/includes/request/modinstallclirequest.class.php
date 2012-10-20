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
    /** @var int $timeStart */
    public $timeStart = 0;
    /** @var string $timeTotal */
    public $timeTotal = '';
    
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
        $this->beginTimer();
        /* prepare the settings */
        $settings = $_REQUEST;
        if (empty($settings['installmode'])) $settings['installmode'] = modInstall::MODE_NEW;
        $settings['installmode'] = $this->getInstallMode($settings['installmode']);
        $this->settings->fromArray($settings); /* load CLI args into settings */

        /* load the config.xml file */
        $config = $this->getConfig($settings['installmode']);
        if (empty($config)) {
            $this->end($this->install->lexicon('cli_no_config_file'));
        }
        $this->settings->fromArray($config);
        $this->settings->fromArray($settings); /* do again to allow CLI-based overrides of config.xml */

        /* load the driver */
        $this->install->loadDriver();

        /* Run tests */
        $mode = (int)$this->install->settings->get('installmode');
        $results= $this->install->test($mode);
        if (!$this->install->test->success) {
            $msg = "\n";
            foreach ($results['fail'] as $field => $result) {
                $msg .= $field.': '.$result['title'].' - '.$result['message'];
            }
            $msg = $this->install->lexicon('cli_tests_failed',array(
                'errors' => $msg,
            ));
            $this->end($msg);
        }

        /** Attempt to create the database */
        $this->checkDatabase();

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
                    $this->install->xpdo->log(xPDO::LOG_LEVEL_ERROR,$item['msg']);
                    $errors[] = $item;
                    break;
                }
            }
        }

        if ($failed) {
            $msg = "\n";
            foreach ($errors as $field => $result) {
                $msg .= $result['msg'];
            }
            $msg = $this->install->lexicon('cli_install_failed',array(
                'errors' => $msg,
            ));
            $this->end($msg);
        }

        /* cleanup */
        $errors= $this->install->verify();
        foreach ($errors as $error) {
            $this->install->xpdo->log(xPDO::LOG_LEVEL_ERROR,$error);
        }
        $cleanupErrors = $this->install->cleanup();
        foreach ($cleanupErrors as $key => $error) {
            $this->install->xpdo->log(xPDO::LOG_LEVEL_ERROR,$error);
        }

        if ($this->settings->get('remove_setup_directory')) {
            $this->install->removeSetupDirectory();
        }
        $this->endTimer();
        $this->end(''.$this->install->lexicon('installation_finished',array(
            'time' => $this->timeTotal,
        )));
    }

    /**
     * {@inheritDoc}
     * @param int $mode
     * @param array $config
     * @return array
     */
    public function getConfig($mode = 0, array $config = array()) {
        /* load the config file */
        $config = array_merge($this->loadConfigFile(), $config);
        $config = parent::getConfig($mode, $config);
        $this->prepareSettings($config);
        return $config;
    }

    /**
     * Attempt to load the config.xml (or other config file) to use when installing. One must be present to run
     * MODX Setup in CLI mode.
     * 
     * @return array
     */
    public function loadConfigFile() {
        $settings = array();
        $configFile = $this->settings->get('config');
        if (empty($configFile)) $configFile = MODX_INSTALL_PATH.'setup/config.xml';
        if (!empty($configFile)) {
            if (!file_exists($configFile) && file_exists(MODX_SETUP_PATH.$configFile)) {
                $configFile = MODX_SETUP_PATH.$configFile;
            }
        } elseif (file_exists(MODX_SETUP_PATH.'config.xml')) {
            $configFile = MODX_SETUP_PATH.'config.xml';
        }
        if (!empty($configFile) && file_exists($configFile)) {
            $settings = $this->parseConfigFile($configFile);
        }
        return $settings;
    }

    /**
     * Prepares settings for installation, including setting of defaults
     * 
     * @param array $settings
     * @return void
     */
    public function prepareSettings(array &$settings) {
        if (empty($settings['site_sessionname'])) {
            $settings['site_sessionname'] = 'SN' . uniqid('');
        }
        if (empty($settings['config_options'])) {
            $settings['config_options'] = array();
        }
        if (empty($settings['database']) && !empty($settings['dbase'])) {
            $settings['database'] = $settings['dbase'];
        }
        $settings['database_dsn'] = $this->getDatabaseDSN($settings['database_type'],$settings['database_server'],$settings['database'],$settings['database_connection_charset']);
        if (!empty($settings['database'])) {
            $settings['dbase'] = $settings['database'];
        }
        $this->settings->fromArray($settings);

        $this->setDefaultSetting('processors_path',$this->settings->get('core_path').'model/modx/processors/');
        $this->setDefaultSetting('connectors_path',$this->settings->get('context_connectors_path'));
        $this->setDefaultSetting('connectors_url',$this->settings->get('context_connectors_url'));
        $this->setDefaultSetting('mgr_path',$this->settings->get('context_mgr_path'));
        $this->setDefaultSetting('mgr_url',$this->settings->get('context_mgr_url'));
        $this->setDefaultSetting('web_path',$this->settings->get('context_web_path'));
        $this->setDefaultSetting('web_url',$this->settings->get('context_web_url'));
        $this->setDefaultSetting('assets_path',$this->settings->get('context_assets_path',$this->settings->get('context_web_path').'assets/'));
        $this->setDefaultSetting('assets_url',$this->settings->get('context_assets_url',$this->settings->get('context_web_url').'assets/'));
    }

    /**
     * Sets a default for a setting if not set
     * @param string $key
     * @param mixed $default
     * @return void
     */
    public function setDefaultSetting($key,$default) {
        $value = $this->settings->get($key,null);
        if ($value === null) {
            $this->settings->set($key,$default);
        }
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
     * Check database settings
     * @return void
     */
    public function checkDatabase() {
        $mode = $this->settings->get('installmode');

        /* get an instance of xPDO using the install settings */
        $xpdo = $this->install->getConnection($mode);
        if (!is_object($xpdo) || !($xpdo instanceof xPDO)) {
            $this->end($this->install->lexicon('xpdo_err_ins'));
        }

        /* try to get a connection to the actual database */
        $dbExists = $xpdo->connect();
        if (!$dbExists) {
            if ($mode == modInstall::MODE_NEW && $xpdo->getManager()) {
                /* otherwise try to create the database */
                $dbExists = $xpdo->manager->createSourceContainer(
                    array(
                        'dbname' => $this->settings->get('dbase')
                        ,'host' => $this->settings->get('database_server')
                    )
                    ,$this->settings->get('database_user')
                    ,$this->settings->get('database_password')
                    ,array(
                        'charset' => $this->settings->get('database_connection_charset')
                        ,'collation' => $this->settings->get('database_collation')
                    )
                );
                if (!$dbExists) {
                    $this->end($this->install->lexicon('db_err_create_database'));
                } else {
                    $xpdo = $this->install->getConnection($mode);
                    if (!is_object($xpdo) || !($xpdo instanceof xPDO)) {
                        $this->end($this->install->lexicon('xpdo_err_ins'));
                    }
                }
            } elseif ($mode == modInstall::MODE_NEW) {
                $this->end($this->install->lexicon('db_err_connect_server'));
            }
        }
        if (!$xpdo->connect()) {
            $this->end($this->install->lexicon('db_err_connect'));
        }

        /* test table prefix */
        if ($mode == modInstall::MODE_NEW || $mode == modInstall::MODE_UPGRADE_REVO_ADVANCED) {
            $count = null;
            $database = $this->settings->get('dbase');
            $prefix = $this->settings->get('table_prefix');
            $stmt = $xpdo->query($this->install->driver->testTablePrefix($database,$prefix));
            if ($stmt) {
                $row = $stmt->fetch(PDO::FETCH_ASSOC);
                if ($row) {
                    $count = (integer) $row['ct'];
                }
                $stmt->closeCursor();
            }
            if ($mode == modInstall::MODE_NEW && $count !== null) {
                $this->end($this->install->lexicon('test_table_prefix_inuse'));
            } elseif ($mode == modInstall::MODE_UPGRADE_REVO_ADVANCED && $count === null) {
                $this->end($this->install->lexicon('test_table_prefix_nf'));
            }
        }
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

    /**
     * Start the debugging timer
     * @return int
     */
    protected function beginTimer() {
        $mtime = microtime();
        $mtime = explode(" ", $mtime);
        $mtime = $mtime[1] + $mtime[0];
        $this->timeStart = $mtime;
        return $this->timeStart;
    }
    /**
     * End the debugging timer
     * @return string
     */
    protected function endTimer() {
        $mtime = microtime();
        $mtime = explode(" ", $mtime);
        $mtime = $mtime[1] + $mtime[0];
        $tend = $mtime;
        $this->timeTotal = ($tend - $this->timeStart);
        $this->timeTotal = sprintf("%2.4f s", $this->timeTotal);
        return $this->timeTotal;
    }
}