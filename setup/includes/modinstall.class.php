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
 * Common classes for the MODx installation and provisioning services.
 *
 * @package setup
 */
/**
 * Provides common functionality and data for installation and provisioning.
 *
 * @package setup
 */
class modInstall {
    const MODE_NEW = 0;
    const MODE_UPGRADE_REVO = 1;
    const MODE_UPGRADE_EVO = 2;

    public $xpdo = null;
    public $options = array ();
    public $config = array ();
    public $action = '';
    public $lexicon = array ();

    /**
     * The constructor for the modInstall object.
     *
     * @constructor
     * @param array $options An array of configuration options.
     */
    function __construct(array $options = array()) {
    	if (isset ($_REQUEST['action'])) {
            $this->action = $_REQUEST['action'];
        }
        if (is_array($options)) {
            $this->options = $options;
        }
    }

    /**
     * Loads the request handler for the setup.
     * @return boolean True if successful.
     */
    public function loadRequestHandler($class = 'modInstallRequest') {
        $path = dirname(__FILE__).'/'.strtolower($class).'.class.php';
        $included = @include $path;
        if ($included) {
            $this->request = new $class($this);
        } else {
            $this->_fatalError(sprintf($this->lexicon['request_handler_err_nf'],$path));
        }
        return $included;
    }

    /**
     * Load the language strings.
     */
    public function loadLang($topic = 'default') {
        $_lang= array ();
        if (!include (MODX_SETUP_PATH . 'lang/en/'.$topic.'.inc.php')) {
            die('<html><head><title></title></head><body><h1>FATAL ERROR: MODx Setup cannot continue.</h1><p>Could not load the default language directory. Make sure you have uploaded all the necessary files.</p></body></html>');
        }

        $language= 'en';
        if (isset ($_COOKIE['modx_setup_language'])) {
            $language= $_COOKIE['modx_setup_language'];
        }
        $language= isset ($_REQUEST['language']) ? $_REQUEST['language'] : $language;
        if ($language && $language != 'en') {
            include MODX_SETUP_PATH . 'lang/'.$language.'/'.$topic.'.inc.php';
        }
        if (!is_array($this->lexicon)) $this->lexicon = array();

        $this->lexicon = array_merge($this->lexicon,$_lang);

        return $this->lexicon;
    }

    /**
     * Get the existing or create a new configuration.
     *
     * @param integer $mode The install mode.
     * @param array $config An array of config attributes.
     * @return array A copy of the config attributes array.
     */
    public function getConfig($mode = 0, $config = array ()) {
        global $database_type, $database_server, $dbase, $database_user, $database_password, $database_connection_charset, $table_prefix;
        if (!is_array($config)) {
            $config = array ();
        }
        switch ($mode) {
            case modInstall::MODE_UPGRADE_EVO :
                $included = @ include MODX_INSTALL_PATH . 'manager/includes/config.inc.php';
                if ($included && isset ($dbase))
                    break;

            case modInstall::MODE_UPGRADE_REVO :
                $included = @ include MODX_CORE_PATH . 'config/' . MODX_CONFIG_KEY . '.inc.php';
                if ($included && isset ($dbase))
                    break;

            default :
                $included = false;
                $database_server = isset ($_POST['databasehost']) ? $_POST['databasehost'] : 'localhost';
                $database_user = isset ($_POST['databaseloginname']) ? $_POST['databaseloginname'] : '';
                $database_password = isset ($_POST['databaseloginpassword']) ? $_POST['databaseloginpassword'] : '';
                $database_collation = isset ($_POST['database_collation']) ? $_POST['database_collation'] : 'utf8_unicode_ci';
                $database_charset = substr($database_collation, 0, strpos($database_collation, '_'));
                $database_connection_charset = isset ($_POST['database_connection_charset']) ? $_POST['database_connection_charset'] : $database_charset;
                $dbase = isset ($_POST['database_name']) ? $_POST['database_name'] : 'modx';
                $table_prefix = isset ($_POST['tableprefix']) ? $_POST['tableprefix'] : 'modx_';
                $https_port = isset ($_POST['httpsport']) ? $_POST['httpsport'] : '443';
                $cache_disabled = isset ($_POST['cache_disabled']) ? $_POST['cache_disabled'] : 'false';
                $site_sessionname = 'SN' . uniqid('');
                break;
        }
        $config = array_merge($config,array(
            'database_type' => 'mysql',
            'database_server' => $database_server,
            'dbase' => trim($dbase,'`'),
            'database_user' => $database_user,
            'database_password' => $database_password,
            'database_collation' => isset ($database_collation) ? $database_collation : 'utf8_unicode_ci',
            'database_charset' => $database_charset,
            'database_connection_charset' => $database_connection_charset,
            'table_prefix' => $table_prefix,
            'https_port' => isset ($https_port) ? $https_port : '443',
            'site_sessionname' => isset ($site_sessionname) ? $site_sessionname : 'SN' . uniqid(''),
            'cache_disabled' => isset ($cache_disabled) && $cache_disabled ? 'true' : 'false',
            'inplace' => isset ($_POST['inplace']) ? 1 : 0,
            'unpacked' => isset ($_POST['unpacked']) ? 1 : 0,
        ));
        $this->config = array_merge($this->config, $config);
        return $this->config;
    }

