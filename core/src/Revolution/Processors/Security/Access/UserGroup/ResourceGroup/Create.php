<?php
/*
 * This file is part of MODX Revolution.
 *
 * Copyright (c) MODX, LLC. All Rights Reserved.
 *
 * For complete copyright and license information, see the COPYRIGHT and LICENSE
 * files found in the top-level directory of this distribution.
 */

namespace MODX\Revolution\Processors\Security\Access\UserGroup\ResourceGroup;

use MODX\Revolution\modAccessPolicy;
use MODX\Revolution\modAccessResourceGroup;
use MODX\Revolution\Processors\Model\CreateProcessor;
use MODX\Revolution\modResourceGroup;
use MODX\Revolution\modUserGroup;

/**
 * @package MODX\Revolution\Processors\Security\Access\UserGroup\ResourceGroup
 */
class Create extends CreateProcessor
{
    public $classKey = modAccessResourceGroup::class;
    public $objectType = 'access_rgroup';
    public $languageTopics = ['access', 'user'];
    public $permission = 'access_permissions';

    /**
     * @return mixed
     */
    public function beforeSet()
    {
        if ($this->getProperty('principal') === null) {
            $this->addFieldError('principal', $this->modx->lexicon('usergroup_err_ns'));
        }

        if (!$this->getProperty('target')) {
            $this->addFieldError('target', $this->modx->lexicon('resource_group_err_ns'));
        }

        if (!$this->getProperty('policy')) {
            $this->addFieldError('policy', $this->modx->lexicon('access_policy_err_ns'));
        }

        if ($this->getProperty('authority') === null) {
            $this->addFieldError('authority', $this->modx->lexicon('authority_err_ns'));
        }

        return parent::beforeSet();
    }

    /**
     * @return mixed
     */
    public function beforeSave()
    {
        $resourceGroup = $this->modx->getObject(modResourceGroup::class, $this->getProperty('target'));
        if (!$resourceGroup) {
            $this->addFieldError('target', $this->modx->lexicon('resource_group_err_nf'));
        }

        $policy = $this->modx->getObject(modAccessPolicy::class, $this->getProperty('policy'));
        if (!$policy) {
            $this->addFieldError('policy', $this->modx->lexicon('access_policy_err_nf'));
        }

        if ($this->doesAlreadyExist([
            'principal' => $this->object->get('principal'),
            'principal_class' => modUserGroup::class,
            'target' => $this->object->get('target'),
            'policy' => $this->object->get('policy'),
            'context_key' => $this->object->get('context_key'),
        ])) {
            $this->addFieldError('target', $this->modx->lexicon($this->objectType . '_err_ae'));
        }

        $this->object->set('principal_class', modUserGroup::class);

        return parent::beforeSave();
    }
}
