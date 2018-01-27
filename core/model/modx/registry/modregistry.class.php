<?php
/*
 * This file is part of MODX Revolution.
 *
 * Copyright (c) MODX, LLC. All Rights Reserved.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

/**
 * Represents a collection of message registers.
 *
 * A register can consist of loggable audit events, error events, debug events,
 * and any other messages that may be sent to a message queue and later
 * retrieved or redirected to some other output source.  Some key features will
 * include:
 *
 * -Logging of registry transactions to file or DB
 * -Tracking progress of asynchonous processes
 * -Can serve as a generic message queue, where MODX elements can register new
 * messages or grab the latest messages via scheduled or ad hoc requests.
 *
 * @todo Encapsulate all debugging, error handling, error reporting, and audit
 * logging features into appropriate registers.
 *
 * @package modx
 * @subpackage registry
 */
class modRegistry {
    /**
     * A reference to the modX instance the registry is loaded by.
     * @var modX
     * @access public
     */
    public $modx = null;
    /**
     * An array of global options applied to the registry.
     * @var array
     * @access protected
     */
    public $_options = array();
    /**
     * An array of register keys that are reserved from use.
     * @var array
     * @access protected
     */
    protected $_invalidKeys = array(
        'modx',
    );
    /**
     * An array of MODX registers managed by the registry.
     * @var array
     * @access private
     */
    protected $_registers = array();
    /**
     * @var modRegister The current logging registry
     */
    protected $_loggingRegister = null;
    /**
     * @var string The previous logTarget for xPDO, to be reset when finished
     */
    protected $_prevLogTarget = null;
    /**
     * @var integer The previous log level for xPDO, to be reset when finished
     */
    protected $_prevLogLevel = null;

    /**
     * Construct a new registry instance.
     *
     * @param modX &$modx A reference to a modX instance.
     * @param array $options Optional array of registry options.
     */
    function __construct(modX &$modx, array $options = array()) {
        $this->modx =& $modx;
        $this->_options = $options;
    }

    /**
     * Get a modRegister instance from the registry.
     *
     * If the register does not exist, it is added to the registry.
     *
     * @access public
     * @param string $key A unique name for the register in the registry. Must
     * be a valid PHP variable string.
     * @param string $class The actual modRegister derivative which implements
     * the register functionality.
     * @param array $options An optional array of register options.
     * @return modRegister A modRegister instance.
     */
    public function getRegister($key, $class, array $options = array()) {
        if (isset($this->_registers[$key])) {
            if ($this->_registers[$key] !== $class) {
                $this->addRegister($key, $class, $options);
            }
        } else {
            $this->addRegister($key, $class, $options);
        }
        return (isset($this->$key) ? $this->$key : null);
    }

    /**
     * Add a modRegister instance to the registry.
     *
     * Once a register is added, it is available directly from this registry
     * instance by the key provided, e.g. $registry->key.
     *
     * @access public
     * @param string $key A unique name for the register in the registry. Must
     * be a valid PHP variable string.
     * @param string $class The actual modRegister derivative which implements
     * the register functionality.
     * @param array $options An optional array of register options.
     */
    public function addRegister($key, $class, array $options = array()) {
        if (!in_array($key, $this->_invalidKeys) && substr($key, 0, 1) !== '_' && preg_match('/^[a-zA-Z_\x7f-\xff][a-zA-Z0-9_\x7f-\xff]*$/', $key)) {
            $this->_registers[$key] = $class;
            $this->$key = $this->_initRegister($key, $class, $options);
        }
    }

    /**
     * Remove a modRegister instance from the registry.
     *
     * @access public
     * @param string $key The unique name of the register to remove.
     */
    public function removeRegister($key) {
        if (!in_array($key, $this->_invalidKeys) && substr($key, 0, 1) !== '_' && preg_match('/^[a-zA-Z_\x7f-\xff][a-zA-Z0-9_\x7f-\xff]*$/', $key)) {
            $this->_registers[$key] = null;
            $this->$key = null;
        }
    }

    /**
     * Initialize a register within the registry.
     *
     * @access protected
     * @param string $key The key of the registry
     * @param string $class The class of the modRegister implementation to
     * initialize.
     * @param array $options An optional array of register options.
     * @return modRegister The register instance.
     */
    protected function _initRegister($key, $class, array $options = array()) {
        $register = null;
        if ($className = $this->modx->loadClass($class, '', false, true)) {
            $register = new $className($this->modx, $key, $options);
        }
        return $register;
    }

    /**
     * Set the logging level for the topic.
     *
     * @access public
     * @param modRegister &$register
     * @param string $topic
     * @param int $level
     * @param boolean $clear Clear the register before subscribing to it
     * @return boolean True if successful.
     */
    public function setLogging(modRegister &$register, $topic, $level = modX::LOG_LEVEL_ERROR, $clear = false) {
        $set = false;
        $this->_loggingRegister = &$register;
        if (isset($topic) && !empty($topic)) {
            $topic = trim($topic);
            if ($this->_loggingRegister->connect()) {
                $this->_prevLogTarget = $this->modx->getLogTarget();
                $this->_prevLogLevel = $this->modx->getLogLevel();
                if ($clear) $this->_loggingRegister->clear($topic);
                $this->_loggingRegister->subscribe($topic);
                $this->_loggingRegister->setCurrentTopic($topic);
                $this->modx->setLogTarget($this->_loggingRegister);
                $this->modx->setLogLevel($level);
                $set = true;
            }
        }
        return $set;
    }

    /**
     * Reset the current logging.
     *
     * @access public
     */
    public function resetLogging() {
        if ($this->_loggingRegister && $this->_prevLogTarget && $this->_prevLogLevel) {
            $this->modx->setLogTarget($this->_prevLogTarget);
            $this->modx->setLogLevel($this->_prevLogLevel);
            $this->_loggingRegister = null;
        }
    }

    /**
     * Check if logging is currently active
     *
     * @access public
     * @return boolean
     */
    public function isLogging() {
        return $this->_loggingRegister !== null;
    }
}
