<?php
/**
 * Gets a list of users in a usergroup
 *
 * @param boolean $combo (optional) If true, will append a (anonymous) row
 * @param integer $start (optional) The record to start at. Defaults to 0.
 * @param integer $limit (optional) The number of records to limit to. Defaults
 * to 10.
 * @param string $sort (optional) The column to sort by. Defaults to name.
 * @param string $dir (optional) The direction of the sort. Defaults to ASC.
 *
 * @package modx
 * @subpackage processors.security.group
 */
class modUserGroupUserGetListProcessor extends modProcessor {
    public function checkPermissions() {
        return $this->modx->hasPermission('access_permissions');
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
            'username' => '',
        ));
        return true;
    }

    public function process() {
        $data = $this->getData();
        $list = array();
        /** @var modUser $user */
        foreach ($data['results'] as $user) {
            $userArray = $user->toArray();
            $list[] = $userArray;
        }
        return $this->outputArray($list,$data['total']);
    }

    /**
     * @return array
     */
    public function getData() {
        $data = array();
        
        /* build query */
        $c = $this->modx->newQuery('modUser');
        $c->innerJoin('modUserGroupMember','UserGroupMembers');
        $c->innerJoin('modUserGroup','UserGroup','UserGroupMembers.user_group = UserGroup.id');
        $c->leftJoin('modUserGroupRole','UserGroupRole','UserGroupMembers.role = UserGroupRole.id');

        $userGroup = $this->getProperty('usergroup',0);
        $c->where(array(
            'UserGroupMembers.user_group' => $userGroup,
        ));

        $username = $this->getProperty('username','');
        if (!empty($username)) {
            $c->where(array(
                'modUser.username:LIKE' => '%'.$username.'%',
            ));
        }

        $data['total'] = $this->modx->getCount('modUser',$c);

        $c->select($this->modx->getSelectColumns('modUser','modUser'));
        $c->select(array(
            'usergroup' => 'UserGroup.id',
            'usergroup_name' => 'UserGroup.name',
            'role' => 'UserGroupRole.id',
            'role_name' => 'UserGroupRole.name',
            'authority' => 'UserGroupRole.authority',
        ));
        $c->sortby('authority','ASC');
        $c->sortby($this->getProperty('sort'),$this->getProperty('dir'));
        if ($this->getProperty('limit') > 0) {
            $c->limit($this->getProperty('limit'),$this->getProperty('start'));
        }
        $data['results'] = $this->modx->getCollection('modUser',$c);
        return $data;
    }
}
return 'modUserGroupUserGetListProcessor';