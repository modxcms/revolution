<?php
/*
 * This file is part of the MODX Revolution package.
 *
 * Copyright (c) MODX, LLC
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace MODX\Revolution\Transport;

/**
 * Abstracts the vehicle construct for package building.
 *
 * @package MODX\Revolution\Transport
 */
class modTransportVehicle
{

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
     * @var string The collection of dependencies to validate against pre-install/upgrade.
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
     * @param  mixed  $obj  The object that the vehicle represents.
     * @param  array  $attr  An array of attributes for the object.
     */
    public function __construct($obj, array $attr = [])
    {
        $this->obj = $obj;
        $this->attributes = $attr;
        $this->validators = [];
        $this->resolvers = [];
    }

    /**
     * Adds a pre-creation validator to the vehicle.
     *
     * @access public
     *
     * @param  string  $type  The type of validator (php,file,etc)
     * @param  array  $options  An array of options for the validator.
     *
     * @return array The added validator.
     */
    public function validate($type, $options)
    {
        $options['type'] = $type;
        array_push($this->validators, $options);
        return $options;
    }

    /**
     * Adds a post-save resolver to the vehicle.
     *
     * @access public
     *
     * @param  string  $type  The type of resolver (php,file,etc)
     * @param  array  $options  An array of options for the resolver.
     *
     * @return array The added resolver.
     */
    public function resolve($type, $options)
    {
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
    public function compile()
    {
        return array_merge(
            $this->attributes,
            [
                'resolve'  => empty ($this->resolvers) ? null : $this->resolvers,
                'validate' => empty ($this->validators) ? null : $this->validators,
            ]
        );
    }

    /**
     * Returns the artifact payload associated with the vehicle.
     *
     * @access public
     * @return mixed The payload for this vehicle.
     */
    public function fetch()
    {
        return $this->obj;
    }

}
