<?php
/**
 * Specific upgrades for Revolution 2.2.0-pl
 *
 * @var modX $modx
 *
 * @package setup
 * @subpackage upgrades
 */

/** @var modSystemSetting $setting Upgrade principal targets to add Media Sources */
$setting = $modx->getObject('modSystemSetting',array('key' => 'principal_targets'));
if ($setting) {
    $values = $setting->get('value');
    $values = is_array($values) ? $values : explode(',',$values);
    if (!in_array('sources.modAccessMediaSource',$values)) {
        $values[] = 'sources.modAccessMediaSource';
        $setting->set('value',implode(',',$values));
        $setting->save();
    }
}