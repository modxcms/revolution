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
 * @param integer $resourceGroup The ID of the resource group
 * @param integer $resource The ID of the resource
 *
 * @package modx
 * @subpackage processors.security.resourcegroup
 */
class modResourceGroupRemoveResourceProcessor extends modProcessor {
    /** @var modResourceGroup $resourceGroup */
    public $resourceGroup;
    /** @var modResource $resource */
    public $resource;

    public function checkPermissions() {
        return $this->modx->hasPermission('resourcegroup_resource_edit');
    }
    public function getLanguageTopics() {
        return array('resource','access');
    }

    public function initialize() {
        $resource = $this->getProperty('resource');
        if (empty($resource)) return $this->modx->lexicon('resource_err_ns');
        $this->resource = $this->modx->getObject('modResource',$resource);
        if ($this->resource == null) return $this->modx->lexicon('resource_err_nfs',array('id' => $resource));

        $resourceGroup = $this->getProperty('resourceGroup',false);
        if (empty($resourceGroup)) return $this->modx->lexicon('resource_group_err_ns');
        $this->resourceGroup = $this->modx->getObject('modResourceGroup',$resourceGroup);
        if (empty($this->resourceGroup)) return $this->modx->lexicon('resource_group_err_ns');

        return true;
    }

    public function process() {
        /* @var modResourceGroupResource $resourceGroupResource */
        $resourceGroupResource = $this->modx->getObject('modResourceGroupResource',array(
            'document_group' => $this->resourceGroup->get('id'),
            'document' => $this->resource->get('id'),
        ));
        if (empty($resourceGroupResource)) {
            return $this->failure($this->modx->lexicon('resource_group_resource_err_nf'));
        }

        if ($resourceGroupResource->remove() == false) {
            return $this->failure($this->modx->lexicon('resource_group_resource_err_remove'));

        } else {
            $this->fireAfterRemove();
        }
        return $this->success('',$resourceGroupResource);
    }

    public function fireAfterRemove() {
        $this->modx->invokeEvent('OnResourceRemoveFromResourceGroup',array(
            'mode' => 'resource-group-tree-remove-resource',
            'resource' => &$this->resource,
            'resourceGroup' => &$this->resourceGroup,
        ));

    }
}
return 'modResourceGroupRemoveResourceProcessor';
