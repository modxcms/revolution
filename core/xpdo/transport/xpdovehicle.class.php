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
 * Abstract class that represents an artifact within a transportable package.
 *
 * @package xpdo
 * @subpackage transport
 */

/**
 * Represents an individual artifact within an {@link xPDOTransport} package.
 *
 * Extend this abstract class to provide custom xPDOVehicle behavior for various kinds of artifacts
 * (e.g. objects, xPDOObjects, files, database schemas, etc.).
 *
 * @package xpdo
 * @subpackage transport
 *
 * @abstract
 */
abstract class xPDOVehicle {
    /**
     * Represents the artifact and related attributes stored in the vehicle.
     * @var array
     */
    public $payload = array ();

    public $class = 'xPDOVehicle';

    /**
     * Build a manifest entry to be registered in a transport for this vehicle.
     *
     * @param xPDOTransport &$transport The xPDOTransport instance to register
     * the vehicle into.
     * @return array An array of vehicle attributes that will be registered into
     * an xPDOTransport manifest.
     */
    public function register(& $transport) {
        $vPackage = isset($this->payload['vehicle_package']) ? $this->payload['vehicle_package'] : 'transport';
        $vClass = isset($this->payload['vehicle_class']) ? $this->payload['vehicle_class'] : $this->class;
        $class = isset($this->payload['class']) ? $this->payload['class'] : $vClass;
        $entry = array(
            'vehicle_package' => $vPackage,
            'vehicle_class' => $vClass,
            'class' => $class,
            'guid' => $this->payload['guid'],
            'native_key' => array_key_exists('native_key', $this->payload) ? $this->payload['native_key'] : null,
            'filename' => $class . '/' . $this->payload['filename'],
        );
        if (isset($this->payload['namespace'])) {
            $entry['namespace'] = $this->payload['namespace'];
        }
        return $entry;
    }

    /**
     * Retrieve an artifact represented in this vehicle.
     *
     * By default, this method simply returns the raw payload merged with the
     * provided options, but you can optionally provide a payload element
     * specifically on which to operate as well as override the method in
     * derivatives to further transform the returned artifact.
     *
     * @param xPDOTransport $transport The transport package containing this
     * vehicle.
     * @param array $options Options that apply to the artifact or retrieval
     * process.
     * @param array $element An optional payload element representing a specific
     * part of the artifact to operate on.  If not specified, the root element
     * of the payload is used.
     */
    public function get(& $transport, $options = array (), $element = null) {
        $artifact = null;
        if ($element === null) $element = $this->payload;
        $artifact = array_merge($options, $element);
        return $artifact;
    }

    /**
     * Install the vehicle artifact into a transport host.
     *
     * @abstract Implement this in a derivative to make an installable vehicle.
     * @param xPDOTransport &$transport A reference to the transport.
     * @param array $options An array of options for altering the installation
     * of the artifact.
     * @return boolean True if the installation of the vehicle artifact was
     * successful.
     */
    abstract public function install(& $transport, $options);

    /**
     * Uninstalls the vehicle artifact from a transport host.
     *
     * @abstract Implement this in a derivative to make an uninstallable
     * vehicle.
     * @param xPDOTransport &$transport A reference to the transport.
     * @param array $options An array of options for altering the uninstallation
     * of the artifact.
     */
    abstract public function uninstall(& $transport, $options);

