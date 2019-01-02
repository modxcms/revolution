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
 * Represents a container used for producing and consuming messages.
 *
 * @abstract Implement a derivative of this class to provide the behavior for
 * the abstract methods, or override other public or protected methods at your
 * discretion.
 *
 * @package modx
 * @subpackage registry
 */
abstract class modRegister {
    /**
     * A reference to the modX instance the register is loaded by.
     * @var modX
     * @access public
     */
    public $modx = null;
    /**
     * An array of global options applied to the registry.
     * @var array
     * @access public
     */
    public $options = null;
    /**
     * An array of topics and/or messages the register is subscribed to.
     * @var array
     * @access public
     */
    public $subscriptions = array();
    /**
     * An optional current topic to allow writes to relative paths.
     * @var string
     * @access protected
     */
    protected $_currentTopic = '/';
    /**
     * The key identifying this register in a registry.
     * @var string
     * @access protected
     */
    protected $_key = null;
    /**
     * A polling flag that will terminate additional polling when true.
     * @var boolean
     */
    public $__kill = false;

    /**
     * Construct a new modRegister.
     *
     * @param modX &$modx A reference to a modX instance.
     * @param string $key A valid PHP variable which will be set on the modRegistry instance.
     * @param array $options Optional array of registry options.
     */
    function __construct(& $modx, $key, $options = array()) {
        $this->modx =& $modx;
        $this->_key = $key;
        $this->options = $options;
    }

    /**
     * Reads any undigested messages from subscribed topics.
     *
     * @param array $options An array of general or protocol specific options.
     * @return mixed The resulting message from the register.
     */
    abstract public function read(array $options = array());

    /**
     * Send a message to the register.
     *
     * @abstract Implement this function in derivatives to send a message to a
     * specific register (e.g. modFileRegister for file-based registers,
     * modStompRegister for ActiveMQ, etc.).
     * @param string $topic A topic container in which to broadcast the message.
     * @param mixed $message A message, or collection of messages to be sent to
     * the register.
     * @param array $options An optional array of general or protocol
     * specific message properties.
     * @return boolean Indicates if the message was recorded.
     */
    abstract public function send($topic, $message, array $options = array());

    /**
     * Connect to the register service implementation.
     *
     * @abstract Implement this only if necessary for the implementation.
     * @param array $attributes A collection of attributes required for
     * connection to the register.
     * @return boolean Indicates if the connection was successful.
     */
    abstract public function connect(array $attributes = array());

    /**
     * Close the connection to the register service implementation.
     *
     * @abstract Implement this only if necessary for the implementation.
     * @return boolean Indicates if the connection was closed successfully.
     */
    abstract public function close();

    /**
     * Clear all the register messages.
     *
     * @param string $topic The path representing the topic or message.
     * @return boolean Indicates if the clear was successful.
     */
    abstract public function clear($topic);

    /**
     * Subscribe to a topic (or specific message) in the register.
     *
     * @param string $topic The path representing the topic or message.
     * @return boolean Indicates if the subscription was successful.
     */
    public function subscribe($topic) {
        $this->subscriptions[] = $topic;
        return true;
    }

    /**
     * Unsubscribe from a topic (or specific message) in the register.
     *
     * @param string $topic The path representing the topic or message.
     * @return boolean Indicates if the subscription was removed successfully.
     */
    public function unsubscribe($topic) {
        $success = false;
        $topicIdx = array_search($topic, $this->subscriptions);
        if ($topicIdx !== false && $topicIdx !== null) {
            unset($this->subscriptions[$topicIdx]);
            $success = true;
        }
        return $success;
    }

    /**
     * Acknowledge the registry was read
     *
     * @param string $messageKey The key of the message being read
     * @param string $transactionKey The secure key of the transaction that is reading
     * @return void
     */
    public function acknowledge($messageKey, $transactionKey) {}

    /**
     * Begin the reading of the message
     *
     * @param $transactionKey The key of the message
     * @return void
     */
    public function begin($transactionKey) {}

    /**
     * Commit the transaction and finish
     *
     * @param string $transactionKey The key of the transaction
     * @return void
     */
    public function commit($transactionKey) {}

    /**
     * @param $transactionKey
     * @return void
     */
    public function abort($transactionKey) {}

    /**
     * Set the current topic to be read
     *
     * @param string $topic The key of the topic
     * @return void
     */
    public function setCurrentTopic($topic) {
        if (is_string($topic) && strlen($topic) > 0) {
            if ($topic[0] != '/') $topic = $this->_currentTopic . $topic;
            if ($topic[strlen($topic) - 1] != '/') $topic .= '/';
            $topicIdx = array_search($topic, $this->subscriptions);
            if ($topicIdx !== false && $topicIdx !== null) {
                $this->_currentTopic = $topic;
            }
        }
    }

    /**
     * Get the current topic of the register.
     *
     * @return string The current topic set for the register.
     */
    public function getCurrentTopic() {
        return $this->_currentTopic;
    }

    /**
     * Get the key of this registry
     * @return string The key of the current registry
     */
    public function getKey() {
        return $this->_key;
    }
}
