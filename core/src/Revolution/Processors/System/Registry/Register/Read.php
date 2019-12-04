<?php
/*
 * This file is part of MODX Revolution.
 *
 * Copyright (c) MODX, LLC. All Rights Reserved.
 *
 * For complete copyright and license information, see the COPYRIGHT and LICENSE
 * files found in the top-level directory of this distribution.
 */

namespace MODX\Revolution\Processors\System\Registry\Register;

use MODX\Revolution\Processors\Processor;
use MODX\Revolution\Registry\modFileRegister;
use MODX\Revolution\Registry\modRegister;
use MODX\Revolution\Registry\modRegistry;

/**
 * Read from the registry
 * @param string $register The register to read from
 * @param string $topic The topic in the register to read from
 * @param string $format (optional) The format to output as. Defaults to json.
 * @param string $register_class (optional) If set, will load a custom registry class.
 * @param integer $poll_limit (optional) The number of polls to limit to. Defaults to 1.
 * @param integer $poll_interval (optional) The interval of polls to grab from. Defaults to 1.
 * @param integer $time_limit (optional) The time limit to sort by. Defaults to 10.
 * @param integer $message_limit (optional) The max amount of messages to grab. Defaults to 200.
 * @param boolean $remove_read (optional) If false, will not remove the message when read. Defaults to true.
 * @param boolean $show_filename (optional) If true, will show the filename in the message. Defaults to false.
 * @package MODX\Revolution\Processors\System\Registry\Register
 */
class Read extends Processor
{
    /** @var  modRegister */
    public $register;

    public $topic;

    public $options;

    /**
     * {@inheritdoc}
     * @return bool|null|string
     */
    public function initialize()
    {
        $register = trim($this->getProperty('register'));
        $this->topic = trim($this->getProperty('topic'));
        if (!$register || !$this->topic || !preg_match('/^[a-zA-Z_\x7f-\xff][a-zA-Z0-9_\x7f-\xff]*$/', $register)) {
            return $this->modx->lexicon('error');
        }

        $this->options = [
            'poll_limit' => (int)$this->getProperty('poll_limit', 1),
            'poll_interval' => (int)$this->getProperty('poll_interval', 1),
            'time_limit' => (int)$this->getProperty('time_limit', 10),
            'msg_limit' => (int)$this->getProperty('message_limit', 200),
            'remove_read' => (bool)$this->getProperty('remove_read', true),
            'include_keys' => (bool)$this->getProperty('include_keys', false),
            'show_filename' => (bool)$this->getProperty('show_filename', false),
        ];

        return $this->connectRegister($register);
    }

    /**
     * Create instance of register and connect to it
     * @param $register
     * @return bool|null|string
     */
    public function connectRegister($register)
    {
        $register_class = trim($this->getProperty('register_class', modFileRegister::class));
        $this->modx->getService('registry', modRegistry::class);
        $this->modx->registry->addRegister($register, $register_class, ['directory' => $register]);

        $this->register = $this->modx->registry->$register;

        if (!$this->register->connect()) {
            return $this->modx->lexicon('error');
        }

        return true;
    }

    /**
     * Subscribe to register and read it
     * @return mixed
     */
    public function readRegister()
    {
        $this->register->subscribe($this->topic);
        return $this->register->read($this->options);
    }

    /**
     * Prepare message from register
     * @param $messages
     * @return string
     * @throws \xPDO\xPDOException
     */
    public function prepareMessage($messages)
    {
        $format = trim($this->getProperty('format', 'json'));

        switch ($format) {
            case 'html_log':
                $message = '';
                foreach ($messages as $msgKey => $msg) {
                    if (!empty ($msg['def'])) {
                        $msg['def'] .= ' ';
                    }
                    if (!empty ($msg['file'])) {
                        $msg['file'] = '@ ' . $msg['file'] . ' ';
                    }
                    if (!empty ($msg['line'])) {
                        $msg['line'] = 'line ' . $msg['line'] . ' ';
                    }
                    $message .= '<span class="' . strtolower($msg['level']) . '">';
                    if ($this->options['show_filename']) {
                        $message .= '<small>(' . trim($msg['def'] . $msg['file'] . $msg['line']) . ')</small>';
                    }
                    $message .= $msg['msg'] . "</span><br />\n";
                }
                break;
            case 'json':
                $message = $this->modx->toJSON($messages);
                break;
            default:
                $message = $messages;
                break;
        }

        return $message;
    }

    /**
     * {@inheritdoc}
     * @return array|mixed|string
     * @throws \xPDO\xPDOException
     */
    public function process()
    {
        $messages = $this->readRegister();
        $message = !empty($messages) ? $this->prepareMessage($messages) : '';

        return $this->success($message);
    }
}
