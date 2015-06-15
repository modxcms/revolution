<?php
/**
 * @package modx
 * @subpackage processors.security.user
 */
/**
 * Get a user
 *
 * @param integer $id The ID of the user
 *
 * @var modX $modx
 * @var array $scriptProperties
 *
 * @package modx
 * @subpackage processors.security.user
 */
class modUserGetProcessor extends modObjectGetProcessor {
    public $classKey = 'modUser';
    public $languageTopics = array('user');
    public $permission = 'view_user';
    public $objectType = 'user';

    public function beforeOutput() {
        if ($this->getProperty('getGroups',false)) {
            $this->getGroups();
        }
        return parent::beforeOutput();
    }

    /**
     * Get all the groups for the user
     * @return array
     */
    public function getGroups() {
        $c = $this->modx->newQuery('modUserGroupMember');
        $c->select($this->modx->getSelectColumns('modUserGroupMember','modUserGroupMember'));
        $c->select(array(
            'role_name' => 'UserGroupRole.name',
            'user_group_name' => 'UserGroup.name',
        ));
        $c->leftJoin('modUserGroupRole','UserGroupRole');
        $c->innerJoin('modUserGroup','UserGroup');
        $c->where(array(
            'member' => $this->object->get('id'),
        ));
        $c->sortby('modUserGroupMember.rank','ASC');
        $members = $this->modx->getCollection('modUserGroupMember',$c);

        $data = array();
        /** @var modUserGroupMember $member */
        foreach ($members as $member) {
            $roleName = $member->get('role_name');
            if ($member->get('role') == 0) { $roleName = $this->modx->lexicon('none'); }
            $data[] = array(
                $member->get('user_group'),
                $member->get('user_group_name'),
                $member->get('member'),
                $member->get('role'),
                empty($roleName) ? '' : $roleName,
                $this->object->get('primary_group') == $member->get('user_group') ? true : false,
                $member->get('rank'),
            );
        }
        $this->object->set('groups','(' . $this->modx->toJSON($data) . ')');
        return $data;
    }

    public function cleanup() {
        $userArray = $this->object->toArray();

        $profile = $this->object->getOne('Profile');
        if ($profile) {
            $userArray = array_merge($profile->toArray(),$userArray);
        }

        $userArray['dob'] = !empty($userArray['dob']) ? strftime('%m/%d/%Y',$userArray['dob']) : '';
        $userArray['blockeduntil'] = !empty($userArray['blockeduntil']) ? strftime('%Y-%m-%d %H:%M:%S',$userArray['blockeduntil']) : '';
        $userArray['blockedafter'] = !empty($userArray['blockedafter']) ? strftime('%Y-%m-%d %H:%M:%S',$userArray['blockedafter']) : '';
        $userArray['lastlogin'] = !empty($userArray['lastlogin'])
            ? date(
                $this->modx->getOption('manager_date_format') .', '. $this->modx->getOption('manager_time_format'),
                $userArray['lastlogin']
            )
            : '';

        unset($userArray['password'],$userArray['cachepwd']);
        return $this->success('',$userArray);
    }
}
return 'modUserGetProcessor';
