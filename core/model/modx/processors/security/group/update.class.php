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
class modUserGroupUpdateProcessor extends modObjectUpdateProcessor {
    public $classKey = 'modUserGroup';
    public $languageTopics = array('user');
    public $permission = 'usergroup_save';
    public $objectType = 'user_group';
    public $beforeSaveEvent = 'OnUserGroupBeforeFormSave';
    public $afterSaveEvent = 'OnUserGroupFormSave';

    /**
     * Override the modObjectUpdateProcessor::initialize method to allow for grabbing the (anonymous) user group
     * @return boolean|string
     */
    public function initialize() {
        $id = $this->getProperty('id',false);
        if (empty($id)) {
            $this->object = $this->modx->newObject('modUserGroup');
            $this->object->set('id',0);
        } else {
            $this->object = $this->modx->getObject('modUserGroup',$id);
            if (empty($this->object)) {
                return $this->modx->lexicon('user_group_err_not_found');
            }
        }
        return true;
    }

    /**
     * Override the saveObject method to prevent saving of the (anonymous) group
     * 
     * {@inheritDoc}
     * @return boolean
     */
    public function saveObject() {
        $saved = true;
        if (!in_array($this->getProperty('id'),array('0',0,null))) {
            $saved = $this->object->save();
        }
        return $saved;
    }
    
    public function afterSave() {
        if ($this->modx->hasPermission('usergroup_user_edit')) {
            $this->addUsers();
        }
        return parent::afterSave();
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
            $oldMemberships = $this->object->getMany('UserGroupMembers');
            /** @var modUserGroupMember $oldMembership */
            foreach ($oldMemberships as $oldMembership) {
                $oldMembership->remove();
            }

            $users = is_array($users) ? $users : $this->modx->fromJSON($users);
            foreach ($users as $user) {
                /** @var modUserGroupMember $membership */
                $membership = $this->modx->newObject('modUserGroupMember');
                $membership->set('user_group',$this->object->get('id'));
                $membership->set('member',$user['id']);
                $membership->set('role',empty($user['role']) ? 0 : $user['role']);

                if ($membership->save()) {
                    $memberships[] = $membership;
                }
            }
        }
        return $memberships;
    }
}
return 'modUserGroupUpdateProcessor';