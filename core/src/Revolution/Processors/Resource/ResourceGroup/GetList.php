<?php
/*
 * This file is part of MODX Revolution.
 *
 * Copyright (c) MODX, LLC. All Rights Reserved.
 *
 * For complete copyright and license information, see the COPYRIGHT and LICENSE
 * files found in the top-level directory of this distribution.
 */

namespace MODX\Revolution\Processors\Resource\ResourceGroup;

use MODX\Revolution\Processors\Processor;
use MODX\Revolution\modResource;
use MODX\Revolution\modResourceGroup;
use MODX\Revolution\modResourceGroupResource;

/**
 * Grabs a list of resource groups for a resource.
 *
 * @param integer $resource The resource to grab groups for.
 * @param integer $start (optional) The record to start at. Defaults to 0.
 * @param integer $limit (optional) The number of records to limit to. Defaults
 * to 10.
 * @param string $sort (optional) The column to sort by. Defaults to name.
 * @param string $dir (optional) The direction of the sort. Defaults to ASC.
 */
class GetList extends Processor
{
    /** @var modResource $resource */
    public $resource;

    public function checkPermissions()
    {
        return $this->modx->hasPermission('list');
    }

    public function getLanguageTopics()
    {
        return ['resource'];
    }

    public function initialize()
    {
        $this->setDefaultProperties([
            'start' => 0,
            'limit' => 10,
            'sort' => 'name',
            'dir' => 'ASC',
            'resource' => 0,
            'parent' => 0,
            'mode' => 'update',
        ]);
        return true;
    }

    public function process()
    {
        $this->resource = $this->getResource();
        if (!is_object($this->resource) || !($this->resource instanceof modResource)) {
            return $this->failure($this->resource);
        }

        /* setup default properties */
        $isLimit = $this->getProperty('limit') > 0;
        $resourceGroupList = $this->resource->getGroupsList([$this->getProperty('sort') => $this->getProperty('dir')], $isLimit ? $this->getProperty('limit') : 0, $this->getProperty('start'));
        $resourceGroups = $resourceGroupList['collection'];

        $parentGroups = [];
        $mode = $this->getProperty('mode');
        $parent = $this->getProperty('parent', 0);

        if (!empty($parent) && $mode == 'create') {
            $parent = $this->modx->getObject(modResource::class, $parent);
            /** @var modResource $parent */
            if ($parent) {
                $parentResourceGroups = $parent->getMany('ResourceGroupResources');
                /** @var modResourceGroupResource $parentResourceGroup */
                foreach ($parentResourceGroups as $parentResourceGroup) {
                    $parentGroups[] = $parentResourceGroup->get('document_group');
                }
                $parentGroups = array_unique($parentGroups);
            }
        }

        $list = [];
        /** @var modResourceGroup $resourceGroup */
        foreach ($resourceGroups as $resourceGroup) {
            $resourceGroupArray = $resourceGroup->toArray();
            $resourceGroupArray['access'] = (boolean)$resourceGroupArray['access'];
            if (!empty($parent) && $mode == 'create') {
                $resourceGroupArray['access'] = in_array($resourceGroupArray['id'], $parentGroups) ? true : false;
            }
            $list[] = $resourceGroupArray;
        }

        return $this->outputArray($list, $resourceGroupList['total']);
    }

    /**
     * Get the Resource associated
     *
     * @return modResource|string
     */
    public function getResource()
    {
        $resourceId = $this->getProperty('resource', 0);
        if (empty($resourceId)) {
            $this->resource = $this->modx->newObject(modResource::class);
            $this->resource->set('id', 0);
        } else {
            $this->resource = $this->modx->getObject(modResource::class, $resourceId);
            if (empty($this->resource)) return $this->modx->lexicon('resource_err_nfs', ['id' => $resourceId]);

            /* check access */
            if (!$this->resource->checkPolicy('view')) {
                return $this->modx->lexicon('permission_denied');
            }
        }
        return $this->resource;
    }
}
