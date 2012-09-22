<?php
/**
 * Create a user group
 *
 * @param string $name (optional) The name of the new user group. Defaults to
 * Untitled User Group.
 * @param integer $parent (optional) The ID of the parent user group. Defaults
 * to 0.
 *
 * @package modx
 * @subpackage processors.security.group
 */
class modUserGroupCreateProcessor extends modObjectCreateProcessor {
    public $classKey = 'modUserGroup';
    public $languageTopics = array('user');
    public $permission = 'usergroup_new';
    public $objectType = 'user_group';
    public $beforeSaveEvent = 'OnUserGroupBeforeFormSave';
    public $afterSaveEvent = 'OnUserGroupFormSave';

    public function initialize() {
        $this->setDefaultProperties(array(
            'parent' => 0,
        ));
        return parent::initialize();
    }

    public function beforeSave() {
        $this->setUsersIn();

        $name = $this->getProperty('name');
        if (empty($name)) {
            $this->addFieldError('name',$this->modx->lexicon('user_group_err_ns_name'));
        }
        $parent = $this->getProperty('parent');
        if (empty($parent)) {
            $this->setProperty('parent',0);
        }

        if ($this->doesAlreadyExist(array('name' => $name))) {
            $this->addFieldError('name',$this->modx->lexicon('user_group_err_already_exists'));
        }

        return parent::beforeSave();
    }

    public function afterSave() {
        $this->setContexts();
        if ($this->modx->hasPermission('usergroup_user_edit')) {
            $this->setResourceGroups();
        }

        /* access wizard stuff */
        $flush = false;
        $users = $this->getProperty('aw_users','');
        if (!empty($users)) {
            $this->addUsersViaWizard($users);
        }
        $contexts = $this->getProperty('aw_contexts','');
        if (!empty($contexts)) {
            $contexts = is_array($contexts) ? $contexts : explode(',',$contexts);
            $contexts = array_unique($contexts);

            $adminPolicy = trim($this->getProperty('aw_manager_policy',0));
            if (!empty($adminPolicy)) {
                $this->addManagerContextAccessViaWizard($adminPolicy);
            }

            $policy = trim($this->getProperty('aw_contexts_policy',0));
            if ($this->addContextAccessViaWizard($contexts,$policy)) {
                $flush = true;
            }

            $resourceGroups = $this->getProperty('aw_resource_groups','');
            if (!empty($resourceGroups)) {
                $this->addResourceGroupsViaWizard($resourceGroups,$contexts);
            }

            $categories = $this->getProperty('aw_categories','');
            if (!empty($categories)) {
                $this->addElementCategoriesViaWizard($categories,$contexts);
            }

            $parallel = $this->getProperty('aw_parallel',false);
            if ($parallel) {
                $this->addParallelResourceGroup($contexts);
            }
        }

        if ($flush) {
            $this->flushPermissions();
        }

        return parent::afterSave();
    }

    /**
     * Add user groups via a wizard property, which is a comma-separated list of username:role key pairs, ie:
     * jimbob:Member,johndoe:Administrator,marksmith
     *
     * If the Role is left off, it will default to the Member role.
     *
     * @param string|array $users
     * @return bool
     */
    public function addUsersViaWizard($users) {
        $users = is_array($users) ? $users : explode(',',$users);
        $users = array_unique($users);
        foreach ($users as $userKey) {
            $userKey = explode(':',$userKey);
            $c = intval($userKey[0]) > 0 ? trim($userKey[0]) : array('username' => trim($userKey[0]));
            /** @var modUser $user */
            $user = $this->modx->getObject('modUser',$c);
            if (empty($user)) continue;

            /** @var modUserGroupRole $role */
            if (empty($userKey[1])) $userKey[1] = 'Member';
            $c = intval($userKey[1]) > 0 ? trim($userKey[1]) : array('name' => trim($userKey[1]));
            $role = $this->modx->getObject('modUserGroupRole',$c);
            if (empty($role)) continue;

            /** @var modUserGroupMember $membership */
            $membership = $this->modx->newObject('modUserGroupMember');
            $membership->set('user_group',$this->object->get('id'));
            $membership->set('member',$user->get('id'));
            $membership->set('role',$role->get('id'));
            $membership->save();
        }
        return true;
    }

