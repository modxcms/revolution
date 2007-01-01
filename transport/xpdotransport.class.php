<?php
/*
 * OpenExpedio (XPDO)
 * 
 * Copyright (c) 2006, Jason Coward <jason@opengeek.com>.
 * All rights reserved.
 *
 * Redistribution and use in source and binary forms, with or without
 * modification, are permitted provided that the following conditions are met:
 * 
 * - Redistributions of source code must retain the above copyright notice, this
 * list of conditions and the following disclaimer.
 * - Redistributions in binary form must reproduce the above copyright notice,
 * this list of conditions and the following disclaimer in the documentation
 * and/or other materials provided with the distribution.
 * - Neither the name of the OpenExpedio (XPDO) Project nor the names of its
 * contributors may be used to endorse or promote products derived from this
 * software without specific prior written permission.
 *
 * THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS "AS IS"
 * AND ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT LIMITED TO, THE
 * IMPLIED WARRANTIES OF MERCHANTABILITY AND FITNESS FOR A PARTICULAR PURPOSE
 * ARE DISCLAIMED. IN NO EVENT SHALL THE COPYRIGHT OWNER OR CONTRIBUTORS BE
 * LIABLE FOR ANY DIRECT, INDIRECT, INCIDENTAL, SPECIAL, EXEMPLARY, OR
 * CONSEQUENTIAL DAMAGES (INCLUDING, BUT NOT LIMITED TO, PROCUREMENT OF
 * SUBSTITUTE GOODS OR SERVICES; LOSS OF USE, DATA, OR PROFITS; OR BUSINESS
 * INTERRUPTION) HOWEVER CAUSED AND ON ANY THEORY OF LIABILITY, WHETHER IN
 * CONTRACT, STRICT LIABILITY, OR TORT (INCLUDING NEGLIGENCE OR OTHERWISE)
 * ARISING IN ANY WAY OUT OF THE USE OF THIS SOFTWARE, EVEN IF ADVISED OF THE
 * POSSIBILITY OF SUCH DAMAGE.
 */

/**
 * Represents a transportable package of related data.
 * 
 * @package xpdo.transport
 */

if (!defined('XPDO_TRANSPORT_PRESERVE_KEYS')) {
    /**#@+
     * Attributes of the package that can be used to control behavior.
     */
    define('XPDO_TRANSPORT_PRESERVE_KEYS', 1);
    define('XPDO_TRANSPORT_STATE_UNPACKED', 0);
    define('XPDO_TRANSPORT_STATE_PACKED', 1);
}

/**
 * Represent object data in a database agnostic format.
 * 
 * @todo Implement a way to export/import a transport package.
 * @package xpdo.transport
 */
class xPDOTransport {
    /**
     * An {@link xPDO} reference controlling this transport instance.
     * @var xPDO
     * @access public
     */
    var $xpdo= null;
    /**
     * A unique signature to identify the package.
     */
    var $signature= null;
    /**
     * Indicates the state of the xPDOTransport instance.
     */
    var $state= null;
    /**
     * Stores various attributes about the transport package.
     */
    var $attributes= array ();
    /**
     * A map of object vehicles containing payloads of data for transport.
     */
    var $vehicles= array ();
    /**
     * The physical location of the transport package.
     */
    var $path= null;
    
    /**
     * Prepares and returns a new xPDOTransport instance.
     * 
     * @param xPDOManager $manager An xPDOManager object to sponsor this
     * instance.
     * @param string $signature The unique signature of the package.
     * @param string $path Valid path to the physical transport package.
     */
    function xPDOTransport(& $xpdo, $signature, $path) {
        $this->xpdo= & $xpdo;
        $this->signature= $signature;
        $this->path= $path;
        $this->loadClass('transport.xPDOVehicle', XPDO_CORE_PATH, true, true);
        include_once (XPDO_CORE_PATH . 'compression/pclzip.lib.php');
    }

