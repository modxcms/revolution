<?php

namespace MODX\Processors\Security\ResourceGroup;

use MODX\modResourceGroup;
use MODX\Processors\modProcessor;

/**
 * Remove a resource group
 *
 * @param integer $id The ID of the resource group
 *
 * @package modx
 * @subpackage processors.security.resourcegroup
 */
class Remove extends modProcessor
{
    /** @var modResourceGroup $resourceGroup */
    public $resourceGroup;


    public function checkPermissions()
    {
        return $this->modx->hasPermission('resourcegroup_delete');
    }


    public function getLanguageTopics()
    {
        return ['user', 'access'];
    }


    public function initialize()
    {
        $id = $this->getProperty('id', false);
        if (empty($id)) return $this->modx->lexicon('resource_group_err_ns');
        $this->resourceGroup = $this->modx->getObject('modResourceGroup', $id);
        if (empty($this->resourceGroup)) return $this->modx->lexicon('resource_group_err_nf');

        return true;
    }


    public function process()
    {
        if ($this->resourceGroup->remove() == false) {
            return $this->failure($this->modx->lexicon('resource_group_err_remove'));
        }
        $this->logManagerAction();

        return $this->success('', $this->resourceGroup);
    }


    public function logManagerAction()
    {
        $this->modx->logManagerAction('delete_resource_group', 'modResourceGroup', $this->resourceGroup->get('id'));
    }
}