<?php
/**
 * Updates a user setting
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

/* get setting */
if (empty($scriptProperties['fk'])) return $modx->error->failure($modx->lexicon('user_err_ns'));
if (empty($scriptProperties['key'])) return $modx->error->failure($modx->lexicon('setting_err_ns'));
$setting = $modx->getObject('modUserSetting',array(
    'key' => $scriptProperties['key'],
    'user' => $scriptProperties['fk'],
));
if (empty($setting)) return $modx->error->failure($modx->lexicon('setting_err_nf'));
$setting->remove();

/* do this this way b/c of error with xpdo and compound PK values */
$setting = $modx->newObject('modUserSetting');
$setting->set('key',$scriptProperties['key']);
$setting->set('user',$scriptProperties['fk']);
$setting->fromArray($scriptProperties);

/* save setting */
if ($setting->save() == false) {
    return $modx->error->failure($modx->lexicon('setting_err_save'));
}

$modx->reloadConfig();

return $modx->error->success();