<?php

namespace MODX\Processors\Security\Access\UserGroup\ResourceGroup;

use MODX\Processors\modObjectCreateProcessor;

/**
 * @package modx
 * @subpackage processors.security.group.resourcegroup
 */
class Create extends modObjectCreateProcessor
{
    public $classKey = 'modAccessResourceGroup';
    public $objectType = 'access_rgroup';
    public $languageTopics = ['access', 'user'];
    public $permission = 'access_permissions';


    public function beforeSet()
    {
        if ($this->getProperty('principal') == null) {
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


    public function beforeSave()
    {
        $resourceGroup = $this->modx->getObject('modResourceGroup', $this->getProperty('target'));
        if (!$resourceGroup) {
            $this->addFieldError('target', $this->modx->lexicon('resource_group_err_nf'));
        }

        $policy = $this->modx->getObject('modAccessPolicy', $this->getProperty('policy'));
        if (!$policy) {
            $this->addFieldError('policy', $this->modx->lexicon('access_policy_err_nf'));
        }

        if ($this->doesAlreadyExist([
            'principal' => $this->object->get('principal'),
            'principal_class' => 'modUserGroup',
            'target' => $this->object->get('target'),
            'policy' => $this->object->get('policy'),
            'context_key' => $this->object->get('context_key'),
        ])) {
            $this->addFieldError('target', $this->modx->lexicon($this->objectType . '_err_ae'));
        }

        $this->object->set('principal_class', 'modUserGroup');

        return parent::beforeSave();
    }
}
