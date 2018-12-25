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
            'lexiconPath' => dirname(__DIR__).'/lang/',
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
        } else {
            $availableLangs = $this->getLanguageList();
            if (isset($_SERVER['HTTP_ACCEPT_LANGUAGE'])) {
                // break up string into pieces (languages and q factors)
                preg_match_all('/([a-z]{1,8}(-[a-z]{1,8})?)\s*(;\s*q\s*=\s*(1|0\.[0-9]+))?/i', $_SERVER['HTTP_ACCEPT_LANGUAGE'], $lang_parse);

                if (count($lang_parse[1])) {
                    // create a list like "en" => 0.8
                    $acceptLangs = array_combine($lang_parse[1], $lang_parse[4]);
                    // set default to 1 for any without q factor
                    foreach ($acceptLangs as $lang => $q) {
                        if ($q === '') $acceptLangs[$lang] = 1;
                    }

                    // sort list based on value
                    arsort($acceptLangs, SORT_NUMERIC);
                    foreach ($acceptLangs as $lang => $q) {
                        $primary = explode('-', $lang);
                        $primary = array_shift($primary);
                        if (in_array($lang, $availableLangs)) {
                            $language = $lang;
                            break;
                        } else if (in_array($primary, $availableLangs)) {
                            $language = $primary;
                            break;
                        }
                    }
                }
            }
        }
        if (!empty($this->install) && !empty($this->install->settings) && is_object($this->install->settings)) {
            $language = $this->install->settings->get('language', $language);
        }
        return $language;
    }

    /**
     * Get a list of available languages.
     *
     * @return array An array of available languages
     */
    public function getLanguageList() {
        $path = dirname(__DIR__).'/lang/';
        $languages = array();
        /** @var DirectoryIterator $file */
        foreach (new DirectoryIterator($path) as $file) {
            $basename = $file->getFilename();
            if (!in_array($basename, array('.', '..','.htaccess','.svn','.git')) && $file->isDir()) {
                if (file_exists($file->getPathname().'/default.inc.php')) {
                    $languages[] = $basename;
                }
            }
        }
        sort($languages);
        return $languages;
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
