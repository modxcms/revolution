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

use MODX\Revolution\modAccessPolicy;
use MODX\Revolution\Processors\Model\UpdateProcessor;

/**
 * Updates a policy
 * @param integer $id The ID of the policy
 * @param string $name The name of the policy.
 * @param string $description (optional) A short description
 * @param integer $parent (optional) A parent policy
 * @param string $class
 * @param string $data The JSON-encoded policy data
 * @package MODX\Revolution\Processors\Security\Access\Policy
 */
class Update extends UpdateProcessor
{
    public $classKey = modAccessPolicy::class;
    public $languageTopics = ['policy'];
    public $permission = 'policy_save';
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
        if ($this->doesAlreadyExist([
            'name' => $name,
            'id:!=' => $this->object->get('id')
        ])) {
            $this->addFieldError('name', $this->modx->lexicon('policy_err_ae', ['name' => $name]));
        }

        return parent::beforeSet();
    }

    /**
     * @return bool
     * @throws \xPDO\xPDOException
     */
    public function beforeSave()
    {
        /* now store the permissions into the modAccessPermission table */
        /* and cache the data into the policy table */
        $permissions = $this->getProperty('permissions');
        if ($permissions !== null) {
            $permData = [];
            $permissions = is_array($permissions) ? $permissions : $this->modx->fromJSON($permissions);
            foreach ($permissions as $permissionArray) {
                $permData[$permissionArray['name']] = $permissionArray['enabled'] ? true : false;
            }
            $this->object->set('data', $permData);
        }

        return parent::beforeSave();
    }

    public function afterSave()
    {
        $this->modx->cacheManager->flushPermissions();
        
        return parent::afterSave();
    }
}
