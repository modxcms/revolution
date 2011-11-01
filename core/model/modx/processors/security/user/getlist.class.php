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
class modUserGetListProcessor extends modProcessor {
    public function checkPermissions() {
        return $this->modx->hasPermission('view_user');
    }
    public function getLanguageTopics() {
        return array('user');
    }

    public function initialize() {
        $this->setDefaultProperties(array(
            'start' => 0,
            'limit' => 10,
            'sort' => 'username',
            'dir' => 'ASC',
            'usergroup' => false,
            'query' => '',
        ));
        if ($this->getProperty('sort') == 'username_link') $this->setProperty('sort','username');
        if ($this->getProperty('sort') == 'id') $this->setProperty('sort','modUser.id');
        return true;
    }

    public function process() {
        $data = $this->getData();
        $list = array();
        
        foreach ($data['results'] as $user) {
            $userArray = $this->prepareRow($user);
            if (!empty($userArray)) {
                $list[] = $userArray;
            }
        }

        return $this->outputArray($list,$data['total']);
    }

    /**
     * Get the collection of modUser objects
     * @return array
     */
    public function getData() {
        $data = array();
        $limit = intval($this->getProperty('limit'));

        /* query for users */
        $c = $this->modx->newQuery('modUser');
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
        
        $data['total'] = $this->modx->getCount('modUser',$c);
        
        $c->select($this->modx->getSelectColumns('modUser','modUser'));
        $c->select($this->modx->getSelectColumns('modUserProfile','Profile','',array('fullname','email','blocked')));
        $c->sortby($this->getProperty('sort'),$this->getProperty('dir'));
        if ($limit > 0) {
            $c->limit($limit,$this->getProperty('start'));
        }

        $data['results'] = $this->modx->getCollection('modUser',$c);
        return $data;
    }

    /**
     * Prepare the row for iteration
     * @param modUser $user
     * @return array
     */
    public function prepareRow(modUser $user) {
        $userArray = $user->toArray();
        $userArray['blocked'] = $user->get('blocked') ? true : false;
        $userArray['cls'] = 'pupdate premove';
        unset($userArray['password'],$userArray['cachepwd']);
        return $userArray;
    }
}
return 'modUserGetListProcessor';