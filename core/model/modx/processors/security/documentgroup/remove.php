<?php
/**
 * Remove a resource group
 *
 * @param integer $id The ID of the resource group
 *
 * @package modx
 * @subpackage processors.security.documentgroup
 */
$modx->lexicon->load('user','access');

if (!$modx->hasPermission('access_permissions')) return $modx->error->failure($modx->lexicon('permission_denied'));

if (!isset($_POST['id'])) return $modx->error->failure($modx->lexicon('resource_group_err_ns'));

$dg = $modx->getObject('modResourceGroup',$_POST['id']);
if ($dg == null) return $modx->error->failure($modx->lexicon('resource_group_err_nf'));

if (!$dg->remove()) return $modx->error->failure($modx->lexicon('resource_group_err_remove'));

/* log manager action */
$modx->logManagerAction('delete_resource_group','modResourceGroup',$dg->get('id'));

return $modx->error->success();