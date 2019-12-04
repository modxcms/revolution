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

use MODX\Revolution\Processors\Model\RemoveProcessor;
use MODX\Revolution\modUserMessage;

/**
 * Remove a message
 * @param integer $id The ID of the message
 * @package MODX\Revolution\Processors\Security\Message
 */
class Remove extends RemoveProcessor
{
    public $classKey = modUserMessage::class;
    public $objectType = 'message';
    public $permission = 'messages';
    public $languageTopics = ['messages'];

    /**
     * Make sure user is message recipient
     * @return bool
     */
    public function beforeRemove()
    {
        if ($this->object->get('recipient') !== $this->modx->user->get('id')) {
            return $this->modx->lexicon($this->objectType . '_err_remove_notauth');
        }

        return parent::beforeRemove();
    }
}
