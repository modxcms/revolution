<?php

namespace MODX\Xmlrss;

use MODX\MODX;

/**
 * RSS Parser for MODX, implementing MagpieRSS
 *
 * @package modx
 * @subpackage xmlrss
 */
class modRSSParser
{
    /** @var MODX */
    public $modx;


    /**
     * @param MODX &$modx A reference to the modx object.
     * @param array $config A configuration array of properties
     */
    function __construct(&$modx, array $config = [])
    {
        $this->modx =& $modx;
        if (!defined('MAGPIE_CACHE_DIR')) {
            define('MAGPIE_CACHE_DIR', $this->modx->getOption('core_path') . 'cache/rss/');
        }
        if (!defined('MAGPIE_USER_AGENT')) {
            $this->modx->getVersionData();
            define('MAGPIE_USER_AGENT', 'RevoRSS/' . $this->modx->version['full_version']);
        }
    }


    /**
     * Parses and interprets an RSS or Atom URL
     *
     * @param string $url The URL of the RSS/Atom feed.
     *
     * @return array|string The parsed RSS/Atom feed. $rss->items gets you the items parsed.
     */
    public function parse($url)
    {
        $feed = new \SimplePie();
//        $feed->set_cache_location(MODX_CORE_PATH . 'cache/');
        $feed->enable_cache(false);
        $feed->set_feed_url($url);
        $feed->init();
        $feed->handle_content_type();

        return $feed->error()
            ? $feed->error()
            : $feed->get_items();
    }
}