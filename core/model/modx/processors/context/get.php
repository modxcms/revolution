<?php
/**
 * Grabs a context
 *
 * @param string $key The key of the context
 *
 * @package modx
 * @subpackage processors.context
 */
if (!$modx->hasPermission('view_context')) return $modx->error->failure($modx->lexicon('permission_denied'));
$modx->lexicon->load('context');

if (!isset($scriptProperties['key'])) {
    return $modx->error->failure($modx->lexicon('context_err_ns'));
}
$contextKey = urldecode($scriptProperties['key']);
$context = $modx->getObject('modContext',$contextKey);
if ($context == null) {
    return $modx->error->failure($modx->lexicon('context_err_nfs',array('key' => $scriptProperties['key'])));
}
if(!$context->checkPolicy('view')) return $modx->error->failure($modx->lexicon('permission_denied'));

return $modx->error->success('',$context);