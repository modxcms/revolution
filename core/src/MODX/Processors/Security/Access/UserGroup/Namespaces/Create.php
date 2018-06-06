<?php

namespace MODX\Processors\Security\Access\UserGroup\Namespaces;

use MODX\modNamespace;
use MODX\Processors\modObjectCreateProcessor;

/**
 * @package modx
 * @subpackage processors.security.group.namespace
 */
class Create extends modObjectCreateProcessor
{
    public $classKey = 'modAccessNamespace';
    public $objectType = 'access_namespace';
    public $languageTopics = ['access', 'user', 'namespace'];
    public $permission = 'access_permissions';


    public function beforeSet()
    {
        $principal = $this->getProperty('principal');
        if ($principal == null) {
            $this->addFieldError('principal', $this->modx->lexicon('usergroup_err_ns'));
        }

        $policy = $this->getProperty('policy', '');
        if (empty($policy)) {
            $this->addFieldError('policy', $this->modx->lexicon('access_policy_err_ns'));
        }

        $authority = $this->getProperty('authority');
        if ($authority === null) {
            $this->addFieldError('authority', $this->modx->lexicon('authority_err_ns'));
        }

        $namespace = $this->getProperty('target');
        if (!$namespace) {
            $this->addFieldError('target', $this->modx->lexicon('namespace_err_ns'));
        }

        return parent::beforeSet();
    }


    public function beforeSave()
    {
        $policy = $this->modx->getObject('modAccessPolicy', $this->getProperty('policy'));
        if (!$policy) {
            $this->addFieldError('policy', $this->modx->lexicon('access_policy_err_nf'));
        }
        /** @var modNamespace $namespace */
        $namespace = $this->modx->getObject('modNamespace', $this->getProperty('target'));
        if (!$namespace) {
            $this->addFieldError('target', $this->modx->lexicon('namespace_err_nf'));
        } else {
            if (!$namespace->checkPolicy('view')) {
                $this->addFieldError('target', $this->modx->lexicon('access_denied'));
            }
        }

        if ($this->doesAlreadyExist([
            'principal' => $this->getProperty('principal'),
            'principal_class' => 'modUserGroup',
            'target' => $this->getProperty('target'),
            'policy' => $this->getProperty('policy'),
            'context_key' => $this->getProperty('context_key'),
        ])) {
            $this->addFieldError('target', $this->modx->lexicon($this->objectType . '_err_ae'));
        }
        $this->object->set('principal_class', 'modUserGroup');

        return parent::beforeSave();
    }

}