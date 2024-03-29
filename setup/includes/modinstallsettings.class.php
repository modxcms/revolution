<?php
/*
 * This file is part of MODX Revolution.
 *
 * Copyright (c) MODX, LLC. All Rights Reserved.
 *
 * For complete copyright and license information, see the COPYRIGHT and LICENSE
 * files found in the top-level directory of this distribution.
 */

/**
 * @package setup
 */
class modInstallSettings
{
    public $install = null;
    public $config = [];
    public $fileName = '';
    public $settings = [];

    public function __construct(modInstall &$install, array $config = [])
    {
        $this->install =& $install;
        $this->config = array_merge([], $config);

        $this->fileName = $this->getCachePath() . 'settings.cache.php';
        $this->load();
    }

    /**
     * @return string
     */
    protected function getCachePath()
    {
        return MODX_CORE_PATH . 'cache/' . (MODX_CONFIG_KEY == 'config' ? '' : MODX_CONFIG_KEY . '/') . 'setup/';
    }

    /**
     * @return void
     */
    public function load()
    {
        if (file_exists($this->fileName)) {
            $this->settings = include $this->fileName;
            if (empty($this->settings)) {
                $this->restart();
            }
        }
    }

    /**
     * @return void
     */
    public function restart()
    {
        $this->erase();
        if (empty($this->install->request) && !($this->install->request instanceof modInstallCLIRequest)) {
            header('Location: ' . MODX_SETUP_URL . '?restarted=1');
            exit();
        }
    }

    /**
     * @return void
     */
    public function erase()
    {
        if (file_exists($this->fileName)) {
            @unlink($this->fileName);
        }
    }

    /**
     * @param string $k
     * @param mixed $v
     * @return void
     */
    public function set($k, $v)
    {
        $this->settings[$k] = $v;
        if (in_array($k, ['database_type', 'database_server', 'dbase', 'database_connection_charset'])) {
            $this->rebuildDSN();
        }
    }

    /**
     * @return void
     */
    protected function rebuildDSN()
    {
        if (array_key_exists('database_type', $this->settings)) {
            switch ($this->settings['database_type']) {
                case 'mysql':
                    $server = explode(':', $this->settings['database_server']);
                    $port = !empty($server[1]) ? "port={$server[1]};" : '';
                    $database_dsn = "{$this->settings['database_type']}:host={$server[0]};{$port}dbname={$this->settings['dbase']};charset={$this->settings['database_connection_charset']}";
                    $server_dsn = "{$this->settings['database_type']}:host={$server[0]};{$port}charset={$this->settings['database_connection_charset']}";
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

    /**
     * @param string $k
     * @param mixed $default
     * @return mixed
     */
    public function get($k, $default = null)
    {
        if (in_array($k, ['database_dsn', 'server_dsn'])) {
            $this->rebuildDSN();
        }
        return isset($this->settings[$k]) ? $this->settings[$k] : $default;
    }

    /**
     * @param array $array
     * @return void
     */
    public function fromArray($array)
    {
        foreach ($array as $k => $v) {
            $this->settings[$k] = $v;
        }
    }

    /**
     * @return void
     */
    public function check()
    {
        $this->load();
        if (empty($this->settings)) {
            $this->restart();
        }
    }

    /**
     * @param string $k
     * @return void
     */
    public function delete($k)
    {
        unset($this->settings[$k]);
    }

    /**
     * @param array $settings
     * @param int $expire
     * @return bool|int
     */
    public function store(array $settings = [], $expire = 900)
    {
        $this->settings = array_merge($this->settings, $settings);
        $this->rebuildDSN();
        $written = false;

        if ($file = @ fopen($this->fileName, 'wb')) {
            $expirationTS = $expire ? time() + $expire : time();
            $expireContent = 'if(time() > ' . $expirationTS . '){return array();}';
            $content = '<?php ' . $expireContent . ' return ' . var_export($this->settings, true) . ';';

            $written = @ fwrite($file, $content);
            @ fclose($file);
        }
        return $written;
    }

    /**
     * @return array
     */
    public function fetch()
    {
        ksort($this->settings);
        return $this->settings;
    }
}
