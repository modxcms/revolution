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
 * Defines a class that represents an xPDOObject within a transportable package.
 *
 * @package xpdo
 * @subpackage transport
 */

/**
 * Represents an xPDOObject within an {@link xPDOTransport} package.
 *
 * @package xpdo
 * @subpackage transport
 */
class xPDOObjectVehicle extends xPDOVehicle {
    public $class = 'xPDOObjectVehicle';

    /**
     * Retrieve an xPDOObject instance represented in this vehicle.
     *
     * This method returns the main object contained in the payload, but you can optionally specify
     * a related_objects node within the payload to retrieve a specific dependent object.
     */
    public function get(& $transport, $options = array (), $element = null) {
        $object = null;
        $element = parent :: get($transport, $options, $element);
        if (isset ($element['class']) && isset ($element['object'])) {
            $vClass = $element['class'];
            if (!empty ($element['package'])) {
                $pkgPrefix = $element['package'];
                $pkgKeys = array_keys($transport->xpdo->packages);
                if ($pkgFound = in_array($pkgPrefix, $pkgKeys)) {
                    $pkgPrefix = '';
                }
                elseif ($pos = strpos($pkgPrefix, '.')) {
                    $prefixParts = explode('.', $pkgPrefix);
                    $prefix = '';
                    foreach ($prefixParts as $prefixPart) {
                        $prefix .= $prefixPart;
                        $pkgPrefix = substr($pkgPrefix, $pos +1);
                        if ($pkgFound = in_array($prefix, $pkgKeys))
                            break;
                        $prefix .= '.';
                        $pos = strpos($pkgPrefix, '.');
                    }
                    if (!$pkgFound)
                        $pkgPrefix = $element['package'];
                }
                $vClass = (!empty ($pkgPrefix) ? $pkgPrefix . '.' : '') . $vClass;
            }
            $object = $transport->xpdo->newObject($vClass);
            if (is_object($object) && $object instanceof xPDOObject) {
                $options = array_merge($options, $element);
                $setKeys = false;
                if (isset ($options[xPDOTransport::PRESERVE_KEYS])) {
                    $setKeys = (boolean) $options[xPDOTransport::PRESERVE_KEYS];
                }
                $object->fromJSON($element['object'], '', $setKeys, true);
            }
        }
        return $object;
    }

    /**
     * Install the xPDOObjects represented by vehicle into the transport host.
     */
    public function install(& $transport, $options) {
        $parentObj = null;
        $parentMeta = null;
        $installed = $this->_installObject($transport, $options, $this->payload, $parentObj, $parentMeta);
        return $installed;
    }

