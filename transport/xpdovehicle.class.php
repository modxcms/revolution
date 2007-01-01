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
 * Defines a class that represents an object within a transportable package.
 * 
 * @package xpdo.transport
 */

/**
 * Represents an individual object within an {@link xPDOTransport} package.
 */
class xPDOVehicle {
    var $payload= array ();
    
    /**
     * Retrieve the object represented by this vehicle.
     * 
     * @param xPDOTransport $transport The transport package containing this
     * vehicle.
     * @param array $options Options that apply to the object or retrieval
     * process.
     */
    function get(& $transport, $options= array ()) {
        $object= $transport->xpdo->newObject($this->payload['class']);
        if (is_object($object) && $object instanceof xPDOObject && isset ($this->payload['object'])) {
            $setKeys= false;
            if (isset ($options[XPDO_TRANSPORT_PRESERVE_KEYS])) {
                $setKeys= (boolean) $options[XPDO_TRANSPORT_PRESERVE_KEYS];
            }
            $object->fromJSON($this->payload['object'], '', $setKeys, true);
        }
        return $object;
    }
    
    /**
     * Define and load an object representation into a transport package.
     * 
     * @param xPDOTransport $transport The transport package accepting the
     * vehicle.
     * @param object $object An object this vehicle will represent.
     * @param array $attributes Additional attributes represented in the
     * vehicle.
     */
    function put(& $transport, & $object, $attributes= array ()) {
        $this->payload= array_merge($this->payload, $attributes);
        if (is_object($object)) {
            if (!isset ($this->payload['class'])) {
                $className= get_class($object);
                $this->payload['class']= $className;
            }
            if (!isset ($this->payload['guid'])) {
                $guid= md5(uniqid(rand(), true));
                $this->payload['guid']= $guid;
            }
            if ($object instanceof xPDOObject) {
                $nativeKey= $object->getPrimaryKey();
                if (is_array($nativeKey)) {
                    $nativeKey= implode('-', $nativeKey);
                }
                $this->payload['object']= $object->toJSON('', true);
                $this->payload['native_key']= $nativeKey;
                $this->payload['signature']= md5($className . '_' . $nativeKey);
            } elseif (is_object($object)) {
                $this->payload['object']= $transport->xpdo->toJSON(get_object_vars($object));
                $this->payload['native_key']= $guid;
                $this->payload['signature']= md5($className . '_' . $guid);
            }
        }
    }
    
    function store(& $transport) {
        $stored= false;
        if (!empty ($this->payload) && is_object($transport) && $transport  instanceof xPDOTransport) {
            $content= '<?php return ';
            $content.= var_export($this->payload, true);
            $content.= ';';
            $this->payload['filename']= $this->payload['signature'] . '.vehicle.php';
            $fileName= $transport->path . '/' . $transport->signature . '/' . $this->payload['filename'];
            $file= @fopen($fileName, 'wt');
            $set= @fwrite($file, $content);
            @fclose($file);
        }
        return $stored;
    }
}