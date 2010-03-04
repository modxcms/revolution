<?php
/**
 * Update a context from a grid. Passed as JSON data.
 *
 * @param string $key The key of the context
 *
 * @package modx
 * @subpackage processors.context
 */
if (!$modx->hasPermission('edit_context')) return $modx->error->failure($modx->lexicon('permission_denied'));
$modx->lexicon->load('context');

if (empty($scriptProperties['data'])) return $modx->error->failure();
$_DATA = $modx->fromJSON($scriptProperties['data']);

/* get context */
if (empty($_DATA['key'])) return $modx->error->failure($modx->lexicon('context_err_ns'));
$context= $modx->getObject('modContext', $_DATA['key']);
if ($context == null) return $modx->error->failure($modx->lexicon('context_err_nf'));

/* set and save context */
$context->fromArray($_DATA);
if ($context->save() == false) {
    $modx->error->checkValidation($context);
    return $modx->error->failure($modx->lexicon('context_err_save'));
}

/* run event */
$modx->invokeEvent('OnContextUpdate',array(
    'context' => &$context,
));


/* log manager action */
$modx->logManagerAction('context_update','modContext',$context->get('id'));

return $modx->error->success('', $context);