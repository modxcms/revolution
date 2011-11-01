<?php
/**
 * Remove a user group
 *
 * @param integer $id The ID of the user group
 *
 * @package modx
 * @subpackage processors.security.group
 */
class modUserGroupRemoveProcessor extends modProcessor {
    /** @var modUserGroup $userGroup */
    public $userGroup;
    
    public function checkPermissions() {
        return $this->modx->hasPermission('access_permissions');
    }
    public function getLanguageTopics() {
        return array('user');
    }
    public function initialize() {
        $id = $this->getProperty('id',false);
        if (empty($id)) return $this->modx->lexicon('user_group_err_not_specified');
        $this->userGroup = $this->modx->getObject('modUserGroup',$id);
        if (empty($this->userGroup)) return $this->modx->lexicon('user_group_err_not_found');
        return true;
    }
    public function process() {
        if ($this->isAdminGroup()) {
            return $this->failure($this->modx->lexicon('user_group_err_remove_admin'));
        }

        $canRemove = $this->fireBeforeRemove();
        if (!empty($canRemove)) {
            return $this->modx->error->failure($canRemove);
        }

        if ($this->userGroup->remove() == false) {
            return $this->modx->error->failure($this->modx->lexicon('user_group_err_remove'));
        }

        $this->fireAfterRemove();

        $this->logManagerAction();
        return $this->success();
    }

    /**
     * See if this User Group is the Administrator group
     * @return boolean
     */
    public function isAdminGroup() {
        return $this->userGroup->get('id') == 1 || $this->userGroup->get('name') == $this->modx->lexicon('administrator');
    }

    /**
     * Fire the before remove event
     * @return boolean|string
     */
    public function fireBeforeRemove() {
        $OnUserGroupBeforeFormRemove = $this->modx->invokeEvent('OnUserGroupBeforeFormRemove',array(
            'usergroup' => &$this->userGroup,
            'id' => $this->userGroup->get('id'),
        ));
        return $this->processEventResponse($OnUserGroupBeforeFormRemove);
    }

    /**
     * Fire after remove event
     * @return void
     */
    public function fireAfterRemove() {
        $this->modx->invokeEvent('OnUserGroupFormRemove',array(
            'usergroup' => &$this->userGroup,
            'id' => $this->userGroup->get('id'),
        ));
    }

    /**
     * Log the manager action
     * @return void
     */
    public function logManagerAction() {
        $this->modx->logManagerAction('delete_user_group','modUserGroup',$this->userGroup->get('id'));
    }
}
return 'modUserGroupRemoveProcessor';