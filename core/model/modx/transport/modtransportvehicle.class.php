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
 * @package modx
 * @subpackage transport
 */
/**
* Abstracts the vehicle construct for package building.
*
* @package modx
* @subpackage transport
*/
class modTransportVehicle {
    /**
     * @var array The collection of attributes to attach to the vehicle.
     * @access public
     */
    public $attributes;
    /**
    * @var array The collection of dependencies to resolve post-install/upgrade.
    * @access public
    */
    public $resolvers;
    /**
    * @var string The collection of dependences to validate against pre-install/upgrade.
    * @access public
    */
    public $validators;
    /**
    * @var mixed The actual object or artifact payload that the vehicle represents.
    * @access public
    */
    public $obj;

    /**
     * Creates an instance of the modTransportVehicle class.
     *
     * @param mixed $obj The object that the vehicle represents.
     * @param array $attr An array of attributes for the object.
     */
    function __construct($obj, array $attr = array ()) {
        $this->obj = $obj;
        $this->attributes = $attr;
        $this->validators = array ();
        $this->resolvers = array ();
    }

    /**
    * Adds a pre-creation validator to the vehicle.
    *
    * @access public
    * @param string $type The type of validator (php,file,etc)
    * @param array $options An array of options for the validator.
    * @return array The added validator.
    */
    public function validate($type, $options) {
        $options['type'] = $type;
        array_push($this->validators, $options);
        return $options;
    }

    /**
    * Adds a post-save resolver to the vehicle.
    *
    * @access public
    * @param string $type The type of resolver (php,file,etc)
    * @param array $options An array of options for the resolver.
    * @return array The added resolver.
    */
    public function resolve($type, $options) {
        $options['type'] = $type;
        array_push($this->resolvers, $options);
        return $options;
    }

    /**
    * Compiles the attributes array to pass on to the modPackageBuilder instance.
    *
    * @access public
    * @return array An array of added attributes.
    */
    public function compile() {
        $attributes = array_merge($this->attributes, array (
            'resolve' => empty ($this->resolvers) ? null : $this->resolvers,
            'validate' => empty ($this->validators) ? null : $this->validators,

        ));
        return $attributes;
    }

    /**
    * Returns the artifact payload associated with the vehicle.
    *
    * @access public
    * @return mixed The payload for this vehicle.
    */
    public function fetch() {
        return $this->obj;
    }
}