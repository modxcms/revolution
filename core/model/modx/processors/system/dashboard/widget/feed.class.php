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
    public function process()
    {
        $feed = $this->getProperty('feed', 'news');
        if (!in_array($feed, ['news', 'security'], true)) {
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
        $feed = new \SimplePie();

        $feed->set_cache_location($this->modx->getOption('core_path') . 'cache/rss/');
        $feed->set_useragent($this->modx->getVersionData()['full_version']);
        $feed->set_feed_url($url);
        $feed->init();
        $feed->handle_content_type();

        if ($feed->error()) {
            $this->modx->log(modX::LOG_LEVEL_ERROR, is_array($feed->error()) ? print_r($feed->error(), true) : $feed->error());
        }

        $output = [];
        /** @var SimplePie_Item $item */
        foreach ($feed->get_items() as $item) {
            $output[] = $this->getFileChunk('dashboard/rssitem.tpl', [
                'title' => $item->get_title(),
                'description' => $item->get_description(),
                'link' => $item->get_permalink(),
                'pubdate' => $item->get_local_date()
            ]);
        }

        return $this->success('', ['html' => implode(PHP_EOL, $output)]);
    }

    /**
     * @param string $tpl
     * @param array $placeholders
     * @return string
     */
    public function getFileChunk($tpl, array $placeholders = [])
    {
        $output = '';
        $file = $tpl;

        if (!file_exists($file)) {
            $file = $this->modx->getOption('manager_path')
                . 'templates/'
                . $this->modx->getOption('manager_theme', null, 'default')
                . '/' . $tpl;
        }
        if (!file_exists($file)) {
            $file = $this->modx->getOption('manager_path') . 'templates/default/' . $tpl;
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
