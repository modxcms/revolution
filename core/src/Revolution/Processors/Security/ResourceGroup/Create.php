<?php
/*
 * This file is part of MODX Revolution.
 *
 * Copyright (c) MODX, LLC. All Rights Reserved.
 *
 * For complete copyright and license information, see the COPYRIGHT and LICENSE
 * files found in the top-level directory of this distribution.
 */

namespace MODX\Revolution\Processors\Security\ResourceGroup;

use MODX\Revolution\modAccessPolicy;
use MODX\Revolution\modAccessResourceGroup;
use MODX\Revolution\modContext;
use MODX\Revolution\Processors\Model\CreateProcessor;
use MODX\Revolution\modResourceGroup;
use MODX\Revolution\modUser;
use MODX\Revolution\modUserGroup;
use PDO;

/**
 * Create a resource group
 * @param string $name The name of the new resource group
 * @package MODX\Revolution\Processors\Security\ResourceGroup
 */
class Create extends CreateProcessor
{
    public $classKey = modResourceGroup::class;
    public $languageTopics = ['access'];
    public $permission = 'resourcegroup_new';
    public $objectType = 'resource_group';
    public $beforeSaveEvent = 'OnResourceGroupBeforeSave';
    public $afterSaveEvent = 'OnResourceGroupSave';

    /**
     * @return bool
     */
    public function initialize()
    {
        $this->setDefaultProperties(['automatic_access' => false]);

        return parent::initialize();
    }

    /**
     * @return bool
     */
    public function beforeSave()
    {
        $name = $this->getProperty('name');
        if (empty($name)) {
            $this->addFieldError('name', $this->modx->lexicon('resource_group_ns_name'));
        }

        if ($this->doesAlreadyExist(['name' => $name])) {
            $this->addFieldError('name', $this->modx->lexicon('resource_group_err_ae'));
        }

        $this->setCheckbox('access_admin');
        $this->setCheckbox('access_anon');
        $this->setCheckbox('access_parallel');

        return parent::beforeSave();
    }

    /**
     * @return bool
     */
    public function afterSave()
    {
        $contexts = $this->getProperty('access_contexts', '');
        $contexts = is_array($contexts) ? $contexts : explode(',', $contexts);
        $contexts = array_unique($contexts);
        if (!empty($contexts)) {
            $flush = false;
            if ($this->getProperty('access_admin')) {
                if ($this->addAdminAccess($contexts)) {
                    $flush = true;
                }
            }
            if ($this->getProperty('access_anon')) {
                if ($this->addAnonymousAccess($contexts)) {
                    $flush = true;
                }
            }
            if ($this->getProperty('access_parallel')) {
                if ($this->addParallelUserGroup($contexts)) {
                    $flush = true;
                }
            }
            $userGroups = $this->getProperty('access_usergroups');
            if (!empty($userGroups)) {
                $userGroups = is_array($userGroups) ? $userGroups : explode(',', $userGroups);
                if ($this->addOtherUserGroups($userGroups, $contexts)) {
                    $flush = true;
                }
            }

            if ($flush) {
                $this->flushPermissions();
            }
        }

        return parent::afterSave();
    }

    /**
     * Give the Administrator User Group access to this Resource Group
     * @param array $contexts
     * @return boolean
     */
    protected function addAdminAccess(array $contexts = [])
    {
        /** @var modUserGroup $adminGroup */
        $adminGroup = $this->modx->getObject(modUserGroup::class, ['name' => 'Administrator']);
        if (!$adminGroup) {
            return false;
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
                'context_key' => trim($context),
                'target' => $this->object->get('id'),
                'principal_class' => modUserGroup::class,
                'principal' => $adminGroup->get('id'),
                'authority' => 9999,
                'policy' => $policy->get('id'),
            ]);
            $acl->save();
        }

        return true;
    }

    /**
     * Give anonymous users view access to this Resource Group
     * @param array $contexts
     * @return boolean
     */
    protected function addAnonymousAccess(array $contexts = [])
    {
        /** @var modAccessPolicy $policy */
        $policy = $this->modx->getObject(modAccessPolicy::class, ['name' => 'Load, List and View']);
        if (!$policy) {
            return false;
        }

        foreach ($contexts as $context) {
            /** @var modAccessResourceGroup $acl */
            $acl = $this->modx->newObject(modAccessResourceGroup::class);
            $acl->fromArray([
                'context_key' => trim($context),
                'target' => $this->object->get('id'),
                'principal_class' => modUserGroup::class,
                'principal' => 0,
                'authority' => 9999,
                'policy' => $policy->get('id'),
            ]);
            $acl->save();
        }
        return true;
    }

    /**
     * Create a User Group with the same name and give it access to this Resource Group
     * @param array $contexts
     * @return boolean
     */
    protected function addParallelUserGroup(array $contexts = [])
    {
        /** @var modUserGroup $userGroup */
        $userGroup = $this->modx->getObject(modUserGroup::class, [
            'name' => $this->object->get('name')
        ]);
        if (!$userGroup) {
            $userGroup = $this->modx->newObject(modUserGroup::class);
            $userGroup->set('name', $this->object->get('name'));
            if (!$userGroup->save()) {
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
                'context_key' => trim($context),
                'target' => $this->object->get('id'),
                'principal_class' => modUserGroup::class,
                'principal' => $userGroup->get('id'),
                'authority' => 9999,
                'policy' => $policy->get('id'),
            ]);
            $acl->save();
        }

        return true;
    }

    /**
     * Give a list of User Groups access to this Resource Group
     * @param array $userGroupNames
     * @param array $contexts
     * @return boolean
     */
    protected function addOtherUserGroups(array $userGroupNames = [], array $contexts = [])
    {
        $userGroupNames = array_unique($userGroupNames);

        /** @var modAccessPolicy $policy */
        $policy = $this->modx->getObject(modAccessPolicy::class, ['name' => 'Resource']);
        if (!$policy) {
            return false;
        }

        foreach ($userGroupNames as $userGroupName) {
            /** @var modUserGroup $userGroup */
            $userGroup = $this->modx->getObject(modUserGroup::class, ['name' => trim($userGroupName)]);
            if (!$userGroup) {
                return false;
            }

            foreach ($contexts as $context) {
                /** @var modAccessResourceGroup $acl */
                $acl = $this->modx->newObject(modAccessResourceGroup::class);
                $acl->fromArray([
                    'context_key' => trim($context),
                    'target' => $this->object->get('id'),
                    'principal_class' => modUserGroup::class,
                    'principal' => $userGroup->get('id'),
                    'authority' => 9999,
                    'policy' => $policy->get('id'),
                ]);
                $acl->save();
            }
        }

        return true;
    }

    /**
     * Flush the permissions
     * @return array|string
     */
    protected function flushPermissions()
    {
        $ctxQuery = $this->modx->newQuery(modContext::class);
        $ctxQuery->select($this->modx->getSelectColumns(modContext::class, '', '', ['key']));
        if ($ctxQuery->prepare() && $ctxQuery->stmt->execute()) {
            $contexts = $ctxQuery->stmt->fetchAll(PDO::FETCH_COLUMN);
            if ($contexts) {
                $serialized = serialize($contexts);
                $this->modx->exec("UPDATE {$this->modx->getTableName(modUser::class)} SET {$this->modx->escape('session_stale')} = {$this->modx->quote($serialized)}");
            }
        }
        return $this->success();
    }
}
