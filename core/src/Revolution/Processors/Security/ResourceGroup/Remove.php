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
use MODX\Revolution\modResourceGroup;

/**
 * Remove a resource group
 * @param integer $id The ID of the resource group
 * @package MODX\Revolution\Processors\Security\ResourceGroup
 */
class Remove extends Processor
{
    /** @var modResourceGroup $resourceGroup */
    public $resourceGroup;

    /**
     * @return bool
     */
    public function checkPermissions()
    {
        return $this->modx->hasPermission('resourcegroup_delete');
    }

    /**
     * @return array
     */
    public function getLanguageTopics()
    {
        return ['user', 'access'];
    }

    /**
     * @return bool|string|null
     */
    public function initialize()
    {
        $id = $this->getProperty('id', false);
        if (empty($id)) {
            return $this->modx->lexicon('resource_group_err_ns');
        }
        $this->resourceGroup = $this->modx->getObject(modResourceGroup::class, $id);
        if ($this->resourceGroup === null) {
            return $this->modx->lexicon('resource_group_err_nf');
        }

        return true;
    }

    /**
     * @return array|mixed|string
     */
    public function process()
    {
        if ($this->resourceGroup->remove() === false) {
            return $this->failure($this->modx->lexicon('resource_group_err_remove'));
        }
        $this->logManagerAction();
        return $this->success('', $this->resourceGroup);
    }

    public function logManagerAction()
    {
        $this->modx->logManagerAction('delete_resource_group', modResourceGroup::class, $this->resourceGroup->get('id'));
    }
}

