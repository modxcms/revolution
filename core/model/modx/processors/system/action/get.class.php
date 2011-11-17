<?php
/**
 * Gets an action
 *
 * @param integer $id The ID of the action
 *
 * @package modx
 * @subpackage processors.system.action
 */
class modActionGetProcessor extends modObjectGetProcessor {
    public $classKey = 'modAction';
    public $languageTopics = array('action','menu');
    public $permission = 'actions';
    public $objectType = 'action';

    public function beforeOutput() {
        $this->getParent();
    }

    /**
     * Get the parent action and set fields if found
     */
    public function getParent() {
        /* get parent */
        $parent = $this->object->getOne('Parent');
        if ($parent != null) {
            $this->object->set('parent',$parent->get('id'));
            $this->object->set('parent_controller',$parent->get('controller'));
        }
    }
}
return 'modActionGetProcessor';