<?php
/**
 * Update a user group setting from a grid
 *
 * @param integer $group The group to create the setting for
 * @param string $key The setting key
 * @param string $value The setting value
 *
 * @package modx
 * @subpackage processors.security.group.setting
 */
if (!$modx->hasPermission(array('save_group' => true, 'settings' => true))) {
    return $modx->error->failure($modx->lexicon('permission_denied'));
}
$modx->lexicon->load('setting');

$_DATA = $modx->fromJSON($scriptProperties['data']);

/* get setting */
if (empty($_DATA['group'])) return $modx->error->failure($modx->lexicon('group_err_ns'));
if (empty($_DATA['key'])) return $modx->error->failure($modx->lexicon('setting_err_ns'));
$setting = $modx->getObject('modUserGroupSetting',array(
    'key' => $_DATA['key'],
    'group' => $_DATA['group'],
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
