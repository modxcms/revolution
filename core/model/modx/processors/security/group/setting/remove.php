<?php
/**
 * Remove a user group setting and its lexicon strings
 *
 * @param integer $group The group associated to the setting
 * @param string $key The setting key
 *
 * @package modx
 * @subpackage processors.security.group.setting
 */
if (!$modx->hasPermission('settings')) return $modx->error->failure($modx->lexicon('permission_denied'));
$modx->lexicon->load('setting');

if (!isset($scriptProperties['key'],$scriptProperties['group'])) return $modx->error->failure($modx->lexicon('setting_err_ns'));
$setting = $modx->getObject('modUserGroupSetting',array(
    'key' => $scriptProperties['key'],
    'group' => $scriptProperties['group'],
));
if ($setting == null) return $modx->error->failure($modx->lexicon('setting_err_nf'));

/* remove relative lexicon strings */
$entry = $modx->getObject('modLexiconEntry',array(
    'namespace' => $setting->get('namespace'),
    'name' => 'setting_'.$setting->get('key'),
));
if (!empty($entry) && $entry instanceof modLexiconEntry) $entry->remove();

$description = $modx->getObject('modLexiconEntry',array(
    'namespace' => $setting->get('namespace'),
    'name' => 'setting_'.$setting->get('key').'_desc',
));
if (!empty($description) && $description instanceof modLexiconEntry) $description->remove();

/* remove setting */
if ($setting->remove() == null) {
    return $modx->error->failure($modx->lexicon('setting_err_remove'));
}

$modx->reloadConfig();

return $modx->error->success();