    /**
     * Add Manager Access via wizard property with a specified policy.
     *
     * @param int|string $adminPolicy
     * @return bool
     */
    public function addManagerContextAccessViaWizard($adminPolicy) {
        $c = intval($adminPolicy) > 0 ? $adminPolicy : array('name' => $adminPolicy);
        /** @var modAccessPolicy $policy */
        $policy = $this->modx->getObject('modAccessPolicy',$c);
        if (!$policy) return false;

        /** @var modAccessResourceGroup $acl */
        $acl = $this->modx->newObject('modAccessContext');
        $acl->fromArray(array(
            'target' => 'mgr',
            'principal_class' => 'modUserGroup',
            'principal' => $this->object->get('id'),
            'authority' => 9999,
            'policy' => $policy->get('id'),
        ));
        $acl->save();

        return true;
    }

    /**
     * Add Context Access via wizard property.
     *
     * @param array $contexts
     * @return boolean
     */
    public function addContextAccessViaWizard(array $contexts) {
        /** @var modAccessPolicy $policy */
        $policy = $this->modx->getObject('modAccessPolicy',array(
            'name' => 'Context',
        ));
        if (!$policy) return false;

        foreach ($contexts as $context) {
            /** @var modAccessResourceGroup $acl */
            $acl = $this->modx->newObject('modAccessContext');
            $acl->fromArray(array(
                'target' => trim($context),
                'principal_class' => 'modUserGroup',
                'principal' => $this->object->get('id'),
                'authority' => 9999,
                'policy' => $policy->get('id'),
            ));
            $acl->save();
        }
        return true;
    }

    /**
     * @param string|array $resourceGroupNames
     * @param array $contexts
     * @return boolean
     */
    public function addResourceGroupsViaWizard($resourceGroupNames,array $contexts) {
        $resourceGroupNames = is_array($resourceGroupNames) ? $resourceGroupNames : explode(',',$resourceGroupNames);
        $resourceGroupNames = array_unique($resourceGroupNames);

        /** @var modAccessPolicy $policy */
        $policy = $this->modx->getObject('modAccessPolicy',array('name' => 'Resource'));
        if (!$policy) return false;

        foreach ($resourceGroupNames as $resourceGroupName) {
            /** @var modResourceGroup $resourceGroup */
            $resourceGroup = $this->modx->getObject('modResourceGroup',array('name' => trim($resourceGroupName)));
            if (!$resourceGroup) return false;

            foreach ($contexts as $context) {
                /** @var modAccessResourceGroup $acl */
                $acl = $this->modx->newObject('modAccessResourceGroup');
                $acl->fromArray(array(
                    'target' => $resourceGroup->get('id'),
                    'principal_class' => 'modUserGroup',
                    'principal' => $this->object->get('id'),
                    'authority' => 9999,
                    'policy' => $policy->get('id'),
                    'context_key' => trim($context),
                ));
                $acl->save();
            }
        }
        return true;
    }


    /**
     * Adds a Resource Group with the same name and grants access for the specified Contexts
     *
     * @param array $contexts
     * @return boolean
     */
    public function addParallelResourceGroup(array $contexts) {
        /** @var modResourceGroup $resourceGroup */
        $resourceGroup = $this->modx->getObject('modResourceGroup',array(
            'name' => $this->object->get('name'),
        ));
        if (!$resourceGroup) {
            $resourceGroup = $this->modx->newObject('modResourceGroup');
            $resourceGroup->set('name',$this->object->get('name'));
            if (!$resourceGroup->save()) {
                return false;
            }
        }

        /** @var modAccessPolicy $policy */
        $policy = $this->modx->getObject('modAccessPolicy',array('name' => 'Resource'));
        if (!$policy) return false;

        foreach ($contexts as $context) {
            /** @var modAccessResourceGroup $acl */
            $acl = $this->modx->newObject('modAccessResourceGroup');
            $acl->fromArray(array(
                'target' => $resourceGroup->get('id'),
                'principal_class' => 'modUserGroup',
                'principal' => $this->object->get('id'),
                'authority' => 9999,
                'policy' => $policy->get('id'),
                'context_key' => trim($context),
            ));
            $acl->save();
        }
        return true;
    }