    /**
     * Get an {@link xPDOObject} instance from an unpacked transport package.
     * 
     * @param string $objFile Full path to an object file to import.  The object
     * file must be in the proper xPDOObject export format.
     * @param array $options An array of options to be applied when getting the
     * object.
     */
    function get($objFile, $options= array ()) {
        $obj= null;
        if (file_exists($objFile)) {
            if ($vehicle= @include ($objFile)) {
                $obj= $vehicle->get($this, $options);
            }
        }
        return $obj;
    }
    
    /**
     * Install all the objects in this transport into the {@link xPDO} instance.
     * 
     * @param array $options Install options to be applied to the process.
     */
    function install($options= array ()) {
        $installed= false;
        if (!empty ($this->vehicles)) {
            foreach ($this->vehicles as $vKey => $vehicleMeta) {
                if ($object= $this->get($vehicleMeta['filename'], array_merge($vehicleMeta, $options))) {
                    if (is_object($object) && $object instanceof xPDOObject) {
                        if ($this->validate($object)) {
                            if ($object->save()) {
                                $this->resolve($object);
                            }
                        }
                    }
                }
            }
        }
        return $installed;
    }
    
    /**
     * Put an {@link xPDOVehicle} into the transport package.
     * 
     * @param xPDOObject $object An xPDOObject to load into the transport.
     * @param array $attributes A set of attributes related to the object; these
     * can be anything from rules describing how to pack or unpack the object,
     * or any other data that might be useful when dealing with a transportable
     * data object.
     */
    function put($object, $attributes= array ()) {
        $added= false;
        if (is_object($object) && $object instanceof xPDOObject) {
            $vehicle= new xPDOVehicle();
            $vehicle->put($object, $attributes);
            if ($added= $vehicle->store($this)) {
                $this->registerVehicle($vehicle);
            }
        }
        return $added;
    }
    
    /**
     * Pack the {@link xPDOTransport} instance in preparation for distribution.
     */
    function pack() {
        // TODO: implement pack
    }
    
    function registerVehicle(& $vehicle) {
        $this->vehicles[$vehicle->payload['class']][$vehicle->payload['guid']]['native_key']= $vehicle->payload['native_key']; 
        $this->vehicles[$vehicle->payload['class']][$vehicle->payload['guid']]['filename']= $vehicle->payload['filename']; 
    }
    
    /**
     * Resolve the entire vehicleMap after installing an object from a vehicle.
     */
    function resolve(& $object) {
        // TODO: implement resolve()
    }

    /**
     * Get an existing {@link xPDOTransport} instance.
     */
    function retrieve(& $xpdo, $source, $target, $state= XPDO_TRANSPORT_STATE_PACKED) {
        $instance= null;
        if ($source) {
            if ($state === XPDO_TRANSPORT_STATE_PACKED) {
                if (file_exists($source) && is_writable($target)) {
                    if ($manifest= xPDOTransport :: unpack($source, $target)) {
                        $instance= new xPDOTransport($xpdo, $manifest['signature'], $target);
                    }
                }
            }
        }
        return $instance;
    }

    /**
     * Store the package to a specified resource location.
     * 
     * @param mixed $location The location to store the package.
     */
    function store($location) {
        $stored= false;
        if ($this->state === XPDO_TRANSPORT_PACKED) {
            //TODO: store the packed package to a specified location (support any resource context)
        }
        return $stored;
    }
    
    /**
     * Unpack the package to prepare for installation and return a manifest.
     * 
     * @param string $from Filename of the archive containing the transport
     * package.
     * @param string $to The root path where the contents of the archive should
     * be extracted.  This path must be writable by the user executing the PHP
     * process on the server.
     * @return array The manifest which is included after successful extraction.
     */
    function unpack($from, $to) {
        $manifest= null;
        $archive= new PclZip($from);
        if ($resources= $archive->extract(PCLZIP_OPT_PATH, $to)) {
            $manifestFilename= $to . '/' . basename($from, '.zip') . '/' . basename($from, '.zip') . '.manifest.php';
            if (file_exists($manifestFilename)) {
                $manifest= @include ($manifestFilename);
            }
        }
        return $manifest;
    }
    
    /**
     * Validate an object before it is installed from a vehicle.
     * 
     * @param xPDOObject $object An xPDOObject to validate.
     */
    function validate(& $object) {
        // TODO: implement validate()
    }
}