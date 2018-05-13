<?php

namespace MODX\Processors\Security\Access\UserGroup\Category;

use MODX\modCategory;
use MODX\Processors\modObjectCreateProcessor;

/**
 * @package modx
 * @subpackage processors.security.group.category
 */
class Create extends modObjectCreateProcessor
{
    public $classKey = 'modAccessCategory';
    public $objectType = 'access_category';
    public $languageTopics = ['access', 'user', 'category'];
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

        $category = $this->getProperty('target');
        if (!$category) {
            $this->addFieldError('target', $this->modx->lexicon('category_err_ns'));
        }

        return parent::beforeSet();
    }


    public function beforeSave()
    {
        $policy = $this->modx->getObject('modAccessPolicy', $this->getProperty('policy'));
        if (!$policy) {
            $this->addFieldError('policy', $this->modx->lexicon('access_policy_err_nf'));
        }
        /** @var modCategory $category */
        $category = $this->modx->getObject('modCategory', $this->getProperty('target'));
        if (!$category) {
            $this->addFieldError('target', $this->modx->lexicon('category_err_nf'));
        } else {
            if (!$category->checkPolicy('view')) {
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