    /**
     * Resolve any dependencies of the artifact represented in this vehicle.
     *
     * @param xPDOTransport &$transport A reference to the xPDOTransport in
     * which this vehicle is stored.
     * @param mixed &$object An object reference to resolve dependencies for.
     * Use this to make the artifact or other important data available to the
     * resolver scripts.
     * @param array $options Additional options for the resolution process.
     * @return boolean Indicates if the resolution was successful.
     */
    public function resolve(& $transport, & $object, $options = array ()) {
        $resolved = false;
        if (isset ($this->payload['resolve'])) {
            while (list ($rKey, $r) = each($this->payload['resolve'])) {
                $type = $r['type'];
                $body = $r['body'];
                $preExistingMode = xPDOTransport::PRESERVE_PREEXISTING;
                if (!empty ($options[xPDOTransport::PREEXISTING_MODE])) {
                    $preExistingMode = intval($options[xPDOTransport::PREEXISTING_MODE]);
                }
                switch ($type) {
                    case 'file' :
                        if (isset ($options[xPDOTransport::RESOLVE_FILES]) && !$options[xPDOTransport::RESOLVE_FILES]) {
                            $resolved = true;
                            continue;
                        }
                        if ($transport->xpdo->getDebug() === true) {
                            $transport->xpdo->log(xPDO::LOG_LEVEL_DEBUG, "Resolving transport files: " . print_r($this, true));
                        }
                        $fileMeta = $transport->xpdo->fromJSON($body, true);
                        $fileName = $fileMeta['name'];
                        $fileSource = $transport->path . $fileMeta['source'];
                        $fileTarget = eval ($fileMeta['target']);
                        $fileTargetPath = $fileTarget . $fileName;
                        $preservedArchive = $transport->path . $transport->signature . '/' . $this->payload['class'] . '/' . $this->payload['signature'] . '.' . $rKey . '.preserved.zip';
                        $cacheManager = $transport->xpdo->getCacheManager();
                        switch ($options[xPDOTransport::PACKAGE_ACTION]) {
                            case xPDOTransport::ACTION_UPGRADE:
                            case xPDOTransport::ACTION_INSTALL: // if package is installing
                                if ($cacheManager && file_exists($fileSource) && !empty ($fileTarget)) {
                                    $copied = array();
                                    if ($preExistingMode === xPDOTransport::PRESERVE_PREEXISTING && file_exists($fileTargetPath)) {
                                        $transport->xpdo->log(xPDO::LOG_LEVEL_INFO, "Attempting to preserve files at {$fileTargetPath} into archive {$preservedArchive}");
                                        $preserved = xPDOTransport::_pack($transport->xpdo, $preservedArchive, $fileTarget, $fileName);
                                    }
                                    if (is_dir($fileSource)) {
                                        $copied = $cacheManager->copyTree($fileSource, $fileTarget, array_merge($options, array('copy_return_file_stat' => true)));
                                    } elseif (is_file($fileSource)) {
                                        $copied = $cacheManager->copyFile($fileSource, $fileTarget, array_merge($options, array('copy_return_file_stat' => true)));
                                    }
                                    if (empty($copied)) {
                                        $transport->xpdo->log(xPDO::LOG_LEVEL_ERROR, "Could not copy {$fileSource} to {$fileTargetPath}");
                                    } else {
                                        if ($preExistingMode === xPDOTransport::PRESERVE_PREEXISTING && is_array($copied)) {
                                            foreach ($copied as $copiedFile => $stat) {
                                                if (isset($stat['overwritten'])) $transport->_preserved[$options['guid']]['files'][substr($copiedFile, strlen($fileTarget))]= $stat;
                                            }
                                        }
                                        $resolved = true;
                                    }
                                } else {
                                    $transport->xpdo->log(xPDO::LOG_LEVEL_ERROR, "Could not copy {$fileSource} to {$fileTargetPath}");
                                }
                                break;
                            case xPDOTransport::ACTION_UNINSTALL: /* if package is uninstalling
                                   user can override whether or not files from resolver are removed
                                   however default action is to remove */
                                if (!isset($options[xPDOTransport::RESOLVE_FILES_REMOVE]) || $options[xPDOTransport::RESOLVE_FILES_REMOVE] !== false) {
                                    $path = $fileTarget.$fileName;
                                    $transport->xpdo->log(xPDO::LOG_LEVEL_INFO,'Removing files in file resolver: '.$path);
                                    if ($cacheManager && file_exists($path)) {
                                        if (is_dir($path) && $cacheManager->deleteTree($path, array_merge(array('deleteTop' => true, 'skipDirs' => false, 'extensions' => array()), $options))) {
                                            $resolved = true;
                                        } elseif (is_file($path) && unlink($path)) {
                                            $resolved = true;
                                        } else {
                                            $transport->xpdo->log(xPDO::LOG_LEVEL_ERROR,'Could not remove files from path: '.$path);
                                        }
                                    } else {
                                        $transport->xpdo->log(xPDO::LOG_LEVEL_ERROR,'Could not find files to remove.');
                                    }
                                } else {
                                    /* action was chosen not to remove, send log message and continue */
                                    $transport->xpdo->log(xPDO::LOG_LEVEL_INFO,'Skipping removing of files according to vehicle attributes.');
                                    $resolved = true;
                                }
                                if ($preExistingMode === xPDOTransport::RESTORE_PREEXISTING && file_exists($preservedArchive)) {
                                    $transport->xpdo->log(xPDO::LOG_LEVEL_ERROR, "Attempting to restore files to {$fileTarget} from archive {$preservedArchive}");
                                    $unpackedResult = xPDOTransport::_unpack($transport->xpdo, $preservedArchive, $fileTarget);
                                    if ($unpackedResult > 0) {
                                        $resolved = true;
                                    } else {
                                        $transport->xpdo->log(xPDO::LOG_LEVEL_ERROR, "Error unpacking preserved files from archive {$preservedArchive}");
                                    }
                                }
                                break;
                        }
                        break;

                    case 'php' :
                        if (isset ($options[xPDOTransport::RESOLVE_PHP]) && !$options[xPDOTransport::RESOLVE_PHP]) {
                            continue;
                        }
                        $fileMeta = $transport->xpdo->fromJSON($body, true);
                        $fileName = $fileMeta['name'];
                        $fileSource = $transport->path . $fileMeta['source'];
                        if (!$resolved = include ($fileSource)) {
                            $transport->xpdo->log(xPDO::LOG_LEVEL_ERROR, "xPDOVehicle resolver failed: type php ({$fileSource})");
                        }
                        break;

                    default :
                        $transport->xpdo->log(xPDO::LOG_LEVEL_WARN, "xPDOVehicle does not support resolvers of type {$type}.");
                        break;
                }
            }
        } else {
            $resolved = true;
        }
        return $resolved;
    }

