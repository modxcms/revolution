<?php

namespace MODX\Processors\Security\Profile;

use MODX\modUser;
use MODX\modUserGroupMember;
use MODX\Processors\modProcessor;

/**
 * Get a user profile
 *
 * @param integer $id The ID of the user
 *
 * @package modx
 * @subpackage processors.security.profile
 */
class Get extends modProcessor
{
    /** @var modUser $user */
    public $user;


    public function checkPermissions()
    {
        return $this->modx->hasPermission('change_profile');
    }


    public function getLanguageTopics()
    {
        return ['user'];
    }


    public function initialize()
    {
        $id = $this->getProperty('id');
        if (empty($id)) return $this->modx->lexicon('user_err_ns');
        $this->user = $this->modx->getObject('modUser', $id);
        if (!$this->user) return $this->modx->lexicon('user_err_not_found');

        return true;
    }


    public function process()
    {
        /* if set, get groups for user */
        if ($this->getProperty('getGroups', false)) {
            $this->getUserGroups();
        }

        $userArray = $this->user->toArray();
        $profile = $this->user->getOne('Profile');
        if ($profile) {
            $userArray = array_merge($profile->toArray(), $userArray);
        }

        $userArray['dob'] = !empty($userArray['dob']) ? strftime('%m/%d/%Y', $userArray['dob']) : '';
        $userArray['blockeduntil'] = !empty($userArray['blockeduntil'])
            ? strftime('%m/%d/%Y %I:%M %p', $userArray['blockeduntil']) : '';
        $userArray['blockedafter'] = !empty($userArray['blockedafter'])
            ? strftime('%m/%d/%Y %I:%M %p', $userArray['blockedafter']) : '';
        $userArray['lastlogin'] = !empty($userArray['lastlogin']) ? strftime('%m/%d/%Y', $userArray['lastlogin']) : '';

        return $this->success('', $userArray);
    }


    /**
     * Get the User Groups for the user
     *
     * @return array
     */
    public function getUserGroups()
    {
        $c = $this->modx->newQuery('modUserGroupMember');
        $c->leftJoin('modUserGroupRole', 'UserGroupRole');
        $c->innerJoin('modUserGroup', 'UserGroup');
        $c->where([
            'member' => $this->user->get('id'),
        ]);
        $c->select($this->modx->getSelectColumns('modUserGroupMember', 'modUserGroupMember'));
        $c->select([
            'role_name' => 'UserGroupRole.name',
            'user_group_name' => 'UserGroup.name',
        ]);
        $members = $this->modx->getCollection('modUserGroupMember', $c);

        $data = [];
        /** @var modUserGroupMember $member */
        foreach ($members as $member) {
            $roleName = $member->get('role_name');
            if ($member->get('role') == 0) {
                $roleName = $this->modx->lexicon('none');
            }
            $data[] = [
                $member->get('user_group'),
                $member->get('user_group_name'),
                $member->get('member'),
                $member->get('role'),
                empty($roleName) ? '' : $roleName,
            ];
        }
        $this->user->set('groups', '(' . json_encode($data) . ')');

        return $data;
    }
}