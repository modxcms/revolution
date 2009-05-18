<?php
/**
 * Create a resource group
 *
 * @param string $name The name of the new resource group
 *
 * @package modx
 * @subpackage processors.security.documentgroup
 */
$modx->lexicon->load('access');
if (!$modx->hasPermission('access_permissions')) return $modx->error->failure($modx->lexicon('permission_denied'));

if (!isset($_POST['name'])) $_POST['name'] = $modx->lexicon('resource_group_untitled');

$dg = $modx->getObject('modResourceGroup',array('name' => $_POST['name']));
if ($dg != null) return $modx->error->failure($modx->lexicon('resource_group_err_ae'));

$dg = $modx->newObject('modResourceGroup');
$dg->set('name',$_POST['name']);
if ($dg->save() == false) {
    return $modx->error->failure($modx->lexicon('resource_group_err_create'));
}

/* log manager action */
$modx->logManagerAction('new_resource_group','modResourceGroup',$dg->get('id'));

return $modx->error->success();