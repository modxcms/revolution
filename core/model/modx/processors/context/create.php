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

/* prevent duplicate contexts */
$alreadyExists = $modx->getObject('modContext',$scriptProperties['key']);
if ($alreadyExists != null) $modx->error->addField('key',$modx->lexicon('context_err_ae'));

/* if any errors, return */
if ($modx->error->hasError()) {
    return $modx->error->failure();
}

/* create context */
$context= $modx->newObject('modContext');
$context->fromArray($scriptProperties, '', true);
if ($context->save() == false) {
    $modx->error->checkValidation($context);
    return $modx->error->failure($modx->lexicon('context_err_create'));
}

/* log manager action */
$modx->logManagerAction('context_create','modContext',$context->get('id'));

return $modx->error->success('', $context);