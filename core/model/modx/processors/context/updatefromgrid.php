<?php
/**
 * Update a context from a grid. Passed as JSON data.
 *
 * @param string $key The key of the context
 *
 * @package modx
 * @subpackage processors.context
 */
$modx->lexicon->load('context');

if (!$modx->hasPermission('edit_context')) return $modx->error->failure($modx->lexicon('permission_denied'));

$_DATA = $modx->fromJSON($_POST['data']);

$context= $modx->getObject('modContext', $_DATA['key']);
if ($context == null) return $modx->error->failure($modx->lexicon('context_err_nf'));

$context->fromArray($_DATA);

if ($context->save() == false) {
    return $modx->error->failure($modx->lexicon('context_err_save'));
}

/* log manager action */
$modx->logManagerAction('context_update','modContext',$context->get('id'));

return $modx->error->success('', $context);