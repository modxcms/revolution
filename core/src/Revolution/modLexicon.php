<?php

/*
 * This file is part of the MODX Revolution package.
 *
 * Copyright (c) MODX, LLC
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace MODX\Revolution;

use DirectoryIterator;
use RecursiveArrayIterator;
use RecursiveIteratorIterator;
use xPDO\Cache\xPDOCacheManager;
use xPDO\xPDO;

/**
 * The lexicon handling class. Handles all lexicon topics by loading and storing their entries into a cached array.
 * Also considers database-based overrides for specific lexicon entries that preserve the originals and allow reversion.
 *
 * @package MODX\Revolution
 */
class modLexicon
{
    const CACHE_DIRECTORY = 'lexicon_topics';

    const NATIVE_CACHE = 'native';

    /**
     * Reference to the MODX instance.
     *
     * @var modX $modx
     * @access protected
     */
    public $modx = null;
    /**
     * The actual language array.
     *
     * @todo   Separate into separate arrays for each namespace (and maybe topic)
     * so that no namespacing in lexicon entries is needed. Maybe keep a master
     * array of entries, but then have subarrays for topic-specific referencing.
     *
     * @var array $_lexicon
     * @access protected
     */
    protected $_lexicon = [];
    /**
     * Directories to search for language strings in.
     *
     * @deprecated
     * @var array $_paths
     * @access protected
     */
    protected $_paths = [];
    /**
     * An array of loaded topic strings
     *
     * @var array $_loadedTopics
     */
    protected $_loadedTopics = [];

    /**
     * Creates the modLexicon instance.
     *
     * @constructor
     *
     * @param xPDO  $modx   A reference to the modX instance.
     * @param array $config An array of configuration properties
     */
    function __construct(xPDO &$modx, array $config = [])
    {
        $this->modx =& $modx;
        $this->_paths = [
            'core' => $this->modx->getOption('core_path') . 'cache/lexicon/',
        ];
        $this->_lexicon = [$this->modx->getOption('cultureKey', null, 'en') => []];
        $this->config = array_merge($config, []);
    }

    /**
     * Clears the lexicon cache for the specified path.
     *
     * @access public
     *
     * @param string $path The path to clear.
     *
     * @return string The results of the cache clearing.
     */
    public function clearCache($path = '')
    {
        $path = 'lexicon/' . $path;

        return $this->modx->cacheManager->refresh([
            self::CACHE_DIRECTORY => [$path],
        ]);
    }

    /**
     * Returns if the key exists in the lexicon.
     *
     * @access public
     *
     * @param string $index
     *
     * @return boolean True if exists.
     */
    public function exists($index, $language = '')
    {
        $language = !empty($language) ? $language : $this->modx->getOption('cultureKey', null, 'en');

        return (is_string($index) && isset($this->_lexicon[$language][$index]));
    }

    /**
     * Accessor method for the lexicon array.
     *
     * @access public
     *
     * @param string  $prefix       If set, will only return the lexicon entries with this prefix.
     * @param boolean $removePrefix If true, will strip the prefix from the returned indexes
     * @param string  $language
     *
     * @return array The internal lexicon.
     */
    public function fetch($prefix = '', $removePrefix = false, $language = '')
    {
        $language = !empty($language) ? $language : $this->modx->getOption('cultureKey', null, 'en');
        if (!empty($prefix)) {
            $lex = [];
            $lang = $this->_lexicon[$language];
            if (is_array($lang)) {
                foreach ($lang as $k => $v) {
                    if (strpos($k, $prefix) !== false) {
                        $key = $removePrefix ? str_replace($prefix, '', $k) : $k;
                        $lex[$key] = $v;
                    }
                }
            }

            return $lex;
        }

        return $this->_lexicon[$language];
    }