    /**
     * @param string|array $categoryNames
     * @param array $contexts
     * @return boolean
     */
    public function addElementCategoriesViaWizard($categoryNames,array $contexts) {
        $categoryNames = is_array($categoryNames) ? $categoryNames : explode(',',$categoryNames);
        $categoryNames = array_unique($categoryNames);

        /** @var modAccessPolicy $policy */
        $policy = $this->modx->getObject('modAccessPolicy',array('name' => 'Element'));
        if (!$policy) return false;

        foreach ($categoryNames as $categoryName) {
            /** @var modCategory $category */
            $category = $this->modx->getObject('modCategory',array('category' => trim($categoryName)));
            if (!$category) return false;

            foreach ($contexts as $context) {
                /** @var modAccessCategory $acl */
                $acl = $this->modx->newObject('modAccessCategory');
                $acl->fromArray(array(
                    'target' => $category->get('id'),
                    'principal_class' => 'modUserGroup',
                    'principal' => $this->object->get('id'),
                    'authority' => 9999,
                    'policy' => $policy->get('id'),
                    'context_key' => trim($context),
                ));
                $acl->save();
            }
        }
        return true;
    }

    public function flushPermissions() {
        $ctxQuery = $this->modx->newQuery('modContext');
        $ctxQuery->select($this->modx->getSelectColumns('modContext', '', '', array('key')));
        if ($ctxQuery->prepare() && $ctxQuery->stmt->execute()) {
            $contexts = $ctxQuery->stmt->fetchAll(PDO::FETCH_COLUMN);
            if ($contexts) {
                $serialized = serialize($contexts);
                $this->modx->exec("UPDATE {$this->modx->getTableName('modUser')} SET {$this->modx->escape('session_stale')} = {$this->modx->quote($serialized)}");
            }
        }
        return $this->success();
    }

    /**
     * Set the users in the group
     * @return array
     */
    public function setUsersIn() {
        $users = $this->getProperty('users');
        $memberships = array();
        if (!empty($users)) {
            $users = is_array($users) ? $users : $this->modx->fromJSON($users);
            $memberships = array();
            foreach ($users as $userArray) {
                if (empty($userArray['id']) || empty($userArray['role'])) continue;
                
                /** @var modUserGroupMember $membership */
                $membership = $this->modx->newObject('modUserGroupMember');
                $membership->set('user_group',$this->object->get('id'));
                $membership->set('member',$userArray['id']);
                $membership->set('role',$userArray['role']);
                $memberships[] = $membership;
            }
            $this->object->addMany($memberships);
        }
        return $memberships;
    }

    /**
     * Set the Context ACLs for the Group
     * @return array
     */
    public function setContexts() {
        $contexts = $this->getProperty('contexts');
        $access = array();
        if (!empty($contexts)) {
            $contexts = is_array($contexts) ? $contexts : $this->modx->fromJSON($contexts);
            foreach ($contexts as $context) {
                /** @var modAccessContext $acl */
                $acl = $this->modx->newObject('modAccessContext');
                $acl->fromArray($context);
                $acl->set('principal',$this->object->get('id'));
                $acl->set('principal_class','modUserGroup');
                if ($acl->save()) {
                    $access[] = $acl;
                }
            }
        }
        return $access;
    }

    /**
     * Set the Resource Group ACLs for the Group
     * @return array
     */
    public function setResourceGroups() {
        $resourceGroups = $this->getProperty('resource_groups');
        $access = array();
        if (!empty($resourceGroups)) {
            $resourceGroups = is_array($resourceGroups) ? $resourceGroups : $this->modx->fromJSON($resourceGroups);
            foreach ($resourceGroups as $resourceGroup) {
                /** @var modAccessResourceGroup $acl */
                $acl = $this->modx->newObject('modAccessResourceGroup');
                $acl->fromArray($resourceGroup);
                $acl->set('principal',$this->object->get('id'));
                $acl->set('principal_class','modUserGroup');
                if ($acl->save()) {
                    $access[] = $acl;
                }
            }
        }
        return $access;
    }
}
return 'modUserGroupCreateProcessor';