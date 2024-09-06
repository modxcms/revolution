<?php

/*
 * This file is part of MODX Revolution.
 *
 * Copyright (c) MODX, LLC. All Rights Reserved.
 *
 * For complete copyright and license information, see the COPYRIGHT and LICENSE
 * files found in the top-level directory of this distribution.
 */

namespace MODX\Revolution\Processors\System\Dashboard\Widget;

use MODX\Revolution\Formatter\modManagerDateFormatter;
use MODX\Revolution\modChunk;
use MODX\Revolution\modX;
use MODX\Revolution\Processors\Processor;
use SimplePie_Item;
use xPDO\xPDO;

/**
 * Class modDashboardWidgetFeedProcessor
 * Used to load the news and security feeds on the dashboard over AJAX. The processed feed content
 * (i.e. HTML) is returned in object->html.
 * @package MODX\Revolution\Processors\System\Dashboard\Widget
 */
class Feed extends Processor
{
    private modManagerDateFormatter $formatter;

    public function initialize()
    {
        $this->formatter = $this->modx->services->get(modManagerDateFormatter::class);
        return parent::initialize();
    }

    /**
     * @return array|mixed|string
     */
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

        return $this->loadFeed($url);
    }

    /**
     * @param $url
     * @return array|string
     */
    public function loadFeed($url)
    {
        $feed = new \SimplePie();

        $cachePath = $this->modx->getOption(xPDO::OPT_CACHE_PATH) . 'rss/';
        if (!is_dir($cachePath)) {
            $this->modx->cacheManager->writeTree($cachePath);
        }

        $proxy_config = $this->buildProxyOptions();
        if (!empty($proxy_config)) {
            $feed->set_curl_options($proxy_config);
        }

        $feed->set_cache_location($cachePath);
        $feed->set_useragent($this->modx->getVersionData()['full_version']);
        $feed->set_feed_url($url);
        $feed->init();
        $feed->handle_content_type();

        if ($feed->error()) {
            $this->modx->log(
                modX::LOG_LEVEL_ERROR,
                is_array($feed->error()) ? print_r($feed->error(), true) : $feed->error()
            );
        }

        $output = [];
        /** @var SimplePie_Item $item */
        foreach ($feed->get_items() as $item) {
            $date = $item->get_date('Y-m-d H:i:s');
            $output[] = $this->getFileChunk('dashboard/rssitem.tpl', [
                'title' => $item->get_title(),
                'description' => $item->get_description(),
                'link' => $item->get_permalink(),
                'pubdate' => $this->formatter->formatDateTime($date)
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
            $file = $this->modx->getOption('manager_path') . 'templates/' . $this->modx->getOption('manager_theme', null, 'default') . '/' . $tpl;
        }
        if (!file_exists($file)) {
            $file = $this->modx->getOption('manager_path') . 'templates/default/' . $tpl;
        }
        if (file_exists($file)) {
            /** @var modChunk $chunk */
            $chunk = $this->modx->newObject(modChunk::class);
            $chunk->setCacheable(false);
            $tplContent = file_get_contents($file);
            $chunk->setContent($tplContent);
            $output = $chunk->process($placeholders);
        }

        return $output;
    }

    /**
     * Build configuration for the SimplePie client based on configuration settings.
     *
     * @return array The proxy configuration.
     */
    private function buildProxyOptions()
    {
        $config = [];
        $proxyHost = $this->modx->getOption('proxy_host', null, '');
        if (!empty($proxyHost)) {
            $config['CURLOPT_PROXY'] = $proxyHost;
            $proxyPort = $this->modx->getOption('proxy_port', null, '');
            if (!empty($proxyPort)) {
                $config['CURLOPT_PROXY'] .= ':' . $proxyPort;
            }
            $proxyUsername = $this->modx->getOption('proxy_username', null, '');
            if (!empty($proxyUsername)) {
                $proxyPassword = $this->modx->getOption('proxy_password', null, '');
                $config['CURLOPT_PROXYUSERPWD'] = $proxyUsername . ':' . $proxyPassword;
            }
        }
        return $config;
    }
}
