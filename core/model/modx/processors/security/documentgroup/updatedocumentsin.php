<?php
/**
 * Update documents in a resource group
 *
 *
 * @package modx
 * @subpackage processors.security.documentgroup
 */
$modx->lexicon->load('resource','access');

if (!$modx->hasPermission('access_permissions')) return $modx->error->failure($modx->lexicon('permission_denied'));

/* format data */
$_POST['resource'] = substr(strrchr($_POST['resource'],'_'),1);
$_POST['resource_group'] = substr(strrchr($_POST['resource_group'],'_'),1);

if ($_POST['resource'] == 0 || $_POST['resource_group'] == 0) return $modx->error->failure('Invalid data.');

/* get resource */
$resource = $modx->getObject('modResource',$_POST['resource']);
if ($resource == null) return $modx->error->failure($modx->lexicon('resource_err_nfs',array('id' => $_POST['resource'])));

/* get resource group */
$resourceGroup = $modx->getObject('modResourceGroup',$_POST['resource_group']);
if ($resourceGroup == null) return $modx->error->failure($modx->lexicon('resource_group_err_ns'));

/* check to make sure already isnt in group */
$rgr = $modx->getObject('modResourceGroupResource',array(
	'document' => $resource->get('id'),
	'document_group' => $resourceGroup->get('id'),
));
if ($rgr != null) return $modx->error->failure($modx->lexicon('resource_group_resource_err_ae'));

/* create resource group -> resource pairing */
$rgr = $modx->newObject('modResourceGroupResource');
$rgr->set('document',$resource->get('id'));
$rgr->set('document_group',$resourceGroup->get('id'));

if (!$rgr->save()) return $modx->error->failure($modx->lexicon('resource_group_resource_err_create'));

return $modx->error->success();