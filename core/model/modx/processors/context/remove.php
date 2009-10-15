<?php
/**
 * Removes a context
 *
 * @param string $key The key of the context. Cannot be mgr or web.
 *
 * @package modx
 * @subpackage processors.context
 */
$modx->lexicon->load('context');

if (!$modx->hasPermission('delete_context')) return $modx->error->failure($modx->lexicon('permission_denied'));

/* get context */
if (empty($_POST['key'])) return $modx->error->failure($modx->lexicon('context_err_ns'));
$context= $modx->getObject('modContext', $_POST['key']);
if ($context == null) return $modx->error->failure($modx->lexicon('context_err_nf'));

/* prevent removing of mgr/web contexts */
if ($context->get('key') == 'web' || $context->get('key') == 'mgr') {
    return $modx->error->failure($modx->lexicon('permission_denied'));
}

/* remove context */
if ($context->remove() == false) {
    return $modx->error->failure($modx->lexicon('context_err_remove'));
}

/* log manager action */
$modx->logManagerAction('context_delete','modContext',$context->get('id'));

/* invoke event */
$modx->invokeEvent('OnContextDelete',array(
    'context' => &$context,
));

/* clear cache */
$cacheManager= $modx->getCacheManager();
$cacheManager->clearCache();

return $modx->error->success();