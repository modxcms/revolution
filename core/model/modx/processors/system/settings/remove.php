<?php
/**
 * Remove a system setting
 *
 * @param string $key The key of the setting
 *
 * @package modx
 * @subpackage processors.system.settings
 */
$modx->lexicon->load('setting');

if (!$modx->hasPermission('settings')) return $modx->error->failure($modx->lexicon('permission_denied'));

if (!isset($_POST['key'])) return $modx->error->failure($modx->lexicon('setting_err_ns'));
$setting = $modx->getObject('modSystemSetting',$_POST['key']);
if ($setting == null) return $modx->error->failure($modx->lexicon('setting_err_nf'));

/* remove relative lexicon strings */
$entry = $modx->getObject('modLexiconEntry',array(
    'namespace' => $setting->get('namespace'),
    'name' => 'setting_'.$setting->get('key'),
));
if ($entry != null) $entry->remove();

$description = $modx->getObject('modLexiconEntry',array(
    'namespace' => $setting->get('namespace'),
    'name' => 'setting_'.$setting->get('key').'_desc',
));
if ($description != null) $description->remove();

if ($setting->remove() == false) {
    return $modx->error->failure($modx->lexicon('setting_err_remove'));
}

$modx->reloadConfig();

return $modx->error->success();