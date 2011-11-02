<?php
/**
 * Removes an action
 *
 * @param integer $id The ID of the action
 *
 * @package modx
 * @subpackage processors.system.action
 */
class modActionRemoveProcessor extends modObjectRemoveProcessor {
    public $classKey = 'modAction';
    public $languageTopics = array('action','menu');
    public $permission = 'actions';
    public $objectType = 'action';
}
return 'modActionRemoveProcessor';