    /**
     * Set the install configuration settings.
     *
     * @param integer $mode The install mode.
     */
    public function setConfig($mode = modInstall::MODE_NEW) {
        $config = array(
            'database_type' => 'mysql',
            'database_server' => isset ($_POST['databasehost']) ? $_POST['databasehost'] : 'localhost',
            'database_user' => isset ($_POST['databaseloginname']) ? $_POST['databaseloginname'] : '',
            'database_password' => isset ($_POST['databaseloginpassword']) ? $_POST['databaseloginpassword'] : '',
            'database_collation' => isset ($_POST['database_collation']) ? $_POST['database_collation'] : 'utf8_unicode_ci',
            'dbase' => isset ($_POST['database_name']) ? $_POST['database_name'] : 'modx',
            'table_prefix' => isset ($_POST['tableprefix']) ? $_POST['tableprefix'] : 'modx_',
            'https_port' => isset ($_POST['httpsport']) ? $_POST['httpsport'] : '443',
            'cache_disabled' => isset ($_POST['cachedisabled']) ? $_POST['cachedisabled'] : 'false',
            'site_sessionname' => isset ($_POST['site_sessionname']) ? $_POST['site_sessionname'] : 'SN' . uniqid(''),
            'inplace' => isset ($_POST['inplace']) ? 1 : 0,
            'unpacked' => isset ($_POST['unpacked']) ? 1 : 0,
        );
        $config['database_charset'] = substr($config['database_collation'], 0, strpos($config['database_collation'], '_'));
        $config['database_connection_charset'] = isset($_POST['database_connection_charset']) ? $_POST['database_connection_charset'] : $config['database_charset'];

        $this->config = array_merge($this->config, $config);
    }

    /**
     * Get an xPDO connection to the database.
     *
     * @return xPDO A copy of the xpdo object.
     */
    public function getConnection($mode = modInstall::MODE_NEW) {
        if ($mode === modInstall::MODE_UPGRADE_REVO) {
            $errors = array ();
            $this->xpdo = $this->_modx($errors);
        } else if (!is_object($this->xpdo)) {
            $this->xpdo = $this->_connect($this->config['database_type'] . ':host=' . $this->config['database_server'] . ';dbname=' . trim($this->config['dbase'], '`') . ';charset=' . $this->config['database_connection_charset'] . ';collation=' . $this->config['database_collation'], $this->config['database_user'], $this->config['database_password'], $this->config['table_prefix']);
            if (!($this->xpdo instanceof xPDO)) { return $this->xpdo; }

            $this->xpdo->config['cache_path'] = MODX_CORE_PATH . 'cache/';
        }
        if (is_object($this->xpdo)) {
            $this->xpdo->setDebug(E_ALL & ~E_STRICT);
            $this->xpdo->setLogTarget(array(
                'target' => 'FILE',
                'options' => array(
                    'filename' => 'install.' . MODX_CONFIG_KEY . '.' . strftime('%Y-%m-%dT%H:%M:%S')
                )
            ));
            $this->xpdo->setLogLevel(xPDO::LOG_LEVEL_WARN);
            $this->xpdo->setPackage('modx', MODX_CORE_PATH . 'model/', $this->config['table_prefix']);
        }
        return $this->xpdo;
    }

