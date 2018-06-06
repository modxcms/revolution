<?php

namespace MODX\Processors\Security\Group;

use MODX\Processors\modObjectRemoveProcessor;

/**
 * Remove a user group
 *
 * @param integer $id The ID of the user group
 *
 * @package modx
 * @subpackage processors.security.group
 */
class Remove extends modObjectRemoveProcessor
{
    public $classKey = 'modUserGroup';
    public $languageTopics = ['user'];
    public $permission = 'usergroup_delete';
    public $objectType = 'user_group';
    public $beforeRemoveEvent = 'OnUserGroupBeforeFormRemove';
    public $afterRemoveEvent = 'OnUserGroupFormRemove';


    public function beforeRemove()
    {
        if ($this->isAdminGroup()) {
            return $this->modx->lexicon('user_group_err_remove_admin');
        }

        return parent::beforeRemove();
    }


    /**
     * See if this User Group is the Administrator group
     *
     * @return boolean
     */
    public function isAdminGroup()
    {
        return $this->object->get('id') == 1 || $this->object->get('name') == $this->modx->lexicon('administrator');
    }
}