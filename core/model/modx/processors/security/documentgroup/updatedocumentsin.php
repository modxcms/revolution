<?php
/*
 * This file is part of MODX Revolution.
 *
 * Copyright (c) MODX, LLC. All Rights Reserved.
 *
 * For complete copyright and license information, see the COPYRIGHT and LICENSE
 * files found in the top-level directory of this distribution.
 */

/**
 * Update documents in a resource group
 *
 *
 * @package modx
 * @subpackage processors.security.documentgroup
 */
if (!$modx->hasPermission('access_permissions')) return $modx->error->failure($modx->lexicon('permission_denied'));
$modx->lexicon->load('resource','access');

/* format data */
$scriptProperties['resource'] = substr(strrchr($scriptProperties['resource'],'_'),1);
$scriptProperties['resource_group'] = substr(strrchr($scriptProperties['resource_group'],'_'),1);

if (empty($scriptProperties['resource']) || empty($scriptProperties['resource_group'])) return $modx->error->failure('Invalid data.');

/* get resource */
$resource = $modx->getObject('modResource',$scriptProperties['resource']);
if ($resource == null) return $modx->error->failure($modx->lexicon('resource_err_nfs',array('id' => $scriptProperties['resource'])));

/* get resource group */
$resourceGroup = $modx->getObject('modResourceGroup',$scriptProperties['resource_group']);
if ($resourceGroup == null) return $modx->error->failure($modx->lexicon('resource_group_err_ns'));

/* check to make sure already isnt in group */
$alreadyExists = $modx->getObject('modResourceGroupResource',array(
	'document' => $resource->get('id'),
	'document_group' => $resourceGroup->get('id'),
));
if ($alreadyExists) return $modx->error->failure($modx->lexicon('resource_group_resource_err_ae'));

/* create resource group -> resource pairing */
$resourceGroupResource = $modx->newObject('modResourceGroupResource');
$resourceGroupResource->set('document',$resource->get('id'));
$resourceGroupResource->set('document_group',$resourceGroup->get('id'));

if ($resourceGroupResource->save() == false) {
    return $modx->error->failure($modx->lexicon('resource_group_resource_err_create'));
}

return $modx->error->success('',$resourceGroupResource);
