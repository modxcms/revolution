<?php
/**
 * @package setup
 */
class modInstallSettings {
    public $install = null;
    public $config = array();
    public $fileName = '';
    public $settings = array();

    function __construct(modInstall &$install,array $config = array()) {
        $this->install =& $install;
        $this->config = array_merge(array(),$config);

        $this->fileName = $this->getCachePath().'settings.cache.php';
        $this->load();
    }

    protected function getCachePath() {
        return MODX_CORE_PATH . 'cache/' . (MODX_CONFIG_KEY == 'config' ? '' : MODX_CONFIG_KEY . '/') . 'setup/';
    }

    protected function rebuildDSN() {
        if (array_key_exists('database_type', $this->settings)) {
            switch ($this->settings['database_type']) {
                case 'sqlsrv':
                    $database_dsn = "{$this->settings['database_type']}:server={$this->settings['database_server']};database={$this->settings['dbase']}";
                    $server_dsn = "{$this->settings['database_type']}:server={$this->settings['database_server']}";
                    break;
                case 'mysql':
                    $database_dsn = "{$this->settings['database_type']}:host={$this->settings['database_server']};dbname={$this->settings['dbase']};charset={$this->settings['database_connection_charset']}";
                    $server_dsn = "{$this->settings['database_type']}:host={$this->settings['database_server']};charset={$this->settings['database_connection_charset']}";
                    break;
                default:
                    $database_dsn = '';
                    $server_dsn = '';
                    break;
            }
            $this->settings['database_dsn'] = $database_dsn;
            $this->settings['server_dsn'] = $server_dsn;
        }
    }

    public function set($k,$v) {
        $this->settings[$k] = $v;
        if (in_array($k, array('database_type', 'database_server', 'dbase', 'database_connection_charset'))) {
            $this->rebuildDSN();
        }
    }
    public function get($k,$default = null) {
        if (in_array($k, array('database_dsn', 'server_dsn'))) {
            $this->rebuildDSN();
        }
        return isset($this->settings[$k]) ? $this->settings[$k] : $default;
    }
    public function load() {
        if (file_exists($this->fileName)) {
            $this->settings = include $this->fileName;
            if (empty($this->settings)) {
                $this->restart();
            }
        }
    }
    public function check() {
        $this->load();
        if (empty($this->settings)) {
            $this->restart();
        }
    }
    public function restart() {
        $this->erase();
        header('Location: ' . MODX_SETUP_URL.'?restarted=1');
        exit();
    }
    public function delete($k) {
        unset($this->settings[$k]);
    }
    public function store(array $settings = array(),$expire = 900) {
        $this->settings = array_merge($this->settings,$settings);
        $this->rebuildDSN();
        $written = false;

        if ($file= @ fopen($this->fileName,'wb')) {
            $expirationTS= $expire ? time() + $expire : time();
            $expireContent= 'if(time() > ' . $expirationTS . '){return array();}';
            $content = '<?php ' . $expireContent . ' return ' . var_export($this->settings, true) . ';';

            $written= @ fwrite($file, $content);
            @ fclose($file);
        }
        return $written;
    }

    public function erase() {
        if (file_exists($this->fileName)) {
            @unlink($this->fileName);
        }
    }

    public function fetch() {
        return $this->settings;
    }
}