<?php
/*
 * This file is part of MODX Revolution.
 *
 * Copyright (c) MODX, LLC. All Rights Reserved.
 *
 * For complete copyright and license information, see the COPYRIGHT and LICENSE
 * files found in the top-level directory of this distribution.
 */

use MODX\Revolution\modWorkspace;
use xPDO\Transport\xPDOTransport;
use xPDO\xPDO;

/**
 * @package modx
 * @subpackage setup
 */
abstract class modInstallRunner {
    const RESULT_FAILURE = 'failed';
    const RESULT_ERROR = 'error';
    const RESULT_WARNING = 'warning';
    const RESULT_SUCCESS = 'success';

    /** @var modInstall $install */
    public $install;
    /** @var xPDO $xpdo */
    public $xpdo;
    /** @var array $config */
    public $config = [];

    public $success = false;

    /** @var modInstallVersion $versioner */
    public $versioner;
    /** @var array $results */
    public $results = [];

    function __construct(modInstall $install,array $config = []) {
        $this->install =& $install;
        $this->xpdo =& $install->xpdo;
        $this->config = array_merge([

        ],$config);
    }

    public function run($mode) {
        $this->initialize();
        $this->execute($mode);
        if ($this->success) {
            $this->cleanup();
        }
        return $this->success;
    }

    public function addResult($type,$message) {
        $this->results[] = [
            'class' => $type,
            'msg' => $message,
        ];
    }

    public function getResults() {
        return $this->results;
    }

    /**
     * Load version-specific installer.
     *
     * @access public
     * @param string $class The class to load.
     * @param string $path
     * @return modInstallVersion
     */
    public function loadVersionInstaller($class = 'modInstallVersion',$path = '') {
        $className = $this->install->loadClass($class,$path);
        if (!empty($className)) {
            $this->versioner = new $className($this);
            return $this->versioner;
        } else {
            $this->install->_fatalError($this->install->lexicon('versioner_err_nf', ['path' => $path]));
        }
        return $this->versioner;
    }

    /**
     * Update the workspace path
     * @return boolean
     */
    public function updateWorkspace() {
        $updated = false;
        /* @var modWorkspace $workspace set default workspace path */
        $workspace = $this->install->xpdo->getObject(modWorkspace::class, [
            'active' => 1
        ]);
        if ($workspace) {
            $path = $workspace->get('path');
            if (!empty($path)) {
                $path = trim($path);
            }
            if (empty ($path) || !file_exists($path)) {
                $workspace->set('path', '{core_path}');
                if (!$workspace->save()) {
                    $this->addResult(modInstallRunner::RESULT_ERROR,'<p class="notok">'.$this->install->lexicon('workspace_err_path').'</p>');
                } else {
                    $updated = true;
                    $this->addResult(modInstallRunner::RESULT_SUCCESS,'<p class="ok">'.$this->install->lexicon('workspace_path_updated').'</p>');
                }
            }
        } else {
            $this->addResult(modInstallRunner::RESULT_ERROR,'<p class="notok">'.$this->install->lexicon('workspace_err_nf').'</p>');
        }
        return $updated;
    }