    /**
     * Validate any dependencies for the object represented in this vehicle.
     *
     * @param xPDOTransport &$transport A reference to the xPDOTransport in
     * which this vehicle is stored.
     * @param xPDOObject &$object An object reference to access during
     * validation.
     * @param array $options Additional options for the validation process.
     * @return boolean Indicating if the validation was successful.
     */
    public function validate(& $transport, & $object, $options = array ()) {
        $validated = true;
        if (isset ($this->payload['validate'])) {
            while (list ($rKey, $r) = each($this->payload['validate'])) {
                $type = $r['type'];
                $body = $r['body'];
                switch ($type) {
                    case 'php' :
//                        if (isset ($options[xPDOTransport::VALIDATE_PHP]) && !$options[xPDOTransport::VALIDATE_PHP]) {
//                            continue;
//                        }
                        $fileMeta = $transport->xpdo->fromJSON($body, true);
                        $fileName = $fileMeta['name'];
                        $fileSource = $transport->path . $fileMeta['source'];
                        if (!$validated = include ($fileSource)) {
                            if (!isset($fileMeta['silent_fail']) || !$fileMeta['silent_fail']) {
                                $transport->xpdo->log(xPDO::LOG_LEVEL_ERROR, "xPDOVehicle validator failed: type php ({$fileSource})");
                            }
                        }
                        break;

                    default :
                        $transport->xpdo->log(xPDO::LOG_LEVEL_WARN, "xPDOVehicle does not support validators of type {$type}.");
                        break;
                }
            }
        } else {
            $validated = true;
        }
        return $validated;
    }

