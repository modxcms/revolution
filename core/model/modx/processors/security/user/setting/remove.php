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
$modx->lexicon->load('setting');

if (!isset($_POST['key'],$_POST['user'])) return $modx->error->failure($modx->lexicon('setting_err_ns'));
$setting = $modx->getObject('modUserSetting',array(
    'key' => $_POST['key'],
    'user' => $_POST['user'],
));
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


if ($setting->remove() == null) {
    return $modx->error->failure($modx->lexicon('setting_err_remove'));
}

$modx->reloadConfig();

return $modx->error->success();