    /**
     * Install a single xPDOObject from the vehicle payload.
     *
     * @param xPDOTransport $transport The host xPDOTransport instance.
     * @param array $options Any optional attributes to apply to the installation.
     * @param array $element A node of the payload representing the object to install.
     * @param xPDOObject &$parentObject A reference to the object serving as a parent to the one
     * being installed.
     * @param array $fkMeta The foreign key relationship data that defines the relationship with the
     * parentObject.
     */
    protected function _installObject(& $transport, $options, $element, & $parentObject, $fkMeta) {
        $saved = false;
        $preExistingMode = xPDOTransport::PRESERVE_PREEXISTING;
        $upgrade = false;
        $exists = false;
        /** @var xPDOObject $object */
        $object = $this->get($transport, $options, $element);
        if (is_object($object) && $object instanceof xPDOObject) {
            $vOptions = array_merge($options, $element);
            $vClass = $vOptions['class'];
            if ($transport->xpdo->getDebug() === true)
                $transport->xpdo->log(xPDO::LOG_LEVEL_DEBUG, "Installing Vehicle: " . print_r($vOptions, true));
            if ($parentObject !== null && $fkMeta !== null) {
                if ($fkMeta['owner'] == 'local') {
                    if ($object->get($fkMeta['foreign']) !== $parentObject->get($fkMeta['local'])) {
                        $object->set($fkMeta['foreign'], $parentObject->get($fkMeta['local']));
                    }
                }
            }
            $preserveKeys = !empty ($vOptions[xPDOTransport::PRESERVE_KEYS]);
            $upgrade = !empty ($vOptions[xPDOTransport::UPDATE_OBJECT]);
            if (!empty ($vOptions[xPDOTransport::UNIQUE_KEY])) {
                $uniqueKey = $object->get($vOptions[xPDOTransport::UNIQUE_KEY]);
                if (is_array($uniqueKey)) {
                    $criteria = array_combine($vOptions[xPDOTransport::UNIQUE_KEY], $uniqueKey);
                } else {
                    $criteria = array (
                        $vOptions[xPDOTransport::UNIQUE_KEY] => $uniqueKey
                    );
                }
            }
            elseif (isset ($vOptions['key_expr']) && isset ($vOptions['key_format'])) {
                //TODO: implement ability to generate new keys
            } else {
                $pk = $object->getPK();
                $nativeKey = $vOptions[xPDOTransport::NATIVE_KEY];
                if (is_array($pk) && is_array($nativeKey)) {
                    $criteria = array_combine($pk, $nativeKey);
                } elseif (is_string($pk) && is_scalar($nativeKey)) {
                    $criteria = array($pk => $nativeKey);
                } else {
                    $criteria = $nativeKey;
                    $transport->xpdo->log(xPDO::LOG_LEVEL_WARN, 'The native key provided in the vehicle does not match the primary key field(s) for the object: ' . print_r($nativeKey, true));
                }
            }
            if (!empty ($vOptions[xPDOTransport::PREEXISTING_MODE])) {
                $preExistingMode = intval($vOptions[xPDOTransport::PREEXISTING_MODE]);
            }
            if ($this->validate($transport, $object, $vOptions)) {
                if (!$this->_installRelated($transport, $object, $element, $options, 'foreign')) {
                    $transport->xpdo->log(xPDO::LOG_LEVEL_ERROR, "Could not install related objects with foreign owned keys for vehicle object of class {$vClass}; criteria: " . print_r($criteria, true));
                    if ($transport->xpdo->getDebug() === true) $transport->xpdo->log(xPDO::LOG_LEVEL_DEBUG, 'Could not install related objects for vehicle: ' . print_r($vOptions, true));
                } elseif (!empty($vOptions[xPDOTransport::UNIQUE_KEY])) {
                    $uniqueKey = $object->get($vOptions[xPDOTransport::UNIQUE_KEY]);
                    if (is_array($uniqueKey)) {
                        $criteria = array_combine($vOptions[xPDOTransport::UNIQUE_KEY], $uniqueKey);
                    } else {
                        $criteria = array (
                            $vOptions[xPDOTransport::UNIQUE_KEY] => $uniqueKey
                        );
                    }
                } else {
                    $pk = $object->getPK();
                    $nativeKey = $object->get($pk);
                    if (is_array($pk) && is_array($nativeKey)) {
                        $criteria = array_combine($pk, $nativeKey);
                    } elseif (is_string($pk) && is_scalar($nativeKey)) {
                        $criteria = array($pk => $nativeKey);
                    } else {
                        $criteria = $nativeKey;
                        $transport->xpdo->log(xPDO::LOG_LEVEL_WARN, 'The native key provided in the vehicle does not match the primary key field(s) for the object: ' . print_r($nativeKey, true));
                    }
                }
                /** @var xPDOObject $obj */
                if ($obj = $transport->xpdo->getObject($vClass, $criteria)) {
                    $exists = true;
                    if ($preExistingMode !== xPDOTransport::REMOVE_PREEXISTING) {
                        $transport->_preserved[$vOptions['guid']] = array (
                            'criteria' => $criteria,
                            'object' => $obj->toArray('', true)
                        );
                    }
                    if ($upgrade) {
                        $obj->fromArray($object->toArray('', true), '', false, true);
                        $object = $obj;
                    } else {
                        if (is_array($criteria)) {
                            $obj->fromArray($criteria, '', true);
                        }
                        $object = $obj;
                    }
                }
                elseif ($transport->xpdo->getDebug() === true) {
                    $transport->xpdo->log(xPDO::LOG_LEVEL_DEBUG, "Object for class {$vClass} not found; criteria: " . print_r($criteria, true));
                }
                if (!$exists || ($exists && $upgrade)) {
                    if ($transport->xpdo->getOption('dbtype') === 'sqlsrv' && !$exists && $preserveKeys) {
                        $transport->xpdo->exec("SET IDENTITY_INSERT {$transport->xpdo->getTableName($vClass)} ON");
                    }
                    $saved = $object->save();
                    if ($transport->xpdo->getOption('dbtype') === 'sqlsrv' && !$exists && $preserveKeys) {
                        $transport->xpdo->exec("SET IDENTITY_INSERT {$transport->xpdo->getTableName($vClass)} OFF");
                    }
                    if (!$saved) {
                        $transport->xpdo->log(xPDO::LOG_LEVEL_ERROR, "Error saving vehicle object of class {$vClass}; criteria: " . print_r($criteria, true));
                        if ($transport->xpdo->getDebug() === true) $transport->xpdo->log(xPDO::LOG_LEVEL_DEBUG, "Error saving vehicle object: " . print_r($vOptions, true));
                    }
                } else {
                    $transport->xpdo->log(xPDO::LOG_LEVEL_INFO, "Skipping vehicle object of class {$vClass} (data object exists and cannot be upgraded); criteria: " . print_r($criteria, true));
                    if ($transport->xpdo->getDebug() === true) $transport->xpdo->log(xPDO::LOG_LEVEL_DEBUG, 'Skipping vehicle object (data object exists and cannot be upgraded): ' . print_r($vOptions, true));
                }
                if (($saved || $exists)) {
                    if ($parentObject !== null && $fkMeta !== null) {
                        if ($fkMeta['owner'] == 'foreign') {
                            if ($object->get($fkMeta['foreign']) !== $parentObject->get($fkMeta['local'])) {
                                $parentObject->set($fkMeta['local'], $object->get($fkMeta['foreign']));
                            }
                        }
                    }
                }
                if (($saved || $exists) && !$this->_installRelated($transport, $object, $element, $options, 'local')) {
                    $transport->xpdo->log(xPDO::LOG_LEVEL_ERROR, "Could not install related objects with locally owned keys for vehicle object of class {$vClass}; criteria: " . print_r($criteria, true));
                    if ($transport->xpdo->getDebug() === true) $transport->xpdo->log(xPDO::LOG_LEVEL_DEBUG, 'Could not install related objects for vehicle: ' . print_r($vOptions, true));
                }
                if ($parentObject === null && !$this->resolve($transport, $object, $vOptions)) {
                    $transport->xpdo->log(xPDO::LOG_LEVEL_ERROR, "Could not resolve vehicle for object of class {$vClass}; criteria: " . print_r($criteria, true));
                    if ($transport->xpdo->getDebug() === true) $transport->xpdo->log(xPDO::LOG_LEVEL_DEBUG, 'Could not resolve vehicle: ' . print_r($vOptions, true));
                }
            } else {
                //$transport->xpdo->log(xPDO::LOG_LEVEL_ERROR, "Could not validate vehicle object of class {$vClass}; criteria: " . print_r($criteria, true));
                if ($transport->xpdo->getDebug() === true) $transport->xpdo->log(xPDO::LOG_LEVEL_DEBUG, 'Could not validate vehicle object: ' . print_r($vOptions, true));
            }
        } else {
            $transport->xpdo->log(xPDO::LOG_LEVEL_ERROR, 'Could not load vehicle!');
        }
        return ($saved || ($exists && !$upgrade));
    }

