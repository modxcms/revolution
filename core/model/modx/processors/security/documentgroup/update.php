<?php
/**
 * Update a resource group
 *
 * @param integer $id The ID of the resource group
 *
 * @package modx
 * @subpackage processors.security.documentgroup
 */
if (!$modx->hasPermission('access_permissions')) return $modx->error->failure($modx->lexicon('permission_denied'));
$modx->lexicon->load('user','access');

/* get resource group */
if (empty($scriptProperties['id'])) return $modx->error->failure($modx->lexicon('resource_group_err_ns'));
$resourceGroup = $modx->getObject('modResourceGroup',$scriptProperties['id']);
if ($resourceGroup == null) return $modx->error->failure($modx->lexicon('resource_group_err_nf'));

/* make sure name is unique */
$alreadyExists = $modx->getObject('modResourceGroup',array(
    'name' => $scriptProperties['name'],
    'id:!=' => $resourceGroup->get('id'),
));
if ($alreadyExists) return $modx->error->failure($modx->lexicon('resource_group_err_ae'));

/* save resource group */
$resourceGroup->fromArray($scriptProperties);
if ($resourceGroup->save() == false) {
    return $modx->error->failure($modx->lexicon('resource_group_err_remove'));
}

/* log manager action */
$modx->logManagerAction('update_resource_group','modResourceGroup',$resourceGroup->get('id'));

return $modx->error->success('',$resourceGroup);