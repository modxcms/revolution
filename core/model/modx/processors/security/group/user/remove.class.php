<?php
/**
 * Remove a user from a user group
 *
 * @param integer $usergroup The ID of the user group
 * @param integer $user The ID of the user
 *
 * @package modx
 * @subpackage processors.security.group
 */
class modUserGroupUserRemoveProcessor extends modProcessor {
    /** @var modUserGroupMember $membership */
    public $membership;

    public function checkPermissions() {
        return $this->modx->hasPermission('usergroup_user_edit');
    }
    public function getLanguageTopics() {
        return array('user');
    }

    public function initialize() {
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
        /** @var modUserGroup $userGroup */
        $userGroup = $this->membership->getOne('UserGroup');
        /** @var modUser $user */
        $user = $this->membership->getOne('User');
        
        /* remove */
        if ($this->membership->remove() == false) {
            return $this->failure($this->modx->lexicon('user_group_member_err_remove'));
        }

        /* unset primary group if that was this group */
        if ($user && $userGroup) {
            if ($user->get('primary_group') == $userGroup->get('id')) {
                $user->set('primary_group',0);
                $user->save();
            }
        }

        return $this->success('',$this->membership);
    }
}
return 'modUserGroupUserRemoveProcessor';
