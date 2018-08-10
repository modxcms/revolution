<?php
/*
 * Copyright 2010-2015 by MODX, LLC.
 *
 * This file is part of xPDO.
 *
 * xPDO is free software; you can redistribute it and/or modify it under the
 * terms of the GNU General Public License as published by the Free Software
 * Foundation; either version 2 of the License, or (at your option) any later
 * version.
 *
 * xPDO is distributed in the hope that it will be useful, but WITHOUT ANY
 * WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR
 * A PARTICULAR PURPOSE. See the GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License along with
 * xPDO; if not, write to the Free Software Foundation, Inc., 59 Temple Place,
 * Suite 330, Boston, MA 02111-1307 USA
*/

/**
 * Defines a class that represents an executable PHP script within a transportable package.
 *
 * @package xpdo
 * @subpackage transport
 */

/**
 * Represents an executable PHP script within an {@link xPDOTransport} package.
 *
 * @package xpdo
 * @subpackage transport
 */
class xPDOScriptVehicle extends xPDOVehicle {
    public $class = 'xPDOScriptVehicle';

    /**
     * Execute a PHP script represented by and stored in this vehicle.
     */
    public function install(& $transport, $options) {
        return $this->_executeScript($transport, $options);
    }

    /**
     * Execute a PHP script represented by and stored in this vehicle.
     */
    public function uninstall(& $transport, $options) {
        return $this->_executeScript($transport, $options);
    }

    /**
     * Execute the script represented by and stored in this vehicle.
     *
     * @access protected
     * @param xPDOTransport &$transport A reference the transport this vehicle is stored in.
     * @param array $options Optional attributes that can be applied to vehicle install process.
     * @return boolean True if the scripts are executed successfully.
     */
    protected function _executeScript(& $transport, $options) {
        $installed = false;
        $vOptions = $this->get($transport, $options);
        if (isset ($vOptions['object']) && isset ($vOptions['object']['source'])) {
            $object = $vOptions['object'];
            if ($this->validate($transport, $object, $vOptions)) {
                $fileSource = $transport->path . $object['source'];
                if (!$installed = include ($fileSource)) {
                    $transport->xpdo->log(xPDO::LOG_LEVEL_ERROR, "xPDOScriptVehicle execution failed: ({$fileSource})");
                } elseif (!$this->resolve($transport, $object, $vOptions)) {
                    $transport->xpdo->log(xPDO::LOG_LEVEL_ERROR, 'Could not resolve vehicle for object: ' . print_r($object, true));
                    if ($transport->xpdo->getDebug() === true) $transport->xpdo->log(xPDO::LOG_LEVEL_DEBUG, 'Could not resolve vehicle: ' . print_r($vOptions, true));
                }
            } else {
                //$transport->xpdo->log(xPDO::LOG_LEVEL_ERROR, 'Could not validate vehicle for object: ' . print_r($object, true));
                if ($transport->xpdo->getDebug() === true) $transport->xpdo->log(xPDO::LOG_LEVEL_DEBUG, 'Could not validate vehicle: ' . print_r($vOptions, true));
            }
        }
        return $installed;
    }

    /**
     * Adds the file definition object to the payload.
     */
    public function put(& $transport, & $object, $attributes = array ()) {
        if (!isset ($this->payload['class'])) {
            $this->payload['class'] = 'xPDOScriptVehicle';
        }
        if (is_array($object) && isset ($object['source'])) {
            $this->payload['object'] = $object;
        }
        parent :: put($transport, $object, $attributes);
    }

    /**
     * Copies the files into the vehicle and transforms the payload for storage.
     */
    protected function _compilePayload(& $transport) {
        parent :: _compilePayload($transport);
        $body = array ();
        $cacheManager = $transport->xpdo->getCacheManager();
        if ($cacheManager) {
            if (isset($this->payload['object'])) {
                $object = $this->payload['object'];
                $fileSource = $object['source'];
                $scriptName = basename($fileSource, '.php');
                $body['source'] = $transport->signature . '/' . $this->payload['class'] . '/' . $this->payload['signature'] . '.' . $scriptName . '.script';
                $fileTarget = $transport->path . $body['source'];
                $body = array_merge($object, $body);
                if (!$cacheManager->copyFile($fileSource, $fileTarget)) {
                    $transport->xpdo->log(xPDO::LOG_LEVEL_ERROR, "Source file {$fileSource} is missing or {$fileTarget} could not be written");
                    $body = null;
                }

            }
        }
        if (!empty($body)) {
            $this->payload['object'] = $body;
        }
    }
}
