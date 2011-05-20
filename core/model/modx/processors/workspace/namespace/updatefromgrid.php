<?php
/**
 * Updates a namespace from a grid
 *
 * @package modx
 * @subpackage processors.workspace.namespace
 */
$modx->lexicon->load('workspace','lexicon');

if (!$modx->hasPermission('namespaces')) return $modx->error->failure($modx->lexicon('permission_denied'));

$_DATA = $modx->fromJSON($scriptProperties['data']);

/* get namespace */
if (empty($_DATA['name'])) return $modx->error->failure($modx->lexicon('namespace_err_ns'));
$namespace = $modx->getObject('modNamespace',$_DATA['name']);
if ($namespace == null) return $modx->error->failure($modx->lexicon('namespace_err_nf'));

/* set and save namespace */
$namespace->fromArray($_DATA);
$namespace->set('path',trim($_DATA['path']));
if ($namespace->save() === false) {
    return $modx->error->failure($modx->lexicon('namespace_err_save'));
}

/* log manager action */
$modx->logManagerAction('namespace_update','modNamespace',$namespace->get('name'));

return $modx->error->success();