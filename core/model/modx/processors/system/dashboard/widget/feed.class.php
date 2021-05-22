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
 * Class modDashboardWidgetFeedProcessor
 *
 * Used to load the news and security feeds on the dashboard over AJAX. The processed feed content
 * (i.e. HTML) is returned in object->html.
 */
class modDashboardWidgetFeedProcessor extends modProcessor
{
    /**
     * @var modRSSParser
     */
    protected $rss;

    public function process()
    {
        $feed = $this->getProperty('feed', 'news');
        if (!in_array($feed, array('news', 'security'), true)) {
            return $this->failure('Invalid feed type');
        }

        $enabled = $this->modx->getOption('feed_modx_' . $feed . '_enabled', null, true);
        if (!$enabled) {
            return $this->failure();
        }

        $url = $this->modx->getOption('feed_modx_' . $feed);
        // Check if the domain is valid before attempting to load it
        $feedHost = parse_url($url, PHP_URL_HOST);
        if ($feedHost && function_exists('checkdnsrr') && !checkdnsrr($feedHost, 'A')) {
            return $this->failure();
        }

        $cacheKey = 'dashboard_feed/' . $feed;
        $fromCache = $this->modx->getCacheManager()->get($cacheKey);
        if ($fromCache) {
            return $fromCache;
        }

        $result = $this->loadFeed($url);
        $this->modx->getCacheManager()->set($cacheKey, $result, 3600);

        return $result;
    }

    public function loadFeed($url)
    {
        $this->rss = $this->modx->getService('rss', 'xmlrss.modRSSParser');

        $o = array();
        $rss = $this->rss->parse($url);
        if (is_object($rss)) {
            foreach (array_keys($rss->items) as $key) {
                $item= &$rss->items[$key];
                $item['pubdate'] = strftime('%c',$item['date_timestamp']);
                $o[] = $this->getFileChunk('dashboard/rssitem.tpl',$item);
            }
        }
        return $this->success('', array('html' => implode("\n",$o)));
    }

    /**
     * @param string $tpl
     * @param array $placeholders
     * @return string
     */
    public function getFileChunk($tpl,array $placeholders = array()) {
        $output = '';
        $file = $tpl;
        if (!file_exists($file)) {
            $file = $this->modx->getOption('manager_path').'templates/'.$this->modx->getOption('manager_theme',null,'default').'/'.$tpl;
        }
        if (!file_exists($file)) {
            $file = $this->modx->getOption('manager_path').'templates/default/'.$tpl;
        }
        if (file_exists($file)) {
            /** @var modChunk $chunk */
            $chunk = $this->modx->newObject('modChunk');
            $chunk->setCacheable(false);
            $tplContent = file_get_contents($file);
            $chunk->setContent($tplContent);
            $output = $chunk->process($placeholders);
        }
        return $output;
    }
}

return 'modDashboardWidgetFeedProcessor';
