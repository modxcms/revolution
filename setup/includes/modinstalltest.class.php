<?php
/**
 * Runs tests on the server to determine if MODx can be installed
 *
 * @package setup
 * @subpackage tests
 */
abstract class modInstallTest {
    public $results = array();
    public $mode;

    function __construct(&$install) {
        $this->install =& $install;
    }

    /**
     * Run tests.
     *
     * @param integer $mode The install mode.
     * @return array An array of result messages collected during the process.
     */
    public function run($mode = modInstall::MODE_NEW) {
        $this->results = array();
        $this->mode = $mode;

        $this->_checkDependencies();
        $this->_checkPHPVersion();
        $this->_checkMySQLServerVersion();
        $this->_checkMySQLClientVersion();
        $this->_checkMemoryLimit();
        $this->_checkSessions();
        $this->_checkCache();
        $this->_checkExport();
        $this->_checkPackages();
        $this->_checkContexts();
        $this->_checkConfig();
        $this->_checkDatabase();

        return $this->results;
    }

    /**
     * Checks PHP version
     */
    protected function _checkPHPVersion() {
        $this->results['php_version']['msg'] = '<p>'.$this->install->lexicon['test_php_version_start'].' ';
        $phpVersion = phpversion();
        $php_ver_comp = version_compare($phpVersion,'5.1.1','>=');
        $php_ver_comp_516 = version_compare($phpVersion, '5.1.6','==');
        /* -1 if left is less, 0 if equal, +1 if left is higher */
        if (!$php_ver_comp) {
            $this->results['php_version']['msg'] .= '<span class="notok">'.$this->install->lexicon['failed'].'</span> - '.sprintf($this->install->lexicon['test_php_version_fail'],$phpVersion).'</p>';
            $this->results['php_version']['class'] = 'testFailed';

        } else if ($php_ver_comp_516) {
            $this->results['php_version']['msg'] .= '<span class="notok">'.$this->install->lexicon['failed'].'</span><p>'.sprintf($this->install->lexicon['test_php_version_516'],$phpVersion).'</p>';
            $this->results['php_version']['class'] = 'testFailed';

        } else {
            $this->results['php_version']['class'] = 'testPassed';
            $this->results['php_version']['msg'] .= '<span class="ok">'.sprintf($this->install->lexicon['test_php_version_success'],$phpVersion).'</span></p>';
        }
    }

    private function _sanitizeMySqlVersion($mysqlVersion) {
        $mysqlVersion = str_replace(array(
            'mysqlnd ',
            '-dev',
            ' ',
        ),'',$mysqlVersion);
        return $mysqlVersion;
    }

