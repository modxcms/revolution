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
 * Defines the interface for a modHash implementation.
 * @abstract Implement a derivative of this class to define an actual hash algorithm implementation.
 * @package modx
 * @subpackage hashing
 */
abstract class modHash
{
    /**
     * A reference to the modHashing service hosting this modHash instance.
     * @var modHashing
     */
    public $host;

    /**
     * An array of options for the modHash implementation.
     * @var array
     */
    public $options = array();

    /**
     * Constructs a new instance of the modHash class.
     * @param modHashing $host A reference to the modHashing instance
     * @param array|null $options An optional array of configuration options
     */
    function __construct(modHashing &$host, $options = array())
    {
        $this->host =& $host;
        if (is_array($options)) {
            $this->options = $options;
        }
    }

    /**
     * Get an option for this modHash implementation
     * Searches for local options and then prefixes keys with hashing_ to look for
     * MODX System Settings.
     * @param string $key The option key to get a value for.
     * @param array|null $options An optional array of options to look in first.
     * @param mixed $default An optional default value to return if no value is set.
     * @return mixed The option value or the specified default if not found.
     */
    public function getOption($key, $options = null, $default = null)
    {
        if (is_array($options) && array_key_exists($key, $options)) {
            $option = $options[$key];
        } else {
            $option = $this->host->getOption($key, $this->options, $default);
        }
        return $option;
    }

    /**
     * Generate a hash of the given string using the provided options.
     * @abstract
     * @param string $string A string to generate a secure hash from.
     * @param array $options An array of options to be passed to the hash implementation.
     * @return mixed The hash result or false on failure.
     */
    abstract public function hash($string, array $options = array());

    /**
     * Verify a password against a hash
     * @param $string string The password to verify
     * @param $expected string The hash to verify against
     * @return boolean If the password matches the hash
     */
    abstract public function verify($string, $expected);
}
