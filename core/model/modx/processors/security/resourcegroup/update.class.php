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
 * Update a resource group
 *
 * @package modx
 * @subpackage processors.security.resourcegroup
 */
class modResourceGroupUpdateProcessor extends modProcessor {
    /** @var modResourceGroup $resourceGroup */
    public $resourceGroup;

    public function checkPermissions() {
        return $this->modx->hasPermission('resourcegroup_save');
    }
    public function getLanguageTopics() {
        return array('user','access');
    }

    public function initialize() {
        $id = $this->getProperty('id',false);
        if (empty($id)) return $this->modx->lexicon('resource_group_err_ns');
        $this->resourceGroup = $this->modx->getObject('modResourceGroup',$id);
        if (empty($this->resourceGroup)) return $this->modx->lexicon('resource_group_err_nf');
        return true;
    }

    public function process() {
        if (!$this->validate()) {
            return $this->failure();
        }
        
        $this->resourceGroup->fromArray($this->getProperties());
        if ($this->resourceGroup->save() == false) {
            return $this->failure($this->modx->lexicon('resource_group_err_save'));
        }
        
        $this->logManagerAction();
        return $this->success('',$this->resourceGroup);
    }

    /**
     * Validate the form
     * @return boolean
     */
    public function validate() {
        $name = $this->getProperty('name');
        if (empty($name)) $this->addFieldError('name',$this->modx->lexicon('resource_group_err_ns_name'));

        if ($this->alreadyExists($name)) {
            $this->addFieldError('name',$this->modx->lexicon('resource_group_err_ae'));
        }

        return !$this->hasErrors();
    }

    /**
     * Check if a Resource Group already exists with that name
     * @param string $name
     * @return boolean
     */
    public function alreadyExists($name) {
        return $this->modx->getCount('modResourceGroup',array(
            'name' => $name,
            'id:!=' => $this->resourceGroup->get('id'),
        )) > 0;
    }

    /**
     * Log the manager action
     * 
     * @return void
     */
    public function logManagerAction() {
        $this->modx->logManagerAction('update_resource_group','modResourceGroup',$this->resourceGroup->get('id'));
    }
}
return 'modResourceGroupUpdateProcessor';
