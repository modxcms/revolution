<?php

namespace MODX\Processors\Security\User;

use MODX\modUser;
use MODX\modUserGroup;
use MODX\Processors\modObjectRemoveProcessor;

/**
 * Deletes a user
 *
 * @param integer $id The ID of the user
 *
 * @package modx
 * @subpackage processors.security.user
 */
class Delete extends modObjectRemoveProcessor
{
    public $classKey = 'modUser';
    public $languageTopics = ['user'];
    public $permission = 'delete_user';
    public $objectType = 'user';
    public $beforeRemoveEvent = 'OnBeforeUserFormDelete';
    public $afterRemoveEvent = 'OnUserFormDelete';

    /** @var modUser $object */
    public $object;


    public function beforeRemove()
    {
        /* check if we are deleting our own record */
        if ($this->isSelf()) {
            return $this->modx->lexicon('user_err_cannot_delete_self');
        }

        /* ensure we cant delete last user in Administrator group */
        if ($this->isLastUserInAdministrators()) {
            return $this->modx->lexicon('user_err_cannot_delete_last_admin');
        }

        return parent::beforeRemove();
    }


    /**
     * See if the user is the active user
     *
     * @return boolean
     */
    public function isSelf()
    {
        return $this->object->get('id') == $this->modx->user->get('id');
    }


    /**
     * See if the user is the last member in the administrators group
     *
     * @return boolean
     */
    public function isLastUserInAdministrators()
    {
        $last = false;
        /** @var modUserGroup $group */
        $group = $this->modx->getObject('modUserGroup', ['name' => 'Administrator']);
        if ($group && $this->object->isMember('Administrator')) {
            $numberInAdminGroup = $this->modx->getCount('modUserGroupMember', [
                'user_group' => $group->get('id'),
            ]);
            if ($numberInAdminGroup <= 1) {
                $last = true;
            }
        }

        return $last;
    }
}