    /**
     * Return the cache key representing the specified lexicon topic.
     *
     * @access public
     *
     * @param string $namespace The namespace for the topic
     * @param string $topic     The topic to grab
     * @param string $language  The language for the topic
     *
     * @return string The cache key for the specified topic
     */
    public function getCacheKey($namespace = 'core', $topic = 'default', $language = '')
    {
        if (empty($namespace)) {
            $namespace = 'core';
        }
        if (empty($topic)) {
            $topic = 'default';
        }
        if (empty($language)) {
            $language = $this->modx->getOption('cultureKey', null, 'en');
        }

        return 'lexicon/' . $language . '/' . $namespace . '/' . $topic;
    }

    /**
     * Loads a variable number of topic areas. They must reside as topicname.
     * inc.php files in their proper culture directory. Can load an infinite
     * number of topic areas via a dynamic number of arguments.
     *
     * They are loaded by language:namespace:topic, namespace:topic, or just
     * topic. Examples: $modx->lexicon->load('en:core:snippet'); $modx->lexicon-
     * >load ('demo:test'); $modx->lexicon->load('chunk');
     *
     * @access public
     */
    public function load()
    {
        $topics = func_get_args(); /* allow for dynamic number of lexicons to load */

        if ($this->modx->context && $this->modx->context->get('key') == 'mgr') {
            $defaultLanguage = $this->modx->getOption('manager_language', $_SESSION ?? [], $this->modx->getOption('cultureKey', null, 'en'));
        } else {
            $defaultLanguage = $this->modx->getOption('cultureKey', null, 'en');
        }

        foreach ($topics as $topicStr) {
            if (!is_string($topicStr) || $topicStr == '') {
                continue;
            }
            if (in_array($topicStr, $this->_loadedTopics)) {
                continue;
            }
            $nspos = strpos($topicStr, ':');
            $topic = str_replace('.', '/', $topicStr);
            /** @deprecated 2.0.0 Allow for lexicon subdirs */

            /* if no namespace, search all lexicons */
            if ($nspos === false) {
                foreach ($this->_paths as $namespace => $path) {
                    $entries = $this->loadCache($namespace, $topic);
                    if (is_array($entries)) {
                        if (!array_key_exists($defaultLanguage, $this->_lexicon)) {
                            $this->_lexicon[$defaultLanguage] = [];
                        }
                        $this->_lexicon[$defaultLanguage] = is_array($this->_lexicon[$defaultLanguage]) ? array_merge($this->_lexicon[$defaultLanguage],
                            $entries) : $entries;
                    }
                }
            } else { /* if namespace, search specified lexicon */
                $params = explode(':', $topic);
                if (count($params) <= 2) {
                    $language = $defaultLanguage;
                    $namespace = $params[0];
                    $topic_parsed = $params[1];
                } else {
                    $language = $params[0];
                    $namespace = $params[1];
                    $topic_parsed = $params[2];
                }

                $englishEntries = $language != 'en' ? $this->loadCache($namespace, $topic_parsed, 'en') : false;
                $entries = $this->loadCache($namespace, $topic_parsed, $language);
                if (!is_array($entries)) {
                    if (is_string($entries) && !empty($entries)) {
                        $entries = $this->modx->fromJSON($entries);
                    }
                    if (empty($entries)) {
                        $entries = [];
                    }
                }
                if (is_array($englishEntries) && !empty($englishEntries) && is_array($entries)) {
                    $entries = array_merge($englishEntries, $entries);
                }
                if (is_array($entries)) {
                    $this->_loadedTopics[] = $topicStr;
                    if (!array_key_exists($language, $this->_lexicon)) {
                        $this->_lexicon[$language] = [];
                    }
                    $this->_lexicon[$language] = is_array($this->_lexicon[$language])
                        ? array_merge($this->_lexicon[$language], $entries)
                        : $entries
                        ;
                }
            }
        }
    }