    /**
     * Checks MySQL server version
     */
    protected function _checkMySQLServerVersion() {
        $this->results['mysql_server_version']['msg'] = '<p>'.$this->install->lexicon['test_mysql_version_server_start'].' ';
        $mysqlVersion = mysql_get_server_info();
        $mysqlVersion = $this->_sanitizeMySqlVersion($mysqlVersion);
        if (empty($mysqlVersion)) {
            $this->results['mysql_server_version']['msg'] = '<span class="ok">'.$this->install->lexicon['ok'].'</span></p>';
            $this->results['mysql_server_version']['msg'] .= '<div class="notes"><h3>'.$this->install->lexicon['test_mysql_version_server_nf'].'</h3><p>'.$this->install->lexicon['test_mysql_version_server_nf_msg'].'</p></div>';
            $this->results['mysql_server_version']['class'] = 'testWarn';
            return true;
        }

        $mysql_ver_comp = version_compare($mysqlVersion,'4.1.20','>=');
        $mysql_ver_comp_5051 = version_compare($mysqlVersion,'5.0.51','==');
        $mysql_ver_comp_5051a = version_compare($mysqlVersion,'5.0.51a','==');

        if (!$mysql_ver_comp) {
            $this->results['mysql_server_version']['msg'] .= '<span class="notok">'.$this->install->lexicon['failed'].'</span> - '.sprintf($this->install->lexicon['test_mysql_version_fail'],$mysqlVersion).'</p>';
            $this->results['mysql_server_version']['class'] = 'testFailed';

        } else if ($mysql_ver_comp_5051 || $mysql_ver_comp_5051a) {
            $this->results['mysql_server_version']['msg'] .= '<span class="notok">'.$this->install->lexicon['failed'].'</span><p>'.sprintf($this->install->lexicon['test_mysql_version_5051'],$mysqlVersion).'</p>';
            $this->results['mysql_server_version']['class'] = 'testFailed';

        } else {
            $this->results['mysql_server_version']['class'] = 'testPassed';
            $this->results['mysql_server_version']['msg'] .= '<span class="ok">'.sprintf($this->install->lexicon['test_mysql_version_success'],$mysqlVersion).'</span></p>';
        }
    }
    /**
     * Checks MySQL client version
     */
    protected function _checkMySQLClientVersion() {
        $this->results['mysql_client_version']['msg'] = '<p>'.$this->install->lexicon['test_mysql_version_client_start'].' ';
        $mysqlVersion = mysql_get_client_info();
        $mysqlVersion = $this->_sanitizeMySqlVersion($mysqlVersion);
        if (empty($mysqlVersion)) {
            $this->results['mysql_client_version']['msg'] = '<span class="ok">'.$this->install->lexicon['ok'].'</span></p>';
            $this->results['mysql_client_version']['msg'] .= '<div class="notes"><h3>'.$this->install->lexicon['test_mysql_version_client_nf'].'</h3><p>'.$this->install->lexicon['test_mysql_version_client_nf_msg'].'</p></div>';
            $this->results['mysql_client_version']['class'] = 'testWarn';
            return true;
        }

        $mysql_ver_comp = version_compare($mysqlVersion,'4.1.20','>=');
        $mysql_ver_comp_5051 = version_compare($mysqlVersion,'5.0.51','==');
        $mysql_ver_comp_5051a = version_compare($mysqlVersion,'5.0.51a','==');

        if (!$mysql_ver_comp) {
            $this->results['mysql_client_version']['msg'] .= '<span class="notok">'.$this->install->lexicon['failed'].'</span> - '.sprintf($this->install->lexicon['test_mysql_version_fail'],$mysqlVersion).'</p>';
            $this->results['mysql_client_version']['class'] = 'testFailed';

        } else if ($mysql_ver_comp_5051 || $mysql_ver_comp_5051a) {
            $this->results['mysql_client_version']['msg'] .= '<span class="notok">'.$this->install->lexicon['failed'].'</span><p>'.sprintf($this->install->lexicon['test_mysql_version_5051'],$mysqlVersion).'</p>';
            $this->results['mysql_client_version']['class'] = 'testFailed';

        } else {
            $this->results['mysql_client_version']['class'] = 'testPassed';
            $this->results['mysql_client_version']['msg'] .= '<span class="ok">'.sprintf($this->install->lexicon['test_mysql_version_success'],$mysqlVersion).'</span></p>';
        }
    }

    /**
     * Check memory limit, to make sure it is set at least to 32M
     */
    protected function _checkMemoryLimit() {
        $success = false;
        $ml = ini_get('memory_limit');
        $bytes = $this->_returnBytes($ml);

        if ($bytes < 25165824) { /* 24M = 25165824, 32M = 33554432, 64M = 67108864 */
            $success = @ini_set('memory_limit','24M');
            $success = $success !== false ? true : false;
        } else {
            $success = true;
        }

        $this->results['memory_limit']['msg'] = '<p>'.$this->install->lexicon['test_memory_limit'].' ';
        if ($success) {
            $this->results['memory_limit']['msg'] .= '<span class="ok">'.sprintf($this->install->lexicon['test_memory_limit_success'],$ml).'</span></p>';
            $this->results['memory_limit']['class'] = 'testPassed';
        } else {
            $s = '<span class="notok">'.$this->install->lexicon['failed'].'</span>';
            $s .= '<div class="notes"><p>'.sprintf($this->install->lexicon['test_memory_limit_fail'],$ml).'</p></div>';
            $s .= '</p>';
            $this->results['memory_limit']['msg'] .= $s;
            $this->results['memory_limit']['class'] = 'testFailed';
        }
    }

    /**
     * Helper function that converts php.ini memory string settings to bytes
     *
     * @access private
     * @param string $val The byte string
     * @return integer The string converted into a proper integer bytes
     */
    protected function _returnBytes($val) {
        $val = trim($val);
        $num = intval(substr($val,0,strlen($val)-1));
        $last = strtolower(substr($val,-1));
        switch ($last) {
            case 'g':
                $num *= 1024;
            case 'm':
                $num *= 1024;
            case 'k':
                $num *= 1024;
        }
        return $num;
    }

