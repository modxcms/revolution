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
class modUserGroupCreateProcessor extends modObjectCreateProcessor {
    public $classKey = 'modUserGroup';
    public $languageTopics = array('user');
    public $permission = 'usergroup_new';
    public $elementType = 'user_group';
    public $beforeSaveEvent = 'OnUserGroupBeforeFormSave';
    public $afterSaveEvent = 'OnUserGroupFormSave';

    public function initialize() {
        $this->setDefaultProperties(array(
            'parent' => 0,
        ));
        return parent::initialize();
    }

    public function beforeSave() {
        $this->setUsersIn();

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

        return parent::beforeSave();
    }

    public function afterSave() {
        $this->setContexts();
        if ($this->modx->hasPermission('usergroup_user_edit')) {
            $this->setResourceGroups();
        }
        return parent::afterSave();
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
                $membership->set('user_group',$this->object->get('id'));
                $membership->set('member',$userArray['id']);
                $membership->set('role',$userArray['role']);
                $memberships[] = $membership;
            }
            $this->object->addMany($memberships);
        }
        return $memberships;
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
                $acl->set('principal',$this->object->get('id'));
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
                $acl->set('principal',$this->object->get('id'));
                $acl->set('principal_class','modUserGroup');
                if ($acl->save()) {
                    $access[] = $acl;
                }
            }
        }
        return $access;
    }
}
return 'modUserGroupCreateProcessor';