<?php
/**
 * Updates a policy template
 *
 * @param integer $id The ID of the policy template
 * @param string $name The name of the policy template
 * @param string $description (optional) A short description
 * @param json $data The JSON-encoded policy permissions
 *
 * @package modx
 * @subpackage processors.security.access.policy.template
 */
class modAccessPolicyTemplateUpdateProcessor extends modObjectUpdateProcessor {
    public $classKey = 'modAccessPolicyTemplate';
    public $languageTopics = array('policy');
    public $permission = 'policy_template_save';
    public $objectType = 'policy';

    public function afterSave() {
        /* now store the permissions into the modAccessPermission table */
        /* and cache the data into the policy table */
        $permissions = $this->getProperty('permissions',null);
        if ($permissions !== null) {
            $permissions = is_array($permissions) ? $permissions : $this->modx->fromJSON($permissions);

            /* first erase all prior permissions */
            $oldPermissions = $this->object->getMany('Permissions');
            /** @var modAccessPermission $permission */
            foreach ($oldPermissions as $permission) {
                $permission->remove();
            }

            $added = array();
            foreach ($permissions as $permissionArray) {
                if (in_array($permissionArray['name'],$added)) continue;
                $permission = $this->modx->newObject('modAccessPermission');
                $permission->set('template',$this->object->get('id'));
                $permission->set('name',$permissionArray['name']);
                $permission->set('description',$permissionArray['description']);
                $permission->set('value',true);
                $permission->save();
                $added[] = $permissionArray['name'];
            }
        }
        return parent::afterSave();
    }
}
return 'modAccessPolicyTemplateUpdateProcessor';