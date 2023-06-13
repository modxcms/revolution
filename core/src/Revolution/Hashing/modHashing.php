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


use MODX\Revolution\modX;
use xPDO\xPDO;

/**
 * The modX hashing service class.
 *
 * @package MODX\Revolution\Hashing
 */
#[\AllowDynamicProperties]
class modHashing
{
    /**
     * A reference to an xPDO instance communicating with this service instance.
     *
     * Though this is typically a modX instance, an xPDO instance must work for new installs.
     *
     * @var modX|xPDO
     */
    public $modx = null;
    /**
     * An array of options for the hashing service.
     *
     * @var array
     */
    public $options = [];
    /**
     * An array of loaded modHash implementations.
     *
     * @var array
     */
    protected $_hashes = [];

    /**
     * Constructs a new instance of the modHashing service class.
     *
     * @param modX|xPDO  $modx    A reference to an modX (or xPDO) instance.
     * @param array|null $options An array of options for the hashing service.
     */
    function __construct(xPDO &$modx, $options = [])
    {
        $this->modx = &$modx;
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
     * @param string     $key     A key string identifying the instance; must be a valid PHP variable name.
     * @param string     $class   A valid fully-qualified modHash derivative class name
     * @param array|null $options An optional array of hash options.
     *
     * @return modHash|null A reference to a modHash instance or null if could not be instantiated.
     */
    public function getHash($key, $class, $options = [])
    {
        $className = $this->modx->loadClass($class, '', false, true);
        if (class_exists($className) && is_subclass_of($className, modHash::class, true)) {
            if (empty($key)) {
                $exploded = explode('\\', $className);
                $key = strtolower(str_replace('mod', '', array_pop($exploded)));
            }
            if (!array_key_exists($key, $this->_hashes)) {
                $hash = new $className($this, $options);
                if ($hash instanceof $className) {
                    $this->_hashes[$key] = $hash;
                    $this->$key = $this->_hashes[$key];
                }
            }
            if (array_key_exists($key, $this->_hashes)) {
                return $this->_hashes[$key];
            }
        }

        return null;
    }
}
