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
 * This file contains the modHashing service class definition and the modHash abstract implementation class.
 * @package modx
 * @subpackage hashing
*/

/**
 * The modX hashing service class.
 *
 * @package modx
 * @subpackage hashing
 */
class modHashing {
    /**
     * A reference to an xPDO instance communicating with this service instance.
     *
     * Though this is typically a modX instance, an xPDO instance must work for new installs.
     * @var xPDO
     */
    public $modx= null;
    /**
     * An array of options for the hashing service.
     * @var array
     */
    public $options= array();
    /**
     * An array of loaded modHash implementations.
     * @var array
     */
    protected $_hashes= array();

    /**
     * Constructs a new instance of the modHashing service class.
     *
     * @param xPDO &$modx A reference to an modX (or xPDO) instance.
     * @param array|null $options An array of options for the hashing service.
     */
    function __construct(xPDO &$modx, $options= array()) {
        $this->modx= & $modx;
        if (is_array($options)) {
            $this->options = $options;
        }
    }

    /**
     * Get an option for the MODX hashing service.
     *
     * Searches for local options and then prefixes keys with encrypt_ to look for
     * MODX System Settings.
     *
     * @param string $key The option key to get a value for.
     * @param array|null $options An optional array of options to look in first.
     * @param mixed $default An optional default value to return if no value is set.
     * @return mixed The option value or the specified default if not found.
     */
    public function getOption($key, $options = null, $default = null) {
        if (is_array($options) && array_key_exists($key, $options)) {
            $option = $options[$key];
        } elseif (array_key_exists($key, $this->options)) {
            $option = $this->options[$key];
        } else {
            $option = $this->modx->getOption('hashing_' . $key, $this->options, $default);
        }
        return $option;
    }

    /**
     * Get a hash implementation instance.
     *
     * The implementation is made available as a member variable of the modHashing service.
     *
     * @param string $key A key string identifying the instance; must be a valid PHP variable name.
     * @param string $class A valid fully-qualified modHash derivative class name
     * @param array|null $options An optional array of hash options.
     * @return modHash|null A reference to a modHash instance or null if could not be instantiated.
     */
    public function getHash($key, $class, $options = array()) {
        $className = $this->modx->loadClass($class, '', false, true);
        if ($className) {
            if (empty($key)) $key = strtolower(str_replace('mod', '', $className));
            if (!array_key_exists($key, $this->_hashes)) {
                $hash = new $className($this, $options);
                if ($hash instanceof $className) {
                    $this->_hashes[$key] = $hash;
                    $this->$key =& $this->_hashes[$key];
                }
            }
            if (array_key_exists($key, $this->_hashes)) {
                return $this->_hashes[$key];
            }
        }
        return null;
    }
}

/**
 * Defines the interface for a modHash implementation.
 *
 * @abstract Implement a derivative of this class to define an actual hash algorithm implementation.
 * @package modx
 * @subpackage hashing
 */
abstract class modHash {
    /**
     * A reference to the modHashing service hosting this modHash instance.
     * @var modHashing
     */
    public $host= null;
    /**
     * An array of options for the modHash implementation.
     * @var array
     */
    public $options= array();

    /**
     * Constructs a new instance of the modHash class.
     *
     * @param modHashing $host A reference to the modHashing instance
     * @param array|null $options An optional array of configuration options
     * @return modHash A new derivative instance of the modHash class
     */
    public function __construct(modHashing &$host, $options= array()) {
        $this->host =& $host;
        if (is_array($options)) {
            $this->options = $options;
        }
    }

    /**
     * Get an option for this modHash implementation
     *
     * Searches for local options and then prefixes keys with hashing_ to look for
     * MODX System Settings.
     *
     * @param string $key The option key to get a value for.
     * @param array|null $options An optional array of options to look in first.
     * @param mixed $default An optional default value to return if no value is set.
     * @return mixed The option value or the specified default if not found.
     */
    public function getOption($key, $options = null, $default = null) {
        if (is_array($options) && array_key_exists($key, $options)) {
            $option = $options[$key];
        } else {
            $option = $this->host->getOption($key, $this->options, $default);
        }
        return $option;
    }

    /**
     * Generate a hash of the given string using the provided options.
     *
     * @abstract
     * @param string $string A string to generate a secure hash from.
     * @param array $options An array of options to be passed to the hash implementation.
     * @return mixed The hash result or false on failure.
     */
    public abstract function hash($string, array $options = array());

    /**
     * Verifies if $string, when hashed according to this hash implementation, matches the stored hash in $expected.
     *
     * @param string $string
     * @param string $expected
     * @param array $options Implementation-specific hash options.
     * @return bool
     */
    public function verify($string, $expected, array $options = array())
    {
        $hashedPassword = $this->hash($string, $options);
        return $expected === $hashedPassword;
    }
}