    /**
     * Check to see if PHP has zlib installed.
     *
     * @access public
     */
    protected function _checkDependencies() {
        $this->results['dependencies']['msg'] = '<p>'.$this->install->lexicon['test_dependencies'].' ';
        if (!extension_loaded('zlib')) {
            $s = '<span class="notok">'.$this->install->lexicon['failed'].'</span>';
            $s .= '<div class="notes"><p>'.$this->install->lexicon['test_dependencies_fail_zlib'].'</p></div>';
            $s .= '</p>';
            $this->results['dependencies']['msg'] = $s;
            $this->results['dependencies']['class'] = 'testFailed';
        } else {
            $this->results['dependencies']['msg'] .= '<span class="ok">'.$this->install->lexicon['ok'].'</span></p>';
            $this->results['dependencies']['class'] = 'testPassed';
        }
    }


    /**
     * Check sessions
     */
    protected function _checkSessions() {
        $this->results['sessions']['msg'] = '<p>'.$this->install->lexicon['test_sessions_start'].' ';
        if ($_SESSION['session_test'] != 1) {
            $this->results['sessions']['msg'] .= '<span class="notok">'.$this->install->lexicon['failed'].'</span></p>';
            $this->results['sessions']['class'] = 'testFailed';
        } else {
            $this->results['sessions']['msg'] .= '<span class="ok">'.$this->install->lexicon['ok'].'</span></p>';
            $this->results['sessions']['class'] = 'testPassed';
        }

    }

    /**
     * Check if cache exists and is writable
     */
    protected function _checkCache() {
        /* cache exists? */
        $this->results['cache_exists']['msg'] = '<p>'.sprintf($this->install->lexicon['test_directory_exists'],'core/cache');
        if (!file_exists(MODX_CORE_PATH . 'cache')) {
            $this->results['cache_exists']['msg'] .= '<span class="notok">'.$this->install->lexicon['failed'].'</span></p>';
            $this->results['cache_exists']['class'] = 'testFailed';
        } else {
            $this->results['cache_exists']['msg'] .= '<span class="ok">'.$this->install->lexicon['ok'].'</span></p>';
            $this->results['cache_exists']['class'] = 'testPassed';
        }

        /* cache writable? */
        $this->results['cache_writable']['msg'] = '<p>'.sprintf($this->install->lexicon['test_directory_writable'],'core/cache');
        if (!is_writable(MODX_CORE_PATH . 'cache')) {
            $this->results['cache_writable']['msg'] .= '<span class="notok">'.$this->install->lexicon['failed'].'</span></p>';
            $this->results['cache_writable']['class'] = 'testFailed';
        } else {
            $this->results['cache_writable']['msg'] .= '<span class="ok">'.$this->install->lexicon['ok'].'</span></p>';
            $this->results['cache_writable']['class'] = 'testPassed';
        }
    }

    /**
     * Check if core/export exists and is writable
     */
    protected function _checkExport() {
        /* export exists? */
        $this->results['assets_export_exists']['msg'] = '<p>'.sprintf($this->install->lexicon['test_directory_exists'],'core/export');
        if (!file_exists(MODX_CORE_PATH . 'export')) {
            $this->results['assets_export_exists']['msg'] .= '<span class="notok">'.$this->install->lexicon['failed'].'</span></p>';
            $this->results['assets_export_exists']['class'] = 'testFailed';
        } else {
            $this->results['assets_export_exists']['msg'] .= '<span class="ok">'.$this->install->lexicon['ok'].'</span></p>';
            $this->results['assets_export_exists']['class'] = 'testPassed';
        }

        /* export writable? */
        $this->results['assets_export_writable']['msg'] = '<p>'.sprintf($this->install->lexicon['test_directory_writable'],'core/export');
        if (!is_writable(MODX_CORE_PATH . 'export')) {
            $this->results['assets_export_writable']['msg'] .= '<span class="notok">'.$this->install->lexicon['failed'].'</span></p>';
            $this->results['assets_export_writable']['class'] = 'testFailed';
        } else {
            $this->results['assets_export_writable']['msg'] .= '<span class="ok">'.$this->install->lexicon['ok'].'</span></p>';
            $this->results['assets_export_writable']['class'] = 'testPassed';
        }
    }

