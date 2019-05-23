<?php
/*
 * This file is part of the MODX Revolution package.
 *
 * Copyright (c) MODX, LLC
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace MODX\Revolution\Hashing;

/**
 * Defines the interface for a modHash implementation.
 *
 * @abstract Implement a derivative of this class to define an actual hash algorithm implementation.
 *
 * @package  MODX\Revolution\Hashing
 */
abstract class modHash
{
    /**
     * A reference to the modHashing service hosting this modHash instance.
     *
     * @var modHashing
     */
    public $host = null;
    /**
     * An array of options for the modHash implementation.
     *
     * @var array
     */
    public $options = [];

    /**
     * Constructs a new instance of the modHash class.
     *
     * @param modHashing $host    A reference to the modHashing instance
     * @param array|null $options An optional array of configuration options
     */
    public function __construct(modHashing &$host, $options = [])
    {
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
     * @param string     $key     The option key to get a value for.
     * @param array|null $options An optional array of options to look in first.
     * @param mixed      $default An optional default value to return if no value is set.
     *
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
     *
     * @abstract
     *
     * @param string $string  A string to generate a secure hash from.
     * @param array  $options An array of options to be passed to the hash implementation.
     *
     * @return mixed The hash result or false on failure.
     */
    public abstract function hash($string, array $options = []);

    /**
     * Verifies if $string, when hashed according to this hash implementation, matches the stored hash in $expected.
     *
     * @param string $string
     * @param string $expected
     * @param array  $options Implementation-specific hash options.
     *
     * @return bool
     */
    public function verify($string, $expected, array $options = [])
    {
        $hashedPassword = $this->hash($string, $options);

        return $expected === $hashedPassword;
    }
}
