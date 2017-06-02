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
 * Provides the default input filter implementation for modElement processing.
 * @package modx
 * @subpackage filters
 */

/**
 * Base input filter implementation for modElement processing, based on phX.
 *
 * @package modx
 * @subpackage filters
 */
class modInputFilter {
    /** @var modX A reference to the modX instance. */
    public $modx = null;
    /** @var array An array of filter commands. */
    private $_commands = null;
    /** @var array An array of filter modifiers. */
    private $_modifiers = null;

    /**
     * Constructor for modInputFilter
     *
     * @param modX $modx A reference to the modX instance.
     */
    function __construct(modX &$modx) {
        $this->modx = &$modx;
    }

    /**
     * Filters a modElement before it is processed.
     *
     * @param modElement &$element The element to apply filtering to.
     */
    public function filter(&$element) {
        /* split commands and modifiers and store them as properties for the output filtering */
        $output= $element->get('name');
        $name= $output;
        $splitPos= strpos($output, ':');
        if ($splitPos !== false && $splitPos > 0) {
            $matches= array ();
            $name= trim(substr($output, 0, $splitPos));
            $modifiers= substr($output, $splitPos);
            if (preg_match_all('~:([^:=]+)(?:=`(.*?)`[\r\n\s]*(?=:[^:=]+|$))?~s', $modifiers, $matches)) {
                $this->_commands = $matches[1]; /* modifier commands */
                $this->_modifiers = $matches[2]; /* modifier values */
            }
        }
        $element->set('name', $name);
    }

    /**
     * Indicates if the element has any input filter commands.
     *
     * @return boolean True if the input filter has commands to execute.
     */
    public function hasCommands() {
        return !empty($this->_commands);
    }

    /**
     * Returns a list of filter input commands to be applied through output filtering.
     *
     * @return array|null An array of filter commands or null if no commands exist.
     */
    public function & getCommands() {
        return $this->_commands;
    }

    /**
     * Returns a list of filter input modifiers corresponding to the input commands.
     *
     * @return array|null An array of filter modifiers for corresponding commands.
     */
    public function & getModifiers() {
        return $this->_modifiers;
    }
}
