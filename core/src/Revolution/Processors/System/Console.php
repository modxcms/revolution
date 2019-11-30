<?php
/*
 * This file is part of MODX Revolution.
 *
 * Copyright (c) MODX, LLC. All Rights Reserved.
 *
 * For complete copyright and license information, see the COPYRIGHT and LICENSE
 * files found in the top-level directory of this distribution.
 */

namespace MODX\Revolution\Processors\System;

use MODX\Revolution\Processors\Processor;
use MODX\Revolution\Registry\modFileRegister;
use MODX\Revolution\Registry\modRegistry;

/**
 * Read from the registry to console
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
 * @package MODX\Revolution\Processors\System
 */
class Console extends Processor
{
    /**
     * @return bool
     */
    public function initialize()
    {
        $register = $this->getProperty('register');
        if (empty($register) || !preg_match('/^[a-zA-Z_\x7f-\xff][a-zA-Z0-9_\x7f-\xff]*$/', $register)) {
            return $this->modx->lexicon('error');
        }

        $topic = $this->getProperty('topic');
        if (empty($topic)) {
            return $this->modx->lexicon('error');
        }
        return true;
    }

    /**
     * @return array|mixed|string
     * @throws \xPDO\xPDOException
     */
    public function process()
    {
        $register = trim($this->getProperty('register'));
        $registerClass = trim($this->getProperty('register_class', modFileRegister::class));
        $topic = trim($this->getProperty('topic'));

        $options = [
            'poll_limit' => $this->getProperty('poll_limit', 1),
            'poll_interval' => $this->getProperty('poll_interval', 1),
            'time_limit' => $this->getProperty('time_limit', 10),
            'msg_limit' => $this->getProperty('message_limit', 200),
            'show_filename' => $this->getProperty('show_filename', true),
            'remove_read' => true,
        ];

        $this->modx->getService('registry', modRegistry::class);
        $this->modx->registry->addRegister($register, $registerClass, ['directory' => $register]);
        if (!$this->modx->registry->$register->connect()) {
            return $this->failure($this->modx->lexicon('error'));
        }
        $this->modx->registry->$register->subscribe($topic);

        $messages = $this->modx->registry->$register->read($options);
        $response = [];
        if (!empty($messages)) {
            $response = [
                'type' => 'event',
                'name' => 'message',
                'data' => '',
                'complete' => false,
            ];
            foreach ($messages as $messageKey => $message) {
                if ($message['msg'] === 'COMPLETED') {
                    $response['complete'] = true;
                    continue;
                }
                if (!empty ($message['def'])) {
                    $message['def'] .= ' ';
                }
                if (!empty ($message['file'])) {
                    $message['file'] = '@ ' . $message['file'] . ' ';
                }
                if (!empty ($message['line'])) {
                    $message['line'] = 'line ' . $message['line'] . ' ';
                }
                $response['data'] .= '<span class="' . strtolower($message['level']) . '">';
                if ($options['show_filename']) {
                    $response['data'] .= '<small>(' . trim($message['def'] . $message['file'] . $message['line']) . ')</small>';
                }
                $response['data'] .= $message['msg'] . "</span><br />\n";
            }
        }
        return $this->modx->toJSON($response);
    }
}
