<?php
/**
 * Updates a namespace from a grid
 *
 * @param string $name A valid name
 * @param string $path An absolute path
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

$namespace->set('path',$_POST['path']);

if ($namespace->save() === false) {
    return $modx->error->failure($modx->lexicon('namespace_err_save'));
}

/* log manager action */
$modx->logManagerAction('namespace_update','modNamespace',$namespace->get('id'));

return $modx->error->success();