<?php
/**
 * Runs tests on the server to determine if MODX can be installed
 *
 * @package setup
 * @subpackage tests
 */
abstract class modInstallTest {
    public $results = array(
        'pass' => array(),
        'fail' => array(),
        'warn' => array(),
    );
    /** @var modInstall $install */
    public $install;
    public $mode;
    public $success = true;

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

        $this->_checkPHPVersion();
        $this->_checkDependencies();
        $this->_checkMemoryLimit();
        $this->_checkSessions();
        $this->_checkCache();
        $this->_checkExport();
        $this->_checkPackages();
        $this->_checkContexts();
        $this->_checkConfig();
        $this->_checkDatabase();
        $this->_checkSafeMode();
        $this->_checkSuhosin();
        $this->_checkDocumentRoot();

        return $this->results;
    }

    /**
     * Ensures safe_mode is off
     */
    protected function _checkSafeMode() {
        $this->title('safe_mode',$this->install->lexicon('test_safe_mode_start').' ');
        $safeMode = @ini_get('safe_mode');
        $safeMode = (intval($safeMode) == 1 || strtolower($safeMode) == 'on') && strtolower($safeMode) != 'off' ? true : false;
        if ($safeMode) {
            $this->fail('safe_mode','',$this->install->lexicon('test_safe_mode_fail'));
        } else {
            $this->pass('safe_mode');
        }
    }

    /**
     * Checks PHP version
     */
    protected function _checkPHPVersion() {
        $this->title('php_version',$this->install->lexicon('test_php_version_start').' ');
        $phpVersion = phpversion();
        $php_ver_comp = version_compare($phpVersion,'5.1.1','>=');
        $php_ver_comp_516 = version_compare($phpVersion, '5.1.6','==');
        $php_ver_comp_520 = strpos($phpVersion,'5.2.0') !== false;
        /* -1 if left is less, 0 if equal, +1 if left is higher */
        if (!$php_ver_comp) {
            $this->fail('php_version','',$this->install->lexicon('test_php_version_fail',array('version' => $phpVersion)));

        } else if ($php_ver_comp_520) {
            $this->warn('php_version','',$this->install->lexicon('test_php_version_520',array('version' => $phpVersion)));

        } else if ($php_ver_comp_516) {
            $this->warn('php_version','',$this->install->lexicon('test_php_version_516',array('version' => $phpVersion)));

        } else {
            $this->pass('php_version',$this->install->lexicon('test_php_version_success',array('version' => $phpVersion)));
        }
    }

    /**
     * Check memory limit, to make sure it is set at least to 32M
     */
    protected function _checkMemoryLimit() {
        $success = false;
        $ml = @ini_get('memory_limit');
        $bytes = $this->_returnBytes($ml);

        if ($bytes < 25165824) { /* 24M = 25165824, 32M = 33554432, 64M = 67108864 */
            $success = @ini_set('memory_limit','24M');
            $success = $success !== false ? true : false;
        } else {
            $success = true;
        }

        $this->title('memory_limit',$this->install->lexicon('test_memory_limit').' ');
        if ($success) {
            $this->pass('memory_limit',$this->install->lexicon('test_memory_limit_success',array('memory' => $ml)));
        } else {
            $this->fail('memory_limit','',$this->install->lexicon('test_memory_limit_fail',array('memory' => $ml)));
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
        if (is_integer($val)) return $val;
        $lval = strtolower(trim($val));
        if (strpos($lval,'m') === false && strpos($lval,'g') === false && strpos($lval,'k') === false && intval(trim($lval)) > 0) return intval($lval);
        $num = intval(substr($lval,0,strlen($lval)-1));
        $last = substr($lval,-1);
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
     * Check to see if PHP has zlib and SimpleXML installed.
     *
     * @access public
     */
    protected function _checkDependencies() {
        $this->title('dependencies',$this->install->lexicon('test_dependencies').' ');
        /* check for zlib */
        if (!extension_loaded('zlib')) {
            $this->fail('dependencies','',$this->install->lexicon('test_dependencies_fail_zlib'));
        } else {
            $this->pass('dependencies');
        }

        /* check for SimpleXML */
        $this->title('simplexml',$this->install->lexicon('test_simplexml').' ');
        if (!function_exists('simplexml_load_string')) {
            $this->warn('simplexml','',$this->install->lexicon('test_simplexml_nf_msg'),$this->install->lexicon('test_simplexml_nf'));
        } else {
            $this->pass('simplexml');
        }
    }


    /**
     * Check sessions
     */
    protected function _checkSessions() {
        if ($this->install->request instanceof modInstallCLIRequest) {
            $this->pass('sessions');
        } else {
            $this->title('sessions',$this->install->lexicon('test_sessions_start').' ');
            if (!empty($_SESSION) && $_SESSION['session_test'] != 1) {
                $this->fail('sessions');
            } else {
                $this->pass('sessions');
            }
        }
    }

    /**
     * Check if cache exists and is writable. Should in theory never fail.
     */
    protected function _checkCache() {
        /* cache exists? */
        $this->title('cache_exists',$this->install->lexicon('test_directory_exists',array('dir' => MODX_CORE_PATH . 'cache')));
        if (!file_exists(MODX_CORE_PATH . 'cache')) {
            $this->fail('cache_exists');
        } else {
            $this->pass('cache_exists');
        }

        /* cache writable? */
        $this->title('cache_writable',$this->install->lexicon('test_directory_writable',array('dir' => MODX_CORE_PATH . 'cache')));
        if (!$this->is_writable2(MODX_CORE_PATH . 'cache')) {
            $this->fail('cache_writable');
        } else {
            $this->pass('cache_writable');
        }
    }

    /**
     * Check if core/export exists and is writable
     */
    protected function _checkExport() {
        /* export exists? */
        $this->title('assets_export_exists',$this->install->lexicon('test_directory_exists',array('dir' => MODX_CORE_PATH . 'export')));
        if (!file_exists(MODX_CORE_PATH . 'export')) {
            $this->fail('assets_export_exists');
        } else {
            $this->pass('assets_export_exists');
        }

        /* export writable? */
        $this->title('assets_export_writable',$this->install->lexicon('test_directory_writable',array('dir' => MODX_CORE_PATH . 'export')));
        if (!$this->is_writable2(MODX_CORE_PATH . 'export')) {
            $this->fail('assets_export_writable');
        } else {
            $this->pass('assets_export_writable');
        }
    }

    /**
     * Verify if core/packages exists and is writable
     */
    protected function _checkPackages() {
        /* core/packages exists? */
        $this->title('core_packages_exists',$this->install->lexicon('test_directory_exists',array('dir' => MODX_CORE_PATH . 'packages')));
        if (!file_exists(MODX_CORE_PATH . 'packages')) {
            $this->fail('core_packages_exists');
        } else {
            $this->pass('core_packages_exists');
        }

        /* packages writable? */
        $this->title('core_packages_writable',$this->install->lexicon('test_directory_writable',array('dir' => MODX_CORE_PATH . 'packages')));
        if (!$this->is_writable2(MODX_CORE_PATH . 'packages')) {
            $this->fail('core_packages_writable');
        } else {
            $this->pass('core_packages_writable');
        }
    }

    /**
     * Check context paths if inplace, else make sure paths can be written
     */
    protected function _checkContexts() {
        $coreConfigsExist = false;
        if ($this->install->settings->get('inplace')) {
            /* web_path */
            $this->title('context_web_exists',$this->install->lexicon('test_directory_exists',array('dir' => $this->install->settings->get('context_web_path'))));
            if (!file_exists($this->install->settings->get('context_web_path'))) {
                $this->fail('context_web_exists');
            } else {
                $this->pass('context_web_exists');
            }

            /* mgr_path */
            $this->title('context_mgr_exists',$this->install->lexicon('test_directory_exists',array('dir' => $this->install->settings->get('context_mgr_path'))));
            if (!file_exists($this->install->settings->get('context_mgr_path'))) {
                $this->fail('context_mgr_exists');
            } else {
                $this->pass('context_mgr_exists');
            }

            /* connectors_path */
            $this->title('context_connectors_exists',$this->install->lexicon('test_directory_exists',array('dir' => $this->install->settings->get('context_connectors_path'))));
            if (!file_exists($this->install->settings->get('context_connectors_path'))) {
                $this->fail('context_connectors_exists');
            } else {
                $this->pass('context_connectors_exists');
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
        $this->title('config_writable',$this->install->lexicon('test_config_file',array('file' => $configFilePath)));
        if (!file_exists($configFilePath)) {
            /* make an attempt to create the file */
            @ $hnd = fopen($configFilePath, 'w');
            @ fwrite($hnd, '<?php // '.$this->install->lexicon('modx_configuration_file').' ?>');
            @ fclose($hnd);
        }
        $isWriteable = @is_writable($configFilePath);
        if (!$isWriteable) {
            $this->fail('config_writable',$this->install->lexicon('failed'),$this->install->lexicon('test_config_file_nw',array('key' => MODX_CONFIG_KEY)));
        } else {
            $this->pass('config_writable');
        }
    }

    /**
     * Check connection to database, as well as table prefix
     */
    protected function _checkDatabase() {
        /* connect to the database */
        $this->title('dbase_connection',$this->install->lexicon('test_db_check'));
        $GLOBALS['database_dsn'] = $this->install->settings->get('database_dsn');
        $GLOBALS['config_options'] = $this->install->settings->get('config_options');
        $xpdo = $this->install->getConnection();
        if (!$xpdo || !$xpdo->connect()) {
            if ($this->mode > modInstall::MODE_NEW) {
                $this->fail($this->install->lexicon('dbase_connection'),$this->install->lexicon('test_db_failed'),$this->install->lexicon('test_db_check_conn'));
            } else {
                $this->warn($this->install->lexicon('dbase_connection'),$this->install->lexicon('test_db_failed'),$this->install->lexicon('test_db_setup_create'));
            }
        } else {
            $this->pass('dbase_connection');
        }
    }

    /**
     * Check for suhosin extension, and if found and suhosin.get.max_value_length is less than 1024, throw a warning
     * and force compress_js off.
     */
    public function _checkSuhosin() {
        $this->title('test_suhosin_max_length',$this->install->lexicon('test_suhosin'));
        $maxLength = (int)@ini_get('suhosin.get.max_value_length');
        if ($maxLength != 0 && $maxLength !== null && $maxLength > 0 && $maxLength < 1024) {
            $this->warn($this->install->lexicon('test_suhosin_max_length'),$this->install->lexicon('warning'),$this->install->lexicon('test_suhosin_max_length_err'));
            $this->install->settings->set('compress_js',0);
        } else {
            $this->pass('test_suhosin_max_length');
            $this->install->settings->set('compress_js',1);
        }
        $this->install->settings->store();
    }

    /**
     * If the server document root is empty, don't do JS/CSS compression until they set it up manually.
     * @return void
     */
    public function _checkDocumentRoot() {
        $documentRoot = $_SERVER['DOCUMENT_ROOT'];
        if (empty($documentRoot)) {
            $this->install->settings->set('compress_js',0);
            $this->install->settings->set('compress_css',0);
        }
        $this->install->settings->store();
    }


    /**
     * Custom is_writable function to test on problematic servers
     *
     * @param string $path
     * @return boolean True if write was successful
     */
    protected function is_writable2($path) {
        return $this->install->is_writable2($path);
    }

    /**
     * Titles the check message
     *
     * @param string $key The check being titled
     * @param string $title The title of the check
     */
    protected function title($key,$title) {
        if (!isset($this->results[$key])) $this->results[$key] = array();
        $this->results[$key]['msg'] = '<p>'.$title;
    }

    /**
     * Denotes a check passed successfully
     *
     * @param string $key The check being performed
     * @param string $message The success message.
     */
    protected function pass($key,$message = '') {
        if (empty($message)) $message = $this->install->lexicon('ok');

        if (!isset($this->results[$key])) $this->results[$key] = array();
        if (!isset($this->results[$key]['msg'])) $this->results[$key]['msg'] = '';
        $this->results[$key]['msg'] .= '<span class="ok">'.$message.'</span></p>';
        $this->results[$key]['class'] = 'testPassed';

        if (!isset($this->results['pass'][$key])) $this->results['pass'][$key] = array();
        if (!isset($this->results['pass'][$key]['msg'])) $this->results['pass'][$key]['msg'] = '';
        $this->results['pass'][$key]['msg'] .= '<span class="ok">'.$message.'</span></p>';
        $this->results['pass'][$key]['class'] = 'testPassed';
        $this->results['pass'][$key]['message'] = $message;
    }

    /**
     * Denotes a check message that is a warning
     *
     * @param string $key The check being performed
     * @param string $title The warning message title.
     * @param string $message A detailed warning message.
     * @param string $messageTitle An optional title for the detail panel.
     * @return boolean
     */
    protected function warn($key,$title,$message = '',$messageTitle = '') {
        if (empty($title)) $title = $this->install->lexicon('warning');
        $msg = '<span class="notok">'.$title.'</span></p>';
        if (!empty($message)) {
            $msg .= '<div class="notes">';
            if (!empty($messageTitle)) $msg .= '<h3>'.$messageTitle.'</h3>';
            $msg .= '<p>'.$message.'</p></div>';
        }
        if (!isset($this->results[$key])) $this->results[$key] = array();
        if (!isset($this->results[$key]['msg'])) $this->results[$key]['msg'] = '';

        $this->results[$key]['msg'] .= $msg;
        $this->results[$key]['class'] = 'testWarn';

        if (!isset($this->results['warn'][$key])) $this->results['warn'][$key] = array();
        if (!isset($this->results['warn'][$key]['msg'])) $this->results['warn'][$key]['msg'] = '';
        $this->results['warn'][$key]['msg'] .= $msg;
        $this->results['warn'][$key]['class'] = 'testPassed';
        $this->results['warn'][$key]['title'] = $title;
        $this->results['warn'][$key]['message'] = $message;
        $this->results['warn'][$key]['messageTitle'] = $messageTitle;
        return true;
    }

    /**
     * Denotes a check message that is a failure
     *
     * @param string $key The check being performed
     * @param string $title The failure message title.
     * @param string $message A detailed failure message.
     * @return boolean
     */
    protected function fail($key,$title = '',$message = '') {
        if (empty($title)) $title = $this->install->lexicon('failed');
        $msg = '<span class="notok">'.$title.'</span></p>';
        if (!empty($message)) {
            $msg .= '<p><strong>'.$message.'</strong></p>';
        }
        if (!isset($this->results[$key])) $this->results[$key] = array();
        if (!isset($this->results[$key]['msg'])) $this->results[$key]['msg'] = '';
        $this->results[$key]['msg'] .= $msg;
        $this->results[$key]['class'] = 'testFailed';


        if (!isset($this->results['fail'][$key])) $this->results['fail'][$key] = array();
        if (!isset($this->results['fail'][$key]['msg'])) $this->results['fail'][$key]['msg'] = '';
        $this->results['fail'][$key]['msg'] .= $msg;
        $this->results['fail'][$key]['class'] = 'testFailed';
        $this->results['fail'][$key]['title'] = $title;
        $this->results['fail'][$key]['message'] = $message;
        $this->success = false;
        return true;
    }
}