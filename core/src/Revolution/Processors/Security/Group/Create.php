<?php
/*
 * This file is part of MODX Revolution.
 *
 * Copyright (c) MODX, LLC. All Rights Reserved.
 *
 * For complete copyright and license information, see the COPYRIGHT and LICENSE
 * files found in the top-level directory of this distribution.
 */

namespace MODX\Revolution\Processors\Security\Group;

use MODX\Revolution\modAccessCategory;
use MODX\Revolution\modAccessContext;
use MODX\Revolution\modAccessPolicy;
use MODX\Revolution\modAccessResourceGroup;
use MODX\Revolution\modCategory;
use MODX\Revolution\Processors\Model\CreateProcessor;
use MODX\Revolution\modResourceGroup;
use MODX\Revolution\modUser;
use MODX\Revolution\modUserGroup;
use MODX\Revolution\modUserGroupMember;
use MODX\Revolution\modUserGroupRole;

/**
 * Create a user group
 * @param string $name (optional) The name of the new user group. Defaults to Untitled User Group.
 * @param integer $parent (optional) The ID of the parent user group. Defaults to 0.
 * @package MODX\Revolution\Processors\Security\Group
 */
class Create extends CreateProcessor
{
    public $classKey = modUserGroup::class;
    public $languageTopics = ['user'];
    public $permission = 'usergroup_new';
    public $objectType = 'user_group';
    public $beforeSaveEvent = 'OnUserGroupBeforeFormSave';
    public $afterSaveEvent = 'OnUserGroupFormSave';

    /**
     * @return bool
     */
    public function initialize()
    {
        $this->setDefaultProperties(['parent' => 0]);

        return parent::initialize();
    }

    /**
     * @return bool
     * @throws \xPDO\xPDOException
     */
    public function beforeSave()
    {
        $this->setUsersIn();

        $name = $this->getProperty('name');
        if (empty($name)) {
            $this->addFieldError('name', $this->modx->lexicon('user_group_err_ns_name'));
        }
        $parent = $this->getProperty('parent');
        if (empty($parent)) {
            $this->setProperty('parent', 0);
        }

        if ($this->doesAlreadyExist(['name' => $name])) {
            $this->addFieldError('name', $this->modx->lexicon('user_group_err_already_exists'));
        }

        return parent::beforeSave();
    }

    /**
     * @return bool
     * @throws \xPDO\xPDOException
     */
    public function afterSave()
    {
        $this->setContexts();
        if ($this->modx->hasPermission('usergroup_user_edit')) {
            $this->setResourceGroups();
        }

        /* access wizard stuff */
        $flush = false;
        $users = $this->getProperty('aw_users', '');
        if (!empty($users)) {
            $this->addUsersViaWizard($users);
        }
        $contexts = $this->getProperty('aw_contexts', '');
        if (!empty($contexts)) {
            $contexts = is_array($contexts) ? $contexts : explode(',', $contexts);
            $contexts = array_unique($contexts);

            $adminPolicy = trim($this->getProperty('aw_manager_policy', 0));
            if (!empty($adminPolicy)) {
                $this->addManagerContextAccessViaWizard($adminPolicy);
            }

            $policy = trim($this->getProperty('aw_contexts_policy', 0));
            if ($this->addContextAccessViaWizard($contexts, $policy)) {
                $flush = true;
            }

            $resourceGroups = $this->getProperty('aw_resource_groups', '');
            if (!empty($resourceGroups)) {
                $this->addResourceGroupsViaWizard($resourceGroups, $contexts);
            }

            $categories = $this->getProperty('aw_categories', '');
            if (!empty($categories)) {
                $this->addElementCategoriesViaWizard($categories, $contexts);
            }

            $parallel = $this->getProperty('aw_parallel', false);
            if ($parallel) {
                $this->addParallelResourceGroup($contexts);
            }
        }

        if ($flush) {
            $this->modx->cacheManager->flushPermissions();
        }

        return parent::afterSave();
    }

