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
if (!isset($_POST['key'])) $modx->error->addError('key',$modx->lexicon('context_err_ns'));

/* dont allow contexts with _ in name */
$_POST['key'] = str_replace('_','',$_POST['key']);

/* check to see if key is empty */
if (empty($_POST['key'])) $modx->error->addError('key',$modx->lexicon('context_err_ns'));

/* prevent duplicate contexts */
$ae = $modx->getObject('modContext',$_POST['key']);
if ($ae != null) $modx->error->addField('key',$modx->lexicon('context_err_ae'));

/* if any errors, return */
if ($modx->error->hasError()) {
    return $modx->error->failure();
}


/* create context */
$context= $modx->newObject('modContext');
$context->fromArray($_POST, '', true);
if ($context->save() == false) {
    return $modx->error->failure($modx->lexicon('context_err_create'));
}

/* log manager action */
$modx->logManagerAction('context_create','modContext',$context->get('id'));

return $modx->error->success('', $context);