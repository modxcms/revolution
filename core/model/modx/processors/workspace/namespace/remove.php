<?php
/**
 * Removes a namespace.
 *
 * @param string $name The name of the namespace.
 *
 * @package modx
 * @subpackage processors.workspace.namespace
 */
$modx->lexicon->load('workspace','lexicon');

if (!$modx->hasPermission('namespaces')) return $modx->error->failure($modx->lexicon('permission_denied'));

if (!isset($_POST['name']) || $_POST['name'] == '') {
    return $modx->error->failure($modx->lexicon('namespace_err_ns'));
}
$namespace = $modx->getObject('modNamespace',$_POST['name']);
if ($namespace == null) return $modx->error->failure($modx->lexicon('namespace_err_nf'));

if ($namespace->remove() === false) {
    return $modx->error->failure($modx->lexicon('namespace_err_remove'));
}

/* log manager action */
$modx->logManagerAction('namespace_remove','modNamespace',$namespace->get('id'));

return $modx->error->success();