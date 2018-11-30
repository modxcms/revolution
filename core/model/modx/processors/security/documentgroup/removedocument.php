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
 * Remove a resource-resourcegroup pairing
 *
 * @param integer $document_group The ID of the resource group
 * @param integer $document The ID of the resource
 *
 * @package modx
 * @subpackage processors.security.documentgroup
 */
if (!$modx->hasPermission('access_permissions')) return $modx->error->failure($modx->lexicon('permission_denied'));
$modx->lexicon->load('access','resource');

if (empty($scriptProperties['document_group'])) return $modx->error->failure($modx->lexicon('resource_group_err_ns'));
if (empty($scriptProperties['document'])) return $modx->error->failure($modx->lexicon('resource_err_ns'));

/* get resource group resource */
$resourceGroupResource = $modx->getObject('modResourceGroupResource',array(
	'document_group' => $scriptProperties['document_group'],
	'document' => $scriptProperties['document'],
));
if ($resourceGroupResource == null) return $modx->error->failure($modx->lexicon('resource_group_resource_err_nf'));

/* remove association */
if ($resourceGroupResource->remove() == false) {
    return $modx->error->failure($modx->lexicon('resource_group_resource_err_remove'));
}

return $modx->error->success('',$resourceGroupResource);
