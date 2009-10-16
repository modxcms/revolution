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
    var $modx = null;
    /**
     * The actual language array.
     *
     * @todo Separate into separate arrays for each namespace (and maybe topic)
     * so that no namespacing in lexicon entries is needed. Maybe keep a master
     * array of entries, but then have subarrays for topic-specific referencing.
     *
     * @var array $_lexicon
     * @access private
     */
    var $_lexicon = array();
    /**
     * Directories to search for language strings in.
     *
     * @deprecated
     * @var array $_paths
     * @access private
     */
    var $_paths = array();

    /**#@+
     * Creates the modLexicon instance.
     *
     * @constructor
     * @param modX &$modx A reference to the modX instance.
     * @return modLexicon
     */
    function modLexicon(&$modx) {
        $this->__construct($modx);
    }
    /** @ignore */
    function __construct(&$modx) {
        $this->modx =& $modx;
        $this->_paths = array(
             'core' => $this->modx->getOption('core_path') . 'cache/lexicon/',
        );
        $this->_lexicon = array();
    }
    /**#@-*/

    /**
     * Clears the lexicon cache for the specified path.
     *
     * @access public
     * @param string $path The path to clear.
     * @return string The results of the cache clearing.
     */
    function clearCache($path = '') {
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
    function exists($index) {
        return (is_string($index) && isset($this->_lexicon[$index]));
    }

    /**
     * Accessor method for the lexicon array.
     *
     * @access public
     * @return array The internal lexicon.
     */
    function fetch() {
        return $this->_lexicon;
    }

    /**
     * Return the cache key representing the specified lexicon topic.
     */
    function getCacheKey($namespace = 'core',$topic = 'default',$language = '') {
        if (empty($namespace)) $namespace = 'core';
        if (empty($topic)) $topic = 'default';
        if (empty($language)) $language = $this->modx->cultureKey;
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
    function load() {
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
                    $language = $this->modx->cultureKey;
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
    function loadCache($namespace = 'core', $topic = 'default', $language = '') {
        if (empty($language)) $language = $this->modx->cultureKey;
        $key = $this->getCacheKey($namespace, $topic, $language);

        if (($cached = $this->modx->cacheManager->get($key)) == null) {
            $results= false;

            $c= $this->modx->newQuery('modLexiconEntry');
            $c->innerJoin('modLexiconTopic','Topic');
            $c->innerJoin('modNamespace','Namespace','Namespace.name = Topic.namespace');
            $c->where(array(
                'Topic.name' => $topic,
                'Namespace.name' => $namespace,
                'modLexiconEntry.language' => $language,
            ));
            $c->sortby('`modLexiconEntry`.`name`','ASC');

            if ($entries= $this->modx->getCollection('modLexiconEntry',$c)) {
                $results= array();
                foreach ($entries as $entry) {
                    $results[$entry->get('name')]= $entry->get('value');
                }
            }

            $cached = $this->modx->cacheManager->generateLexiconTopic($key,$results);
        }
        if (empty($cached)) {
            $this->modx->log(MODX_LOG_LEVEL_ERROR, "An error occurred while trying to cache {$key} (lexicon/language/namespace/topic)");
        }
        return $cached;
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
    function process($key,$params = array()) {
        /* make sure key exists */
        if (!is_string($key) || !isset($this->_lexicon[$key])) {
            $this->modx->log(XPDO_LOG_LEVEL_WARN,'Language string not found: "'.$key.'"');
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
    function set($keys, $text = '') {
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
    function _parse($str,$params) {
        if (!$str) return '';
        if (empty($params)) return $str;

        foreach ($params as $k => $v) {
            $str = str_replace('[[+'.$k.']]',$v,$str);
        }
        return $str;
    }
}