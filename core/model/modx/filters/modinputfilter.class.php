<?php
/*
 * MODX Revolution
 *
 * Copyright 2006-2012 by MODX, LLC.
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
            $name= substr($output, 0, $splitPos);
            $modifiers= substr($output, $splitPos);
            if (preg_match_all('~:([^:=]+)(?:=`(.*?)`(?=:[^:=]+|$))?~s', $modifiers, $matches)) {
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