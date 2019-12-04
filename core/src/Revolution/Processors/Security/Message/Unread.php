<?php
/*
 * This file is part of MODX Revolution.
 *
 * Copyright (c) MODX, LLC. All Rights Reserved.
 *
 * For complete copyright and license information, see the COPYRIGHT and LICENSE
 * files found in the top-level directory of this distribution.
 */

namespace MODX\Revolution\Processors\Security\Message;

use MODX\Revolution\Processors\Model\UpdateProcessor;
use MODX\Revolution\modUserMessage;

/**
 * Mark a message as unread
 * @param integer $id The ID of the message
 * @package MODX\Revolution\Processors\Security\Message
 */
class Unread extends UpdateProcessor
{
    public $classKey = modUserMessage::class;
    public $objectType = 'message';
    public $checkSavePermission = false;
    public $permission = 'messages';
    public $languageTopics = ['messages'];

    /**
     * @return bool|string|null
     */
    public function beforeSave()
    {
        if ($this->object->get('recipient') !== $this->modx->user->get('id')) {
            return $this->modx->lexicon($this->objectType . '_err_nfs');
        }

        $this->object->set('read', false);
        
        return parent::beforeSave();
    }
}
