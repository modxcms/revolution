<?php

/**
 * Check MODX core lexicon usage and topic integrity.
 *
 * @package modx
 * @subpackage build
 */

namespace MODX\Revolution\Build;

use xPDO\xPDO;
use MODX\Revolution\modMenu;

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
    private $duplicateIdentical = array();
    private $duplicateConflict = array();

    private $modx = null;

    public function __construct(xPDO $modx, $options)
    {
        $this->modx = $modx;
        $this->language = isset($options['language']) ? $options['language'] : 'en';
        $this->excludedFolders = array_merge(
            $this->excludedFolders,
            isset($options['excludedFolders']) ? array_map('trim', explode(',', $options['excludedFolders'])) : array()
        );
        $this->scanPath = isset($options['scanPath']) ? $options['scanPath'] : MODX_BASE_PATH;
        $this->lexiconPath = MODX_CORE_PATH . 'lexicon/';
        $this->setupLexiconPath = MODX_BASE_PATH . 'setup/lang/';
    }

    public function process()
    {
        $this->addKeys();

        $coreTopics = self::loadLexiconTopics($this->lexiconPath . $this->language . '/');
        if ($coreTopics === false) {
            $path = $this->lexiconPath . $this->language . '/';
            return array(
                'success' => false,
                'message' => 'Could not load the lexicons in the language folder "' . $path . '"!'
            );
        }

        $setupTopics = self::loadLexiconTopics($this->setupLexiconPath . $this->language . '/');
        if ($setupTopics === false) {
            $path = $this->setupLexiconPath . $this->language . '/';
            return array(
                'success' => false,
                'message' => 'Could not load the lexicons in the setup language folder "'
                    . $path . '"!'
            );
        }

        $lexiconEntries = array_merge(
            self::flattenTopicEntries($coreTopics),
            self::flattenTopicEntries($setupTopics)
        );

        $this->missingKeys = array_diff($this->languageKeys, array_keys($lexiconEntries));
        $usedKeys = array_intersect($this->languageKeys, array_keys($lexiconEntries));
        $this->superfluousKeys = array_diff(array_keys($lexiconEntries), $usedKeys);

        $duplicates = self::findCrossTopicDuplicates($coreTopics);
        $this->duplicateIdentical = $duplicates['identical'];
        $this->duplicateConflict = $duplicates['conflict'];

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
        if ($result = $this->writeDuplicateKeys('identical')) {
            $msg[] = $result;
        }
        if ($result = $this->writeDuplicateKeys('conflict')) {
            $msg[] = $result;
        }
        if (empty($msg)) {
            $msg = 'Every lexicon entry is available and no variable keys or cross-topic duplicates are used!';
        } else {
            $msg = implode("\n", $msg);
        }

        return [
            'success' => true,
            'message' => $msg
        ];
    }

    /**
     * Load lexicon topic files into topic => [key => value].
     *
     * @param string $path
     * @return array|false
     */
    public static function loadLexiconTopics($path)
    {
        if (!is_dir($path)) {
            return false;
        }

        $topics = [];
        $iterator = new \DirectoryIterator($path);
        foreach ($iterator as $current) {
            if ($current->isDot() || strpos($current->getFilename(), 'inc.php') === false) {
                continue;
            }
            $_lang = [];
            try {
                include $current->getRealPath();
            } catch (\Exception $e) {
                continue;
            }
            $topic = basename($current->getFilename(), '.inc.php');
            $topics[$topic] = $_lang;
        }
        ksort($topics);

        return $topics;
    }

    /**
     * Flatten topic maps into a single key => value map (later topics overwrite).
     *
     * @param array $entriesByTopic
     * @return array
     */
    public static function flattenTopicEntries(array $entriesByTopic)
    {
        $flat = [];
        foreach ($entriesByTopic as $entries) {
            $flat = array_merge($flat, $entries);
        }

        return $flat;
    }

    /**
     * Find keys defined in more than one topic.
     *
     * @param array $entriesByTopic topic => [key => value]
     * @return array{identical: array, conflict: array}
     */
    public static function findCrossTopicDuplicates(array $entriesByTopic)
    {
        $byKey = [];
        foreach ($entriesByTopic as $topic => $entries) {
            foreach ($entries as $key => $value) {
                $byKey[$key][$topic] = $value;
            }
        }

        $identical = [];
        $conflict = [];
        foreach ($byKey as $key => $topics) {
            if (count($topics) < 2) {
                continue;
            }
            $values = array_unique(array_values($topics));
            $row = [
                'key' => $key,
                'topics' => array_keys($topics),
                'values' => $topics,
            ];
            if (count($values) === 1) {
                $identical[$key] = $row;
            } else {
                $conflict[$key] = $row;
            }
        }
        ksort($identical);
        ksort($conflict);

        return [
            'identical' => $identical,
            'conflict' => $conflict,
        ];
    }

    /**
     * Add used lexicon keys
     */
    private function addKeys()
    {
        $directory = new \RecursiveDirectoryIterator($this->scanPath, \RecursiveDirectoryIterator::SKIP_DOTS);
        $filter = new \RecursiveCallbackFilterIterator($directory, function ($current, $key, $iterator) {
            /** @var \RecursiveDirectoryIterator $current */
            if ($current->getFilename()[0] === '.') {
                return false;
            }
            if ($current->isDir()) {
                return !in_array($current->getFilename(), $this->excludedFolders);
            } else {
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
     * @param string $filename
     */
    private function addPhpKeys($filename)
    {
        $fileContent = file_get_contents($filename);
        $results = [];
        preg_match_all('/(modx|xpdo)->lexicon\((?<quote>["\'])(.*?)\k<quote>\s*[,)]/m', $fileContent, $results);
        if (is_array($results[3])) {
            foreach ($results[3] as $result) {
                if (
                    substr($result, -1) !== '.' &&
                    substr($result, -1) !== '_'
                ) {
                    if (
                        strpos($result, '$') === false
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
     * @param string $filename
     */
    private function addJsKeys($filename)
    {
        $fileContent = file_get_contents($filename);
        $results = [];
        preg_match_all('/_\((?<quote>[\'"])(.*?)\k<quote>\s*[,)]/m', $fileContent, $results);
        if (is_array($results[2])) {
            foreach ($results[2] as $result) {
                if (
                    substr($result, -1) !== '.' &&
                    substr($result, -1) !== '_'
                ) {
                    if (
                        strpos($result, '+') === false
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
                if (
                    substr($result, -1) !== '.' &&
                    substr($result, -1) !== '_'
                ) {
                    if (
                        strpos($result, '+') === false
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
     * @param string $filename
     */
    private function addChunkKeys($filename)
    {
        $fileContent = file_get_contents($filename);
        $results = [];
        preg_match_all('/\[\[%(.*?)[?\]]/m', $fileContent, $results);
        if (is_array($results[1])) {
            foreach ($results[1] as $result) {
                if (
                    substr($result, -1) !== '.' &&
                    substr($result, -1) !== '_'
                ) {
                    if (
                        strpos($result, '[[+') === false
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
     * @param string $filename
     */
    private function addSmartyKeys($filename)
    {
        $fileContent = file_get_contents($filename);
        $results = [];
        preg_match_all('/\$_lang\.(.*?)[ |}]/m', $fileContent, $results);
        if (is_array($results[1])) {
            foreach ($results[1] as $result) {
                if (
                    substr($result, -1) !== '.' &&
                    substr($result, -1) !== '_'
                ) {
                    $this->languageKeys[] = $result;
                }
            }
        }
    }

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
            if (
                !in_array($setting->get('area'), [
                'authentication', 'caching', 'file', 'furls', 'gateway',
                'language', 'manager', 'session', 'site', 'system'
                ])
            ) {
                $this->languageKeys[] = 'area_' . $setting->get('area');
            }
        }
    }

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

    private function addPermissionKeys()
    {
        $permissionsPath = MODX_BASE_PATH . '_build/data/permissions/';
        $directory = new \RecursiveDirectoryIterator(
            $permissionsPath,
            \RecursiveDirectoryIterator::SKIP_DOTS
        );
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
            } catch (\Exception $e) {
                $permissions = [];
            }
            foreach ($permissions as $permission) {
                $this->languageKeys[] = $permission->get('description');
            }
        }
    }

    /**
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

            return 'The ' . $type . ' keys could be found in the file ' . $keysFile
                . ' in the folder "' . $folder . '".';
        } else {
            if (file_exists($folder . '/' . $keysFile)) {
                unlink($folder . '/' . $keysFile);
            }
            return false;
        }
    }

    /**
     * Write cross-topic duplicate reports.
     *
     * @param string $type identical|conflict
     * @return bool|string
     */
    private function writeDuplicateKeys($type)
    {
        $folder = dirname(__FILE__);
        if ($type === 'conflict') {
            $rows = $this->duplicateConflict;
            $keysFile = '_duplicates_conflict.php';
        } else {
            $type = 'identical';
            $rows = $this->duplicateIdentical;
            $keysFile = '_duplicates_identical.php';
        }

        $reportPath = $folder . '/' . $keysFile;
        if (empty($rows)) {
            if (file_exists($reportPath)) {
                unlink($reportPath);
            }
            return false;
        }

        $handle = fopen($reportPath, 'w');
        if (!$handle) {
            return 'Cannot write to file:  ' . $keysFile;
        }

        fwrite($handle, "<?php\n");
        fwrite($handle, "/**\n * Cross-topic {$type} lexicon duplicates for language {$this->language}.\n");
        fwrite($handle, " * Generated by checklexicon.php — do not commit.\n */\n");
        foreach ($rows as $row) {
            $topics = implode(', ', $row['topics']);
            if ($type === 'conflict') {
                $parts = [];
                foreach ($row['values'] as $topic => $value) {
                    $parts[] = $topic . '=' . var_export($value, true);
                }
                fwrite($handle, "// {$row['key']} @ {$topics}\n");
                fwrite($handle, '//   ' . implode(' | ', $parts) . "\n");
            } else {
                $value = var_export(reset($row['values']), true);
                fwrite($handle, "// {$row['key']} @ {$topics} = {$value}\n");
            }
        }
        fclose($handle);

        return 'The ' . $type . ' cross-topic duplicates could be found in the file '
            . $keysFile . ' in the folder "' . $folder . '".';
    }
}
