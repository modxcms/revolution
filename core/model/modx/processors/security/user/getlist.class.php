<?php
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
class modUserGetListProcessor extends modObjectGetListProcessor {
    public $classKey = 'modUser';
    public $languageTopics = array('user');
    public $permission = 'view_user';
    public $defaultSortField = 'username';

    public function initialize() {
        $initialized = parent::initialize();
        $this->setDefaultProperties(array(
            'usergroup' => false,
            'query' => '',
        ));
        if ($this->getProperty('sort') == 'username_link') $this->setProperty('sort','username');
        if ($this->getProperty('sort') == 'id') $this->setProperty('sort','modUser.id');
        return $initialized;
    }

    public function prepareQueryBeforeCount(xPDOQuery $c) {
        $c->leftJoin('modUserProfile','Profile');

        $query = $this->getProperty('query','');
        if (!empty($query)) {
            $c->where(array('modUser.username:LIKE' => '%'.$query.'%'));
            $c->orCondition(array('Profile.fullname:LIKE' => '%'.$query.'%'));
            $c->orCondition(array('Profile.email:LIKE' => '%'.$query.'%'));
        }

        $userGroup = $this->getProperty('usergroup',0);
        if (!empty($userGroup)) {
            $c->innerJoin('modUserGroupMember','UserGroupMembers');
            $c->where(array(
                'UserGroupMembers.user_group' => $userGroup,
            ));
        }
        return $c;
    }

    public function prepareQueryAfterCount(xPDOQuery $c) {
        $c->select($this->modx->getSelectColumns('modUser','modUser'));
        $c->select($this->modx->getSelectColumns('modUserProfile','Profile','',array('fullname','email','blocked')));
        return $c;
    }

    /**
     * Prepare the row for iteration
     * @param xPDOObject $object
     * @return array
     */
    public function prepareRow(xPDOObject $object) {
        $objectArray = $object->toArray();
        $objectArray['blocked'] = $object->get('blocked') ? true : false;
        $objectArray['cls'] = 'pupdate premove pcopy';
        unset($objectArray['password'],$objectArray['cachepwd'],$objectArray['salt']);
        return $objectArray;
    }
}
return 'modUserGetListProcessor';