    /**
     * Verify if core/packages exists and is writable
     */
    protected function _checkPackages() {
        /* core/packages exists? */
        $this->results['core_packages_exists']['msg'] = '<p>'.sprintf($this->install->lexicon['test_directory_exists'],'core/packages');
        if (!file_exists(MODX_CORE_PATH . 'packages')) {
            $this->results['core_packages_exists']['msg'] .= '<span class="notok">'.$this->install->lexicon['failed'].'</span></p>';
            $this->results['core_packages_exists']['class'] = 'testFailed';
        } else {
            $this->results['core_packages_exists']['msg'] .= '<span class="ok">'.$this->install->lexicon['ok'].'</span></p>';
            $this->results['core_packages_exists']['class'] = 'testPassed';
        }

        /* packages writable? */
        $this->results['core_packages_writable']['msg'] = '<p>'.sprintf($this->install->lexicon['test_directory_writable'],'core/packages');
        if (!is_writable(MODX_CORE_PATH . 'packages')) {
            $this->results['core_packages_writable']['msg'] .= '<span class="notok">'.$this->install->lexicon['failed'].'</span></p>';
            $this->results['core_packages_writable']['class'] = 'testFailed';
        } else {
            $this->results['core_packages_writable']['msg'] .= '<span class="ok">'.$this->install->lexicon['ok'].'</span></p>';
            $this->results['core_packages_writable']['class'] = 'testPassed';
        }
    }

    /**
     * Check context paths if inplace, else make sure paths can be written
     */
    protected function _checkContexts() {
        $coreConfigsExist = false;
        if ($this->install->settings->get('inplace')) {
            /* web_path */
            $this->results['context_web_exists']['msg'] = '<p>'.sprintf($this->install->lexicon['test_directory_exists'],$this->install->settings->get('context_web_path'));
            if (!file_exists($this->install->settings->get('context_web_path'))) {
                $this->results['context_web_exists']['msg'] .= '<span class="notok">'.$this->install->lexicon['failed'].'</span></p>';
                $this->results['context_web_exists']['class'] = 'testFailed';
            } else {
                $this->results['context_web_exists']['msg'] .= '<span class="ok">'.$this->install->lexicon['ok'].'</span></p>';
                $this->results['context_web_exists']['class'] = 'testPassed';
            }

            /* mgr_path */
            $this->results['context_mgr_exists']['msg'] = '<p>'.sprintf($this->install->lexicon['test_directory_exists'],$this->install->settings->get('context_mgr_path'));
            if (!file_exists($this->install->settings->get('context_mgr_path'))) {
                $this->results['context_mgr_exists']['msg'] .= '<span class="notok">'.$this->install->lexicon['failed'].'</span></p>';
                $this->results['context_mgr_exists']['class'] = 'testFailed';
            } else {
                $this->results['context_mgr_exists']['msg'] .= '<span class="ok">'.$this->install->lexicon['ok'].'</span></p>';
                $this->results['context_mgr_exists']['class'] = 'testPassed';
            }

            /* connectors_path */
            $this->results['context_connectors_exists']['msg'] = '<p>'.sprintf($this->install->lexicon['test_directory_exists'],$this->install->settings->get('context_connectors_path'));
            if (!file_exists($this->install->settings->get('context_connectors_path'))) {
                $this->results['context_connectors_exists']['msg'] .= '<span class="notok">'.$this->install->lexicon['failed'].'</span></p>';
                $this->results['context_connectors_exists']['class'] = 'testFailed';
            } else {
                $this->results['context_connectors_exists']['msg'] .= '<span class="ok">'.$this->install->lexicon['ok'].'</span></p>';
                $this->results['context_connectors_exists']['class'] = 'testPassed';
            }
            if (file_exists($this->install->settings->get('context_web_path') . 'config.core.php') &&
                file_exists($this->install->settings->get('context_connectors_path') . 'config.core.php') &&
                file_exists($this->install->settings->get('context_mgr_path') . 'config.core.php')) {
                $coreConfigsExist = true;
            }
        }
    }

    /**
     * Config file writable?
     */
    protected function _checkConfig() {
        $configFileDisplay= 'config/' . MODX_CONFIG_KEY . '.inc.php';
        $configFilePath= MODX_CORE_PATH . $configFileDisplay;
        $this->results['config_writable']['msg'] = '<p>'.sprintf($this->install->lexicon['test_config_file'],'core/' . $configFileDisplay);
        if (!file_exists($configFilePath)) {
            /* make an attempt to create the file */
            @ $hnd = fopen($configFilePath, 'w');
            @ fwrite($hnd, '<?php // '.$this->install->lexicon['modx_configuration_file'].' ?>');
            @ fclose($hnd);
        }
        $isWriteable = is_writable($configFilePath);
        if (!$isWriteable) {
            $this->results['config_writable']['msg'] .= '<span class="notok">'.$this->install->lexicon['failed'].'</span></p><p><strong>'.sprintf($this->install->lexicon['test_config_file_nw'],MODX_CONFIG_KEY).'</strong></p>';
            $this->results['config_writable']['class'] = 'testFailed';
        } else {
            $this->results['config_writable']['msg'] .= '<span class="ok">'.$this->install->lexicon['ok'].'</span></p>';
            $this->results['config_writable']['class'] = 'testPassed';
        }
    }

