<?php
/**
 * Check the MODX lexicons.
 *
 * @package modx
 * @subpackage build
 */
$mtime = microtime();
$mtime = explode(" ", $mtime);
$mtime = $mtime[1] + $mtime[0];
$tstart = $mtime;
unset($mtime);

/* get rid of time limit */
set_time_limit(0);

error_reporting(E_ALL | E_STRICT);
ini_set('display_errors', true);

$buildConfig = dirname(dirname(__FILE__)) . '/build.config.php';

/* override with your own defines here (see build.config.sample.php) */
$included = false;
if (file_exists($buildConfig)) {
    $included = @include $buildConfig;
}
if (!$included) {
    die('"' . $buildConfig . '" was not found. Please make sure you have created one using the template of build.config.sample.php.');
}

unset($included);

if (!defined('MODX_CORE_PATH')) {
    define('MODX_CORE_PATH', dirname(dirname(__DIR__)) . '/core/');
}

require MODX_CORE_PATH . 'vendor/autoload.php';

use xPDO\xPDO;
use MODX\Revolution\modMenu;

/* define the MODX path constants necessary for core installation */
if (!defined('MODX_BASE_PATH')) {
    define('MODX_BASE_PATH', dirname(MODX_CORE_PATH) . '/');
}
if (!defined('MODX_MANAGER_PATH')) {
    define('MODX_MANAGER_PATH', MODX_BASE_PATH . 'manager/');
}
if (!defined('MODX_CONNECTORS_PATH')) {
    define('MODX_CONNECTORS_PATH', MODX_BASE_PATH . 'connectors/');
}
if (!defined('MODX_ASSETS_PATH')) {
    define('MODX_ASSETS_PATH', MODX_BASE_PATH . 'assets/');
}

/* define the connection variables */
if (!defined('XPDO_DSN')) {
    define('XPDO_DSN', 'mysql:host=localhost;dbname=modx;charset=utf8');
}
if (!defined('XPDO_DB_USER')) {
    define('XPDO_DB_USER', 'root');
}
if (!defined('XPDO_DB_PASS')) {
    define('XPDO_DB_PASS', '');
}
if (!defined('XPDO_TABLE_PREFIX')) {
    define('XPDO_TABLE_PREFIX', 'modx_');
}

/* get properties */
$properties = array();
$f = dirname(dirname(__FILE__)) . '/build.properties.php';
$included = false;
if (file_exists($f)) {
    $included = @include $f;
}
if (!$included) {
    die('build.properties.php was not found. Please make sure you have created one using the template of build.properties.sample.php.');
}

unset($f, $included);

/* instantiate xpdo instance */
$xpdo = new xPDO(XPDO_DSN, XPDO_DB_USER, XPDO_DB_PASS,
    array(
        xPDO::OPT_TABLE_PREFIX => XPDO_TABLE_PREFIX,
        xPDO::OPT_CACHE_PATH => MODX_CORE_PATH . 'cache/',
    ),
    array(
        PDO::ATTR_ERRMODE => PDO::ERRMODE_WARNING,
    )
);
$cacheManager = $xpdo->getCacheManager();
$xpdo->setLogLevel(xPDO::LOG_LEVEL_INFO);
$xpdo->setLogTarget(XPDO_CLI_MODE ? 'ECHO' : 'HTML');

$xpdo->log(xPDO::LOG_LEVEL_INFO, 'Start lexicon check...');
flush();

/* language can be defined for checking language specific lexicons
   en default means checks the english lexicons */
$language = 'en';
if (!empty($argv) && $argc > 1) {
    $language = $argv[1];
}

/* excluded folders can be defined for excluding specific folders with a comma separated list */
$excluded = '';
if (!empty($argv) && $argc > 2) {
    $excluded = $argv[2];
}

$checkLexicon = new CheckLexicon($xpdo, array(
    'language' => $language,
    'excludedFolders' => $excluded
));
$result = $checkLexicon->process();
$xpdo->log(($result['success']) ? xPDO::LOG_LEVEL_INFO : xPDO::LOG_LEVEL_ERROR, $result['message']);

$mtime = microtime();
$mtime = explode(" ", $mtime);
$mtime = $mtime[1] + $mtime[0];
$tend = $mtime;
$totalTime = ($tend - $tstart);
$totalTime = sprintf("%2.4f s", $totalTime);

echo "\nExecution time: {$totalTime}\n";
flush();
exit ();

class CheckLexicon
{
    public $scanPath = null;
    public $lexiconPath = null;
    public $setupLexiconPath = null;

    private $language = null;
    private $excludedFolders = array('_build', 'cache', 'packages', 'node_modules', 'components', 'vendor');

