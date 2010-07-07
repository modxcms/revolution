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

    public function set($k,$v) {
        $this->settings[$k] = $v;
    }
    public function get($k,$default = null) {
        return isset($this->settings[$k]) ? $this->settings[$k] : $default;
    }
    public function load() {
        if (file_exists($this->fileName)) {
            $this->settings = include $this->fileName;
            if (empty($this->settings)) {
                $this->erase();
                header('Location: ' . MODX_SETUP_URL);
                exit();
            }
        }
    }
    public function delete($k) {
        unset($this->settings[$k]);
    }
    public function store(array $settings = array(),$expire = 600) {
        $this->settings = array_merge($this->settings,$settings);
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