<?php

namespace MODX\Processors\Security\Access\Policy;

use MODX\Processors\modObjectUpdateProcessor;

/**
 * Updates a policy
 *
 * @param integer $id The ID of the policy
 * @param string $name The name of the policy.
 * @param string $description (optional) A short description
 * @param integer $parent (optional) A parent policy
 * @param string $class
 * @param string $data The JSON-encoded policy data
 *
 * @package modx
 * @subpackage processors.security.access.policy
 */
class Update extends modObjectUpdateProcessor
{
    public $classKey = 'modAccessPolicy';
    public $languageTopics = ['policy'];
    public $permission = 'policy_save';
    public $objectType = 'policy';


    public function beforeSave()
    {
        /* now store the permissions into the modAccessPermission table */
        /* and cache the data into the policy table */
        $permissions = $this->getProperty('permissions', null);
        if ($permissions !== null) {
            $permData = [];
            $permissions = is_array($permissions) ? $permissions : json_decode($permissions, true);
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