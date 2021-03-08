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

use MODX\Revolution\Processors\ModelProcessor;
use MODX\Revolution\modUser;
use MODX\Revolution\modUserGroup;
use MODX\Revolution\modUserGroupMember;
use MODX\Revolution\modUserGroupRole;
use MODX\Revolution\modUserMessage;
use xPDO\Om\xPDOSimpleObject;
use xPDO\xPDOIterator;

/**
 * Create a message
 *
 * @param string $subject The subject of the message
 * @param string $message The body of the message
 * @param string $type The target of the message. Either user/role/usergroup/all
 * @param integer $role (optional)
 * @param integer $user (optional)
 * @param integer $group (optional)
 * @package MODX\Revolution\Processors\Security\Message
 */
class Create extends ModelProcessor
{
    public $classKey = modUserMessage::class;
    public $objectType = 'message';
    public $permission = 'messages';
    public $languageTopics = ['messages', 'user'];

    /** @var string The type of message */
    public $type;

    /** @var array Recipients of message */
    public $recipients;

    /**
     * {@inheritDoc}
     * @return boolean
     */
    public function initialize()
    {
        $subject = $this->getProperty('subject');
        if (empty($subject)) {
            return $this->modx->lexicon($this->objectType . '_err_not_specified_subject');
        }

        $this->type = $this->getProperty('type', 'user');

        $error = $this->prepareRecipientsByType();
        if ($error !== false) {
            return $error;
        }

        return parent::initialize();
    }

    /**
     * Get object of mailing list
     * @param string $primaryKey
     * @param string $objectClass
     * @param string $objectType
     * @return bool|null|string
     */
    public function getMailingObject($primaryKey, $objectClass, $objectType)
    {
        $id = (int)$this->getProperty($primaryKey);

        if (!$id || !is_numeric($this->getProperty($primaryKey))) {
            return $this->modx->lexicon($objectType . '_err_ns');
        }

        $this->object = $this->modx->getObject($objectClass, ['id' => $id]);
        if (!$this->object) {
            return $this->modx->lexicon($objectType . '_err_nf');
        }

        return false;
    }

    /**
     * Prepare recipients of message with error check
     * @return string|bool
     */
    public function prepareRecipientsByType()
    {
        $error = false;
        switch ($this->type) {
            case 'user':
                $error = $this->getMailingObject('user', modUser::class, 'user');
                break;
            case 'role':
                $error = $this->getMailingObject('role', modUserGroupRole::class, 'role');
                break;
            case 'usergroup':
                $error = $this->getMailingObject('group', modUserGroup::class, 'user_group');
                break;
            case 'all':
                break;
        }

        if ($error) {
            return $error;
        }

        switch ($this->type) {
            case 'user':
                $this->recipients[] = $this->object->get('id');
                break;
            case 'role':
                $users = $this->modx->getIterator(modUserGroupMember::class, [
                    'role' => $this->object->get('id')
                ]);
                $this->recipients = $this->getRecipients($users, 'member');
                break;
            case 'usergroup':
                $users = $this->modx->getIterator(modUserGroupMember::class, [
                    'user_group' => $this->object->get('id')
                ]);
                $this->recipients = $this->getRecipients($users, 'member');
                break;
            case 'all':
                $users = $this->modx->getIterator(modUser::class);
                $this->recipients = $this->getRecipients($users);
                break;
        }

        return false;
    }

    /**
     * Create mailing list of user ids
     * @param xPDOIterator $users
     * @param string $primaryKey
     * @return array
     */
    public function getRecipients(xPDOIterator $users, $primaryKey = 'id')
    {
        /** @var xPDOSimpleObject $user */
        $recipients = [];
        foreach ($users as $user) {
            if ($user->get($primaryKey) === $this->modx->user->get('id')) {
                continue;
            }
            $recipients[] = $user->get($primaryKey);
        }
        return $recipients;
    }

    /**
     * Create message object
     * @param int $user
     * @param bool $private
     * @return modUserMessage
     */
    public function createMessage($user, $private = false)
    {
        /** @var modUserMessage $message */
        $message = $this->modx->newObject(modUserMessage::class);
        $message->set('subject', $this->getProperty('subject'));
        $message->set('message', $this->getProperty('message'));
        $message->set('sender', $this->modx->user->get('id'));
        $message->set('recipient', $user);
        $message->set('private', $private);
        $message->set('date_sent', time());
        $message->set('type', $this->type);

        return $message;
    }

    /**
     * Try to save message and return error or success
     * @param modUserMessage $message
     * @return bool|null|string
     */
    public function sendMessage($message)
    {
        if ($message->save() === false) {
            return $this->modx->lexicon($this->objectType . '_err_save');
        }

        if ($this->getProperty('sendemail', false)) {
            $this->sendEmail($message);
        }

        return true;
    }

    /**
     * Send email if needed
     * @param modUserMessage $message
     */
    public function sendEmail($message)
    {
        /** @var modUser $user */
        $user = $this->object instanceof modUser
                ? $this->object
                : $this->modx->getObject(modUser::class, ['id' => $message->get('recipient')]);
        if ($user) {
            $user->sendEmail($message->get('message'), ['subject' => $message->get('subject')]);
        }
    }

    /**
     * {@inheritdoc}
     * @return array|mixed|string
     */
    public function process()
    {
        $private = ($this->type === 'user');

        foreach ($this->recipients as $user) {
            $message = $this->createMessage($user, $private);
            $sent = $this->sendMessage($message);
            if ($sent !== true) {
                return $this->failure($sent, $message);
            }
        }

        return $this->success();
    }
}
