<?php
/**
 * Update a user group setting
 *
 * @param integer $fk The group ID to create the setting for
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

/* get setting */
if (empty($scriptProperties['fk'])) return $modx->error->failure($modx->lexicon('group_err_ns'));
if (empty($scriptProperties['key'])) return $modx->error->failure($modx->lexicon('setting_err_ns'));
$setting = $modx->getObject('modUserGroupSetting',array(
    'key' => $scriptProperties['key'],
    'group' => $scriptProperties['fk'],
));
if (empty($setting)) return $modx->error->failure($modx->lexicon('setting_err_nf'));

/* value parsing */
if ($scriptProperties['xtype'] == 'combo-boolean' && !is_numeric($scriptProperties['value'])) {
    if (in_array($scriptProperties['value'], array('yes', 'Yes', $modx->lexicon('yes'), 'true', 'True'))) {
        $scriptProperties['value'] = 1;
    } else $scriptProperties['value'] = 0;
}

$setting->set('key',$scriptProperties['key']);
$setting->set('group',$scriptProperties['fk']);
$setting->fromArray($scriptProperties);

/* save setting */
if ($setting->save() == false) {
    return $modx->error->failure($modx->lexicon('setting_err_save'));
}

$modx->reloadConfig();

return $modx->error->success();
