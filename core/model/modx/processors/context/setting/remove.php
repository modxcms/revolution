<?php
/**
 * Removes a context setting.
 *
 * @param string $key The key of the setting
 * @param string $context_key The key of the context
 *
 * @package modx
 * @subpackage processors.context.setting
 */
if (!$modx->hasPermission('settings')) return $modx->error->failure($modx->lexicon('permission_denied'));
$modx->lexicon->load('setting');

if (!isset($scriptProperties['key'],$scriptProperties['context_key'])) return $modx->error->failure($modx->lexicon('setting_err_ns'));
if (!$context = $modx->getObject('modContext', $scriptProperties['context_key'])) return $modx->error->failure($modx->lexicon('setting_err_nf'));
if (!$context->checkPolicy('save')) return $modx->error->failure($modx->lexicon('permission_denied'));

$setting = $modx->getObject('modContextSetting',array(
    'key' => $scriptProperties['key'],
    'context_key' => $scriptProperties['context_key'],
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