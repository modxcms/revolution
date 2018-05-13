<?php

namespace MODX\Processors\Security\User\Group;

use MODX\Processors\modObjectGetListProcessor;
use xPDO\Om\xPDOQuery;

/**
 * Gets a list of groups for a user
 *
 * @package modx
 * @subpackage processors.security.user
 */
class GetList extends modObjectGetListProcessor
{
    public $classKey = 'modUserGroupMember';
    public $languageTopics = ['user'];
    public $permission = 'edit_user';
    public $defaultSortField = 'name';


    public function initialize()
    {
        $this->setDefaultProperties([
            'user' => 0,
        ]);

        return parent::initialize();
    }


    public function prepareQueryBeforeCount(xPDOQuery $c)
    {
        $c->innerJoin('modUserGroupRole', 'UserGroupRole');
        $c->innerJoin('modUserGroup', 'UserGroup');
        $c->innerJoin('modUser', 'User', [
            'User.id' => 'modUserGroupMember.member',
            'User.id' => $this->getProperty('user'),
        ]);
        $c->where([
            'modUserGroupMember.member' => $this->getProperty('user'),
        ]);

        return $c;
    }


    public function prepareQueryAfterCount(xPDOQuery $c)
    {
        $c->select($this->modx->getSelectColumns('modUserGroupMember', 'modUserGroupMember'));
        $c->select([
            'rolename' => 'UserGroupRole.name',
            'name' => 'UserGroup.name',
        ]);

        return $c;
    }
}