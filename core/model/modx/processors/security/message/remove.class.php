<?php
/**
 * Remove a message
 *
 * @param integer $id The ID of the message
 *
 * @package modx
 * @subpackage processors.security.message
 */

class modMessageRemoveProcessor extends modObjectRemoveProcessor {
    public $objectType = 'message';
    public $classKey = 'modUserMessage';
    public $permission = 'messages';
    public $languageTopics = array('messages');

    /**
     * Make sure user is message recipient
     * @return bool
     */
    public function beforeRemove() {
        if ($this->object->get('recipient') != $this->modx->user->get('id')) {
            return $this->modx->lexicon($this->objectType.'_err_remove_notauth');
        }
        return parent::beforeRemove();
    }
}

return 'modMessageRemoveProcessor';
