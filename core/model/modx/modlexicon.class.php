<?php
/*
 * MODx Revolution
 *
 * Copyright 2006-2010 by the MODx Team.
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
 * modLexicon
 *
 * @package modx
 */
/**
 * The lexicon handling class.
 * Eventually needs to be reworked to allow for context/area-specific lexicons.
 *
 * @package modx
 */
class modLexicon {
    /**
     * Reference to the MODx instance.
     *
     * @var modX $modx
     * @access protected
     */
    public $modx = null;
    /**
     * The actual language array.
     *
     * @todo Separate into separate arrays for each namespace (and maybe topic)
     * so that no namespacing in lexicon entries is needed. Maybe keep a master
     * array of entries, but then have subarrays for topic-specific referencing.
     *
     * @var array $_lexicon
     * @access protected
     */
    protected $_lexicon = array();
    /**
     * Directories to search for language strings in.
     *
     * @deprecated
     * @var array $_paths
     * @access protected
     */
    protected $_paths = array();

    /**
     * Creates the modLexicon instance.
     *
     * @constructor
     * @param modX &$modx A reference to the modX instance.
     * @return modLexicon
     */
    function __construct(xPDO &$modx,array $config = array()) {
        $this->modx =& $modx;
        $this->_paths = array(
             'core' => $this->modx->getOption('core_path') . 'cache/lexicon/',
        );
        $this->_lexicon = array();
        $this->config = array_merge($config,array());
    }

    /**
     * Clears the lexicon cache for the specified path.
     *
     * @access public
     * @param string $path The path to clear.
     * @return string The results of the cache clearing.
     */
    public function clearCache($path = '') {
        $path = 'lexicon/'.$path;
        return $this->modx->cacheManager->clearCache(array($path));
    }

    /**
     * Returns if the key exists in the lexicon.
     *
     * @access public
     * @param string $index
     * @return boolean True if exists.
     */
    public function exists($index) {
        return (is_string($index) && isset($this->_lexicon[$index]));
    }

    /**
     * Accessor method for the lexicon array.
     *
     * @access public
     * @return array The internal lexicon.
     */
    public function fetch() {
        return $this->_lexicon;
    }