    /**
     * Loads a lexicon topic from the cache. If not found, tries to generate a
     * cache file from the database.
     *
     * @access public
     *
     * @param string $namespace The namespace to load from. Defaults to 'core'.
     * @param string $topic     The topic to load. Defaults to 'default'.
     * @param string $language  The language to load. Defaults to 'en'.
     *
     * @return array The loaded lexicon array.
     */
    public function loadCache($namespace = 'core', $topic = 'default', $language = '')
    {
        if (empty($language)) {
            $language = $this->modx->getOption('cultureKey', null, 'en');
        }
        $key = $this->getCacheKey($namespace, $topic, $language);
        $enableCache = ($namespace != 'core' && !$this->modx->getOption('cache_noncore_lexicon_topics', null, true))
                ? false
                : true
                ;
        if (!$this->modx->cacheManager) {
            $this->modx->getCacheManager();
        }
        $cached = $this->modx->cacheManager->get($key, [
            xPDO::OPT_CACHE_KEY => $this->modx->getOption(
                'cache_lexicon_topics_key',
                null,
                self::CACHE_DIRECTORY
            ),
            xPDO::OPT_CACHE_HANDLER => $this->modx->getOption(
                'cache_lexicon_topics_handler',
                null,
                $this->modx->getOption(xPDO::OPT_CACHE_HANDLER)
            ),
            xPDO::OPT_CACHE_FORMAT => (int)$this->modx->getOption(
                'cache_lexicon_topics_format',
                null,
                $this->modx->getOption(xPDO::OPT_CACHE_FORMAT, null, xPDOCacheManager::CACHE_PHP)
            ),
        ]);
        if (!$enableCache || $cached == null) {
            $results = false;

            /* load file-based lexicon */
            $results = $this->getFileTopic($language, $namespace, $topic);
            if ($results === false) { /* default back to en */
                $results = $this->getFileTopic('en', $namespace, $topic);
                if ($results === false) {
                    $results = [];
                }
            }

            /* get DB overrides */
            $c = $this->modx->newQuery(modLexiconEntry::class);
            $c->innerJoin(modNamespace::class, 'Namespace');
            $c->where([
                'modLexiconEntry.topic' => $topic,
                'modLexiconEntry.language' => $language,
                'Namespace.name' => $namespace,
            ]);
            $c->sortby($this->modx->getSelectColumns(modLexiconEntry::class, 'modLexiconEntry', '', ['name']), 'ASC');
            $entries = $this->modx->getCollection(modLexiconEntry::class, $c);
            if (!empty($entries)) {
                /** @var modLexiconEntry $entry */
                foreach ($entries as $entry) {
                    $results[$entry->get('name')] = $entry->get('value');
                }
            }
            if ($enableCache) {
                $cached = $this->modx->cacheManager->generateLexiconTopic($key, $results);
            } else {
                $cached = $results;
            }
        }
        if (empty($cached)) {
            $this->modx->log(
                xPDO::LOG_LEVEL_DEBUG,
                "An error occurred while trying to cache {$key} (lexicon/language/namespace/topic)"
            );
        }

        return $cached;
    }

    /**
     * Get entries from file-based lexicon topic
     *
     * @param string $language  The language to filter by.
     * @param string $namespace The namespace to filter by.
     * @param string $topic     The topic to filter by.
     *
     * @return array|boolean An array of lexicon entries in key - value pairs for the specified filter.
     */
    public function getFileTopic($language = 'en', $namespace = 'core', $topic = 'default')
    {
        $corePath = $this->getNamespacePath($namespace);
        $corePath = str_replace([
            '{base_path}',
            '{core_path}',
            '{assets_path}',
        ], [
            $this->modx->getOption('base_path'),
            $this->modx->getOption('core_path'),
            $this->modx->getOption('assets_path'),
        ], $corePath);
        $topicPath = str_replace('//', '/', $corePath . '/lexicon/' . $language . '/' . $topic . '.inc.php');
        $results = [];
        $_lang = [];
        if (file_exists($topicPath)) {
            include $topicPath;
            $results = $_lang;
        } else {
            return false;
        }

        return $results;
    }