    private $languageKeys = array();
    private $missingKeys = array();
    private $superfluousKeys = array();
    private $variableKeys = array();

    private $invalidLexicons = array();

    private $modx = null;

    public function __construct(xPDO $modx, $options)
    {
        $this->modx = $modx;
        $this->language = isset($options['language']) ? $options['language'] : 'en';
        $this->excludedFolders = array_merge($this->excludedFolders, isset($options['excludedFolders']) ? array_map('trim', explode(',', $options['excludedFolders'])) : array());
        $this->scanPath = isset($options['scanPath']) ? $options['scanPath'] : MODX_BASE_PATH;
        $this->lexiconPath = MODX_CORE_PATH . 'lexicon/';
        $this->setupLexiconPath = MODX_BASE_PATH . 'setup/lang/';
    }

    public function process()
    {
        $this->addKeys();

        $lexiconEntries = $this->loadLexicons();
        if (!is_array($lexiconEntries)) {
            return array(
                'success' => false,
                'message' => $lexiconEntries
            );
        }

        $this->missingKeys = array_diff($this->languageKeys, array_keys($lexiconEntries));
        $usedKeys = array_intersect($this->languageKeys, array_keys($lexiconEntries));
        $this->superfluousKeys = array_diff(array_keys($lexiconEntries), $usedKeys);

        $msg = array();
        if ($result = $this->writeKeys('missing')) {
            $msg[] = $result;
        }
        if ($result = $this->writeKeys('superfluous')) {
            $msg[] = $result;
        }
        if ($result = $this->writeKeys('variable')) {
            $msg[] = $result;
        }
        if (empty($msg)) {
            $msg = 'Every lexicon entry is available and no variable keys are used!';
        } else {
            $msg = implode("\n", $msg);
        }

        return [
            'success' => true,
            'message' => $msg
        ];

    }


    /**
     * Load package lexicons
     *
     * @return bool|array
     */
    private function loadLexicons()
    {
        $lexicons = [];

        if (!$lexicon = $this->loadLexiconFiles($this->lexiconPath . $this->language . '/')) {
            return 'Could not load the lexicons in the language folder "' . $this->lexiconPath . $this->language . '/' . '"!';
        } else {
            $lexicons = array_merge($lexicons, $lexicon);
        }

        if (!$lexicon = $this->loadLexiconFiles($this->setupLexiconPath . $this->language . '/')) {
            return 'Could not load the lexicons in the setup language folder "' . $this->lexiconPath . $this->language . '/' . '"!';
        } else {
            $lexicons = array_merge($lexicons, $lexicon);
        }

        return $lexicons;
    }

    /**
     * Load lexicon files
     *
     * @param $path string
     * @return array|bool
     */
    private function loadLexiconFiles($path)
    {
        if (file_exists($path)) {
            $iterator = new \DirectoryIterator($path);
            $_lang = [];
            foreach ($iterator as $path => $current) {
                if (strpos($current->getFilename(), 'inc.php') !== false) {
                    try {
                        include $current->getRealPath();
                    } catch (Exception $e) {
                        $this->invalidLexicons[] = $current->getFilename();
                    }
                }
            }
            return $_lang;
        } else {
            return false;
        }
    }

    /**
     * Add used lexicon keys
     */
    private function addKeys()
    {
        $directory = new \RecursiveDirectoryIterator($this->scanPath, \RecursiveDirectoryIterator::SKIP_DOTS);
        // Filter files ...
        $filter = new \RecursiveCallbackFilterIterator($directory, function ($current, $key, $iterator) {
            /** @var \RecursiveDirectoryIterator $current */
            // ... for files starting with a dot
            if ($current->getFilename()[0] === '.') {
                return false;
            }
            if ($current->isDir()) {
                // ... for excluded folders
                return !in_array($current->getFilename(), $this->excludedFolders);
            } else {
                // ... for allowed file types
                return $this->allowedFiletype($current);
            }
        });
        $iterator = new \RecursiveIteratorIterator($filter);

        foreach ($iterator as $path => $current) {
            $this->addPhpKeys($path);
            $this->addJsKeys($path);
            $this->addChunkKeys($path);
            $this->addSmartyKeys($path);
        }
        $this->addSettingKeys();
        $this->addMenuKeys();
        $this->addWidgetKeys();
        $this->addPermissionKeys();

        $this->languageKeys = array_unique($this->languageKeys);
        sort($this->languageKeys);
    }