    /**
     * @return bool
     */
    public function installPackage() {
        /* add required core data */
        $packageDirectory = MODX_CORE_PATH . 'packages/';
        $packageState = $this->install->settings->get('unpacked') == 1 ? xPDOTransport::STATE_UNPACKED : xPDOTransport::STATE_PACKED;
        $package = xPDOTransport :: retrieve($this->install->xpdo, $packageDirectory . 'core.transport.zip', $packageDirectory, $packageState);
        if (!is_object($package) || !($package instanceof xPDOTransport)) {
            $this->addResult(modInstallRunner::RESULT_FAILURE,'<p class="notok">'.$this->install->lexicon('package_execute_err_retrieve',
                    ['path' => $this->install->settings->get('core_path')]).'</p>');
            return false;
        }

        if (!defined('MODX_BASE_PATH'))
            define('MODX_BASE_PATH', $this->install->settings->get('context_web_path'));
        if (!defined('MODX_ASSETS_PATH')) {
            $assetsDefault = $this->install->settings->get('context_assets_path',$this->install->settings->get('context_web_path').'assets/');
            define('MODX_ASSETS_PATH',$assetsDefault);
        }
        if (!defined('MODX_MANAGER_PATH'))
            define('MODX_MANAGER_PATH', $this->install->settings->get('context_mgr_path'));
        if (!defined('MODX_CONNECTORS_PATH'))
            define('MODX_CONNECTORS_PATH', $this->install->settings->get('context_connectors_path'));

        if (!defined('MODX_BASE_URL'))
            define('MODX_BASE_URL', $this->install->settings->get('context_web_url'));
        if (!defined('MODX_ASSETS_URL')) {
            $assetsDefault = $this->install->settings->get('context_assets_url',$this->install->settings->get('context_web_url').'assets/');
            define('MODX_ASSETS_URL',$assetsDefault);
        }
        if (!defined('MODX_MANAGER_URL'))
            define('MODX_MANAGER_URL', $this->install->settings->get('context_mgr_url'));
        if (!defined('MODX_CONNECTORS_URL'))
            define('MODX_CONNECTORS_URL', $this->install->settings->get('context_connectors_url'));

        return $package->install([
            xPDOTransport::RESOLVE_FILES => ($this->install->settings->get('inplace') == 0 ? 1 : 0)
            ,xPDOTransport::INSTALL_FILES => ($this->install->settings->get('inplace') == 0 ? 1 : 0)
            , xPDOTransport::PREEXISTING_MODE => xPDOTransport::REMOVE_PREEXISTING
        ]);
    }


    /**
     * Writes the config file.
     *
     * @return boolean Returns true if successful; false otherwise.
     */
    public function writeConfig() {
        $written = false;
        $configTpl = MODX_CORE_PATH . 'docs/config.inc.tpl';
        $configFile = MODX_CORE_PATH . 'config/' . MODX_CONFIG_KEY . '.inc.php';

        $settings = $this->install->settings->fetch();
        $settings['last_install_time'] = time();
        $settings['site_id'] = uniqid('modx',true);

        /* make UUID if not set */
        if (empty($settings['uuid'])) {
            $settings['uuid'] = $this->install->generateUUID();
        }

        if (file_exists($configTpl)) {
            if ($tplHandle = @ fopen($configTpl, 'rb')) {
                $content = @ fread($tplHandle, filesize($configTpl));
                @ fclose($tplHandle);
                if ($content) {
                    $replace = [];
                    foreach ($settings as $key => $value) {
                        // Sanitize MySQL Password before writing to config, escaping single quotes and backslashes (https://github.com/modxcms/revolution/issues/12502)
                        if ($key == 'database_password') {
                            $value = str_replace("\\", "\\\\", $value);
                            $value = str_replace("'", "\\'", $value);
                        }
                        if (is_scalar($value)) {
                            $replace['{' . $key . '}'] = "{$value}";
                        } elseif (is_array($value)) {
                            $replace['{' . $key . '}'] = var_export($value, true);
                        }
                    }
                    $content = str_replace(array_keys($replace), array_values($replace), $content);
                    if ($configHandle = @ fopen($configFile, 'wb')) {
                        $written = @ fwrite($configHandle, $content);
                        @ fclose($configHandle);
                    }
                }
            }
        }
        $perms = $this->install->settings->get('new_file_permissions', sprintf("%04o", 0666 & (0777 - umask())));
        if (is_string($perms)) $perms = octdec($perms);
        $chmodSuccess = @ chmod($configFile, $perms);
        if ($written) {
            $this->addResult(modInstallRunner::RESULT_SUCCESS,'<p class="ok">'.$this->install->lexicon('config_file_written').'</p>');
        } else {
            $this->addResult(modInstallRunner::RESULT_FAILURE,'<p class="notok">'.$this->install->lexicon('config_file_err_w').'</p>');
        }
        if ($chmodSuccess) {
            $this->addResult(modInstallRunner::RESULT_SUCCESS,'<p class="ok">'.$this->install->lexicon('config_file_perms_set').'</p>');
        } else {
            $this->addResult(modInstallRunner::RESULT_WARNING,'<p>'.$this->install->lexicon('config_file_perms_notset').'</p>');
        }
        return $written;
    }


    abstract public function execute($mode);
    abstract public function initialize();
    abstract public function cleanup();
}
