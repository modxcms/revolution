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

/* get namespace */
if (empty($scriptProperties['name'])) return $modx->error->failure($modx->lexicon('namespace_err_ns'));
$namespace = $modx->getObject('modNamespace',$scriptProperties['name']);
if ($namespace == null) return $modx->error->failure($modx->lexicon('namespace_err_nf'));

/* set and save */
$namespace->fromArray($scriptProperties);
if ($namespace->save() === false) {
    return $modx->error->failure($modx->lexicon('namespace_err_save'));
}

/* log manager action */
$modx->logManagerAction('namespace_update','modNamespace',$namespace->get('id'));

return $modx->error->success();