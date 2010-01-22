<?php
/*
 * Copyright 2006-2010 by  Jason Coward <xpdo@opengeek.com>
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
 * Defines a class that represents an embedded package within a transportable package.
 *
 * @package xpdo
 * @subpackage transport
 */

/**
 * Represents an xPDOTransport embedded within an {@link xPDOTransport} package.
 *
 * @package xpdo
 * @subpackage transport
 */
class xPDOTransportVehicle extends xPDOVehicle {
    public $class = 'xPDOTransportVehicle';

    /**
     * Install the xPDOTransport represented by this vehicle into the transport host.
     */
    public function install(& $transport, $options) {
        $installed = $this->_installTransport($transport, $options);
        return $installed;
    }

    /**
     * Install the xPDOTransport from the vehicle payload.
     *
     * @param xPDOTransport $transport The host xPDOTransport instance.
     * @param array $options Any optional attributes to apply to the installation.
     */
    protected function _installTransport(& $transport, $options) {
        $installed = false;
        $vOptions = $this->get($transport, $options);
        if (isset($vOptions['source']) && isset($vOptions['target'])) {
            if ($transport->xpdo->getDebug() === true)
                $transport->xpdo->log(xPDO::LOG_LEVEL_DEBUG, "Installing Vehicle: " . print_r($vOptions, true));
            $state = isset($vOptions['state']) ? $vOptions['state'] : xPDOTransport::STATE_PACKED;
            $object = xPDOTransport::retrieve($tansport->xpdo, $vOptions['source'], $vOptions['target'], $state);
            if ($this->validate($transport, $object, $vOptions)) {
                $installed = $object->install($vOptions);
                if (!$installed) {
                    $transport->xpdo->log(xPDO::LOG_LEVEL_ERROR, 'Error installing vehicle: ' . print_r($vOptions, true));
                }
                elseif (!$this->resolve($transport, $object, $vOptions)) {
                    $transport->xpdo->log(xPDO::LOG_LEVEL_ERROR, 'Could not resolve vehicle: ' . print_r($vOptions, true));
                }
            } else {
                $transport->xpdo->log(xPDO::LOG_LEVEL_ERROR, 'Could not validate vehicle: ' . print_r($vOptions, true));
            }
        } else {
            $transport->xpdo->log(xPDO::LOG_LEVEL_ERROR, 'Could not load vehicle!');
        }
        return $installed;
    }

    /**
     * Uninstalls vehicle artifacts from the transport host.
     */
    public function uninstall(& $transport, $options) {
        $uninstalled = false;
        $vOptions = $this->get($transport, $options);
        if (isset($vOptions['source']) && isset($vOptions['target'])) {
            $state = isset($vOptions['state']) ? $vOptions['state'] : xPDOTransport::STATE_UNPACKED;
            $object = xPDOTransport::retrieve($tansport->xpdo, $vOptions['source'], $vOptions['target'], $state);
            if ($this->validate($transport, $object, $vOptions)) {
                $uninstalled = $object->uninstall($vOptions);
                if (!$uninstalled) {
                    $transport->xpdo->log(xPDO::LOG_LEVEL_ERROR, 'Could not uninstall vehicle: ' . print_r($vOptions, true));
                }
                elseif (!$this->resolve($transport, $object, $vOptions)) {
                    $transport->xpdo->log(xPDO::LOG_LEVEL_ERROR, 'Could not resolve vehicle: ' . print_r($vOptions, true));
                }
            } else {
                $transport->xpdo->log(xPDO::LOG_LEVEL_ERROR, 'Could not validate vehicle: ' . print_r($vOptions, true));
            }
        } else {
            $transport->xpdo->log(xPDO::LOG_LEVEL_ERROR, 'Problem instantiating object from vehicle: ' . print_r($vOptions, true));
        }
        return $uninstalled;
    }

    /**
     * Put an xPDOTransport representation into a transport package.
     */
    public function put(& $transport, & $object, $attributes = array ()) {
        if (!isset ($this->payload['class'])) {
            $this->payload['class'] = 'xPDOFileVehicle';
        }
        if (is_array($object) && isset ($object['source']) && isset ($object['target'])) {
            if (!isset($object['name'])) $object['name'] = basename($object['source']);
            $this->payload['object'] = $object;
        }
        parent :: put($transport, $object, $attributes);
    }
}