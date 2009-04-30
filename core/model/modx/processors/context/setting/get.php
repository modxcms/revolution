<?php
/**
 * Gets a context setting
 *
 * @param string $context_key The key of the context
 * @param string $key The key of the setting
 *
 * @package modx
 * @subpackage processors.context.setting
 */
$modx->lexicon->load('setting');

if (!$modx->hasPermission('settings')) return $modx->error->failure($modx->lexicon('permission_denied'));

if (!isset($_POST['key'],$_POST['context_key'])) return $modx->error->failure($modx->lexicon('setting_err_ns'));
if (!$context = $modx->getObject('modContext', $_POST['context_key'])) return $modx->error->failure($modx->lexicon('setting_err_nf'));

if (!$context->checkPolicy('view')) return $modx->error->failure($modx->lexicon('permission_denied'));

$setting = $modx->getObject('modContextSetting',array(
    'key' => $_POST['key'],
    'context_key' => $_POST['context_key'],
));
if ($setting == null) return $modx->error->failure($modx->lexicon('setting_err_nf'));

return $modx->error->success('',$setting);