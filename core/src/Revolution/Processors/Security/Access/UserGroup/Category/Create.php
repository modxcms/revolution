<?php
/*
 * This file is part of MODX Revolution.
 *
 * Copyright (c) MODX, LLC. All Rights Reserved.
 *
 * For complete copyright and license information, see the COPYRIGHT and LICENSE
 * files found in the top-level directory of this distribution.
 */

namespace MODX\Revolution\Processors\Security\Access\UserGroup\Category;

use MODX\Revolution\modAccessCategory;
use MODX\Revolution\modAccessPolicy;
use MODX\Revolution\modCategory;
use MODX\Revolution\Processors\Model\CreateProcessor;
use MODX\Revolution\modUserGroup;

/**
 * Class Create
 * @package MODX\Revolution\Processors\Security\Access\UserGroup\Category
 */
class Create extends CreateProcessor
{
    public $classKey = modAccessCategory::class;
    public $objectType = 'access_category';
    public $languageTopics = ['access', 'user', 'category'];
    public $permission = 'access_permissions';

    /**
     * @return bool
     */
    public function beforeSet()
    {
        $principal = $this->getProperty('principal');
        if ($principal === null) {
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

    /**
     * @return bool
     */
    public function beforeSave()
    {
        $policy = $this->modx->getObject(modAccessPolicy::class, $this->getProperty('policy'));
        if (!$policy) {
            $this->addFieldError('policy', $this->modx->lexicon('access_policy_err_nf'));
        }

        /** @var modCategory $category */
        $category = $this->modx->getObject(modCategory::class, $this->getProperty('target'));
        if (!$category) {
            $this->addFieldError('target', $this->modx->lexicon('category_err_nf'));
        } else {
            if (!$category->checkPolicy('view')) {
                $this->addFieldError('target', $this->modx->lexicon('access_denied'));
            }
        }

        if ($this->doesAlreadyExist([
            'principal' => $this->getProperty('principal'),
            'principal_class' => modUserGroup::class,
            'target' => $this->getProperty('target'),
            'policy' => $this->getProperty('policy'),
            'context_key' => $this->getProperty('context_key'),
        ])) {
            $this->addFieldError('target', $this->modx->lexicon($this->objectType . '_err_ae'));
        }
        $this->object->set('principal_class', modUserGroup::class);

        return parent::beforeSave();
    }

}