    /**
     * Add user groups via a wizard property, which is a comma-separated list of username:role key pairs, ie:
     * jimbob:Member,johndoe:Administrator,marksmith
     * If the Role is left off, it will default to the Member role.
     * @param string|array $users
     * @return bool
     */
    public function addUsersViaWizard($users)
    {
        $users = is_array($users) ? $users : explode(',', $users);
        $users = array_unique($users);
        foreach ($users as $userKey) {
            $userKey = explode(':', $userKey);
            $c = (int)$userKey[0] > 0 ? trim($userKey[0]) : ['username' => trim($userKey[0])];
            /** @var modUser $user */
            $user = $this->modx->getObject(modUser::class, $c);
            if ($user === null) {
                continue;
            }

            /** @var modUserGroupRole $role */
            if (empty($userKey[1])) {
                $userKey[1] = 'Member';
            }
            $c = (int)$userKey[1] > 0 ? trim($userKey[1]) : ['name' => trim($userKey[1])];
            $role = $this->modx->getObject(modUserGroupRole::class, $c);
            if ($role === null) {
                continue;
            }

            /** @var modUserGroupMember $membership */
            $membership = $this->modx->newObject(modUserGroupMember::class);
            $membership->set('user_group', $this->object->get('id'));
            $membership->set('member', $user->get('id'));
            $membership->set('role', $role->get('id'));
            $membership->save();
        }

        return true;
    }

    /**
     * Add Manager Access via wizard property with a specified policy.
     * @param int|string $adminPolicy
     * @return bool
     */
    public function addManagerContextAccessViaWizard($adminPolicy)
    {
        $c = (int)$adminPolicy > 0 ? $adminPolicy : ['name' => $adminPolicy];
        /** @var modAccessPolicy $policy */
        $policy = $this->modx->getObject(modAccessPolicy::class, $c);
        if (!$policy) {
            return false;
        }

        /** @var modAccessResourceGroup $acl */
        $acl = $this->modx->newObject(modAccessContext::class);
        $acl->fromArray([
            'target' => 'mgr',
            'principal_class' => modUserGroup::class,
            'principal' => $this->object->get('id'),
            'authority' => 9999,
            'policy' => $policy->get('id'),
        ]);
        $acl->save();

        return true;
    }

    /**
     * Add Context Access via wizard property.
     * @param array $contexts
     * @return boolean
     */
    public function addContextAccessViaWizard(array $contexts)
    {
        /** @var modAccessPolicy $policy */
        $policy = $this->modx->getObject(modAccessPolicy::class, ['name' => 'Context']);
        if (!$policy) {
            return false;
        }

        foreach ($contexts as $context) {
            /** @var modAccessResourceGroup $acl */
            $acl = $this->modx->newObject(modAccessContext::class);
            $acl->fromArray([
                'target' => trim($context),
                'principal_class' => modUserGroup::class,
                'principal' => $this->object->get('id'),
                'authority' => 9999,
                'policy' => $policy->get('id'),
            ]);
            $acl->save();
        }
        return true;
    }

    /**
     * @param string|array $resourceGroupNames
     * @param array $contexts
     * @return boolean
     */
    public function addResourceGroupsViaWizard($resourceGroupNames, array $contexts)
    {
        $resourceGroupNames = is_array($resourceGroupNames) ? $resourceGroupNames : explode(',', $resourceGroupNames);
        $resourceGroupNames = array_unique($resourceGroupNames);

        /** @var modAccessPolicy $policy */
        $policy = $this->modx->getObject(modAccessPolicy::class, ['name' => 'Resource']);
        if (!$policy) {
            return false;
        }

        foreach ($resourceGroupNames as $resourceGroupName) {
            /** @var modResourceGroup $resourceGroup */
            $resourceGroup = $this->modx->getObject(modResourceGroup::class, ['name' => trim($resourceGroupName)]);
            if (!$resourceGroup) {
                return false;
            }

            foreach ($contexts as $context) {
                /** @var modAccessResourceGroup $acl */
                $acl = $this->modx->newObject(modAccessResourceGroup::class);
                $acl->fromArray([
                    'target' => $resourceGroup->get('id'),
                    'principal_class' => modUserGroup::class,
                    'principal' => $this->object->get('id'),
                    'authority' => 9999,
                    'policy' => $policy->get('id'),
                    'context_key' => trim($context),
                ]);
                $acl->save();
            }
        }
        return true;
    }