    /**
     * Get the path of the specified Namespace
     *
     * @param string $namespace The key of the Namespace
     *
     * @return string The path for the Namespace
     */
    public function getNamespacePath($namespace = 'core')
    {
        $corePath = $this->modx->getOption('core_path', null, MODX_CORE_PATH);
        if ($namespace != 'core') {
            /** @var modNamespace $namespaceObj */
            $namespaceObj = $this->modx->getObject(modNamespace::class, $namespace);
            if ($namespaceObj) {
                $corePath = $namespaceObj->getCorePath();
            }
        }

        return $corePath;
    }

    /**
     * Get a list of available Namespaces when given a Language and Topic
     *
     * @param string $language  The language to filter by
     * @param string $topic The topic to filter by
     *
     * @return array An array of Namespace names
     */
    public function getNamespaceList($language = 'en', $topic = ''): array
    {
        $namespaceList = [];
        if ($namespaces = $this->modx->getCollection(modNamespace::class)) {
            foreach ($namespaces as $namespace) {
                $name = $namespace->get('name');
                $corePath = $name === 'core'
                    ? $this->modx->getOption('core_path', null, MODX_CORE_PATH)
                    : $namespace->getCorePath()
                    ;
                $lexiconPath = str_replace('//', '/', $corePath . '/lexicon/' . $language . '/');

                if (!is_dir($lexiconPath)) {
                    continue;
                }
                if ($topic) {
                    $file = $topic . '.inc.php';
                    if (!is_readable($lexiconPath . $file)) {
                        continue;
                    }
                }
                $namespaceList[] = $name;
            }
        }
        $c = $this->modx->newQuery(modLexiconEntry::class, [
            'language' => $language
        ]);
        if (!empty($namespaceList)) {
            $c->where([
                'namespace:NOT IN' => $namespaceList
            ]);
        }
        if ($topic) {
            $c->where([
                'topic' => $topic
            ]);
        }
        $c->select(['namespace']);
        $c->query['distinct'] = 'DISTINCT';
        if ($c->prepare() && $c->stmt->execute()) {
            $entries = $c->stmt->fetchAll(\PDO::FETCH_ASSOC);
            if (is_array($entries) and count($entries) > 0) {
                foreach ($entries as $v) {
                    $namespaceList[] = $v['namespace'];
                }
            }
        }
        sort($namespaceList);

        return $namespaceList;
    }

    /**
     * Get a list of available Topics when given a Language and Namespace.
     *
     * @param string $language  The language to filter by.
     * @param string $namespace The namespace to filter by.
     *
     * @return array An array of Topic names.
     */
    public function getTopicList($language = 'en', $namespace = 'core')
    {
        $corePath = $this->getNamespacePath($namespace);
        $lexPath = str_replace('//', '/', $corePath . '/lexicon/' . $language . '/');

        $topics = [];
        if (!is_dir($lexPath)) {
            return $topics;
        }
        /** @var DirectoryIterator $topic */
        foreach (new DirectoryIterator($lexPath) as $topic) {
            if (in_array($topic, ['.', '..', '.svn', '.git', '_notes'])) {
                continue;
            }
            if (!$topic->isReadable()) {
                continue;
            }

            if ($topic->isFile()) {
                $fileName = $topic->getFilename();
                if (strpos($fileName, '.inc.php')) {
                    $topics[] = str_replace('.inc.php', '', $fileName);
                }
            }
        }

        $c = $this->modx->newQuery(modLexiconEntry::class);
        $c->where([
            'namespace' => $namespace,
            'topic:NOT IN' => $topics,
            'language' => $language
        ]);
        $c->select(['topic']);
        $c->query['distinct'] = 'DISTINCT';
        if ($c->prepare() && $c->stmt->execute()) {
            $entries = $c->stmt->fetchAll(\PDO::FETCH_ASSOC);
            if (is_array($entries) and count($entries) > 0) {
                foreach ($entries as $v) {
                    $topics[] = $v['topic'];
                }
            }
        }

        sort($topics);

        return $topics;
    }

