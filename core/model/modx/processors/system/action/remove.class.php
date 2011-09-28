<?php
/**
 * Removes an action
 *
 * @param integer $id The ID of the action
 *
 * @package modx
 * @subpackage processors.system.action
 */
class modActionRemoveProcessor extends modProcessor {
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

    /**
     * {@inheritDoc}
     * 
     * @return array|string
     */
    public function process() {
        if ($this->action->remove() == false) {
            return $this->failure($this->modx->lexicon('action_err_remove'));
        }

        return $this->success('',$this->action);
    }

    /**
     * @return void
     */
    public function logManagerAction() {
        $this->modx->logManagerAction('action_delete','modAction',$this->action->get('id'));
    }
}
return 'modActionRemoveProcessor';