    /**
     * Get the initial admin user settings indicated by user.
     *
     * @return array A copy of the install config array merged with the retrieved admin user attributes.
     */
    public function getAdminUser() {
        $config = array (
            'cmsadmin' => $_POST['cmsadmin'],
            'cmsadminemail' => $_POST['cmsadminemail'],
            'cmspassword' => $_POST['cmspassword'],
            'cmspasswordconfirm' => $_POST['cmspasswordconfirm'],
        );
        $this->config = array_merge($this->config, $config);
        return $this->config;
    }

    /**
     * Get the installation paths indicated by user.
     *
     * @return array A copy of the install config array merged with the retrieved context paths.
     */
    public function getContextPaths() {
        $config = array ();
        $webUrl= substr($_SERVER['PHP_SELF'], 0, strpos($_SERVER['PHP_SELF'], 'setup/'));
        $config['core_path'] = MODX_CORE_PATH;
        $config['web_path_auto'] = isset ($_POST['context_web_path_toggle']) && $_POST['context_web_path_toggle'] ? 1 : 0;
        $config['web_path'] = isset($_POST['context_web_path']) ? $_POST['context_web_path'] : MODX_INSTALL_PATH;
        $config['web_url_auto'] = isset ($_POST['context_web_url_toggle']) && $_POST['context_web_url_toggle'] ? 1 : 0;
        $config['web_url'] = isset($_POST['context_web_url']) ? $_POST['context_web_url'] : $webUrl;
        $config['mgr_path_auto'] = isset ($_POST['context_mgr_path_toggle']) && $_POST['context_mgr_path_toggle'] ? 1 : 0;
        $config['mgr_path'] = isset($_POST['context_mgr_path']) ? $_POST['context_mgr_path'] : MODX_INSTALL_PATH . 'manager/';
        $config['mgr_url_auto'] = isset ($_POST['context_mgr_url_toggle']) && $_POST['context_mgr_url_toggle'] ? 1 : 0;
        $config['mgr_url'] = isset($_POST['context_mgr_url']) ? $_POST['context_mgr_url'] : $webUrl . 'manager/';
        $config['connectors_path_auto'] = isset ($_POST['context_connectors_path_toggle']) && $_POST['context_connectors_path_toggle'] ? 1 : 0;
        $config['connectors_path'] = isset($_POST['context_connectors_path']) ? $_POST['context_connectors_path'] : MODX_INSTALL_PATH . 'connectors/';
        $config['connectors_url_auto'] = isset ($_POST['context_connectors_url_toggle']) && $_POST['context_connectors_url_toggle'] ? 1 : 0;
        $config['connectors_url'] = isset($_POST['context_connectors_url']) ? $_POST['context_connectors_url'] : $webUrl . 'connectors/';
        $config['processors_path'] = MODX_CORE_PATH . 'model/modx/processors/';
        $config['assets_path'] = $config['web_path'] . 'assets/';
        $config['assets_url'] = $config['web_url'] . 'assets/';
        $this->config = array_merge($this->config, $config);
        return $this->config;
    }

    /**
     * Load distribution-specific test handlers
     */
    public function loadTestHandler($class = 'modInstallTest') {
        $path = dirname(__FILE__).'/'.strtolower($class).'.class.php';
        $included = @include $path;
        if ($included) {
            $this->loadLang('test');

            $class = $class.ucfirst(str_replace('@','',MODX_SETUP_KEY));
            $versionPath = dirname(__FILE__).'/checks/'.strtolower($class).'.class.php';
            $included = @include $versionPath;
            if (!$included) {
                $this->_fatalError(sprintf($this->lexicon['test_version_class_nf'],$versionPath));
            }
            $this->test = new $class($this);
            return $this->test;
        } else {
            $this->_fatalError(sprintf($this->lexicon['test_class_nf'],$path));
        }
    }

