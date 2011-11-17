<?php
/**
 * Get the user groups in tree node format
 *
 * @param string $id The parent ID
 *
 * @package modx
 * @subpackage processors.security.group
 */
class modSecurityGroupGetNodesProcessor extends modProcessor {
    /** @var string $id */
    public $id;
    /** @var modUserGroup $userGroup */
    public $userGroup;
    
    /**
     * {@inheritDoc}
     * @return boolean
     */
    public function checkPermissions() {
        return $this->modx->hasPermission('usergroup_view');
    }
    /**
     * {@inheritDoc}
     * @return array
     */
    public function getLanguageTopics() {
        return array('user');
    }

    /**
     * {@inheritDoc}
     *
     * @return mixed
     */
    public function initialize() {
        $this->setDefaultProperties(array(
            'id' => 0,
            'sort' => 'name',
            'dir' => 'ASC',
            'showAnonymous' => true,
        ));
        return true;
    }

    /**
     * {@inheritDoc}
     * 
     * @return mixed
     */
    public function process() {
        $this->id = $this->parseId($this->getProperty('id'));
        $this->getUserGroup();

        $groups = $this->getGroups();

        $list = array();
        $list = $this->addAnonymous($list);
        
        /** @var modUserGroup $group */
        foreach ($groups['results'] as $group) {
            $groupArray = $this->prepareGroup($group);
            if (!empty($groupArray)) {
                $list[] = $groupArray;
            }
        }

        if ($this->userGroup && $this->modx->hasPermission('usergroup_user_list')) {
            $users = $this->getUsers();
            /** @var modUser $user */
            foreach ($users['results'] as $user) {
                $userArray = $this->prepareUser($user);
                if (!empty($userArray)) {
                    $list[] = $userArray;
                }
            }
        }
        return $this->toJSON($list);
    }

    /**
     * Parse the ID to get the parent group
     * @param string $id
     * @return mixed
     */
    protected function parseId($id) {
        return str_replace('n_ug_','',$id);
    }

    /**
     * Get the selected user group
     * @return modUserGroup|null
     */
    public function getUserGroup() {
        if (!empty($this->id)) {
            $this->userGroup = $this->modx->getObject('modUserGroup',$this->id);
        }
        return $this->userGroup;
    }

    /**
     * Get the User Groups within the filter
     * @return array
     */
    public function getGroups() {
        $data = array();
        $c = $this->modx->newQuery('modUserGroup');
        $c->where(array(
            'parent' => $this->id,
        ));
        $data['total'] = $this->modx->getCount('modUserGroup',$c);
        $c->sortby($this->getProperty('sort'),$this->getProperty('dir'));
        $data['results'] = $this->modx->getCollection('modUserGroup',$c);
        return $data;
    }

    /**
     * Get the Users in the selected User Group
     * @return array
     */
    public function getUsers() {
        $data = array();
        $c = $this->modx->newQuery('modUser');
        $c->innerJoin('modUserGroupMember','UserGroupMembers');
        $c->where(array(
            'UserGroupMembers.user_group' => $this->userGroup->get('id'),
        ));
        $data['total'] = $this->modx->getCount('modUser',$c);
        $c->sortby('username','ASC');
        $data['results'] = $this->modx->getCollection('modUser',$c);
        return $data;
    }

    /**
     * Add the Anonymous group to the list
     * 
     * @param array $list
     * @return array
     */
    public function addAnonymous(array $list) {
        if ($this->getProperty('showAnonymous') && empty($this->id)) {
            $cls = 'icon-group';
            $cls .= ' pupdate';
            $list[] = array(
                'text' => '('.$this->modx->lexicon('anonymous').')',
                'id' => 'n_ug_0',
                'leaf' => true,
                'type' => 'usergroup',
                'cls' => $cls,
            );
        }
        return $list;
    }

    /**
     * Prepare a User Group for listing
     * 
     * @param modUserGroup $group
     * @return array
     */
    public function prepareGroup(modUserGroup $group) {
        $cls = 'icon-group padduser pcreate pupdate';
        if ($group->get('id') != 1) {
            $cls .= ' premove';
        }
        return array(
            'text' => $group->get('name').' ('.$group->get('id').')',
            'id' => 'n_ug_'.$group->get('id'),
            'leaf' => false,
            'type' => 'usergroup',
            'qtip' => $group->get('description'),
            'cls' => $cls,
        );
    }

    /**
     * Prepare a user for listing
     * 
     * @param modUser $user
     * @return array
     */
    public function prepareUser(modUser $user) {
        return array(
            'text' => $user->get('username'),
            'id' => 'n_user_'.$user->get('id').'_'.$this->userGroup->get('id'),
            'leaf' => true,
            'type' => 'user',
            'cls' => 'icon-user',
        );
    }
}
return 'modSecurityGroupGetNodesProcessor';