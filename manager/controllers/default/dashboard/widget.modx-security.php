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
    public function render() {
        $enabled = $this->modx->getOption('feed_modx_security_enabled',null,true);
        if (!$enabled) {
            return '';
        }

        return '<div id="modx-security-feed-container" class="feed-loading" data-feed="news"><i class="icon icon-refresh icon-spin" aria-hidden="true"></i> ' . $this->modx->lexicon('loading') . '</div>';
    }
}
return 'modDashboardWidgetSecurityFeed';
