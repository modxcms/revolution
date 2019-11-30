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
use MODX\Revolution\Processors\Model\UpdateProcessor;
use MODX\Revolution\modUserGroup;

/**
 * @package MODX\Revolution\Processors\Security\Access\UserGroup\Category
 */
class Update extends UpdateProcessor
{
    public $classKey = modAccessCategory::class;
    public $objectType = 'access_category';
    public $languageTopics = ['access', 'user', 'category'];
    public $permission = 'access_permissions';

    /**
     * @return mixed
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
     * @return mixed
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
            'principal' => $this->object->get('principal'),
            'principal_class' => modUserGroup::class,
            'target' => $this->object->get('target'),
            'policy' => $this->object->get('policy'),
            'context_key' => $this->object->get('context_key'),
            'id:!=' => $this->object->get('id'),
        ])) {
            $this->addFieldError('target', $this->modx->lexicon($this->objectType . '_err_ae'));
        }
        $this->object->set('principal_class', modUserGroup::class);

        return parent::beforeSave();
    }
}
