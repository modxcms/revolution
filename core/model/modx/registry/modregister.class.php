<?php
/*
 * MODX Revolution
 * 
 * Copyright 2006-2011 by MODX, LLC.
 * All rights reserved.
 *
 * This program is free software; you can redistribute it and/or modify it under
 * the terms of the GNU General Public License as published by the Free Software
 * Foundation; either version 2 of the License, or (at your option) any later
 * version.
 *
 * This program is distributed in the hope that it will be useful, but WITHOUT
 * ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS
 * FOR A PARTICULAR PURPOSE. See the GNU General Public License for more
 * details.
 *
 * You should have received a copy of the GNU General Public License along with
 * this program; if not, write to the Free Software Foundation, Inc., 59 Temple
 * Place, Suite 330, Boston, MA 02111-1307 USA
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
class modRegister {
    /**
     * A reference to the modX instance the register is loaded by.
     * @var modX
     * @access public
     */
    var $modx = null;
    /**
     * An array of global options applied to the registry.
     * @var array
     * @access public
     */
    var $options = null;
    /**
     * An array of topics and/or messages the register is subscribed to.
     * @var array
     * @access public
     */
    var $subscriptions = array();
    /**
     * An optional current topic to allow writes to relative paths.
     * @var string
     * @access protected
     */
    var $_currentTopic = '/';
    /**
     * The key identifying this register in a registry.
     * @var string
     * @access protected
     */
    var $_key = null;

    /**
     * Construct a new register.
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
    function read($options = array()) {
        return true;
    }

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
    function send($topic, $message, $options = array()) {
        return true;
    }

    /**
     * Connect to the register service implementation.
     *
     * @abstract Implement this only if necessary for the implementation.
     * @param array $attributes A collection of attributes required for
     * connection to the register.
     * @return boolean Indicates if the connection was successful.
     */
    function connect($attributes = array()) {
        return true;
    }

    /**
     * Close the connection to the register service implementation.
     *
     * @abstract Implement this only if necessary for the implementation.
     * @return boolean Indicates if the connection was closed successfully.
     */
    function close() {
        return true;
    }

    /**
     * Subscribe to a topic (or specific message) in the register.
     *
     * @param string $topic The path representing the topic or message.
     * @return boolean Indicates if the subscription was successful.
     */
    function subscribe($topic) {
        $this->subscriptions[] = $topic;
        return true;
    }

    /**
     * Unsubscribe from a topic (or specific message) in the register.
     *
     * @param string $topic The path representing the topic or message.
     * @return boolean Indicates if the subscription was removed successfully.
     */
    function unsubscribe($topic) {
        $success = false;
        $topicIdx = array_search($topic, $this->subscriptions);
        if ($topicIdx !== false && $topicIdx !== null) {
            unset($this->subscriptions[$topicIdx]);
            $success = true;
        }
        return $success;
    }

    function acknowledge($messageKey, $transactionKey) {}
    function begin($transactionKey) {}
    function commit($transactionKey) {}
    function abort($transactionKey) {}
    
    function setCurrentTopic($topic) {
        if ($topic[0] != '/') $topic = $this->_currentTopic . $topic;
        if ($topic[strlen($topic) - 1] != '/') $topic .= '/';
        $topicIdx = array_search($topic, $this->subscriptions);
        if ($topicIdx !== false && $topicIdx !== null) {
            $this->_currentTopic = $topic;
        }
    }
    
    function getKey() {
        return $this->_key;
    }
}
?>