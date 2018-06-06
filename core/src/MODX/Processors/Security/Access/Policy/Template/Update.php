<?php

namespace MODX\Processors\Security\Access\Policy\Template;

use MODX\modAccessPermission;
use MODX\modAccessPolicy;
use MODX\Processors\modObjectUpdateProcessor;


/**
 * Updates a policy template
 *
 * @param integer $id The ID of the policy template
 * @param string $name The name of the policy template
 * @param string $description (optional) A short description
 * @param string $data The JSON-encoded policy permissions
 *
 * @package modx
 * @subpackage processors.security.access.policy.template
 */
class Update extends modObjectUpdateProcessor
{
    public $classKey = 'modAccessPolicyTemplate';
    public $languageTopics = ['policy'];
    public $permission = 'policy_template_save';
    public $objectType = 'policy';


    public function afterSave()
    {
        /* now store the permissions into the modAccessPermission table */
        /* and cache the data into the policy table */
        $permissions = $this->getProperty('permissions', null);
        if ($permissions !== null) {
            $new_permissions_list = [];

            if (!is_array($permissions)) {
                $permissions = json_decode($permissions, true);

                foreach ($permissions as $permission_item) {
                    $new_permissions_list[] = $permission_item['name'];
                }
            } else {
                $new_permissions_list = $permissions;
            }

            $deleted = [];

            /* first erase all prior permissions */
            $oldPermissions = $this->object->getMany('Permissions');
            /** @var modAccessPermission $permission */
            foreach ($oldPermissions as $permission) {
                if (!in_array($permission->get('name'), $new_permissions_list)) {
                    $deleted[] = $permission->get('name');
                }

                $permission->remove();
            }

            $added = [];
            foreach ($permissions as $permissionArray) {
                if (in_array($permissionArray['name'], $added)) {
                    continue;
                }

                $permission = $this->modx->newObject('modAccessPermission');

                $permission->set('template', $this->object->get('id'));
                $permission->set('name', $permissionArray['name']);
                $permission->set('description', $permissionArray['description']);
                $permission->set('value', true);

                $permission->save();

                $added[] = $permissionArray['name'];
            }

            // update all existing policies if needed
            if (!empty($deleted)) {
                $policies = $this->object->getMany('Policies');

                /** @var modAccessPolicy $policy */
                foreach ($policies as $policy) {
                    $policy_data = $policy->get('data');

                    foreach ($deleted as $deleted_perm) {
                        if (isset($policy_data[$deleted_perm])) {
                            unset($policy_data[$deleted_perm]);
                        }
                    }

                    $policy->set('data', $policy_data);

                    $policy->save();
                }
            }
        }

        return parent::afterSave();
    }
}