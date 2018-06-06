<?php

namespace MODX\Processors\Security\Message;

use MODX\Processors\modObjectUpdateProcessor;

/**
 * Mark a message as read
 *
 * @param integer $id The ID of the message
 *
 * @package modx
 * @subpackage processors.security.message
 */
class Read extends modObjectUpdateProcessor
{
    public $checkSavePermission = false;
    public $objectType = 'message';
    public $classKey = 'modUserMessage';
    public $permission = 'messages';
    public $languageTopics = ['messages'];


    public function beforeSave()
    {
        if ($this->object->get('recipient') != $this->modx->user->get('id')) {
            return $this->modx->lexicon($this->objectType . '_err_nfs');
        }

        $this->object->set('read', true);

        return parent::beforeSave();
    }
}