    /**
     * @param String $language
     *
     * @return String
     */
    public function getLanguageNativeName($language)
    {
        $options = [xPDO::OPT_CACHE_KEY => self::CACHE_DIRECTORY];

        $names = $this->modx->cacheManager->get(self::NATIVE_CACHE, $options) ?: [];

        if (!array_key_exists($language, $names)) {
            $this->modx->lexicon->load("$language:core:languages");
            $names[$language] = $this->modx->lexicon('language_' . $language, [], $language);
            $this->modx->cacheManager->set(self::NATIVE_CACHE, $names, 0, $options);
        }

        return $names[$language];
    }

    /**
     * Get a list of available languages for a Namespace.
     *
     * @param string $namespace The Namespace to filter by.
     *
     * @return array An array of available languages
     */
    public function getLanguageList($namespace = 'core')
    {
        $corePath = $this->getNamespacePath($namespace);
        $lexPath = str_replace('//', '/', $corePath . '/lexicon/');
        if (!is_dir($lexPath)) {
            return [];
        }
        $languages = [];
        /** @var DirectoryIterator $language */
        foreach (new DirectoryIterator($lexPath) as $language) {
            if (in_array($language, ['.', '..', '.svn', '.git', '_notes', 'country'])) {
                continue;
            }
            if (!$language->isReadable()) {
                continue;
            }

            if ($language->isDir()) {
                $languages[] = $language->getFilename();
            }
        }

        $c = $this->modx->newQuery(modLexiconEntry::class);
        $c->where([
            'namespace' => $namespace,
            'language:NOT IN' => $languages,
        ]);
        $c->select(['language']);
        $c->query['distinct'] = 'DISTINCT';
        if ($c->prepare() && $c->stmt->execute()) {
            $entries = $c->stmt->fetchAll(\PDO::FETCH_ASSOC);
            if (is_array($entries) and count($entries) > 0) {
                foreach ($entries as $v) {
                    $languages[] = $v['language'];
                }
            }
        }

        sort($languages);

        return $languages;
    }

    /**
     * Get a list of available languages for a Namespace and Topic
     *
     * @param string $namespace The Namespace to filter by
     * @param string $topic The Topic to filter by
     *
     * @return array An array of available languages
     */
    public function getFilterLanguageList($namespace = 'core', $topic = '')
    {
        $corePath = $this->getNamespacePath($namespace);
        $lexiconPath = str_replace('//', '/', $corePath . '/lexicon/');
        if (!is_dir($lexiconPath)) {
            return [];
        }
        $languageList = [];

        /** @var DirectoryIterator $language */
        foreach (new DirectoryIterator($lexiconPath) as $language) {
            if (in_array($language, ['.', '..', '.svn', '.git', '_notes', 'country']) || !$language->isReadable()) {
                continue;
            }

            if ($language->isDir()) {
                $languageKey = $language->getFilename();

                /*
                    Only show languages that contain the selected topic

                    Note that all custom (new, user-created) topics for core entries will only be
                    present in the database, which is queried below, so no need to assess whether a
                    file for a particular language and topic exists in that scenario
                */
                if ($topic && $namespace !== 'core') {
                    $topicsPath = $language->getPath() . '/' . $languageKey . '/';
                    if (!is_dir($topicsPath)) {
                        continue;
                    }
                    $file = $topic . '.inc.php';
                    if (!is_readable($topicsPath . $file)) {
                        continue;
                    }
                }
                $languageList[] = $languageKey;
            }
        }

        $c = $this->modx->newQuery(modLexiconEntry::class, [
            'namespace' => $namespace
        ]);
        if (!empty($languageList)) {
            $c->where([
                'language:NOT IN' => $languageList
            ]);
        }
        if ($topic) {
            $c->where([
                'topic' => $topic
            ]);
        }
        $c->select(['language']);
        $c->query['distinct'] = 'DISTINCT';
        if ($c->prepare() && $c->stmt->execute()) {
            $entries = $c->stmt->fetchAll(\PDO::FETCH_ASSOC);
            if (is_array($entries) and count($entries) > 0) {
                foreach ($entries as $v) {
                    $languageList[] = $v['language'];
                }
            }
        }
        sort($languageList);

        return $languageList;
    }

