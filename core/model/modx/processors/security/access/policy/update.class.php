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
 * Updates a policy
 *
 * @param integer $id The ID of the policy
 * @param string $name The name of the policy.
 * @param string $description (optional) A short description
 * @param integer $parent (optional) A parent policy
 * @param string $class
 * @param json $data The JSON-encoded policy data
 *
 * @package modx
 * @subpackage processors.security.access.policy
 */
class modAccessPolicyUpdateProcessor extends modObjectUpdateProcessor {
    public $classKey = 'modAccessPolicy';
    public $languageTopics = array('policy');
    public $permission = 'policy_save';
    public $objectType = 'policy';

    public function beforeSave() {
        /* now store the permissions into the modAccessPermission table */
        /* and cache the data into the policy table */
        $permissions = $this->getProperty('permissions',null);
        if ($permissions !== null) {
            $permData = array();
            $permissions = is_array($permissions) ? $permissions : $this->modx->fromJSON($permissions);
            foreach ($permissions as $permissionArray) {
                $permData[$permissionArray['name']] = $permissionArray['enabled'] ? true : false;
            }
            $this->object->set('data',$permData);
        }

        return parent::beforeSave();
    }

    public function afterSave() {
        $this->modx->cacheManager->flushPermissions();
        return parent::afterSave();
    }
}
return 'modAccessPolicyUpdateProcessor';
