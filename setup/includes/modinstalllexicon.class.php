<?php
/*
 * MODX Revolution
 *
 * Copyright 2006-2012 by MODX, LLC.
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
 * The lexicon handling class for setup.
 *
 * @package modx
 */
class modInstallLexicon {
    /**
     * @var modInstall $install Reference to the modInstall instance.
     */
    public $install = null;
    /**
     * @var array $_lexicon The translated lexicon array
     */
    protected $_lexicon = array();


    function __construct(modInstall &$install,array $config = array()) {
        $this->install =& $install;
        $this->config = array_merge(array(
            'lexiconPath' => dirname(dirname(__FILE__)).'/lang/',
        ),$config);
    }

    /**
     * Gets and parses a lexicon entry.
     * @param string $key The key to grab
     * @param array $placeholders Any values to replace placeholders with
     * @return string The translated key.
     */
    public function get($key,array $placeholders = array()) {
        $v = '';
        if ($this->exists($key)) {
            $v = $this->parse($this->_lexicon[$key],$placeholders);
        }
        return $v;
    }

    /**
     * Sets a lexicon entry value.
     * @param string $key The key to set the value to.
     * @param string $value The value to set.
     * @return string The set value.
     */
    public function set($key,$value = '') {
        $this->_lexicon[$key] = $value;
        return $value;
    }

    /**
     * Parses a lexicon string for placeholder replacement
     * @param string $str
     * @param array $placeholders An array of placeholders
     * @return string
     */
    public function parse($str = '',array $placeholders = array()) {
        if (empty($str)) return '';
        if (empty($placeholders) || !is_array($placeholders)) return $str;

        foreach ($placeholders as $k => $v) {
            $str = str_replace('[[+'.$k.']]',$v,$str);
        }
        return $str;
    }
    /**
     * Checks if a key exists in the currently loaded lexicon
     *
     * @param string $key
     * @return boolean True if key is found
     */
    public function exists($key) {
        return array_key_exists($key,$this->_lexicon);
    }

    /**
     * Accessor method for the lexicon array.
     *
     * @access public
     * @param string $prefix If set, will only return the lexicon entries with this prefix.
     * @param boolean If true, will strip the prefix from the returned indexes
     * @return array The internal lexicon.
     */
    public function fetch($prefix = '',$removePrefix = false) {
        if (!empty($prefix)) {
            $lex = array();
            $lang = $this->_lexicon;
            foreach ($lang as $k => $v) {
                if (strpos($k,$prefix) !== false) {
                    $key = $removePrefix ? str_replace($prefix,'',$k) : $k;
                    $lex[$key] = $v;
                }
            }
            return $lex;
        }
        return $this->_lexicon;
    }

    /**
     * Returns the currently specified language.
     * @return string The IANA language code
     */
    public function getLanguage() {
        $language = 'en';
        if (isset ($_COOKIE['modx_setup_language'])) {
            $language= $_COOKIE['modx_setup_language'];
        }
        if (!empty($this->install) && !empty($this->install->settings) && is_object($this->install->settings)) {
            $language = $this->install->settings->get('language');
        }
        return $language;
    }

    /**
     * Loads a lexicon topic.
     * 
     * @param string/array $topics A string name of a topic (or an array of topic names)
     * @return boolean True if successful.
     */
    public function load($topics) {
        $loaded = false;
        $language = $this->getLanguage();
        if (!is_array($topics)) {
            $topics = array($topics);
        }
        foreach ($topics as $topic) {
            $topicFile = $this->config['lexiconPath'].$language.'/'.$topic.'.inc.php';
            if (file_exists($topicFile)) {
                $_lang = array();
                include $topicFile;
                if (is_array($_lang) && !empty($_lang)) {
                    $this->_lexicon = array_merge($this->_lexicon,$_lang);
                    $loaded = true;
                } else {
                    $loaded = false;
                }
            }
        }
        return $loaded;
    }
}