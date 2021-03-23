<?php
/*
 * This file is part of the MODX Revolution package.
 *
 * Copyright (c) MODX, LLC
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace MODX\Revolution;


/**
 * Represents a modEvent when invoking events.
 *
 * @package MODX\Revolution
 */
class modSystemEvent {
    /**
     * @var string For new creations of objects in model events
     */
    const MODE_NEW = 'new';
    /**
     * @var string For updating objects in model events
     */
    const MODE_UPD = 'upd';
    /**
     * The name of the Event
     * @var string $name
     */
    public $name = '';
    /**
     * A reference/instance of the currently processed modPlugin object
     *
     * @var modPlugin|null
     * @deprecated
     */
    public $plugin = null;
    /**
     * Whether or not to allow further execution of Plugins for this event
     * @var boolean $_propagate
     */
    protected $_propagate = true;
    /**
     * The current output for the event
     * @var string $_output
     */
    public $_output;
    /**
     * Whether or not this event has been activated
     * @var boolean
     * @deprecated
     */
    public $activated;
    /**
     * Any returned values for this event
     * @var mixed $returnedValues
     */
    public $returnedValues;
    /**
     * Any params passed to this event
     * @var array $params
     */
    public $params;

    /**
     * modSystemEvent constructor.
     * @param string $name Event name
     */
    public function __construct($name = null)
    {
        if ($name) {
            $this->name = $name;
        }
    }

    /**
     * Render output from the event.
     * @param string $output The output to render.
     */
    public function output($output) {
        if (is_string($output)) {
            $this->_output .= $output;
        }
    }

    /**
     * Stop further execution of plugins for this event.
     */
    public function stopPropagation() {
        $this->_propagate = false;
    }

    /**
     * Returns whether the event will propagate or not.
     *
     * @access public
     * @return boolean
     */
    public function isPropagatable() {
        return $this->_propagate;
    }
}