    /**
     * Installs a related object from the vehicle.
     *
     * @param xPDOTransport &$transport
     * @param xPDOObject &$parent
     * @param array $element
     * @param array $options
     * @return bool
     */
    public function _installRelated(& $transport, & $parent, $element, $options, $owner = '') {
        $installed = true;
        if (is_object($parent) && isset ($element[xPDOTransport::RELATED_OBJECTS]) && is_array($element[xPDOTransport::RELATED_OBJECTS])) {
            foreach ($element[xPDOTransport::RELATED_OBJECTS] as $rAlias => $rVehicles) {
                $rMeta = $parent->getFKDefinition($rAlias);
                if ($rMeta) {
                    if (!empty($owner) && $owner !== $rMeta['owner']) continue;
                    $rOptions = $options;
                    if (isset($element[xPDOTransport::RELATED_OBJECT_ATTRIBUTES])) {
                        if (isset($element[xPDOTransport::RELATED_OBJECT_ATTRIBUTES][$rAlias])) {
                            $rOptions = array_merge($rOptions, $element[xPDOTransport::RELATED_OBJECT_ATTRIBUTES][$rAlias]);
                        } elseif (isset($element[xPDOTransport::RELATED_OBJECT_ATTRIBUTES][$rMeta['class']])) {
                            $rOptions = array_merge($rOptions, $element[xPDOTransport::RELATED_OBJECT_ATTRIBUTES][$rMeta['class']]);
                        }
                    }
                    foreach ($rVehicles as $rKey => $rVehicle) {
                        $installed = $this->_installObject($transport, $rOptions, $rVehicle, $parent, $rMeta);
                    }
                }
            }
        }
        return $installed;
    }

