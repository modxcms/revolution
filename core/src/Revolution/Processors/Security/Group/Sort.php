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

use MODX\Revolution\Processors\Processor;
use MODX\Revolution\modUser;
use MODX\Revolution\modUserGroup;
use MODX\Revolution\modUserGroupMember;
use MODX\Revolution\modX;

/**
 * Sort users and user groups, effectively repositioning users into proper groups
 * @param string $data The encoded in JSON tree data
 * @package MODX\Revolution\Processors\Security\Group
 */
class Sort extends Processor
{
    /**
     * @return bool
     */
    public function checkPermissions()
    {
        return $this->modx->hasPermission('usergroup_save');
    }

    /**
     * @return array
     */
    public function getLanguageTopics()
    {
        return ['user'];
    }

    /**
     * @return array|mixed|string
     * @throws \xPDO\xPDOException
     */
    public function process()
    {
        $data = $this->getProperty('data');
        if (empty($data)) {
            return $this->failure($this->modx->lexicon('invalid_data'));
        }
        $data = urldecode($data);
        $data = $this->modx->fromJSON($data);
        if (empty($data)) {
            return $this->failure($this->modx->lexicon('invalid_data'));
        }

        $this->sortGroups($data);
        if ($this->modx->hasPermission('usergroup_user_edit')) {
            $this->sortUsers($data);
        }

        return $this->success();
    }

    /**
     * Sort and rearrange any groups in the data
     * @param array $data
     * @return void
     */
    public function sortGroups(array $data)
    {
        $groups = [];
        $this->getGroupsFormatted($groups, $data);

        /* readjust groups */
        foreach ($groups as $groupArray) {
            if (!empty($groupArray['id'])) {
                /** @var modUserGroup $userGroup */
                $userGroup = $this->modx->getObject(modUserGroup::class, $groupArray['id']);
                if ($userGroup === null) {
                    $this->modx->log(modX::LOG_LEVEL_ERROR,
                        'Could not sort group ' . $groupArray['id'] . ' because it could not be found.');
                    continue;
                }
                $oldParentId = $userGroup->get('parent');
            } else {
                $userGroup = $this->modx->newObject(modUserGroup::class);
                $oldParentId = 0;
            }

            if ($groupArray['parent'] === $userGroup->get('id')) {
                continue;
            }

            if ($groupArray['parent'] === 0 || $oldParentId !== $groupArray['parent']) {
                /* get new parent, if invalid, skip, unless is root */
                if ($groupArray['parent'] !== 0) {
                    /** @var modUserGroup $parentUserGroup */
                    $parentUserGroup = $this->modx->getObject(modUserGroup::class, $groupArray['parent']);
                    if ($parentUserGroup === null) {
                        continue;
                    }
                    $depth = $parentUserGroup->get('depth') + 1;
                } else {
                    $depth = 0;
                }

                /* save new parent and depth */
                $userGroup->set('parent', $groupArray['parent']);
                $userGroup->set('depth', $depth);
            }
            if ($groupArray['id'] !== 0) {
                $userGroup->save();
            }
        }
    }

    /**
     * Sort and rearrange any users in the data
     * @param array $data
     * @return void
     */
    public function sortUsers(array $data)
    {
        $users = [];
        $this->getUsersFormatted($users, $data);
        /* readjust users */
        foreach ($users as $userArray) {
            if (empty($userArray['id'])) {
                continue;
            }
            /** @var modUser $user */
            $user = $this->modx->getObject(modUser::class, $userArray['id']);
            if ($user === null) {
                continue;
            }

            /* get new parent, if invalid, skip, unless is root */
            if ($userArray['new_group'] !== 0 && $userArray['new_group'] !== $userArray['old_group']) {
                /** @var modUserGroup $membership */
                $membership = $this->modx->getObject(modUserGroupMember::class, [
                    'user_group' => $userArray['new_group'],
                    'member' => $user->get('id'),
                ]);
                if ($membership === null) {
                    $membership = $this->modx->newObject(modUserGroupMember::class);
                    $membership->set('user_group', $userArray['new_group']);
                }
                $membership->set('member', $user->get('id'));
                if ($membership->save()) {
                    /* remove user from old group */
                    if (!empty($userArray['old_group'])) {
                        /** @var modUserGroup $oldMembership */
                        $oldMembership = $this->modx->getObject(modUserGroupMember::class, [
                            'user_group' => $userArray['old_group'],
                            'member' => $user->get('id'),
                        ]);
                        if ($oldMembership) {
                            $oldMembership->remove();
                        }
                    }
                }
            }
        }
    }

    /**
     * @param $ar_nodes
     * @param $cur_level
     * @param int $parent
     */
    protected function getGroupsFormatted(&$ar_nodes, $cur_level, $parent = 0)
    {
        $order = 0;
        foreach ($cur_level as $id => $children) {
            $id = substr($id, 2); /* get rid of CSS id n_ prefix */
            if (substr($id, 0, 2) === 'ug') {
                $ar_nodes[] = [
                    'id' => substr($id, 3),
                    'parent' => substr($parent, 3),
                    'order' => $order,
                ];
                $order++;
            }
            $this->getGroupsFormatted($ar_nodes, $children, $id);
        }
    }

    /**
     * @param $ar_nodes
     * @param $cur_level
     * @param int $parent
     */
    protected function getUsersFormatted(&$ar_nodes, $cur_level, $parent = 0)
    {
        $order = 0;
        foreach ($cur_level as $id => $children) {
            $id = substr($id, 2); /* get rid of CSS id n_ prefix */
            if (substr($id, 0, 4) === 'user') {
                $userMap = substr($id, 5);
                $userMap = explode('_', $userMap);
                $ar_nodes[] = [
                    'id' => $userMap[0],
                    'old_group' => $userMap[1],
                    'new_group' => substr($parent, 3),
                    'order' => $order,
                ];
                $order++;
            }
            $this->getUsersFormatted($ar_nodes, $children, $id);
        }
    }

}