    /**
     * Get a lexicon string by its index.
     *
     * @access public
     *
     * @param string $key    The key of the lexicon string.
     * @param array  $params An assocative array of placeholder
     *                       keys and values to parse
     * @param string $language
     *
     * @return string The text of the lexicon key, blank if not found.
     */
    public function process($key, array $params = [], $language = '')
    {
        $language = !empty($language) ? $language : $this->modx->getOption('cultureKey', null, 'en');
        /* make sure key exists */
        if (!is_string($key) || !isset($this->_lexicon[$language][$key])) {
            $this->modx->log(xPDO::LOG_LEVEL_DEBUG, 'Language string not found: "' . $key . '"');

            return $key;
        }

        /* if params are passed, allow for parsing of [[+key]] values to strings */

        return empty($params)
            ? $this->_lexicon[$language][$key]
            : $this->_parse($this->_lexicon[$language][$key], $params);
    }

    /**
     * Sets a lexicon key to a value. Not recommended, since doesn't query the
     * database.
     *
     * @access public
     *
     * @param string|array $keys     Either an array of array pairs of key/values or
     *                               a key string.
     * @param string       $text     The text to set, if the first parameter is a string.
     * @param string       $language The language to set the key in. Defaults to current.
     */
    public function set($keys, $text = '', $language = '')
    {
        $language = !empty($language) ? $language : $this->modx->getOption('cultureKey', null, $language);
        if (is_array($keys)) {
            foreach ($keys as $key => $str) {
                if ($key == '') {
                    continue;
                }
                $this->_lexicon[$language][$key] = $str;
            }
        } else {
            if (is_string($keys) && $keys != '') {
                $this->_lexicon[$language][$keys] = $text;
            }
        }
    }

    /**
     * Parses a lexicon string, replacing placeholders with
     * specified strings.
     *
     * @access private
     *
     * @param string $str    The string to parse
     * @param array  $params An associative array of keys to replace
     *
     * @return string The processed string
     */
    private function _parse($str, $params)
    {
        if (!$str) {
            return '';
        }
        if (empty($params)) {
            return $str;
        }

        $params = $this->_flatten($params);
        foreach ($params as $k => $v) {
            $str = str_replace('[[+' . $k . ']]', $v, $str);
        }

        return $str;
    }

    /**
     * Flattens an array by dot notation
     *
     * @access private
     * @param array $array The array to flatten
     * @return array The processed array
     */
    private function _flatten($array) {
        $result = [];

        if (is_array($array)) {
            $iterator = new RecursiveIteratorIterator(new RecursiveArrayIterator($array));
            foreach ($iterator as $value) {
                $keys = [];
                foreach (range(0, $iterator->getDepth()) as $depth) {
                    $keys[] = $iterator->getSubIterator($depth)->key();
                }
                $result[ join('.', $keys) ] = $value;
            }
        }
        return $result;
    }

    /**
     * Returns the total # of entries in the active lexicon
     *
     * @param string $language
     *
     * @return int
     */
    public function total($language = '')
    {
        $language = !empty($language) ? $language : $this->modx->getOption('cultureKey', null, 'en');

        return count($this->_lexicon[$language]);
    }

    /**
     * Completely clears the lexicon
     *
     * @param string $language
     *
     * @return void
     */
    public function clear($language = '')
    {
        if (!empty($language)) {
            $this->_lexicon[$language] = [];
        } else {
            $this->_lexicon = [$this->modx->getOption('cultureKey', null, 'en') => []];
        }
    }
}