    /**
     * Uninstalls vehicle artifacts from the transport host.
     */
    public function uninstall(& $transport, $options) {
        $parentObj = null;
        $parentMeta = null;
        return $this->_uninstallObject($transport, $options, $this->payload, $parentObj, $parentMeta);
    }

    /**
     * Uninstall the xPDOObjects represented by a vehicle element from the transport host.
     */
    public function _uninstallObject(& $transport, $options, $element, & $parentObject, $fkMeta) {
        $uninstalled = false;
        $removed = false;
        $uninstallObject = true;
        $upgrade = false;
        $preExistingMode = xPDOTransport::PRESERVE_PREEXISTING;
        $object = $this->get($transport, $options, $element);
        if (is_object($object) && $object instanceof xPDOObject) {
            $vOptions = array_merge($options, $element);
            $vClass = $vOptions['class'];
            $upgrade = !empty($vOptions[xPDOTransport::UPDATE_OBJECT]);
            $preserveKeys = !empty ($vOptions[xPDOTransport::PRESERVE_KEYS]);
            if (!empty ($vOptions[xPDOTransport::UNIQUE_KEY])) {
                $uniqueKey = $object->get($vOptions[xPDOTransport::UNIQUE_KEY]);
                if (is_array($uniqueKey)) {
                    $criteria = array_combine($vOptions[xPDOTransport::UNIQUE_KEY], $uniqueKey);
                } else {
                    $criteria = array (
                        $vOptions[xPDOTransport::UNIQUE_KEY] => $uniqueKey
                    );
                }
            } else {
                $criteria = $vOptions[xPDOTransport::NATIVE_KEY];
            }
            if (!empty ($vOptions[xPDOTransport::PREEXISTING_MODE])) {
                $preExistingMode = intval($vOptions[xPDOTransport::PREEXISTING_MODE]);
            }
            if ($this->validate($transport, $object, $vOptions)) {
                $uninstalled = true;
                if (isset($vOptions[xPDOTransport::UNINSTALL_OBJECT])) {
                    $uninstallObject = !empty($vOptions[xPDOTransport::UNINSTALL_OBJECT]);
                }
                $exists = false;
                if ($obj = $transport->xpdo->getObject($vClass, $criteria)) {
                    $exists = true;
                    $object = $obj;
                }
                if ($exists) {
                    if ($uninstallObject && $upgrade && (!isset ($transport->_preserved[$vOptions['guid']]) || $preExistingMode === xPDOTransport::REMOVE_PREEXISTING)) {
                        $removed = $object->remove();
                        if (!$removed) {
                            $uninstalled = false;
                            $transport->xpdo->log(xPDO::LOG_LEVEL_ERROR, "Error removing vehicle object of class {$vClass}; criteria: " . print_r($criteria, true));
                            if ($transport->xpdo->getDebug() === true) $transport->xpdo->log(xPDO::LOG_LEVEL_DEBUG, 'Error removing vehicle object: ' . print_r($vOptions, true));
                        }
                    }
                    if ($upgrade && $preExistingMode === xPDOTransport::RESTORE_PREEXISTING) {
                        if (isset ($transport->_preserved[$vOptions['guid']])) {
                            $preserved = $transport->_preserved[$vOptions['guid']]['object'];
                            $object->fromArray($preserved, '', true, true);
                            $restored = $object->save();
                            if (!$restored) {
                                $transport->xpdo->log(xPDO::LOG_LEVEL_ERROR, "Error restoring preserved object of class {$vClass}: " . print_r($transport->_preserved[$vOptions['guid']], true));
                            }
                        }
                    }
                } else {
                    $transport->xpdo->log(xPDO::LOG_LEVEL_WARN, 'Skipping ' . $vClass . ' object (data object does not exist and cannot be removed): ' . print_r($criteria, true));
                }
                if (!$this->_uninstallRelated($transport, $object, $element, $options)) {
                    $transport->xpdo->log(xPDO::LOG_LEVEL_ERROR, "Could not uninstall related objects for vehicle object of class {$vClass}; criteria: " . print_r($criteria, true));
                    if ($transport->xpdo->getDebug() === true) $transport->xpdo->log(xPDO::LOG_LEVEL_DEBUG, 'Could not uninstall related objects for vehicle: ' . print_r($vOptions, true));
                }
                if ($parentObject === null && !$this->resolve($transport, $object, $vOptions)) {
                    $transport->xpdo->log(xPDO::LOG_LEVEL_ERROR, "Could not resolve vehicle for object of class {$vClass}; criteria: " . print_r($criteria, true));
                    if ($transport->xpdo->getDebug() === true) $transport->xpdo->log(xPDO::LOG_LEVEL_DEBUG, 'Could not resolve vehicle: ' . print_r($vOptions, true));
                }
            } else {
                //$transport->xpdo->log(xPDO::LOG_LEVEL_ERROR, "Could not validate vehicle object of class {$vClass}; criteria: " . print_r($criteria, true));
                if ($transport->xpdo->getDebug() === true) $transport->xpdo->log(xPDO::LOG_LEVEL_DEBUG, 'Could not validate vehicle object: ' . print_r($vOptions, true));
            }
        } else {
            $transport->xpdo->log(xPDO::LOG_LEVEL_ERROR, 'Problem instantiating object from vehicle');
            if ($transport->xpdo->getDebug() === true) $transport->xpdo->log(xPDO::LOG_LEVEL_DEBUG, 'Problem instantiating object from vehicle: ' . print_r(array_merge($options, $element), true));
        }
        return $uninstalled;
    }

