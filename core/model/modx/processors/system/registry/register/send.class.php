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
 * Send a message to the registry.
 *
 * @param string $register The register to read from.
 * @param string $register_class (optional) If set, will load a custom registry class.
 * @param string $topic The topic in the register to read from.
 * @param string $message The message to send.
 * @param string $message_key (optional) If set, will create the message with this as the key.
 * @param string $message_format (optional) The format of the message. Defaults to string.
 * Also supports json for storing an array of data.
 * @param integer $delay (optional) The delay in seconds to send by. Defaults to 0.
 * @param integer $ttl (optional) The time to live of the message. Defaults to 0, or forever.
 * @param integer $kill (optional) Defaults to false.
 *
 * @package modx
 * @subpackage processors.system.registry.register
 */

class modSystemRegistryRegisterSendProcessor extends modProcessor {
    /** @var modRegister */
    public $register;
    public $topic;
    public $message;

    /**
     * {@inheritdoc}
     * @return bool|null|string
     */
    public function initialize() {
        $register = trim($this->getProperty('register'));
        $this->topic = trim($this->getProperty('topic'));
        $this->message = trim($this->getProperty('message', ''));
        if (!$register || !$this->topic || empty($this->message) ||
            !preg_match('/^[a-zA-Z_\x7f-\xff][a-zA-Z0-9_\x7f-\xff]*$/', $register)) {
            return $this->modx->lexicon('error');
        }

        return $this->connectRegister($register);
    }

    /**
     * Create instance of register and connect to it
     * @param $register
     * @return bool|null|string
     */
    public function connectRegister($register) {
        $register_class = trim($this->getProperty('register_class', 'registry.modFileRegister'));
        $this->modx->getService('registry', 'registry.modRegistry');
        $this->modx->registry->addRegister($register, $register_class, array('directory' => $register));

        $this->register = $this->modx->registry->$register;

        if (!$this->register->connect()) {
            return $this->modx->lexicon('error');
        }

        return true;
    }

    /**
     * Prepare message before sending
     */
    public function prepareMessage() {
        $message_format = $this->getProperty('message_format', 'string');
        $message_key = $this->getProperty('message_key', '');

        switch ($message_format) {
            case 'json':
                $this->message = $this->modx->fromJSON($this->message);
                break;
            case 'string':
            default:
                break;
        }

        if (empty($message_key)) {
            if (is_scalar($this->message)) {
                $this->message = array($this->message);
            }
        } else {
            $this->message = array($message_key => $this->message);
        }
    }

    /**
     * Subscribe register and send message
     * @return bool
     */
    public function sendMessage() {
        $options = array(
            'delay' => (int)$this->getProperty('delay', 0),
            'ttl' => (int)$this->getProperty('ttl', 0),
            'kill' => (bool)$this->getProperty('kill', false),
        );

        $this->register->subscribe($this->topic);
        return $this->register->send($this->topic, $this->message, $options);
    }

    /**
     * {@inheritdoc}
     * @return array|mixed|string
     */
    public function process() {
        $this->prepareMessage();
        $sent = $this->sendMessage();
        if (!$sent) {
            return $this->failure($this->modx->lexicon('error'));
        }
        return $this->success();
    }
}

return 'modSystemRegistryRegisterSendProcessor';
