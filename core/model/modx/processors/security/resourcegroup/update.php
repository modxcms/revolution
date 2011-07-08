<?php
/**
 * Update a resource group
 *
 * @package modx
 * @subpackage processors.security.resourcegroup
 */
if (!$modx->hasPermission('access_permissions')) return $modx->error->failure($modx->lexicon('permission_denied'));
$modx->lexicon->load('user','access');

/* get resource group */
if (empty($scriptProperties['id'])) return $modx->error->failure($modx->lexicon('resource_group_err_ns'));
$resourceGroup = $modx->getObject('modResourceGroup',$scriptProperties['id']);
if ($resourceGroup == null) return $modx->error->failure($modx->lexicon('resource_group_err_nf'));

$resourceGroup->fromArray($scriptProperties);

/* remove resource group */
if ($resourceGroup->save() == false) {
    return $modx->error->failure($modx->lexicon('resource_group_err_save'));
}

/* log manager action */
$modx->logManagerAction('update_resource_group','modResourceGroup',$resourceGroup->get('id'));

return $modx->error->success('',$resourceGroup);