    /**
     * Uninstalls related objects from the vehicle.
     */
    public function _uninstallRelated(& $transport, & $parent, $element, $options) {
        $uninstalled = true;
        if (is_object($parent) && isset ($element[xPDOTransport::RELATED_OBJECTS]) && is_array($element[xPDOTransport::RELATED_OBJECTS])) {
            $uninstalled = false;
            foreach ($element[xPDOTransport::RELATED_OBJECTS] as $rAlias => $rVehicles) {
                $parentClass = $parent->_class;
                $rMeta = $transport->xpdo->getFKDefinition($parentClass, $rAlias);
                if ($rMeta) {
                    $results = array();
                    foreach ($rVehicles as $rKey => $rVehicle) {
                        $results[] = $this->_uninstallObject($transport, $options, $rVehicle, $parent, $rMeta);
                    }
                    $uninstalled = (array_search(false, $results) === false);
                }
            }
        }
        return $uninstalled;
    }

    /**
     * Put an xPDOObject representation into a transport package.
     *
     * This implementation supports the inclusion of related objects. Simply instantiate the related
     * objects that you want to include in the vehicle on the main object, and set
     * xPDOTransport::RELATED_OBJECTS => true in your attributes.
     */
    public function put(& $transport, & $object, $attributes = array ()) {
        parent :: put($transport, $object, $attributes);
        if (is_object($object)) {
            if (!isset ($this->payload['package'])) {
                if ($object instanceof xPDOObject) {
                    $packageName = $object->_package;
                } else {
                    $packageName = '';
                }
                $this->payload['package'] = $packageName;
            }
            if ($object instanceof xPDOObject) {
                $nativeKey = $object->getPrimaryKey();
                $this->payload['object'] = $object->toJSON('', true);
                $this->payload['native_key'] = $nativeKey;
                $this->payload['signature'] = md5($this->payload['class'] . '_' . $this->payload['guid']);
                if (isset ($this->payload[xPDOTransport::RELATED_OBJECTS]) && !empty ($this->payload[xPDOTransport::RELATED_OBJECTS])) {
                    $relatedObjects = array ();
                    foreach ($object->_relatedObjects as $rAlias => $related) {
                        if (is_array($related)) {
                            foreach ($related as $rKey => $rObj) {
                                if (!isset ($relatedObjects[$rAlias]))
                                    $relatedObjects[$rAlias] = array ();
                                $guid = md5(uniqid(rand(), true));
                                $relatedObjects[$rAlias][$guid] = array ();
                                $this->_putRelated($transport, $rAlias, $rObj, $relatedObjects[$rAlias][$guid]);
                            }
                        }
                        elseif (is_object($related)) {
                            if (!isset ($relatedObjects[$rAlias]))
                                $relatedObjects[$rAlias] = array ();
                            $guid = md5(uniqid(rand(), true));
                            $relatedObjects[$rAlias][$guid] = array ();
                            $this->_putRelated($transport, $rAlias, $related, $relatedObjects[$rAlias][$guid]);
                        }
                    }
                    if (!empty ($relatedObjects))
                        $this->payload['related_objects'] = $relatedObjects;
                }
            }
            elseif (is_object($object)) {
                $this->payload['object'] = $transport->xpdo->toJSON(get_object_vars($object));
                $this->payload['native_key'] = $this->payload['guid'];
                $this->payload['signature'] = md5($this->payload['class'] . '_' . $this->payload['guid']);
            }
        }
    }

