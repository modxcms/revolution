<?php
/**
 * Update the RSS security feed to be Revo specific
 */

/** @var modSystemSetting $feed_modx_security */
$feed_modx_security = $modx->getObject('modSystemSetting', array('key' => 'feed_modx_security'));
if ($feed_modx_security) {
    $feed_modx_security->set('value', 'http://forums.modx.com/board.xml?board=294');
    $feed_modx_security->save();
}
