<?php

namespace MODX\Processors\System\Dashboard\Widget;

use MODX\modChunk;
use MODX\Processors\modProcessor;
use MODX\Xmlrss\modRSSParser;
use SimplePie_Item;

/**
 * Class modDashboardWidgetFeedProcessor
 *
 * Used to load the news and security feeds on the dashboard over AJAX. The processed feed content
 * (i.e. HTML) is returned in object->html.
 */
class Feed extends modProcessor
{
    /**
     * @var modRSSParser
     */
    protected $rss;


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


    public function loadFeed($url)
    {
        $rss = new modRSSParser($this->modx);
        $res = $rss->parse($url);
        if (is_array($res)) {
            $o = [];
            /** @var SimplePie_Item $item */
            foreach ($res as $item) {
                $o[] = $this->getFileChunk('dashboard/rssitem.tpl', [
                    'link' => $item->get_link(),
                    'title' => $item->get_title(),
                    'description' => $item->get_description(),
                    'pubdate' => $item->get_local_date('%c'),
                ]);
            }

            return $this->success('', ['html' => implode("\n", $o)]);
        }

        return $this->failure($res);
    }


    /**
     * @param string $tpl
     * @param array $placeholders
     *
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
            $chunk = $this->modx->newObject('modChunk');
            $chunk->setCacheable(false);
            $tplContent = file_get_contents($file);
            $chunk->setContent($tplContent);
            $output = $chunk->process($placeholders);
        }

        return $output;
    }
}