    /**
     * Return the cache key representing the specified lexicon topic.
     *
     * @access public
     * @param string $namespace The namespace for the topic
     * @param string $topic The topic to grab
     * @param string $language The language for the topic
     * @return string The cache key for the specified topic
     */
    public function getCacheKey($namespace = 'core',$topic = 'default',$language = '') {
        if (empty($namespace)) $namespace = 'core';
        if (empty($topic)) $topic = 'default';
        if (empty($language)) $language = $this->modx->getOption('cultureKey',null,'en');
        return 'lexicon/'.$language.'/'.$namespace.'/'.$topic;
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
    public function load() {
        $topics = func_get_args(); /* allow for dynamic number of lexicons to load */

        foreach ($topics as $topic) {
            if (!is_string($topic) || $topic == '') return false;
            $nspos = strpos($topic,':');
            $topic = str_replace('.','/',$topic); /** @deprecated 2.0.0 Allow for lexicon subdirs */

            /* if no namespace, search all lexicons */
            if ($nspos === false) {
                foreach ($this->_paths as $namespace => $path) {
                    $entries = $this->loadCache($namespace,$topic);
                    if (is_array($entries)) {
                        $this->_lexicon = is_array($this->_lexicon) ? array_merge($this->_lexicon,$entries) : $entries;
                    }
                }
            } else { /* if namespace, search specified lexicon */
                $params = explode(':',$topic);
                if (count($params) <= 2) {
                    $language = $this->modx->getOption('cultureKey',null,'en');
                    $namespace = $params[0];
                    $topic_parsed = $params[1];
                } else {
                    $language = $params[0];
                    $namespace = $params[1];
                    $topic_parsed = $params[2];
                }

                $entries = $this->loadCache($namespace,$topic_parsed,$language);
                if (is_array($entries)) {
                    $this->_lexicon = is_array($this->_lexicon) ? array_merge($this->_lexicon, $entries) : $entries;
                }
            }
        }
    }

    /**
     * Loads a lexicon topic from the cache. If not found, tries to generate a
     * cache file from the database.
     *
     * @access public
     * @param string $namespace The namespace to load from. Defaults to 'core'.
     * @param string $topic The topic to load. Defaults to 'default'.
     * @param string $language The language to load. Defaults to 'en'.
     * @return array The loaded lexicon array.
     */
    public function loadCache($namespace = 'core', $topic = 'default', $language = '') {
        if (empty($language)) $language = $this->modx->getOption('cultureKey',null,'en');
        $key = $this->getCacheKey($namespace, $topic, $language);
        $enableCache = ($namespace != 'core' && !$this->modx->getOption('cache_noncore_lexicon_topics',null,true)) ? false : true;
        
        $cached = $this->modx->cacheManager->get($key);
        if (!$enableCache || $cached == null) {
            $results= false;

            /* load file-based lexicon */
            $results = $this->getFileTopic($language,$namespace,$topic);

            /* get DB overrides */
            $c= $this->modx->newQuery('modLexiconEntry');
            $c->innerJoin('modNamespace','Namespace');
            $c->where(array(
                'modLexiconEntry.topic' => $topic,
                'modLexiconEntry.language' => $language,
                'Namespace.name' => $namespace,
            ));
            $c->sortby($this->modx->getSelectColumns('modLexiconEntry','modLexiconEntry','',array('name')),'ASC');
            $entries= $this->modx->getCollection('modLexiconEntry',$c);
            if (!empty($entries)) {
                foreach ($entries as $entry) {
                    $results[$entry->get('name')]= $entry->get('value');
                }
            }
            if ($enableCache) {
                $cached = $this->modx->cacheManager->generateLexiconTopic($key,$results);
            } else {
                $cached = $results;
            }
        }
        if (empty($cached)) {
            $this->modx->log(xPDO::LOG_LEVEL_WARN, "An error occurred while trying to cache {$key} (lexicon/language/namespace/topic)");
        }
        return $cached;
    }

    /**
     * Get entries from file-based lexicon topic
     *
     * @param string $language The language to filter by.
     * @param string $namespace The namespace to filter by.
     * @param string $topic The topic to filter by.
     * @return array An array of lexicon entries in key - value pairs for the specified filter.
     */
    public function getFileTopic($language = 'en',$namespace = 'core',$topic = 'default') {
        $corePath = $this->getNamespacePath($namespace);
        $corePath = str_replace(array(
            '{base_path}',
            '{core_path}',
            '{assets_path}',
        ),array(
            $this->modx->getOption('base_path'),
            $this->modx->getOption('core_path'),
            $this->modx->getOption('assets_path'),
        ),$corePath);
        $topicPath = str_replace('//','/',$corePath.'/lexicon/'.$language.'/'.$topic.'.inc.php');
        $results = array();
        $_lang = array();
        if (file_exists($topicPath)) {
            include $topicPath;
            $results = $_lang;
        }
        return $results;
    }

    /**
     * Get the path of the specified Namespace
     *
     * @param string $namespace The key of the Namespace
     * @return string The path for the Namespace
     */
    public function getNamespacePath($namespace = 'core') {
        $corePath = $this->modx->getOption('core_path',null,MODX_CORE_PATH);
        if ($namespace != 'core') {
            $namespaceObj = $this->modx->getObject('modNamespace',$namespace);
            if ($namespaceObj) {
                $corePath = $namespaceObj->get('path');
            }
        }
        return $corePath;
    }

    /**
     * Get a list of available Topics when given a Language and Namespace.
     *
     * @param string $language The language to filter by.
     * @param string $namespace The language to filter by.
     * @return array An array of Topic names.
     */
    public function getTopicList($language = 'en',$namespace = 'core') {
        $corePath = $this->getNamespacePath($namespace);
        $lexPath = str_replace('//','/',$corePath.'/lexicon/'.$language.'/');

        $topics = array();
        if (!is_dir($lexPath)) return $topics;
        foreach (new DirectoryIterator($lexPath) as $topic) {
            if (in_array($topic,array('.','..','.svn','_notes'))) continue;
            if (!$topic->isReadable()) continue;

            if ($topic->isFile()) {
                $fileName = $topic->getFilename();
                if (strpos($fileName,'.inc.php')) {
                    $topics[] = str_replace('.inc.php','',$fileName);
                }
            }
        }
        return $topics;
    }

    /**
     * Get a list of available languages for a Namespace.
     *
     * @param string $namespace The Namespace to filter by.
     * @return array An array of available languages
     */
    public function getLanguageList($namespace = 'core') {
        $corePath = $this->getNamespacePath($namespace);
        $lexPath = str_replace('//','/',$corePath.'/lexicon/');
        $languages = array();
        foreach (new DirectoryIterator($lexPath) as $language) {
            if (in_array($language,array('.','..','.svn','_notes','country'))) continue;
            if (!$language->isReadable()) continue;

            if ($language->isDir()) {
                $languages[] = $language->getFilename();
            }
        }
        return $languages;
    }

    /**
     * Get a lexicon string by its index.
     *
     * @access public
     * @param string $key The key of the lexicon string.
     * @param array $params An assocative array of placeholder
     * keys and values to parse
     * @return string The text of the lexicon key, blank if not found.
     */
    public function process($key,array $params = array()) {
        /* make sure key exists */
        if (!is_string($key) || !isset($this->_lexicon[$key])) {
            $this->modx->log(xPDO::LOG_LEVEL_WARN,'Language string not found: "'.$key.'"');
            return $key;
        }
        /* if params are passed, allow for parsing of [[+key]] values to strings */
        return empty($params)
            ? $this->_lexicon[$key]
            : $this->_parse($this->_lexicon[$key],$params);
    }

    /**
     * Sets a lexicon key to a value. Not recommended, since doesn't query the
     * database.
     *
     * @access public
     * @param string/array $keys Either an array of array pairs of key/values or
     * a key string.
     * @param string $text The text to set, if the first parameter is a string.
     */
    public function set($keys, $text = '') {
        if (is_array($keys)) {
            foreach ($keys as $key => $str) {
                if ($key == '') continue;
                $this->_lexicon[$key] = $str;
            }
        } else if (is_string($keys) && $keys != '') {
            $this->_lexicon[$keys] = $text;
        }
    }

    /**
     * Parses a lexicon string, replacing placeholders with
     * specified strings.
     *
     * @access private
     * @param string $str The string to parse
     * @param array $params An associative array of keys to replace
     * @return string The processed string
     */
    private function _parse($str,$params) {
        if (!$str) return '';
        if (empty($params)) return $str;

        foreach ($params as $k => $v) {
            $str = str_replace('[[+'.$k.']]',$v,$str);
        }
        return $str;
    }
}