    /**
     * Recursively put related objects into the vehicle.
     *
     * @access protected
     * @param xPDOTransport $transport The host xPDOTransport instance.
     * @param string $alias The alias representing the relation to the parent object.
     * @param xPDOObject &$object A reference to the dependent object being added into the vehicle.
     * @param array $payloadElement An element of the payload to place the dependent object in.
     */
    protected function _putRelated(& $transport, $alias, & $object, & $payloadElement) {
        if (is_array($payloadElement)) {
            if (is_object($object) && $object instanceof xPDOObject) {
                if (isset ($this->payload['related_object_attributes'][$alias]) && is_array($this->payload['related_object_attributes'][$alias])) {
                    $payloadElement = array_merge($payloadElement, $this->payload['related_object_attributes'][$alias]);
                }
                elseif (isset ($this->payload['related_object_attributes'][$object->_class]) && is_array($this->payload['related_object_attributes'][$object->_class])) {
                    $payloadElement = array_merge($payloadElement, $this->payload['related_object_attributes'][$object->_class]);
                }
                $payloadElement['class'] = $object->_class;
                $nativeKey = $object->getPrimaryKey();
                $payloadElement['object'] = $object->toJSON('', true);
                $payloadElement['guid'] = md5(uniqid(rand(), true));
                $payloadElement['native_key'] = $nativeKey;
                $payloadElement['signature'] = md5($object->_class . '_' . $payloadElement['guid']);
                $relatedObjects = array ();
                foreach ($object->_relatedObjects as $rAlias => $related) {
                    if (is_array($related)) {
                        foreach ($related as $rKey => $rObj) {
                            if (!isset ($relatedObjects[$rAlias]))
                                $relatedObjects[$rAlias] = array ();
                            $guid = md5(uniqid(rand(), true));
                            $relatedObjects[$rAlias][$guid] = array ();
                            if (isset($payloadElement['related_object_attributes'][$rAlias]) && is_array($payloadElement['related_object_attributes'][$rAlias])) {
                                $relatedObjects[$rAlias][$guid] = $payloadElement['related_object_attributes'][$rAlias];
                            } elseif (isset ($payloadElement['related_object_attributes'][$rObj->_class]) && is_array($payloadElement['related_object_attributes'][$rObj->_class])) {
                                $relatedObjects[$rAlias][$guid] = $payloadElement['related_object_attributes'][$rObj->_class];
                            }
                            $this->_putRelated($transport, $rAlias, $rObj, $relatedObjects[$rAlias][$guid]);
                        }
                    }
                    elseif (is_object($related)) {
                        if (!isset ($relatedObjects[$rAlias]))
                            $relatedObjects[$rAlias] = array ();
                        $guid = md5(uniqid(rand(), true));
                        $relatedObjects[$rAlias][$guid] = array ();
                        if (isset($payloadElement['related_object_attributes'][$rAlias]) && is_array($payloadElement['related_object_attributes'][$rAlias])) {
                            $relatedObjects[$rAlias][$guid] = $payloadElement['related_object_attributes'][$rAlias];
                        } elseif (isset ($payloadElement['related_object_attributes'][$related->_class]) && is_array($payloadElement['related_object_attributes'][$related->_class])) {
                            $relatedObjects[$rAlias][$guid] = $payloadElement['related_object_attributes'][$related->_class];
                        }
                        $this->_putRelated($transport, $rAlias, $related, $relatedObjects[$rAlias][$guid]);
                    }
                }
                if (!empty ($relatedObjects))
                    $payloadElement['related_objects'] = $relatedObjects;
            }
        }
    }
}
