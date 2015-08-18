<?php
/**
 * Mark a message as unread
 *
 * @param integer $id The ID of the message
 *
 * @package modx
 * @subpackage processors.security.message
 */

class modMessageUnreadProcessor extends modObjectUpdateProcessor {
    public $checkSavePermission = false;
    public $objectType = 'message';
    public $classKey = 'modUserMessage';
    public $permission = 'messages';
    public $languageTopics = array('messages');

    public function beforeSave() {
        $this->object->set('read', false);
        return parent::beforeSave();
    }
}

return 'modMessageUnreadProcessor';