    /**
     * Check for allowed file types
     *
     * @param \RecursiveDirectoryIterator $file
     * @return bool
     */
    private function allowedFiletype($file)
    {
        $pathinfo = pathinfo($file->getFilename());
        return ($file->isFile() && isset($pathinfo['extension']) && (
                $pathinfo['extension'] == 'php' ||
                $pathinfo['extension'] == 'js' ||
                $pathinfo['extension'] == 'html' ||
                $pathinfo['extension'] == 'tpl' ||
                $pathinfo['basename'] == 'config.json'
            ) &&
            strpos($pathinfo['basename'], 'min.js') === false &&
            strpos($pathinfo['basename'], 'ext-') !== 0
        ) ? true : false;
    }

    /**
     * Add lexicon calls in php files:
     * modx->lexicon('whatever',
     *
     * @param string $filename
     */
    private function addPhpKeys($filename)
    {
        $fileContent = file_get_contents($filename);
        $results = [];
        preg_match_all('/(modx|xpdo)->lexicon\((?<quote>["\'])(.*?)\k<quote>\s*[,)]/m', $fileContent, $results);
        if (is_array($results[3])) {
            foreach ($results[3] as $result) {
                // Don't add lexicon keys that end with a dot or an underscore
                if (substr($result, -1) !== '.' &&
                    substr($result, -1) !== '_'
                ) {
                    // Check, if the key contains a variable
                    if (strpos($result, '$') === false
                    ) {
                        $this->languageKeys[] = $result;
                    } else {
                        $this->variableKeys[] = $result;
                    }
                }
            }
        }
    }

    /**
     * Add lexicon calls in javascript files:
     * _('whatever',
     * createDelegate(this, ['whatever'
     *
     * @param string $filename
     */
    private function addJsKeys($filename)
    {
        $fileContent = file_get_contents($filename);
        $results = [];
        preg_match_all('/_\((?<quote>[\'"])(.*?)\k<quote>\s*[,)]/m', $fileContent, $results);
        if (is_array($results[2])) {
            foreach ($results[2] as $result) {
                // Don't add lexicon keys that ends with a dot or an underscore
                if (substr($result, -1) !== '.' &&
                    substr($result, -1) !== '_'
                ) {
                    // Check, if the key is concatenated
                    if (strpos($result, '+') === false
                    ) {
                        $this->languageKeys[] = $result;
                    } else {
                        $this->variableKeys[] = $result;
                    }
                }
            }
        }
        preg_match_all('/(createDelegate)\(.*?,\s+\[(?<quote>[\'"])(.*?)\k<quote>/m', $fileContent, $results);
        if (is_array($results[3])) {
            foreach ($results[3] as $result) {
                // Don't add lexicon keys that ends with a dot or an underscore
                if (substr($result, -1) !== '.' &&
                    substr($result, -1) !== '_'
                ) {
                    // Check, if the key is concatenated
                    if (strpos($result, '+') === false
                    ) {
                        $this->languageKeys[] = $result;
                    } else {
                        $this->variableKeys[] = $result;
                    }
                }
            }
        }
    }

    /**
     * Add lexicon calls in chunk files:
     * [[%whatever
     *
     * @param string $filename
     */
    private function addChunkKeys($filename)
    {
        $fileContent = file_get_contents($filename);
        $results = [];
        preg_match_all('/\[\[%(.*?)[?\]]/m', $fileContent, $results);
        if (is_array($results[1])) {
            foreach ($results[1] as $result) {
                // Don't add lexicon keys that ends with a dot or an underscore
                if (substr($result, -1) !== '.' &&
                    substr($result, -1) !== '_'
                ) {
                    // Check, if the key contains a setting tag
                    if (strpos($result, '[[+') === false
                    ) {
                        $this->languageKeys[] = $result;
                    } else {
                        $this->variableKeys[] = $result;
                    }
                }
            }
        }
    }

    /**
     * Add _lang calls in smarty template files:
     * {$_lang.whatever
     *
     * @param string $filename
     */
    private function addSmartyKeys($filename)
    {
        $fileContent = file_get_contents($filename);
        $results = [];
        preg_match_all('/\$_lang\.(.*?)[ |}]/m', $fileContent, $results);
        if (is_array($results[1])) {
            foreach ($results[1] as $result) {
                // Don't add lexicon keys that ends with a dot or an underscore
                if (substr($result, -1) !== '.' &&
                    substr($result, -1) !== '_'
                ) {
                    $this->languageKeys[] = $result;
                }
            }
        }
    }

