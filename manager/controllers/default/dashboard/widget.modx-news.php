<?php
/**
 * @package modx
 * @subpackage dashboard
 */
/**
 * @package modx
 * @subpackage dashboard
 */
class modDashboardWidgetNewsFeed extends modDashboardWidgetInterface {
    /**
     * @return string
     */
    public function render() {
        $enabled = $this->modx->getOption('feed_modx_news_enabled',null,true);
        if (!$enabled) {
            return '';
        }

        return '<div id="modx-news-feed-container" class="feed-loading" data-feed="news"><i class="icon icon-refresh icon-spin" aria-hidden="true"></i> ' . $this->modx->lexicon('loading') . '</div>';
    }
}
return 'modDashboardWidgetNewsFeed';
