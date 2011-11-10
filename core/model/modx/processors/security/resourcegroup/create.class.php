<?php
/**
 * Create a resource group
 *
 * @param string $name The name of the new resource group
 *
 * @package modx
 * @subpackage processors.security.resourcegroup
 */
class modResourceGroupCreateProcessor extends modProcessor {
    /** @var modResourceGroup $resourceGroup */
    public $resourceGroup;
    
    public function checkPermissions() {
        return $this->modx->hasPermission('resourcegroup_new');
    }
    public function getLanguageTopics() {
        return array('access');
    }

    public function initialize() {
        $this->resourceGroup = $this->modx->newObject('modResourceGroup');
        return true;
    }
    
    public function process() {
        if (!$this->validate()) {
            return $this->failure();
        }

        $this->resourceGroup->fromArray($this->getProperties());
        if ($this->resourceGroup->save() == false) {
            return $this->failure($this->modx->lexicon('resource_group_err_create'));
        }

        $this->logManagerAction();

        return $this->success('',$this->resourceGroup);
    }

    /**
     * Validate the form
     * 
     * @return boolean
     */
    public function validate() {
        $name = $this->getProperty('name');
        if (empty($name)) {
            $this->addFieldError('name',$this->modx->lexicon('resource_group_ns_name'));
        }

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
        return $this->modx->getCount('modResourceGroup',array('name' => $name)) > 0;
    }

    /**
     * Log the manager action
     * @return void
     */
    public function logManagerAction() {
        $this->modx->logManagerAction('new_resource_group','modResourceGroup',$this->resourceGroup->get('id'));
    }
}
return 'modResourceGroupCreateProcessor';
