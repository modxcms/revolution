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
 * @var modX $modx
 * @var array $scriptProperties
 * @var modProcessor $this
 *
 * @package modx
 * @subpackage processors.security.resourcegroup
 */

class modResourceGroupUpdateResourcesInProcessor extends modObjectCreateProcessor {
    public $objectType = 'resource_group_resource';
    public $classKey = 'modResourceGroupResource';
    public $permission = 'resourcegroup_resource_edit';
    public $languageTopics = array('resource', 'access');
    public $afterSaveEvent = 'OnResourceAddToResourceGroup';

    /** @var modResource */
    public $resource;
    /** @var modResourceGroup */
    public $resourceGroup;

    public function beforeSave() {
        /* format data */
        $resourceId = substr(strrchr($this->getProperty('resource',''), '_'), 1);
        $resourceGroupId = substr(strrchr($this->getProperty('resourceGroup',''), '_'), 1);

        if (empty($resourceId) || empty($resourceGroupId)) {
            return $this->modx->lexicon('invalid_data');
        }

        $this->resource = $this->modx->getObject('modResource', $resourceId);
        if (!$this->resource) {
            return $this->modx->lexicon('resource_err_nfs', array('id' => $resourceId));
        }

        $this->resourceGroup = $this->modx->getObject('modResourceGroup', $resourceGroupId);
        if (!$this->resourceGroup) {
            return $this->modx->lexicon('resource_group_err_nf');
        }

        if ($this->doesAlreadyExist(array(
            'document' => $this->resource->get('id'),
            'document_group' => $this->resourceGroup->get('id'),
        ))) {
            return $this->modx->lexicon($this->objectType.'_err_ae');
        }

        $this->object->set('document', $this->resource->get('id'));
        $this->object->set('document_group', $this->resourceGroup->get('id'));

        return parent::beforeSave();
    }

    public function fireAfterSaveEvent() {
        if (!empty($this->afterSaveEvent)) {
            $this->modx->invokeEvent($this->afterSaveEvent, array(
                'mode' => 'resource-group-tree-drag',
                'resource' => &$this->resource,
                'resourceGroup' => &$this->resourceGroup,
            ));
        }
    }

    /**
     * Return the success message
     * @return array
     */
    public function cleanup() {
        $objArray = $this->object->toArray();
        unset($objArray['action'], $objArray['resource'], $objArray['resourceGroup']);
        return $this->success('', $objArray);
    }

}

return 'modResourceGroupUpdateResourcesInProcessor';
