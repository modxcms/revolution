<?php
/**
 * Creates a context
 *
 * @param string $key The key of the context
 *
 * @package modx
 * @subpackage processors.context
 */
if (!$modx->hasPermission('new_context')) return $modx->error->failure($modx->lexicon('permission_denied'));
$modx->lexicon->load('context');

/* validate pk */
if (empty($_POST['key'])) $modx->error->addError('key',$modx->lexicon('context_err_ns'));

/* dont allow contexts with _ in name */
$_POST['key'] = str_replace('_','',$_POST['key']);

/* check to see if key is empty after strip */
if (empty($_POST['key'])) $modx->error->addError('key',$modx->lexicon('context_err_ns'));

/* prevent duplicate contexts */
$alreadyExists = $modx->getObject('modContext',$_POST['key']);
if ($alreadyExists != null) $modx->error->addField('key',$modx->lexicon('context_err_ae'));

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

/* run event */
$modx->invokeEvent('OnContextCreate',array(
    'context' => &$context,
));

/* log manager action */
$modx->logManagerAction('context_create','modContext',$context->get('id'));

return $modx->error->success('', $context);