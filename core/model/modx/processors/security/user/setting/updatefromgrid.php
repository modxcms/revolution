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
if (!$modx->hasPermission(array('save_user' => true, 'settings' => true))) {
    return $modx->error->failure($modx->lexicon('permission_denied'));
}
$modx->lexicon->load('setting');

$_DATA = $modx->fromJSON($scriptProperties['data']);

/* get setting */
if (empty($_DATA['user'])) return $modx->error->failure($modx->lexicon('user_err_ns'));
if (empty($_DATA['key'])) return $modx->error->failure($modx->lexicon('setting_err_ns'));
$setting = $modx->getObject('modUserSetting',array(
    'key' => $_DATA['key'],
    'user' => $_DATA['user'],
));
if (empty($setting)) return $modx->error->failure($modx->lexicon('setting_err_nf'));

$setting->set('key',$_DATA['key']);
$setting->fromArray($_DATA);

/* save setting */
if ($setting->save() == false) {
    return $modx->error->failure($modx->lexicon('setting_err_save'));
}

$modx->reloadConfig();

return $modx->error->success();