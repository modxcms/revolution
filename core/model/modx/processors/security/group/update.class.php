<?php
/**
 * Update a user group
 *
 * @param integer $id The ID of the user group
 * @param string $name The new name of the user group
 *
 * @package modx
 * @subpackage processors.security.group
 */
class modUserGroupUpdateProcessor extends modProcessor {
    /** @var modUserGroup $userGroup; */
    public $userGroup;
    
    public function checkPermissions() {
        return $this->modx->hasPermission('access_permissions');
    }

    public function getLanguageTopics() {
        return array('user');
    }

    public function initialize() {
        $id = $this->getProperty('id',false);
        if (empty($id)) {
            $this->userGroup = $this->modx->newObject('modUserGroup');
            $this->userGroup->set('id',0);
        } else {
            $this->userGroup = $this->modx->getObject('modUserGroup',$id);
            if (empty($this->userGroup)) {
                return $this->modx->lexicon('user_group_err_not_found');
            }
        }
        return true;
    }
    public function process() {
        $this->userGroup->fromArray($this->getProperties());

        $canSave = $this->fireBeforeSave();
        if (!empty($canSave)) {
            return $this->failure($canSave);
        }

        /* save usergroup if not anonymous */
        if (!in_array($this->getProperty('id'),array('0',0,null))) {
            if ($this->userGroup->save() === false) {
                return $this->failure($this->modx->lexicon('user_group_err_save'));
            }
        }

        $this->addUsers();
        $this->fireAfterSave();
        $this->logManagerAction();
        return $this->success('',$this->userGroup);
    }

    /**
     * Fire the before save event
     * @return string
     */
    public function fireBeforeSave() {
        $OnUserGroupBeforeFormSave = $this->modx->invokeEvent('OnUserGroupBeforeFormSave',array(
            'mode' => modSystemEvent::MODE_UPD,
            'usergroup' => &$this->userGroup,
            'id' => $this->userGroup->get('id'),
        ));
        return $this->processEventResponse($OnUserGroupBeforeFormSave);
    }

    /**
     * Add users to the User Group
     * 
     * @return array
     */
    public function addUsers() {
        $users = $this->getProperty('users',null);
        $id = $this->getProperty('id');
        $memberships = array();
        
        if ($users !== null && !empty($id)) {
            $oldMemberships = $this->userGroup->getMany('UserGroupMembers');
            /** @var modUserGroupMember $oldMembership */
            foreach ($oldMemberships as $oldMembership) {
                $oldMembership->remove();
            }

            $users = is_array($users) ? $users : $this->modx->fromJSON($users);
            foreach ($users as $user) {
                /** @var modUserGroupMember $membership */
                $membership = $this->modx->newObject('modUserGroupMember');
                $membership->set('user_group',$this->userGroup->get('id'));
                $membership->set('member',$user['id']);
                $membership->set('role',empty($user['role']) ? 0 : $user['role']);

                if ($membership->save()) {
                    $memberships[] = $membership;
                }
            }
        }
        return $memberships;
    }

    /**
     * Fire the after save event
     * @return void
     */
    public function fireAfterSave() {
        $this->modx->invokeEvent('OnUserGroupFormSave',array(
            'mode' => modSystemEvent::MODE_NEW,
            'usergroup' => &$this->userGroup,
            'id' => $this->userGroup->get('id'),
        ));
    }

    /**
     * Log the manager action
     * @return void
     */
    public function logManagerAction() {
        $this->modx->logManagerAction('save_user_group','modUserGroup',$this->userGroup->get('id'));
    }
}
return 'modUserGroupUpdateProcessor';