<?php
/**
 * Assign or unassigns a resource group to a resource.
 *
 * @param integer $id The resource group to assign to.
 * @param integer $resource The modResource ID to associate with.
 * @param boolean $access Either true or false whether the resource has access
 * to the group specified.
 *
 * @package modx
 * @subpackage processors.resource.resourcegroup
 */
if (!$modx->hasPermission('save_document')) return $modx->error->failure($modx->lexicon('permission_denied'));
$modx->lexicon->load('resource');

$_DATA = $modx->fromJSON($scriptProperties['data']);

/* get resource */
if (empty($_DATA['resource'])) return $modx->error->failure($modx->lexicon('resource_err_ns'));
$resource = $modx->getObject('modResource',$_DATA['resource']);
if (empty($resource)) return $modx->error->failure($modx->lexicon('resource_err_nf'));

if (!$resource->checkPolicy('save')) {
    return $modx->error->failure($modx->lexicon('permission_denied'));
}

/* get resource group */
if (empty($_DATA['id'])) return $modx->error->failure($modx->lexicon('resource_group_err_ns'));
$resourceGroup = $modx->getObject('modResourceGroup',$_DATA['id']);
if (empty($resourceGroup)) return $modx->error->failure($modx->lexicon('resource_group_err_nf'));

/* get access */
$resourceGroupResource = $modx->getObject('modResourceGroupResource',array(
    'document' => $resource->get('id'),
    'document_group' => $resourceGroup->get('id'),
));

if ($_DATA['access'] == true && $resourceGroupResource != null) {
    return $modx->error->failure($modx->lexicon('resource_group_resource_err_ae'));
}
if ($_DATA['access'] == false && $resourceGroupResource == null) {
    return $modx->error->failure($modx->lexicon('resource_group_resource_err_nf'));
}
if ($_DATA['access'] == true) {
    $resourceGroupResource = $modx->newObject('modResourceGroupResource');
    $resourceGroupResource->set('document',$resource->get('id'));
    $resourceGroupResource->set('document_group',$resourceGroup->get('id'));
    $resourceGroupResource->save();
} else if ($resourceGroupResource instanceof modResourceGroupResource) {
    $resourceGroupResource->remove();
}

return $modx->error->success();