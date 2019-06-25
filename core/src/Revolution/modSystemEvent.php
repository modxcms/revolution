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
     * The name of the active plugin being invoked
     * @var string $activePlugin
     * @deprecated
     */
    public $activePlugin = '';
    /**
     * A reference/instance of the currently processed modPlugin object
     *
     * @var modPlugin|null
     */
    public $plugin = null;
    /**
     * @var string The name of the active property set for the invoked Event
     * @deprecated
     */
    public $propertySet = '';
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
     * Display a message to the user during the event.
     *
     * @todo Remove this; the centralized modRegistry will handle configurable
     * logging of any kind of message or data to any repository or output
     * context.  Use {@link modX::_log()} in the meantime.
     * @param string $msg The message to display.
     */
    public function alert($msg) {}

    /**
     * Render output from the event.
     * @param string $output The output to render.
     */
    public function output($output) {
        if ($this->_output === '') {
            $this->_output = $output;
        } else {
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

    /**
     * Reset the event instance for reuse.
     */
    public function resetEventObject(){
        $this->returnedValues = null;
        $this->name = '';
        $this->_output = '';
        $this->_propagate = true;
        $this->activated = false;
    }
}
