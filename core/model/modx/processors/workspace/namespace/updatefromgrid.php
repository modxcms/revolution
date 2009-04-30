<?php
/**
 * Updates a namespace from a grid
 *
 * @package modx
 * @subpackage processors.workspace.namespace
 */
$modx->lexicon->load('workspace','lexicon');

if (!$modx->hasPermission('namespaces')) return $modx->error->failure($modx->lexicon('permission_denied'));

$_DATA = $modx->fromJSON($_POST['data']);

if (!isset($_DATA['name']) || $_DATA['name'] == '') {
    return $modx->error->failure($modx->lexicon('namespace_err_ns'));
}
$namespace = $modx->getObject('modNamespace',$_DATA['name']);
if ($namespace == null) return $modx->error->failure($modx->lexicon('namespace_err_nf'));

$namespace->set('path',$_DATA['path']);

if ($namespace->save() === false) {
    return $modx->error->failure($modx->lexicon('namespace_err_save'));
}

/* log manager action */
$modx->logManagerAction('namespace_update','modNamespace',$namespace->get('id'));

return $modx->error->success();