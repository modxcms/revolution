<?php
/**
 * Create a user group
 *
 * @param string $name (optional) The name of the new user group. Defaults to
 * Untitled User Group.
 * @param integer $parent (optional) The ID of the parent user group. Defaults
 * to 0.
 *
 * @package modx
 * @subpackage processors.security.group
 */
class modUserGroupCreateProcessor extends modProcessor {
    /** @var modUserGroup $userGroup */
    public $userGroup;
    
    public function checkPermissions() {
        return $this->modx->hasPermission('access_permissions');
    }
    public function getLanguageTopics() {
        return array('user');
    }
    public function initialize() {
        $this->setDefaultProperties(array(
            'parent' => 0,
        ));
        return true;
    }
    public function process() {
        if (!$this->validate()) {
            return $this->failure();
        }

        /* add group */
        $this->userGroup = $this->modx->newObject('modUserGroup');
        $this->userGroup->fromArray($this->getProperties());

        /* users */
        $this->setUsersIn();

        $canSave = $this->fireBeforeSave();
        if (!empty($canSave)) {
            return $this->failure($canSave);
        }

        /* save usergroup */
        if ($this->userGroup->save() == false) {
            return $this->failure($this->modx->lexicon('user_group_err_create'));
        }

        $this->setContexts();
        $this->setResourceGroups();
        $this->logManagerAction();
        return $this->success('',$this->userGroup);
    }

    /**
     * Validate the form
     * @return boolean
     */
    public function validate() {
        $name = $this->getProperty('name');
        if (empty($name)) {
            $this->addFieldError('name',$this->modx->lexicon('user_group_err_ns_name'));
        }
        $parent = $this->getProperty('parent');
        if (empty($parent)) {
            $this->setProperty('parent',0);
        }

        if ($this->alreadyExists()) {
            $this->addFieldError('name',$this->modx->lexicon('user_group_err_already_exists'));
        }

        return !$this->hasErrors();
    }

    /**
     * Check to see if a group already exists with the specified name
     * @return boolean
     */
    public function alreadyExists() {
        return $this->modx->getCount('modUserGroup',array('name' => $this->getProperty('name'))) > 0;
    }

    /**
     * Set the users in the group
     * @return array
     */
    public function setUsersIn() {
        $users = $this->getProperty('users');
        $memberships = array();
        if (!empty($users)) {
            $users = is_array($users) ? $users : $this->modx->fromJSON($users);
            $memberships = array();
            foreach ($users as $userArray) {
                if (empty($userArray['id']) || empty($userArray['role'])) continue;
                
                /** @var modUserGroupMember $membership */
                $membership = $this->modx->newObject('modUserGroupMember');
                $membership->set('user_group',$this->userGroup->get('id'));
                $membership->set('member',$userArray['id']);
                $membership->set('role',$userArray['role']);
                $memberships[] = $membership;
            }
            $this->userGroup->addMany($memberships);
        }
        return $memberships;
    }

    /**
     * Fire the before save event
     * @return string
     */
    public function fireBeforeSave() {
        $OnUserGroupBeforeFormSave = $this->modx->invokeEvent('OnUserGroupBeforeFormSave',array(
            'mode' => modSystemEvent::MODE_NEW,
            'usergroup' => &$this->userGroup,
            'id' => $this->userGroup->get('id'),
        ));
        return $this->processEventResponse($OnUserGroupBeforeFormSave);
    }

    /**
     * Fire the after group save event
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
     * Set the Context ACLs for the Group
     * @return array
     */
    public function setContexts() {
        $contexts = $this->getProperty('contexts');
        $access = array();
        if (!empty($contexts)) {
            $contexts = is_array($contexts) ? $contexts : $this->modx->fromJSON($contexts);
            foreach ($contexts as $context) {
                /** @var modAccessContext $acl */
                $acl = $this->modx->newObject('modAccessContext');
                $acl->fromArray($context);
                $acl->set('principal',$this->userGroup->get('id'));
                $acl->set('principal_class','modUserGroup');
                if ($acl->save()) {
                    $access[] = $acl;
                }
            }
        }
        return $access;
    }

    /**
     * Set the Resource Group ACLs for the Group
     * @return array
     */
    public function setResourceGroups() {
        $resourceGroups = $this->getProperty('resource_groups');
        $access = array();
        if (!empty($resourceGroups)) {
            $resourceGroups = is_array($resourceGroups) ? $resourceGroups : $this->modx->fromJSON($resourceGroups);
            foreach ($resourceGroups as $resourceGroup) {
                /** @var modAccessResourceGroup $acl */
                $acl = $this->modx->newObject('modAccessResourceGroup');
                $acl->fromArray($resourceGroup);
                $acl->set('principal',$this->userGroup->get('id'));
                $acl->set('principal_class','modUserGroup');
                if ($acl->save()) {
                    $access[] = $acl;
                }
            }
        }
        return $access;
    }

    /**
     * Log the manager action
     * 
     * @return void
     */
    public function logManagerAction() {
        $this->modx->logManagerAction('new_user_group','modUserGroup',$this->userGroup->get('id'));
    }
}
return 'modUserGroupCreateProcessor';