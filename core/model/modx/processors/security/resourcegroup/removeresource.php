<?php
/**
 * Remove a resource-resourcegroup pairing
 *
 * @param integer $resourceGroup The ID of the resource group
 * @param integer $resource The ID of the resource
 *
 * @var modX $modx
 * @var array $scriptProperties
 * @var modProcessor $this
 *
 * @package modx
 * @subpackage processors.security.resourcegroup
 */
if (!$modx->hasPermission('access_permissions')) return $modx->error->failure($modx->lexicon('permission_denied'));
$modx->lexicon->load('access','resource');

/* @var modResource $resource */
if (empty($scriptProperties['resource'])) return $modx->error->failure($modx->lexicon('resource_err_ns'));
$resource = $modx->getObject('modResource',$scriptProperties['resource']);
if ($resource == null) return $modx->error->failure($modx->lexicon('resource_err_nfs',array('id' => $scriptProperties['resource'])));

/* @var modResourceGroup $resourceGroup */
if (empty($scriptProperties['resourceGroup'])) return $modx->error->failure($modx->lexicon('resource_group_err_ns'));
$resourceGroup = $modx->getObject('modResourceGroup',$scriptProperties['resourceGroup']);
if ($resourceGroup == null) return $modx->error->failure($modx->lexicon('resource_group_err_ns'));

/* @var modResourceGroupResource $resourceGroupResource */
$resourceGroupResource = $modx->getObject('modResourceGroupResource',array(
	'document_group' => $resourceGroup->get('id'),
	'document' => $resource->get('id'),
));
if ($resourceGroupResource == null) return $modx->error->failure($modx->lexicon('resource_group_resource_err_nf'));

/* remove association */
if ($resourceGroupResource->remove() == false) {
    return $modx->error->failure($modx->lexicon('resource_group_resource_err_remove'));
} else {
    $modx->invokeEvent('OnResourceRemoveFromResourceGroup',array(
        'mode' => 'resource-group-tree-remove-resource',
        'resource' => &$resource,
        'resourceGroup' => &$resourceGroup,
    ));
}

return $modx->error->success('',$resourceGroupResource);