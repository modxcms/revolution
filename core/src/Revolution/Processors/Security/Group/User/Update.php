<?php
/*
 * This file is part of MODX Revolution.
 *
 * Copyright (c) MODX, LLC. All Rights Reserved.
 *
 * For complete copyright and license information, see the COPYRIGHT and LICENSE
 * files found in the top-level directory of this distribution.
 */

namespace MODX\Revolution\Processors\Security\Group\User;

use MODX\Revolution\Processors\Processor;
use MODX\Revolution\modUserGroupMember;

/**
 * Update a users role in a user group
 * @param integer $usergroup The ID of the user group
 * @param integer $user The ID of the user
 * @package MODX\Revolution\Processors\Security\Group\User
 */
class Update extends Processor
{
    /** @var modUserGroupMember $membership */
    public $membership;

    /**
     * @return bool
     */
    public function checkPermissions()
    {
        return $this->modx->hasPermission('usergroup_user_edit');
    }

    /**
     * @return array
     */
    public function getLanguageTopics()
    {
        return ['user'];
    }

    /**
     * @return bool|string|null
     */
    public function initialize()
    {
        $user = $this->getProperty('user');
        if (empty($user)) {
            return $this->modx->lexicon('user_err_ns');
        }
        $userGroup = $this->getProperty('usergroup');
        if (empty($userGroup)) {
            return $this->modx->lexicon('user_group_err_ns');
        }

        $this->membership = $this->modx->getObject(modUserGroupMember::class, [
            'member' => $this->getProperty('user', 0),
            'user_group' => $this->getProperty('usergroup', 0),
        ]);
        if ($this->membership === null) {
            return $this->modx->lexicon('user_group_member_err_nf');
        }

        return true;
    }

    /**
     * @return array|mixed|string
     */
    public function process()
    {
        $this->membership->fromArray($this->getProperties());

        if ($this->membership->save() === false) {
            return $this->failure($this->modx->lexicon('user_group_member_err_save'));
        }

        return $this->success('', $this->membership);
    }
}