    /**
     * Check connection to database, as well as table prefix
     */
    protected function _checkDatabase() {
        /* connect to the database */
        $this->results['dbase_connection']['msg'] = '<p>'.$this->install->lexicon['test_db_check'];
        $xpdo = $this->install->getConnection();
        if (!$xpdo || !$xpdo->connect()) {
            if ($this->mode > modInstall::MODE_NEW) {
                $this->results['dbase_connection']['msg'] .= '<span class="notok">'.$this->install->lexicon['test_db_failed'].'</span><p />'.$this->install->lexicon['test_db_check_conn'].'</p>';
                $this->results['dbase_connection']['class'] = 'testFailed';
            } else {
                $this->results['dbase_connection']['msg'] .= '<span class="notok">'.$this->install->lexicon['test_db_failed'].'</span><p />'.$this->install->lexicon['test_db_setup_create'].'</p>';
                $this->results['dbase_connection']['class'] = 'testWarn';
            }
        } else {
            $this->results['dbase_connection']['msg'] .= '<span class="ok">'.$this->install->lexicon['ok'].'</span></p>';
            $this->results['dbase_connection']['class'] = 'testPassed';
        }
        /* check the database collation if not specified in the configuration */
        /*
        if (!isset ($database_connection_charset) || empty ($database_connection_charset)) {
            if (!$rs = @ mysql_query("show session variables like
'collation_database'")) {
                $rs = @ mysql_query("show session variables like
'collation_server'");
            }
            if ($rs && $collation = mysql_fetch_row($rs)) {
                $database_collation = $collation[1];
            }
            if (empty ($database_collation)) {
                $database_collation = 'utf8_unicode_ci';
            }
            $database_charset = substr($database_collation, 0, strpos
($database_collation, '_') - 1);
            $database_connection_charset = $database_charset;
        }
        */

        /* check table prefix */
        if ($xpdo && $xpdo->connect()) {
            $this->results['table_prefix']['msg'] = '<p>'.sprintf($this->install->lexicon['test_table_prefix'],$this->install->settings->get('table_prefix'));
            $count = 0;
            if ($stmt = $xpdo->query('SELECT COUNT(*) FROM `' . $this->install->settings->get('table_prefix') . 'system_settings`')) {
                $count = $stmt->fetchColumn();
                $stmt->closeCursor();
            }
            if ($this->mode == modInstall::MODE_NEW) { /* if new install */
                if ($count > 0) {
                    $this->results['table_prefix']['msg'] .= '<span class="notok">'.$this->install->lexicon['failed'].'</span></b> - '.$this->install->lexicon['test_table_prefix_inuse'].'</p>';
                    $this->results['table_prefix']['class'] = 'testFailed';
                    $this->results['table_prefix']['msg'] .= '<p>'.$this->install->lexicon['test_table_prefix_inuse_desc'].'</p>';
                } else {
                    $this->results['table_prefix']['msg'] .= '<span class="ok">'.$this->install->lexicon['ok'].'</span></p>';
                    $this->results['table_prefix']['class'] = 'testPassed';
                }
            } else { /* if upgrade */
                if ($count < 1) {
                    $this->results['table_prefix']['msg'] .= '<span class="notok">'.$this->install->lexicon['failed'].'</span></b> - '.$this->install->lexicon['test_table_prefix_nf'].'</p>';
                    $this->results['table_prefix']['class'] = 'testFailed';
                    $this->results['table_prefix']['msg'] .= '<p>'.$this->install->lexicon['test_table_prefix_nf_desc'].'</p>';
                } else {
                    $this->results['table_prefix']['msg'] .= '<span class="ok">'.$this->install->lexicon['ok'].'</span></p>';
                    $this->results['table_prefix']['class'] = 'testPassed';
                }
            }
        }
    }


    /**
     * Checks to see if a given path is in a writable container.
     *
     * @access protected
     * @param string $path The file path to test.
     * @return boolean Returns true if the path is in a writable container.
     */
    protected function _inWritableContainer($path) {
        $writable = false;
        if (file_exists($path) && is_dir($path))
            $writable = is_writable($path);
        while (!file_exists($path)) {
            $path = dirname($path);
            if (!$path)
                break;
            if (!file_exists($path))
                break;
            $writable = is_writable($path);
        }
        return $writable;
    }
}