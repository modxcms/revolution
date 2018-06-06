<?php

namespace MODX\Processors\Security\User;

use MODX\Processors\modObjectGetListProcessor;
use xPDO\Om\xPDOObject;
use xPDO\Om\xPDOQuery;

/**
 * Gets a list of users
 *
 * @param string $username (optional) Will filter the grid by searching for this
 * username
 * @param integer $start (optional) The record to start at. Defaults to 0.
 * @param integer $limit (optional) The number of records to limit to. Defaults
 * to 10.
 * @param string $sort (optional) The column to sort by. Defaults to name.
 * @param string $dir (optional) The direction of the sort. Defaults to ASC.
 *
 * @package modx
 * @subpackage processors.security.user
 */
class GetList extends modObjectGetListProcessor
{
    public $classKey = 'modUser';
    public $languageTopics = ['user'];
    public $permission = 'view_user';
    public $defaultSortField = 'username';


    public function initialize()
    {
        $initialized = parent::initialize();
        $this->setDefaultProperties([
            'usergroup' => false,
            'query' => '',
        ]);
        if ($this->getProperty('sort') == 'username_link') {
            $this->setProperty('sort', 'username');
        }
        if ($this->getProperty('sort') == 'id') {
            $this->setProperty('sort', $this->classKey . '.id');
        }

        return $initialized;
    }


    public function prepareQueryBeforeCount(xPDOQuery $c)
    {
        $c->leftJoin('modUserProfile', 'Profile');

        $queryChunks = explode(':', $this->getProperty('query', ''));
        if (count($queryChunks) == 2) {
            list($field, $query) = $queryChunks;
            if (in_array($field, array_keys($this->modx->getFields('modUserProfile')))) {
                $c->where(["Profile.$field:LIKE" => '%' . $query . '%']);
            }
        } else {
            $query = current($queryChunks);
            if (!empty($query)) {
                $c->where([
                    $this->classKey . '.username:LIKE' => '%' . $query . '%',
                    'Profile.fullname:LIKE' => '%' . $query . '%',
                    'Profile.email:LIKE' => '%' . $query . '%',
                ], xPDOQuery::SQL_OR);
            }
        }

        $userGroup = $this->getProperty('usergroup', 0);
        if (!empty($userGroup)) {
            if ($userGroup === 'anonymous') {
                $c->join('modUserGroupMember', 'UserGroupMembers', 'LEFT OUTER JOIN');
                $c->where([
                    'UserGroupMembers.user_group' => null,
                ]);
            } else {
                $c->distinct();
                $c->innerJoin('modUserGroupMember', 'UserGroupMembers');
                $c->where([
                    'UserGroupMembers.user_group' => $userGroup,
                ]);
            }
        }

        return $c;
    }


    public function prepareQueryAfterCount(xPDOQuery $c)
    {
        $c->select($this->modx->getSelectColumns('modUser', 'modUser'));
        $c->select($this->modx->getSelectColumns('modUserProfile', 'Profile', '', ['fullname', 'email', 'blocked']));

        return $c;
    }


    /**
     * Prepare the row for iteration
     *
     * @param xPDOObject $object
     *
     * @return array
     */
    public function prepareRow(xPDOObject $object)
    {
        $objectArray = $object->toArray();
        $objectArray['blocked'] = $object->get('blocked') ? true : false;
        $objectArray['cls'] = 'pupdate premove pcopy';
        unset($objectArray['password'], $objectArray['cachepwd'], $objectArray['salt']);

        return $objectArray;
    }
}

return 'modUserGetListProcessor';
