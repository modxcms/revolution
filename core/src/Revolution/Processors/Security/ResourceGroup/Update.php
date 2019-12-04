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
 * Update a resource group
 * @package MODX\Revolution\Processors\Security\ResourceGroup
 */
class Update extends Processor
{
    /** @var modResourceGroup $resourceGroup */
    public $resourceGroup;

    /**
     * @return bool
     */
    public function checkPermissions()
    {
        return $this->modx->hasPermission('resourcegroup_save');
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
        if (!$this->validate()) {
            return $this->failure();
        }

        $this->resourceGroup->fromArray($this->getProperties());
        if ($this->resourceGroup->save() === false) {
            return $this->failure($this->modx->lexicon('resource_group_err_save'));
        }

        $this->logManagerAction();

        return $this->success('', $this->resourceGroup);
    }

    /**
     * Validate the form
     * @return boolean
     */
    public function validate()
    {
        $name = $this->getProperty('name');
        if (empty($name)) {
            $this->addFieldError('name', $this->modx->lexicon('resource_group_err_ns_name'));
        }

        if ($this->alreadyExists($name)) {
            $this->addFieldError('name', $this->modx->lexicon('resource_group_err_ae'));
        }

        return !$this->hasErrors();
    }

    /**
     * Check if a Resource Group already exists with that name
     * @param string $name
     * @return boolean
     */
    public function alreadyExists($name)
    {
        return $this->modx->getCount(modResourceGroup::class, [
                'name' => $name,
                'id:!=' => $this->resourceGroup->get('id')
            ]) > 0;
    }

    /**
     * Log the manager action
     * @return void
     */
    public function logManagerAction()
    {
        $this->modx->logManagerAction('update_resource_group', modResourceGroup::class, $this->resourceGroup->get('id'));
    }
}
