<?php
/**
 * Remove a user setting and its lexicon strings
 *
 * @param integer $user The user associated to the setting
 * @param string $key The setting key
 *
 * @package modx
 * @subpackage processors.security.user.setting
 */
if (!$modx->hasPermission('settings')) return $modx->error->failure($modx->lexicon('permission_denied'));
$modx->lexicon->load('setting');

if (!isset($scriptProperties['key'],$scriptProperties['user'])) return $modx->error->failure($modx->lexicon('setting_err_ns'));
$setting = $modx->getObject('modUserSetting',array(
    'key' => $scriptProperties['key'],
    'user' => $scriptProperties['user'],
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