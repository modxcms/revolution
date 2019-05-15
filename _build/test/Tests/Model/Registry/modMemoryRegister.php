<?php
namespace MODX\Revolution\Tests\Model\Registry;


use MODX\Revolution\Registry\modRegister;

class modMemoryRegister extends modRegister {
    /**
     * Reads any undigested messages from subscribed topics.
     *
     * @param array $options An array of general or protocol specific options.
     * @return mixed The resulting message from the register.
     */
    public function read(array $options = array()) {
        // TODO: Implement read() method.
        return null;
    }

    /**
     * Send a message to the register.
     *
     * specific register (e.g. modFileRegister for file-based registers,
     * modStompRegister for ActiveMQ, etc.).
     * @param string $topic A topic container in which to broadcast the message.
     * @param mixed $message A message, or collection of messages to be sent to
     * the register.
     * @param array $options An optional array of general or protocol
     * specific message properties.
     * @return boolean Indicates if the message was recorded.
     */
    public function send($topic, $message, array $options = array()) {
        // TODO: Implement send() method.
        return true;
    }

    /**
     * Connect to the register service implementation.
     *
     * @param array $attributes A collection of attributes required for
     * connection to the register.
     * @return boolean Indicates if the connection was successful.
     */
    public function connect(array $attributes = array()) {
        return true;
    }

    public function clear($topic) {
        return true;
    }

    /**
     * Close the connection to the register service implementation.
     *
     * @return boolean Indicates if the connection was closed successfully.
     */
    public function close() {
        return true;
    }
}