    /**
     * Perform a series of pre-installation tests.
     *
     * @param integer $mode The install mode.
     * @param string $test_class The class to run tests with
     * @return array An array of result messages collected during the process.
     */
    public function test($mode = modInstall::MODE_NEW,$test_class = 'modInstallTest') {
        $test = $this->loadTestHandler($test_class);
        $results = $this->test->run($mode);
        return $results;
    }

    public function loadVersionInstaller($class = 'modInstallVersion') {
        $path = dirname(__FILE__).'/'.strtolower($class).'.class.php';
        $included = @include $path;
        if ($included) {
            $this->versioner = new $class($this);
            return $this->versioner;
        } else {
            $this->_fatalError(sprintf($this->lexicon['versioner_err_nf'],$path));
        }
    }

    /**
     * Execute the installation process.
     *
     * @param integer $mode The install mode.
     * @return array An array of result messages collected during execution.
     */
    public function execute($mode) {
        $results = array ();
        /* set the time limit infinite in case it takes a bit
         * TODO: fix this by allowing resume when it takes a long time
         */
        @ set_time_limit(0);
        @ ini_set('max_execution_time', 240);
        @ ini_set('memory_limit','128M');

        /* get connection */
        $this->getConnection($mode);
        $this->xpdo->setLogLevel(xPDO::LOG_LEVEL_ERROR);

        /* run appropriate database routines */
        switch ($mode) {
            /* TODO: MODx Evolution to Revolution migration */
            case modInstall::MODE_UPGRADE_EVO :
                $results = include MODX_SETUP_PATH . 'includes/tables_migrate.php';
                break;
                /* revo-alpha+ upgrades */
            case modInstall::MODE_UPGRADE_REVO :
                $this->loadVersionInstaller();
                $results = $this->versioner->install();
                break;
                /* new install, create tables */
            default :
                $results = include MODX_SETUP_PATH . 'includes/tables_create.php';
                break;
        }

        /* write config file */
        $this->writeConfig($results);

        if ($this->xpdo) {
            /* add required core data */
            $this->xpdo->loadClass('transport.xPDOTransport', XPDO_CORE_PATH, true, true);

            $packageDirectory = MODX_CORE_PATH . 'packages/';
            $packageState = $this->config['unpacked'] == 1 ? xPDOTransport::STATE_UNPACKED : xPDOTransport::STATE_PACKED;
            $package = xPDOTransport :: retrieve($this->xpdo, $packageDirectory . 'core.transport.zip', $packageDirectory, $packageState);

            if (!defined('MODX_BASE_PATH'))
                define('MODX_BASE_PATH', $this->config['web_path']);
            if (!defined('MODX_ASSETS_PATH'))
                define('MODX_ASSETS_PATH', $this->config['assets_path']);
            if (!defined('MODX_MANAGER_PATH'))
                define('MODX_MANAGER_PATH', $this->config['mgr_path']);
            if (!defined('MODX_CONNECTORS_PATH'))
                define('MODX_CONNECTORS_PATH', $this->config['connectors_path']);

            $package->install(array (
                xPDOTransport::RESOLVE_FILES => ($this->config['inplace'] == 0 ? 1 : 0),
            ));

            /* set default workspace path */
            if ($workspace = $this->xpdo->getObject('modWorkspace', array (
                    'active' => 1
                ))) {
                if ($path = $workspace->get('path')) {
                    $path = trim($path);
                }
                if (empty ($path) || !file_exists($path)) {
                    $workspace->set('path', MODX_CORE_PATH);
                    if (!$workspace->save()) {
                        $results[] = array (
                            'class' => 'error',
                            'msg' => '<p class="notok">'.$this->lexicon['workspace_err_path'].'</p>'
                        );
                    } else {
                        $results[] = array (
                            'class' => 'success',
                            'msg' => '<p class="ok">'.$this->lexicon['workspace_path_updated'].'</p>'
                        );
                    }
                }
            } else {
                $results[] = array (
                    'class' => 'error',
                    'msg' => '<p class="notok">'.$this->lexicon['workspace_err_nf'].'</p>'
                );
            }

            /* if new install */
            if ($mode == modInstall::MODE_NEW) {
                /* add settings_version */
                $currentVersion = include MODX_CORE_PATH . 'docs/version.inc.php';

                $settings_version = $this->xpdo->newObject('modSystemSetting');
                $settings_version->set('key','settings_version');
                $settings_version->set('value', $currentVersion['full_version']);
                $settings_version->save();

                /* add default admin user */
                $user = $this->xpdo->newObject('modUser');
                $user->set('username', $this->config['cmsadmin']);
                $user->set('password', md5($this->config['cmspassword']));
                if ($saved = $user->save()) {
                    $userProfile = $this->xpdo->newObject('modUserProfile');
                    $userProfile->set('internalKey', $user->get('id'));
                    $userProfile->set('fullname', $this->lexicon['default_admin_user']);
                    $userProfile->set('email', $this->config['cmsadminemail']);
                    $userProfile->set('role', 1);
                    $saved = $userProfile->save();
                    if ($saved) {
                        $userGroupMembership = $this->xpdo->newObject('modUserGroupMember');
                        $userGroupMembership->set('user_group', 1);
                        $userGroupMembership->set('member', $user->get('id'));
                        $userGroupMembership->set('role', 2);
                        $saved = $userGroupMembership->save();
                    }
                }
                if (!$saved) {
                    $results[] = array (
                        'class' => 'error',
                        'msg' => '<p class="notok">'.$this->lexicon['dau_err_save'].'<br />' . print_r($this->xpdo->errorInfo(), true) . '</p>'
                    );
                } else {
                    $results[] = array (
                        'class' => 'success',
                        'msg' => '<p class="ok">'.$this->lexicon['dau_saved'].'</p>'
                    );
                }
            /* if upgrade */
            } else {
                /* handle change of manager_theme to default (FIXME: temp hack) */
                if ($managerTheme = $this->xpdo->getObject('modSystemSetting', array(
                        'key' => 'manager_theme',
                        'value:!=' => 'default'
                    ))) {
                    $managerTheme->set('value', 'default');
                    $managerTheme->save();
                }

                /* handle change of default language to proper IANA code (FIXME: just forcing en for now) */
                if ($managerLanguage = $this->xpdo->getObject('modSystemSetting', array(
                        'key' => 'manager_language',
                        'value:!=' => 'en'
                    ))) {
                    $managerLanguage->set('value', 'en');
                    $managerLanguage->save();
                }

                /* update settings_version */
                $settings_version = $this->xpdo->getObject('modSystemSetting', array(
                    'key' => 'settings_version'
                ));
                if ($settings_version == null) {
                    $settings_version = $this->xpdo->newObject('modSystemSetting');
                    $settings_version->set('key','settings_version');
                    $settings_version->set('xtype','textfield');
                    $settings_version->set('namespace','core');
                    $settings_version->set('area','system');
                }
                $currentVersion = include MODX_CORE_PATH . 'docs/version.inc.php';
                $settings_version->set('value', $currentVersion['full_version']);
                $settings_version->save();

                /* make sure admin user (1) has proper group and role */
                $adminUser = $this->xpdo->getObject('modUser', 1);
                if ($adminUser) {
                    $userGroupMembership = $this->xpdo->getObject('modUserGroupMember', array('user_group' => true, 'member' => true));
                    if (!$userGroupMembership) {
                        $userGroupMembership = $this->xpdo->newObject('modUserGroupMember');
                        $userGroupMembership->set('user_group', 1);
                        $userGroupMembership->set('member', 1);
                        $userGroupMembership->set('role', 2);
                        $userGroupMembership->save();
                    } else {
                        $userGroupMembership->set('role', 2);
                        $userGroupMembership->save();
                    }
                }
            }

            /* empty sessions table to prevent old permissions from loading */
            $tableName = $this->xpdo->getTableName('modSession');
            $this->xpdo->exec('
                TRUNCATE '.$tableName.'
            ');

            /* clear cache */
            $this->xpdo->cacheManager->deleteTree(MODX_CORE_PATH.'cache/',array(
                'skipDirs' => false,
                'extensions' => array(
                    '.cache.php',
                    '.tpl.php',
                ),
            ));
        }

        return $results;
    }

    /**
     * Verify that the modX class can be initialized.
     *
     * @param integer $mode Indicates the installation mode.
     * @return array An array of error messages collected during the process.
     */
    public function verify($mode) {
        $errors = array ();
        if ($modx = $this->_modx($errors)) {
            if ($modx->getCacheManager()) {
                $modx->cacheManager->clearCache(array(), array(
                    'objects' => '*',
                    'publishing' => 1
                ));
            }
        }
        return $errors;
    }

    /**
     * Cleans up after install.
     *
     * TODO: implement this function to cleanup any temporary files
     * @param array $options
     */
    public function cleanup(array $options = array ()) {
        $errors = array();
        return $errors;
    }

    /**
     * Removes the setup directory
     *
     * @access publics
     */
    public function removeSetupDirectory(array $options = array()) {
        $errors = array();

        $modx = $this->_modx($errors);
        if ($modx) {
            $cacheManager = $modx->getCacheManager();
            if ($cacheManager) {
                $setupPath = $modx->getOption('base_path').'setup/';
                if (!$cacheManager->deleteTree($setupPath,true,false,false)) {
                    $modx->log(modX::LOG_LEVEL_ERROR,$this->lexicon['setup_err_remove']);
                }
            } else {
                $modx->log(modX::LOG_LEVEL_ERROR,$this->lexicon['cache_manager_err']);
            }
        } else {
            $modx->log(modX::LOG_LEVEL_ERROR,$this->lexicon['modx_object_err']);
        }
        return $errors;
    }

    /**
     * Writes the config file.
     *
     * @param array $results An array of result messages.
     * @return boolean Returns true if successful; false otherwise.
     */
    public function writeConfig(array &$results) {
        $written = false;
        $configTpl = MODX_CORE_PATH . 'config/config.inc.tpl';
        $configFile = MODX_CORE_PATH . 'config/' . MODX_CONFIG_KEY . '.inc.php';
        $this->config['last_install_time'] = time();
        if (file_exists($configTpl)) {
            if ($tplHandle = @ fopen($configTpl, 'rb')) {
                $content = @ fread($tplHandle, filesize($configTpl));
                @ fclose($tplHandle);
                if ($content) {
                    $replace = array ();
                    while (list ($key, $value) = each($this->config)) {
                        $replace['{' . $key . '}'] = "{$value}";
                    }
                    $content = str_replace(array_keys($replace), array_values($replace), $content);
                    if ($configHandle = @ fopen($configFile, 'wb')) {
                        $written = @ fwrite($configHandle, $content);
                        @ fclose($configHandle);
                    }
                }
            }
        }
        /* try to chmod the config file go-rwx (for suexeced php)
         * FIXME: need some way to configure the actual permissions to set
         */
        $chmodSuccess = @ chmod($configFile, 0600);
        if (!is_array($results)) {
            $results = array ();
        }
        if ($written) {
            $results[] = array (
                'class' => 'success',
                'msg' => '<p class="ok">'.$this->lexicon['config_file_written'].'</p>'
            );
        } else {
            $results[] = array (
                'class' => 'failed',
                'msg' => '<p class="notok">'.$this->lexicon['config_file_err_w'].'</p>'
            );
        }
        if ($chmodSuccess) {
            $results[] = array (
                'class' => 'success',
                'msg' => '<p class="ok">'.$this->lexicon['config_file_perms_set'].'</p>'
            );
        } else {
            $results[] = array (
                'class' => 'warning',
                'msg' => '<p>'.$this->lexicon['config_file_perms_notset'].'</p>'
            );
        }
        return $results;
    }

    /**
     * Installs a transport package.
     *
     * @param string The package signature.
     * @param array $attributes An array of installation attributes.
     * @return array An array of error messages collected during the process.
     */
    public function installPackage($pkg, array $attributes = array ()) {
        $errors = array ();

        /* instantiate the modX class */
        if (@ require_once (MODX_CORE_PATH . 'model/modx/modx.class.php')) {
            $modx = new modX(MODX_CORE_PATH . 'config/');
            if (!is_object($modx) || !($modx instanceof modX)) {
                $errors[] = '<p>'.$this->lexicon['modx_err_instantiate'].'</p>';
            } else {
                /* try to initialize the mgr context */
                $modx->initialize('mgr');
                if (!$modx->_initialized) {
                    $errors[] = '<p>'.$this->lexicon['modx_err_instantiate_mgr'].'</p>';
                } else {
                    $loaded = $modx->loadClass('transport.xPDOTransport', XPDO_CORE_PATH, true, true);
                    if (!$loaded)
                        $errors[] = '<p>'.$this->lexicon['transport_class_err_load'].'</p>';

                    $packageDirectory = MODX_CORE_PATH . 'packages/';
                    $packageState = (isset ($attributes[xPDOTransport::PACKAGE_STATE]) ? $attributes[xPDOTransport::PACKAGE_STATE] : xPDOTransport::STATE_PACKED);
                    $package = xPDOTransport :: retrieve($modx, $packageDirectory . $pkg . '.transport.zip', $packageDirectory, $packageState);
                    if ($package) {
                        if (!$package->install($attributes)) {
                            $errors[] = '<p>'.sprintf($this->lexicon['package_err_install'],$pkg).'</p>';
                        } else {
                            $modx->log(xPDO::LOG_LEVEL_INFO,sprintf($this->lexicon['package_installed'],$pkg));
                        }
                    } else {
                        $errors[] = '<p>'.sprintf($this->lexicon['package_err_nf'],$pkg).'</p>';
                    }
                }
            }
        } else {
            $errors[] = '<p>'.$this->lexicon['modx_class_err_nf'].'</p>';
        }

        return $errors;
    }

    /**
     * Gets the manager login URL.
     *
     * @return string The URL of the installed manager context.
     */
    public function getManagerLoginUrl() {
        $url = '';

        /* instantiate the modX class */
        if (@ require_once (MODX_CORE_PATH . 'model/modx/modx.class.php')) {
            $modx = new modX(MODX_CORE_PATH . 'config/');
            if (is_object($modx) && $modx instanceof modX) {
                /* try to initialize the mgr context */
                $modx->initialize('mgr');
                $url = $modx->getOption('manager_url');
            }
        }

        return $url;
    }

    /**
     * Determines the possible install modes.
     *
     * @access public
     * @return integer One of three possible mode indicators:<ul>
     * <li>0 = new install only</li>
     * <li>1 = new OR upgrade from older versions of MODx Revolution</li>
     * <li>2 = new OR upgrade from MODx Evolution</li>
     * </ul>
     */
    public function getInstallMode() {
        $mode = modInstall::MODE_NEW;
        if (isset ($_POST['installmode'])) {
            $mode = intval($_POST['installmode']);
        } else {
            if (file_exists(MODX_CORE_PATH . 'config/' . MODX_CONFIG_KEY . '.inc.php')) {
                /* Include the file so we can test its validity */
                $included = @ include (MODX_CORE_PATH . 'config/' . MODX_CONFIG_KEY . '.inc.php');
                $mode = ($included && isset ($dbase)) ? modInstall::MODE_UPGRADE_REVO : modInstall::MODE_NEW;
            }
            if (!$mode && file_exists(MODX_INSTALL_PATH . 'manager/includes/config.inc.php')) {
                $included = @ include (MODX_INSTALL_PATH . 'manager/includes/config.inc.php');
                $mode = ($included && isset ($dbase)) ? modInstall::MODE_UPGRADE_EVO : modInstall::MODE_NEW;
            }
        }
        return $mode;
    }

    /**
     * Creates the database connection for the installation process.
     *
     * @access private
     * @return xPDO The xPDO instance to be used by the installation.
     */
    private function _connect($dsn, $user = '', $password = '', $prefix = '') {
        if (include_once (MODX_CORE_PATH . 'xpdo/xpdo.class.php')) {
            $xpdo = new xPDO($dsn, $user, $password, array(
                    xPDO::OPT_CACHE_PATH => MODX_CORE_PATH . 'cache/',
                    xPDO::OPT_TABLE_PREFIX => $prefix,
                    xPDO::OPT_LOADER_CLASSES => array('modAccessibleObject'),
                    xPDO::OPT_SETUP => true,
                ),
                array (
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_WARNING,
                    PDO::ATTR_PERSISTENT => false,
                    PDO::MYSQL_ATTR_USE_BUFFERED_QUERY => true
                )
            );
            $xpdo->setDebug(E_ALL & ~E_STRICT);
            $xpdo->setLogTarget(array(
                'target' => 'FILE',
                'options' => array(
                    'filename' => 'install.' . MODX_CONFIG_KEY . '.' . strftime('%Y%m%dT%H%M%S') . '.log'
                )
            ));
            $xpdo->setLogLevel(xPDO::LOG_LEVEL_ERROR);
            return $xpdo;
        } else {
            return sprintf($this->lexicon['xpdo_err_nf'],MODX_CORE_PATH.'xpdo/xpdo.class.php');
        }
    }

    /**
     * Instantiate an existing modX configuration.
     *
     * @param array &$errors An array in which error messages are collected.
     * @return modX|null The modX instance, or null if it could not be instantiated.
     */
    private function _modx(array & $errors) {
        $modx = null;

        /* to validate installation, instantiate the modX class and run a few tests */
        if (include_once (MODX_CORE_PATH . 'model/modx/modx.class.php')) {
            $modx = new modX(MODX_CORE_PATH . 'config/',array(
                xPDO::OPT_SETUP => true,
            ));
            if (!is_object($modx) || !($modx instanceof modX)) {
                $errors[] = '<p>'.$this->lexicon['modx_err_instantiate'].'</p>';
            } else {
                $modx->setDebug(E_ALL & ~E_NOTICE);
                $modx->setLogTarget(array(
                    'target' => 'FILE',
                    'options' => array(
                        'filename' => 'install.' . MODX_CONFIG_KEY . '.' . strftime('%Y%m%dT%H%M%S') . '.log'
                    )
                ));

                /* try to initialize the mgr context */
                $modx->initialize('mgr');
                if (!$modx->isInitialized()) {
                    $errors[] = '<p>'.$this->lexicon['modx_err_instantiate_mgr'].'</p>';
                }
            }
        } else {
            $errors[] = '<p>'.$this->lexicon['modx_class_err_nf'].'</p>';
        }

        return $modx;
    }

    /**
     * Does all the pre-load checks, before setup loads.
     *
     * @access public
     */
    public function doPreloadChecks() {
        $this->loadLang('preload');
        $errors= array();

        if ((!extension_loaded('mysql') && !function_exists('mysql_connect')) && ((defined('XPDO_MODE') && XPDO_MODE == 2) || (!defined('XPDO_MODE') && version_compare(MODX_SETUP_PHP_VERSION, '5.1') < 0))) {
            $errors[] = $this->lexicon['preload_err_mysql'];
        }
        if (!extension_loaded('pdo') && ((defined('XPDO_MODE') && XPDO_MODE == 1) || (!defined('XPDO_MODE') && version_compare(MODX_SETUP_PHP_VERSION, '5.1') >= 0))) {
            $errors[] = $this->lexicon['preload_err_pdo'];
        }
        if (!extension_loaded('pdo_mysql') && ((defined('XPDO_MODE') && XPDO_MODE == 1) || (!defined('XPDO_MODE') && version_compare(MODX_SETUP_PHP_VERSION, '5.1') >= 0))) {
            $errors[] = $this->lexicon['preload_err_pdo_mysql'];
        }
        if (!file_exists(MODX_CORE_PATH) || !is_dir(MODX_CORE_PATH)) {
            $errors[] = $this->lexicon['preload_err_core_path'];
        }
        if (!file_exists(MODX_CORE_PATH . 'cache/') || !is_dir(MODX_CORE_PATH . 'cache/') || !is_writable(MODX_CORE_PATH . 'cache/')) {
            $errors[] = sprintf($this->lexicon['preload_err_cache'],MODX_CORE_PATH);
        }

        if (!empty($errors)) {
            $this->_fatalError($errors);
        }
    }

    /**
     * Outputs a fatal error message and then dies.
     *
     * @access private
     * @param string/array A string or array of errors
     */
    private function _fatalError($errors) {
        $output = '<html><head><title></title></head><body><h1>'.$this->lexicon['fatal_error'].'</h1><ul>';
        if (is_array($errors)) {
            foreach ($errors as $error) {
                $output .= '<li>'.$error.'</li>';
            }
        } else {
            $output .= '<li>'.$errors.'</li>';
        }
        $output .= '</ul></body></html>';
        die($output);
    }
}