<?php
/*
 * This file is part of MODX Revolution.
 *
 * Copyright (c) MODX, LLC. All Rights Reserved.
 *
 * For complete copyright and license information, see the COPYRIGHT and LICENSE
 * files found in the top-level directory of this distribution.
 */

namespace MODX\Revolution\Processors\Security\ResourceGroup;

use MODX\Revolution\Processors\Processor;
use MODX\Revolution\modResource;
use MODX\Revolution\modResourceGroup;
use MODX\Revolution\modResourceGroupResource;

/**
 * Remove a resource-resourcegroup pairing
 * @param integer $resourceGroup The ID of the resource group
 * @param integer $resource The ID of the resource
 * @package MODX\Revolution\Processors\Security\ResourceGroup
 */
class RemoveResource extends Processor
{
    /** @var modResourceGroup $resourceGroup */
    public $resourceGroup;

    /** @var modResource $resource */
    public $resource;

    /**
     * @return bool
     */
    public function checkPermissions()
    {
        return $this->modx->hasPermission('resourcegroup_resource_edit');
    }

    /**
     * @return array
     */
    public function getLanguageTopics()
    {
        return ['resource', 'access'];
    }

    /**
     * @return bool|string|null
     */
    public function initialize()
    {
        $resource = $this->getProperty('resource');
        if (empty($resource)) {
            return $this->modx->lexicon('resource_err_ns');
        }
        $this->resource = $this->modx->getObject(modResource::class, $resource);
        if ($this->resource === null) {
            return $this->modx->lexicon('resource_err_nfs', ['id' => $resource]);
        }

        $resourceGroup = $this->getProperty('resourceGroup', false);
        if (empty($resourceGroup)) {
            return $this->modx->lexicon('resource_group_err_ns');
        }
        $this->resourceGroup = $this->modx->getObject(modResourceGroup::class, $resourceGroup);
        if ($this->resourceGroup === null) {
            return $this->modx->lexicon('resource_group_err_ns');
        }

        return true;
    }

    /**
     * @return array|mixed|string
     */
    public function process()
    {
        /* @var modResourceGroupResource $resourceGroupResource */
        $resourceGroupResource = $this->modx->getObject(modResourceGroupResource::class, [
            'document_group' => $this->resourceGroup->get('id'),
            'document' => $this->resource->get('id')
        ]);

        if ($resourceGroupResource === null) {
            return $this->failure($this->modx->lexicon('resource_group_resource_err_nf'));
        }

        if ($resourceGroupResource->remove() === false) {
            return $this->failure($this->modx->lexicon('resource_group_resource_err_remove'));
        }

        $this->fireAfterRemove();

        return $this->success('', $resourceGroupResource);
    }

    public function fireAfterRemove()
    {
        $this->modx->invokeEvent('OnResourceRemoveFromResourceGroup', [
            'mode' => 'resource-group-tree-remove-resource',
            'resource' => &$this->resource,
            'resourceGroup' => &$this->resourceGroup,
        ]);
    }
}
