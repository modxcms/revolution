<?php
/**
 * Update a users role in a user group
 *
 * @param integer $usergroup The ID of the user group
 * @param intger $user The ID of the user
 *
 * @package modx
 * @subpackage processors.security.group
 */
class modUserGroupUserUpdateProcessor extends modProcessor {
    /** @var modUserGroupMember $membership */
    public $membership;

    public function checkPermissions() {
        return $this->modx->hasPermission('usergroup_user_edit');
    }
    public function getLanguageTopics() {
        return array('user');
    }

    public function initialize() {
        $user = $this->getProperty('user');
        if (empty($user)) return $this->modx->lexicon('user_err_ns');
        $userGroup = $this->getProperty('usergroup');
        if (empty($userGroup)) return $this->modx->lexicon('user_group_err_ns');
        
        $this->membership = $this->modx->getObject('modUserGroupMember',array(
            'member' => $this->getProperty('user',0),
            'user_group' => $this->getProperty('usergroup',0),
        ));
        if (empty($this->membership)) {
            return $this->modx->lexicon('user_group_member_err_nf');
        }
        return true;
    }

    public function process() {
        $this->membership->fromArray($this->getProperties());

        if ($this->membership->save() == false) {
            return $this->failure($this->modx->lexicon('user_group_member_err_save'));
        }
        
        return $this->success('',$this->membership);
    }
}
return 'modUserGroupUserUpdateProcessor';