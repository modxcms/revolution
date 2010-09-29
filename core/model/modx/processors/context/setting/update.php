<?php
/**
 * Updates a context setting
 *
 * @param string $context_key The key of the context
 * @param string $key The key of the setting
 * @param string $value The value of the setting.
 *
 * @package modx
 * @subpackage processors.context.setting
 */
if (!$modx->hasPermission('settings')) return $modx->error->failure($modx->lexicon('permission_denied'));
$modx->lexicon->load('setting');

$context = $modx->getObject('modContext', $scriptProperties['context_key']);
if ($context == null) return $modx->error->failure($modx->lexicon('setting_err_nf'));

if (!$context->checkPolicy('save')) return $modx->error->failure($modx->lexicon('permission_denied'));

$setting = $modx->getObject('modContextSetting',array(
    'key' => $scriptProperties['key'],
    'context_key' => $scriptProperties['context_key'],
));
/* due to a bug in xPDO, need to remove first */
if ($setting) { $setting->remove(); }
$setting = $modx->newObject('modContextSetting');
$setting->set('key',$scriptProperties['key']);
$setting->set('context_key',$scriptProperties['context_key']);
$setting->set('value',$scriptProperties['value']);


if ($setting->save() == false) {
    return $modx->error->failure($modx->lexicon('setting_err_save'));
}

$modx->reloadConfig();

return $modx->error->success();