    /**
     * Put an artifact representation into this vehicle.
     *
     * @param xPDOTransport $transport The transport package hosting the
     * vehicle.
     * @param mixed &$object A reference to the artifact this vehicle will
     * represent.
     * @param array $attributes Additional attributes represented in the
     * vehicle.
     */
    public function put(& $transport, & $object, $attributes = array ()) {
        $this->payload = array_merge($this->payload, $attributes);
        if (!isset($this->payload['guid'])) {
            $this->payload['guid'] = md5(uniqid(rand(), true));
        }
        if (!isset ($this->payload['package'])) {
            if ($object instanceof xPDOObject) {
                $packageName = $object->_package;
            } else {
                $packageName = '';
            }
            $this->payload['package'] = $packageName;
        }
        if (!isset($this->payload['class'])) {
            $className = 'xPDOVehicle';
            if (is_object($object)) {
                if ($object instanceof xPDOObject) {
                    $className = $object->_class;
                } else {
                    $className = get_class($object);
                }
            }
            $this->payload['class'] = $className;
        }
        if (!isset($this->payload['signature'])) {
            $this->payload['signature'] = md5($this->payload['class'] . '_' . $this->payload['guid']);
        }
        if (!isset($this->payload['native_key'])) {
            $nativeKey = null;
            $nativeKeyAttr = isset($this->payload['native_key_attribute']) ? $this->payload['native_key_attribute'] : null;
            if (is_object($object)) {
                if ($object instanceof xPDOObject) {
                    $nativeKey = $object->getPrimaryKey();
                } elseif (!empty($nativeKeyAttr) && isset($object->$nativeKeyAttr)) {
                    $nativeKey = $object->$nativeKeyAttr;
                }
            } elseif (!empty($nativeKeyAttr) && isset($this->payload[$nativeKeyAttr])) {
                $nativeKey = $this->payload[$nativeKeyAttr];
            } else {
                $nativeKey = $this->payload['guid'];
            }
            $this->payload['native_key'] = $nativeKey;
        }
        if (isset ($attributes['validate'])) {
            $this->payload['validate'] = (array) $attributes['validate'];
        }
        if (isset ($attributes['resolve'])) {
            $this->payload['resolve'] = (array) $attributes['resolve'];
        }
        if (isset ($attributes['namespace'])) {
            if ($attributes['namespace'] instanceof xPDOObject) {
                $this->payload['namespace'] = $attributes['namespace']->get('name');
            } else {
                $this->payload['namespace'] = $attributes['namespace'];
            }
        }
    }

    /**
     * Store this xPDOVehicle instance into an xPDOTransport.
     *
     * @param xPDOTransport &$transport The transport to store the vehicle in.
     * @return boolean Indicates if the vehicle was stored in the transport.
     */
    public function store(& $transport) {
        $stored = false;
        $cacheManager = $transport->xpdo->getCacheManager();
        if ($cacheManager && !empty ($this->payload)) {
            $this->_compilePayload($transport);

            $content = '<?php return ';
            $content .= var_export($this->payload, true);
            $content .= ';';
            $this->payload['filename'] = $this->payload['signature'] . '.vehicle';
            $vFileName = $transport->path . $transport->signature . '/' . $this->payload['class'] . '/' . $this->payload['filename'];
            if (!($stored = $cacheManager->writeFile($vFileName, $content))) {
                $transport->xpdo->log(xPDO::LOG_LEVEL_ERROR, 'Could not store vehicle to file ' . $vFileName);
            }
        }
        return $stored;
    }

