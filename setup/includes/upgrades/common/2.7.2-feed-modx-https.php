<?php
/**
 * Update the RSS feed URLs to HTTPS
 */

/** @var modSystemSetting $feed_modx_security */
$feed_modx_security = $modx->getObject('modSystemSetting', array(
    'key' => 'feed_modx_security',
    'value' => 'http://forums.modx.com/board.xml?board=294',
));
if ($feed_modx_security) {
    $feed_modx_security->set('value', 'https://forums.modx.com/board.xml?board=294');
    $feed_modx_security->save();
}

/** @var modSystemSetting $feed_modx_news */
$feed_modx_news = $modx->getObject('modSystemSetting', array(
    'key' => 'feed_modx_news',
    'value' => 'http://feeds.feedburner.com/modx-announce',
));
if ($feed_modx_news) {
    $feed_modx_news->set('value', 'https://feeds.feedburner.com/modx-announce');
    $feed_modx_news->save();
}
