<?php
/**
 * Updates a setting from a grid
 *
 * @param integer $user The user to create the setting for
 * @param string $key The setting key
 * @param string $value The setting value
 *
 * @package modx
 * @subpackage processors.security.user.setting
 */
if (!$modx->hasPermission('save_user')) return $modx->error->failure($modx->lexicon('permission_denied'));
$modx->lexicon->load('setting');

$_DATA = $modx->fromJSON($_POST['data']);

/* get setting */
$setting = $modx->getObject('modUserSetting',array(
    'key' => $_DATA['key'],
    'user' => $_DATA['user'],
));

$setting->set('value',$_DATA['value']);

/* save setting */
if ($setting->save() == false) {
    return $modx->error->failure($modx->lexicon('setting_err_save'));
}

$modx->reloadConfig();

return $modx->error->success();