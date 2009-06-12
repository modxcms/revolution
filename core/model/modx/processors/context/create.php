<?php
/**
 * Creates a context
 *
 * @param string $key The key of the context
 *
 * @package modx
 * @subpackage processors.context
 */
$modx->lexicon->load('context');

if (!$modx->hasPermission('new_context')) return $modx->error->failure($modx->lexicon('permission_denied'));

/* validate pk */
if (empty($_POST['key'])) {
    return $modx->error->failure($modx->lexicon('context_err_ns'));
}

/* dont allow contexts with _ in name */
$_POST['key'] = str_replace('_','',$_POST['key']);

/* create context */
$context= $modx->newObject('modContext');
$context->fromArray($_POST, '', true);
if ($context->save() == false) {
    return $modx->error->failure($modx->lexicon('context_err_create'));
}

/* log manager action */
$modx->logManagerAction('context_create','modContext',$context->get('id'));

return $modx->error->success('', $context);