    /**
     * Compile necessary resources in preparation for storing the vehicle.
     *
     * @access protected
     * @param xPDOTransport &$transport A reference to the transport the vehicle is being stored in.
     */
    protected function _compilePayload(& $transport) {
        $cacheManager = $transport->xpdo->getCacheManager();
        if ($cacheManager) {
            if (isset ($this->payload['resolve']) && is_array($this->payload['resolve'])) {
                foreach ($this->payload['resolve'] as $rKey => $r) {
                    $type = $r['type'];
                    $body = array ();
                    switch ($type) {
                        case 'file' :
                            $fileSource = $r['source'];
                            $body['source'] = $transport->signature . '/' . $this->payload['class'] . '/' . $this->payload['signature'] . '/' . $rKey . '/';
                            $fileTarget = $transport->path . $body['source'];
                            $body['target'] = $r['target'];
                            $fileName = isset ($r['name']) ? $r['name'] : basename($fileSource);
                            $body['name'] = $fileName;
                            if (!is_writable($fileTarget)) {
                                $cacheManager->writeTree($fileTarget);
                            }
                            if (file_exists($fileSource) && is_writable($fileTarget)) {
                                $copied = false;
                                if (is_dir($fileSource)) {
                                    $copied = $cacheManager->copyTree($fileSource, $fileTarget . $fileName);
                                }
                                elseif (is_file($fileSource)) {
                                    $copied = $cacheManager->copyFile($fileSource, $fileTarget . $fileName);
                                }
                                if (!$copied) {
                                    $transport->xpdo->log(xPDO::LOG_LEVEL_ERROR, "Could not copy file from {$fileSource} to {$fileTarget}{$fileName}");
                                    $body = null;
                                }
                            } else {
                                $transport->xpdo->log(xPDO::LOG_LEVEL_ERROR, "Source file {$fileSource} is missing or {$fileTarget} is not writable");
                                $body = null;
                            }
                            break;

                        case 'php' :
                            $fileSource = $r['source'];
                            $scriptName = basename($fileSource, '.php');
                            $body['source'] = $transport->signature . '/' . $this->payload['class'] . '/' . $this->payload['signature'] . '.' . $scriptName . '.resolver';
                            $fileTarget = $transport->path . $body['source'];
                            $body['name'] = $scriptName;
                            $body = array_merge($r, $body);
                            if (!$cacheManager->copyFile($fileSource, $fileTarget)) {
                                $transport->xpdo->log(xPDO::LOG_LEVEL_ERROR, "Source file {$fileSource} is missing or {$fileTarget} could not be written");
                            }
                            break;

                        default :
                            $transport->xpdo->log(xPDO::LOG_LEVEL_WARN, "xPDOVehicle does not support resolvers of type {$type}.");
                            break;
                    }
                    if ($body) {
                        $this->payload['resolve'][$rKey] = array (
                            'type' => $type,
                            'body' => $transport->xpdo->toJSON($body)
                        );
                    } else {
                        $this->payload['resolve'][$rKey] = null;
                    }
                }
            }

            if (isset($this->payload['validate']) && is_array($this->payload['validate'])) {
                foreach ($this->payload['validate'] as $vKey => $v) {
                    $type = $v['type'];
                    $body = array ();
                    switch ($type) {
                        case 'php' :
                            $fileSource = $v['source'];
                            $scriptName = basename($fileSource, '.php');
                            $body['source'] = $transport->signature . '/' . $this->payload['class'] . '/' . $this->payload['signature'] . '.' . $scriptName . '.validator';
                            $fileTarget = $transport->path . $body['source'];
                            $body['name'] = $scriptName;
                            $body = array_merge($v, $body);
                            if (!$cacheManager->copyFile($fileSource, $fileTarget)) {
                                $transport->xpdo->log(xPDO::LOG_LEVEL_ERROR, "Source file {$fileSource} is missing or {$fileTarget} could not be written");
                            }
                            break;

                        default :
                            $transport->xpdo->log(xPDO::LOG_LEVEL_WARN, "xPDOVehicle does not support validators of type {$type}.");
                            break;
                    }
                    if ($body) {
                        $this->payload['validate'][$vKey] = array (
                            'type' => $type,
                            'body' => $transport->xpdo->toJSON($body)
                        );
                    } else {
                        $this->payload['validate'][$vKey] = null;
                    }
                }
            }
        }
    }
}
