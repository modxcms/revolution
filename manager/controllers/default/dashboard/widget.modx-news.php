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
     * @var modRSSParser $rss
     */
    public $rss;

    /**
     * @return string
     */
    public function render() {
        $this->modx->loadClass('xmlrss.modRSSParser','',false,true);
        $this->rss = new modRSSParser($this->modx);

        $o = array();
        $url = $this->modx->getOption('feed_modx_news');
        $newsEnabled = $this->modx->getOption('feed_modx_news_enabled',null,true);
        if (!empty($url) && !empty($newsEnabled)) {
            $rss = $this->rss->parse($url);
            foreach (array_keys($rss->items) as $key) {
                $item= &$rss->items[$key];
                $item['pubdate'] = strftime('%c',$item['date_timestamp']);
                $o[] = $this->getFileChunk('dashboard/rssitem.tpl',$item);
            }
        }
        return implode("\n",$o);
    }
}
return 'modDashboardWidgetNewsFeed';