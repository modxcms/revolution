<?php
/**
 * Gets an action
 *
 * @param integer $id The ID of the action
 *
 * @package modx
 * @subpackage processors.system.action
 */
class modActionGetProcessor extends modProcessor {
    /** @var modAction $action */
    public $action;
    
    public function checkPermissions() {
        return $this->modx->hasPermission('actions');
    }
    public function getLanguageTopics() {
        return array('action','menu');
    }

    public function initialize() {
        $id = $this->getProperty('id');
        if (empty($id)) return $this->modx->lexicon('action_err_ns');
        $this->action = $this->modx->getObject('modAction',$id);
        if (empty($this->action)) return $this->modx->lexicon('action_err_nf');
        return true;
    }

    public function process() {
        $this->getParent();
        return $this->success('',$this->action);
    }

    /**
     * Get the parent action and set fields if found
     *
     * @return null|modAction
     */
    public function getParent() {
        /* get parent */
        $parent = $this->action->getOne('Parent');
        if ($parent != null) {
            $this->action->set('parent',$parent->get('id'));
            $this->action->set('parent_controller',$parent->get('controller'));
        }
        return $parent;
    }
}
return 'modActionGetProcessor';