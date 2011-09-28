<?php
/**
 * modRSSParser
 *
 * @package modx
 * @subpackage xmlrss
 */
/**
 * RSS Parser for MODX, implementing MagpieRSS
 *
 * @package modx
 * @subpackage xmlrss
 */
class modRSSParser {
    /**
     * Constructor for modRSSParser
     *
     * @param modX &$modx A reference to the modx object.
     * @param array $config A configuration array of properties
     */
    function __construct(&$modx,array $config = array()) {
        $this->modx =& $modx;
        if (!defined('MAGPIE_CACHE_DIR')) {
            define('MAGPIE_CACHE_DIR',$this->modx->getOption('core_path').'cache/rss/');
        }
        if (!defined('MAGPIE_USER_AGENT')) {
            $this->modx->getVersionData();
            define('MAGPIE_USER_AGENT','RevoRSS/'.$this->modx->version['full_version']);
        }
        $this->modx->loadClass('xmlrss.rssfetch','',false,true);
    }

    /**
     * Parses and interprets an RSS or Atom URL
     *
     * @param string $url The URL of the RSS/Atom feed.
     * @return array The parsed RSS/Atom feed. $rss->items gets you the items parsed.
     */
    public function parse($url) {
        $rss = call_user_func('fetch_rss',$url);
        return $rss;
    }
}