    /**
     * Adds a Resource Group with the same name and grants access for the specified Contexts
     * @param array $contexts
     * @return boolean
     */
    public function addParallelResourceGroup(array $contexts)
    {
        /** @var modResourceGroup $resourceGroup */
        $resourceGroup = $this->modx->getObject(modResourceGroup::class, [
            'name' => $this->object->get('name')
        ]);
        if (!$resourceGroup) {
            $resourceGroup = $this->modx->newObject(modResourceGroup::class);
            $resourceGroup->set('name', $this->object->get('name'));
            if (!$resourceGroup->save()) {
                return false;
            }
        }

        /** @var modAccessPolicy $policy */
        $policy = $this->modx->getObject(modAccessPolicy::class, ['name' => 'Resource']);
        if (!$policy) {
            return false;
        }

        foreach ($contexts as $context) {
            /** @var modAccessResourceGroup $acl */
            $acl = $this->modx->newObject(modAccessResourceGroup::class);
            $acl->fromArray([
                'target' => $resourceGroup->get('id'),
                'principal_class' => modUserGroup::class,
                'principal' => $this->object->get('id'),
                'authority' => 9999,
                'policy' => $policy->get('id'),
                'context_key' => trim($context),
            ]);
            $acl->save();
        }
        return true;
    }

    /**
     * @param string|array $categoryNames
     * @param array $contexts
     * @return boolean
     */
    public function addElementCategoriesViaWizard($categoryNames, array $contexts)
    {
        $categoryNames = is_array($categoryNames) ? $categoryNames : explode(',', $categoryNames);
        $categoryNames = array_unique($categoryNames);

        /** @var modAccessPolicy $policy */
        $policy = $this->modx->getObject(modAccessPolicy::class, ['name' => 'Element']);
        if (!$policy) {
            return false;
        }

        foreach ($categoryNames as $categoryName) {
            /** @var modCategory $category */
            $category = $this->modx->getObject(modCategory::class, ['category' => trim($categoryName)]);
            if (!$category) {
                return false;
            }

            foreach ($contexts as $context) {
                /** @var modAccessCategory $acl */
                $acl = $this->modx->newObject(modAccessCategory::class);
                $acl->fromArray([
                    'target' => $category->get('id'),
                    'principal_class' => modUserGroup::class,
                    'principal' => $this->object->get('id'),
                    'authority' => 9999,
                    'policy' => $policy->get('id'),
                    'context_key' => trim($context),
                ]);
                $acl->save();
            }
        }
        return true;
    }

    /**
     * Set the users in the group
     * @return array
     * @throws \xPDO\xPDOException
     */
    public function setUsersIn()
    {
        $users = $this->getProperty('users');
        $memberships = [];
        if (!empty($users)) {
            $users = is_array($users) ? $users : $this->modx->fromJSON($users);
            $memberships = [];
            foreach ($users as $userArray) {
                if (empty($userArray['id']) || empty($userArray['role'])) {
                    continue;
                }

                /** @var modUserGroupMember $membership */
                $membership = $this->modx->newObject(modUserGroupMember::class);
                $membership->set('user_group', $this->object->get('id'));
                $membership->set('member', $userArray['id']);
                $membership->set('role', $userArray['role']);
                $memberships[] = $membership;
            }
            $this->object->addMany($memberships);
        }
        return $memberships;
    }

    /**
     * Set the Context ACLs for the Group
     * @return array
     * @throws \xPDO\xPDOException
     */
    public function setContexts()
    {
        $contexts = $this->getProperty('contexts');
        $access = [];
        if (!empty($contexts)) {
            $contexts = is_array($contexts) ? $contexts : $this->modx->fromJSON($contexts);
            foreach ($contexts as $context) {
                /** @var modAccessContext $acl */
                $acl = $this->modx->newObject(modAccessContext::class);
                $acl->fromArray($context);
                $acl->set('principal', $this->object->get('id'));
                $acl->set('principal_class', modUserGroup::class);
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
     * @throws \xPDO\xPDOException
     */
    public function setResourceGroups()
    {
        $resourceGroups = $this->getProperty('resource_groups');
        $access = [];
        if (!empty($resourceGroups)) {
            $resourceGroups = is_array($resourceGroups) ? $resourceGroups : $this->modx->fromJSON($resourceGroups);
            foreach ($resourceGroups as $resourceGroup) {
                /** @var modAccessResourceGroup $acl */
                $acl = $this->modx->newObject(modAccessResourceGroup::class);
                $acl->fromArray($resourceGroup);
                $acl->set('principal', $this->object->get('id'));
                $acl->set('principal_class', modUserGroup::class);
                if ($acl->save()) {
                    $access[] = $acl;
                }
            }
        }

        return $access;
    }
}
