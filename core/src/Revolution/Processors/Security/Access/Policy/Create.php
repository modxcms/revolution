<?php
/*
 * This file is part of MODX Revolution.
 *
 * Copyright (c) MODX, LLC. All Rights Reserved.
 *
 * For complete copyright and license information, see the COPYRIGHT and LICENSE
 * files found in the top-level directory of this distribution.
 */

namespace MODX\Revolution\Processors\Security\Access\Policy;

use MODX\Revolution\modAccessPermission;
use MODX\Revolution\modAccessPolicy;
use MODX\Revolution\modAccessPolicyTemplate;
use MODX\Revolution\Processors\Model\CreateProcessor;

/**
 * Create an access policy.
 * @param string $name The name of the policy.
 * @param string $description (optional) A short description
 * @param integer $parent (optional) A parent policy
 * @param string $class
 * @param string $data The JSON-encoded policy data
 * @package MODX\Revolution\Processors\Security\Access\Policy
 */
class Create extends CreateProcessor
{
    public $classKey = modAccessPolicy::class;
    public $languageTopics = ['policy'];
    public $permission = 'policy_new';
    public $objectType = 'policy';

    /**
     * @return bool
     */
    public function beforeSet()
    {
        $name = $this->getProperty('name');
        if (empty($name)) {
            $this->addFieldError('name', $this->modx->lexicon('field_required'));
        }

        if ($this->doesAlreadyExist(['name' => $name])) {
            $this->addFieldError('name', $this->modx->lexicon('policy_err_ae', ['name' => $name]));
        }

        return parent::beforeSet();
    }

    /**
     * @return bool|void
     */
    public function beforeSave()
    {
        /** @var modAccessPolicyTemplate $template */
        $template = $this->modx->getObject(modAccessPolicyTemplate::class, $this->getProperty('template'));
        if (empty($template)) {
            return $this->addFieldError('template', $this->modx->lexicon('policy_template_err_nf'));
        } else {
            $permissions = $template->getMany('Permissions');
            $permList = [];
            /** @var modAccessPermission $permission */
            foreach ($permissions as $permission) {
                $permList[$permission->get('name')] = true;
            }
            $this->object->set('data', $permList);
        }

        return parent::beforeSave();
    }

    /**
     * @return bool
     */
    public function afterSave()
    {
        $this->modx->cacheManager->flushPermissions();

        return parent::afterSave();
    }
}

