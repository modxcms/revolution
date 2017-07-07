<?php
/**
 * @package modx
 * @subpackage dashboard
 */
/**
 * @package modx
 * @subpackage dashboard
 */
class modDashboardWidgetSecurityFeed extends modDashboardWidgetInterface {
    /**
     * @var modRSSParser $rss
     */
    public $rss;

    public function render() {
        $o = array();
        $url = $this->modx->getOption('feed_modx_security');
        $newsEnabled = $this->modx->getOption('feed_modx_security_enabled',null,true);
        if (!empty($url) && !empty($newsEnabled)) {
            $feedHost = parse_url($url, PHP_URL_HOST);
            if ($feedHost && function_exists('checkdnsrr') && !checkdnsrr($feedHost, 'A')) {
                return '';
            }
            $this->modx->loadClass('xmlrss.modRSSParser','',false,true);
            $this->rss = new modRSSParser($this->modx);

            $rss = $this->rss->parse($url);
            if (is_object($rss)) {
                foreach (array_keys($rss->items) as $key) {
                    $item= &$rss->items[$key];
                    $item['pubdate'] = strftime('%c',$item['date_timestamp']);
                    $o[] = $this->getFileChunk('dashboard/rssitem.tpl',$item);
                }
            }
        }
        return implode("\n",$o);
    }
}
return 'modDashboardWidgetSecurityFeed';
