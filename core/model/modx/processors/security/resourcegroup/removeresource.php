<?php
/**
 * Remove a resource-resourcegroup pairing
 *
 * @param integer $resourceGroup The ID of the resource group
 * @param integer $resource The ID of the resource
 *
 * @package modx
 * @subpackage processors.security.documentgroup
 */
if (!$modx->hasPermission('access_permissions')) return $modx->error->failure($modx->lexicon('permission_denied'));
$modx->lexicon->load('access','resource');

if (empty($scriptProperties['resourceGroup'])) return $modx->error->failure($modx->lexicon('resource_group_err_ns'));
if (empty($scriptProperties['resource'])) return $modx->error->failure($modx->lexicon('resource_err_ns'));

/* get resource group resource */
$resourceGroupResource = $modx->getObject('modResourceGroupResource',array(
	'document_group' => $scriptProperties['resourceGroup'],
	'document' => $scriptProperties['resource'],
));
if ($resourceGroupResource == null) return $modx->error->failure($modx->lexicon('resource_group_resource_err_nf'));

/* remove association */
if ($resourceGroupResource->remove() == false) {
    return $modx->error->failure($modx->lexicon('resource_group_resource_err_remove'));
}

return $modx->error->success('',$resourceGroupResource);