    /**
     * Add setting language keys
     */
    private function addSettingKeys()
    {
        $settings = [];
        $xpdo = &$this->modx;
        if (file_exists(MODX_BASE_PATH . '_build/data/transport.core.system_settings.php')) {
            $settings = include MODX_BASE_PATH . '_build/data/transport.core.system_settings.php';
        }

        foreach ($settings as $setting) {
            $this->languageKeys[] = 'setting_' . $setting->get('key');
            $this->languageKeys[] = 'setting_' . $setting->get('key') . '_desc';
            if (!in_array($setting->get('area'), [
                'authentication', 'caching', 'file', 'furls', 'gateway',
                'language', 'manager', 'session', 'site', 'system'
            ])) {
                $this->languageKeys[] = 'area_' . $setting->get('area');
            }
        }
    }

    /**
     * Add menu language keys
     */
    private function addMenuKeys()
    {
        $menus = [];
        $xpdo = &$this->modx;
        if (file_exists(MODX_BASE_PATH . '_build/data/transport.core.menus.php')) {
            $menus = include MODX_BASE_PATH . '_build/data/transport.core.menus.php';
        }

        $xpdo->setLogLevel(xPDO::LOG_LEVEL_FATAL);
        foreach ($menus as $menu) {
            $this->addMenuKey($menu);
        }
        $xpdo->setLogLevel(xPDO::LOG_LEVEL_INFO);
    }

    /**
     * Recursive add menu language key
     * @param modMenu $menu
     */
    private function addMenuKey(modMenu $menu)
    {
        $this->languageKeys[] = $menu->get('text');
        $this->languageKeys[] = $menu->get('description');
        $children = $menu->getMany('Children');
        foreach ($children as $child) {
            $this->addMenuKey($child);
        }
    }

    /**
     * Add widget language keys
     */
    private function addWidgetKeys()
    {
        $widgets = [];
        $xpdo = &$this->modx;
        if (file_exists(MODX_BASE_PATH . '_build/data/transport.core.dashboard_widgets.php')) {
            $widgets = include MODX_BASE_PATH . '_build/data/transport.core.dashboard_widgets.php';
        }


        foreach ($widgets as $widget) {
            $this->languageKeys[] = $widget->get('name');
            $this->languageKeys[] = $widget->get('description');
        }
    }

    /**
     * Add permission language keys
     */
    private function addPermissionKeys()
    {
        $directory = new \RecursiveDirectoryIterator(MODX_BASE_PATH . '_build/data/permissions/', \RecursiveDirectoryIterator::SKIP_DOTS);
        $filter = new \RecursiveCallbackFilterIterator($directory, function ($current, $key, $iterator) {
            /** @var \RecursiveDirectoryIterator $current */
            if ($current->getFilename()[0] === '.') {
                return false;
            }
            if ($current->isDir()) {
                return !in_array($current->getFilename(), $this->excludedFolders);
            } else {
                $pathinfo = pathinfo($current->getFilename());
                return ($current->isFile() && isset($pathinfo['extension']) &&
                    $pathinfo['extension'] == 'php'
                ) ? true : false;
            }
        });
        $iterator = new \RecursiveIteratorIterator($filter);

        $xpdo = &$this->modx;
        foreach ($iterator as $path => $current) {
            try {
                $permissions = include $current->getRealPath();
            } catch (Exception $e) {
                $permissions = [];
            }
            foreach ($permissions as $permission) {
                $this->languageKeys[] = $permission->get('description');
            }

        }
    }

    /**
     * Write missing/superfluous/variable keys to the file _missing.php/_superfluous.php/_variable.php in the _build/lexicon folder
     *
     * @param string $type
     * @return bool|string
     */
    private function writeKeys($type)
    {
        $folder = dirname(__FILE__);
        switch ($type) {
            case 'superfluous':
                $keys = &$this->superfluousKeys;
                $keysFile = '_superfluous.php';
                break;
            case 'variable':
                $keys = &$this->variableKeys;
                $keysFile = '_variable.php';
                break;
            default:
                $type = 'missing';
                $keys = &$this->missingKeys;
                $keysFile = '_missing.php';
                break;
        }
        sort($keys);
        if (!empty($keys)) {
            $handle = fopen($folder . '/' . $keysFile, 'w');
            if ($handle) {
                fwrite($handle, "<?php\n");
                foreach ($keys as $key) {
                    if ($key != '') {
                        fwrite($handle, "\$_lang['{$key}'] = '';\n");
                    }
                }
                fclose($handle);
            } else {
                return 'Cannot write to file:  ' . $keysFile;
            }

            return 'The ' . $type . ' keys could be found in the file ' . $keysFile . ' in the folder "' . $folder . '".';
        } else {
            if (file_exists($folder . '/' . $keysFile)) {
                unlink($folder . '/' . $keysFile);
            }
            return false;
        }
    }
}
