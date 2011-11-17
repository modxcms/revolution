<?php
/**
 * Update documents in a resource group
 *
 * @var modX $modx
 * @var array $scriptProperties
 * @var modProcessor $this
 * 
 * @package modx
 * @subpackage processors.security.resourcegroup
 */
if (!$modx->hasPermission('resourcegroup_resource_edit')) return $modx->error->failure($modx->lexicon('permission_denied'));
$modx->lexicon->load('resource','access');

/* format data */
$scriptProperties['resource'] = substr(strrchr($scriptProperties['resource'],'_'),1);
$scriptProperties['resourceGroup'] = substr(strrchr($scriptProperties['resourceGroup'],'_'),1);

if (empty($scriptProperties['resource']) || empty($scriptProperties['resourceGroup'])) return $modx->error->failure('Invalid data.');

/* @var modResource $resource */
$resource = $modx->getObject('modResource',$scriptProperties['resource']);
if ($resource == null) return $modx->error->failure($modx->lexicon('resource_err_nfs',array('id' => $scriptProperties['resource'])));

/* @var modResourceGroup $resourceGroup */
$resourceGroup = $modx->getObject('modResourceGroup',$scriptProperties['resourceGroup']);
if ($resourceGroup == null) return $modx->error->failure($modx->lexicon('resource_group_err_ns'));

/* check to make sure already isnt in group */
$alreadyExists = $modx->getObject('modResourceGroupResource',array(
    'document' => $resource->get('id'),
    'document_group' => $resourceGroup->get('id'),
));
if ($alreadyExists) return $modx->error->failure($modx->lexicon('resource_group_resource_err_ae'));

/* create resource group -> resource pairing */
/** @var $resourceGroupResource modResourceGroupResource */
$resourceGroupResource = $modx->newObject('modResourceGroupResource');
$resourceGroupResource->set('document',$resource->get('id'));
$resourceGroupResource->set('document_group',$resourceGroup->get('id'));

if ($resourceGroupResource->save() == false) {
    return $modx->error->failure($modx->lexicon('resource_group_resource_err_create'));
} else {
    $modx->invokeEvent('OnResourceAddToResourceGroup',array(
        'mode' => 'resource-group-tree-drag',
        'resource' => &$resource,
        'resourceGroup' => &$resourceGroup,
    ));
}

return $modx->error->success('',$resourceGroupResource);