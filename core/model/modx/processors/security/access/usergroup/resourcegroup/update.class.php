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
 * @package modx
 * @subpackage processors.security.group.resourcegroup
 */
class modUserGroupAccessResourceGroupUpdateProcessor extends modObjectUpdateProcessor {
    public $classKey = 'modAccessResourceGroup';
    public $objectType = 'access_rgroup';
    public $languageTopics = array('access', 'user');
    public $permission = 'access_permissions';

    public function beforeSet() {
        if ($this->getProperty('principal') == null) {
            $this->addFieldError('principal', $this->modx->lexicon('usergroup_err_ns'));
        }

        if (!$this->getProperty('target')) {
            $this->addFieldError('target', $this->modx->lexicon('resource_group_err_ns'));
        }

        if (!$this->getProperty('policy')) {
            $this->addFieldError('policy', $this->modx->lexicon('access_policy_err_ns'));
        }

        if ($this->getProperty('authority') === null ) {
            $this->addFieldError('authority', $this->modx->lexicon('authority_err_ns'));
        }

        return parent::beforeSet();
    }

    public function beforeSave() {
        $resourceGroup = $this->modx->getObject('modResourceGroup', $this->getProperty('target'));
        if (!$resourceGroup) {
            $this->addFieldError('target', $this->modx->lexicon('resource_group_err_nf'));
        }

        $policy = $this->modx->getObject('modAccessPolicy', $this->getProperty('policy'));
        if (!$policy) {
            $this->addFieldError('policy', $this->modx->lexicon('access_policy_err_nf'));
        }

        if ($this->doesAlreadyExist(array(
            'principal' => $this->object->get('principal'),
            'principal_class' => 'modUserGroup',
            'target' => $this->object->get('target'),
            'policy' => $this->object->get('policy'),
            'context_key' => $this->object->get('context_key'),
            'id:!=' => $this->object->get($this->primaryKeyField),
        ))) {
            $this->addFieldError('target', $this->modx->lexicon($this->objectType.'_err_ae'));
        }

        return parent::beforeSave();
    }
}

return 'modUserGroupAccessResourceGroupUpdateProcessor';
