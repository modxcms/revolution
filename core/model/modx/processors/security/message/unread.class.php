<?php
/*
 * This file is part of MODX Revolution.
 *
 * Copyright (c) MODX, LLC. All Rights Reserved.
 *
 * For complete copyright and license information, see the COPYRIGHT and LICENSE
 * files found in the top-level directory of this distribution.
 */

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
        if ($this->object->get('recipient') != $this->modx->user->get('id')) {
            return $this->modx->lexicon($this->objectType.'_err_nfs');
        }

        $this->object->set('read', false);
        return parent::beforeSave();
    }
}

return 'modMessageUnreadProcessor';
