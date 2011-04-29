<?php
/**
 * Creates a namespace
 *
 * @param string $name The name of the namespace
 * @param string $path (optional) The path of the namespace
 *
 * @package modx
 * @subpackage processors.workspace.namespace
 */
if (!$modx->hasPermission('namespaces')) return $modx->error->failure($modx->lexicon('permission_denied'));
$modx->lexicon->load('workspace','lexicon');

/* validate name */
if (empty($scriptProperties['name'])) {
    return $modx->error->failure($modx->lexicon('namespace_err_ns_name'));
}

/* create and save namespace */
$namespace = $modx->newObject('modNamespace');
$namespace->fromArray($scriptProperties,'',true,true);
$namespace->set('path',trim($scriptProperties['path']));
if ($namespace->save() === false) {
    return $modx->error->failure($modx->lexicon('namespace_err_create'));
}

/* log manager action */
$modx->logManagerAction('namespace_create','modNamespace',$namespace->get('name'));

return $modx->error->success('',$namespace);