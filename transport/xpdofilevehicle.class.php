<?php
/*
 * Copyright 2010-2014 by MODX, LLC.
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
 * Defines a class that represents a fileset within a transportable package.
 *
 * @package xpdo
 * @subpackage transport
 */

/**
 * Represents an individual file set within an {@link xPDOTransport} package.
 *
 * @package xpdo
 * @subpackage transport
 */
class xPDOFileVehicle extends xPDOVehicle {
    public $class = 'xPDOFileVehicle';

    /**
     * Install a file set represented by and stored in this vehicle.
     */
    public function install(& $transport, $options) {
        $installed = $this->_installFiles($transport, $options);
        return $installed;
    }

    /**
     * Uninstall a file set represented by and stored in this vehicle.
     */
    public function uninstall(& $transport, $options) {
        $uninstalled = $this->_uninstallFiles($transport, $options);
        return $uninstalled;
    }

    /**
     * Install files or folders represented by and stored in this vehicle.
     *
     * @access protected
     * @param xPDOTransport &$transport A reference the transport this vehicle is stored in.
     * @param array $options Optional attributes that can be applied to vehicle install process.
     * @return boolean True if the files are installed successfully.
     */
    protected function _installFiles(& $transport, $options) {
        $installed = false;
        $copied = false;
        $vOptions = $this->get($transport, $options);
        if (isset ($vOptions['object']) && isset ($vOptions['object']['source']) && isset ($vOptions['object']['target'])) {
            $object = $vOptions['object'];
            $fileName = $object['name'];
            $fileSource = $transport->path . $object['source'];
            $fileTarget = eval ($object['target']);
            $fileTargetPath = $fileTarget . $fileName;
            $preExistingMode = xPDOTransport::PRESERVE_PREEXISTING;
            if (isset ($vOptions[xPDOTransport::PREEXISTING_MODE])) {
                $preExistingMode = (integer) $vOptions[xPDOTransport::PREEXISTING_MODE];
            }
            $cacheManager = $transport->xpdo->getCacheManager();
            if ($this->validate($transport, $object, $vOptions)) {
                if (isset ($vOptions[xPDOTransport::INSTALL_FILES]) && !$vOptions[xPDOTransport::INSTALL_FILES]) {
                    $transport->xpdo->log(xPDO::LOG_LEVEL_INFO, "Skipping installion of files from {$fileSource} to {$fileTargetPath}");
                    $installed = true;
                } elseif ($cacheManager && file_exists($fileSource) && !empty ($fileTarget)) {
                    $transport->xpdo->log(xPDO::LOG_LEVEL_INFO, "Installing files from {$fileSource} to {$fileTargetPath}");
                    $copied = array();
                    if ($preExistingMode === xPDOTransport::PRESERVE_PREEXISTING && file_exists($fileTargetPath)) {
                        $preservedArchive = $transport->path . $transport->signature . '/' . $this->payload['class'] . '/' . $this->payload['signature'] . '.preserved.zip';
                        $transport->xpdo->log(xPDO::LOG_LEVEL_INFO, "Attempting to preserve files at {$fileTargetPath} into archive {$preservedArchive}");
                        $preserved = xPDOTransport::_pack($transport->xpdo, $preservedArchive, $fileTarget, $fileName);
                    }
                    if (is_dir($fileSource)) {
                        $copied = $cacheManager->copyTree($fileSource, $fileTarget, array_merge($vOptions, array('copy_return_file_stat' => true)));
                    } elseif (is_file($fileSource)) {
                        $copied = $cacheManager->copyFile($fileSource, $fileTarget, array_merge($vOptions, array('copy_return_file_stat' => true)));
                    }
                    if (empty($copied)) {
                        $transport->xpdo->log(xPDO::LOG_LEVEL_ERROR, "Error copying files from {$fileSource} to {$fileTargetPath}");
                    } else {
                        if ($preExistingMode === xPDOTransport::PRESERVE_PREEXISTING && is_array($copied)) {
                            foreach ($copied as $copiedFile => $stat) {
                                if (isset($stat['overwritten'])) $transport->_preserved[$vOptions['guid']]['files'][$copiedFile]= $stat;
                            }
                        }
                        $installed = true;
                    }
                } else {
                    $transport->xpdo->log(xPDO::LOG_LEVEL_ERROR, "Could not install files from {$fileSource} to {$fileTarget}");
                }
                if (!$this->resolve($transport, $object, $vOptions)) {
                    $transport->xpdo->log(xPDO::LOG_LEVEL_ERROR, 'Could not resolve vehicle for object: ' . print_r($object, true));
                    if ($transport->xpdo->getDebug() === true) $transport->xpdo->log(xPDO::LOG_LEVEL_DEBUG, 'Could not resolve vehicle: ' . print_r($vOptions, true));
                }
            } else {
                $transport->xpdo->log(xPDO::LOG_LEVEL_ERROR, 'Could not validate vehicle for object: ' . print_r($object, true));
                if ($transport->xpdo->getDebug() === true) $transport->xpdo->log(xPDO::LOG_LEVEL_DEBUG, 'Could not validate vehicle: ' . print_r($vOptions, true));
            }
        }
        return $installed;
    }

    /**
     * Uninstall files or folders represented by and stored in this vehicle.
     *
     * @access protected
     * @param xPDOTransport &$transport A reference the transport this vehicle is stored in.
     * @param array $options Optional attributes that can be applied to vehicle uninstall process.
     * @return boolean True if the files are uninstalled successfully.
     */
    protected function _uninstallFiles(& $transport, $options) {
        $uninstalled = false;
        $vOptions = $this->get($transport, $options);
        if (isset ($vOptions['object']) && isset ($vOptions['object']['source']) && isset ($vOptions['object']['target'])) {
            $object = $vOptions['object'];
            $fileName = $object['name'];
            $fileSource = $transport->path . $object['source'];
            $fileTarget = eval ($object['target']);
            $preExistingMode = xPDOTransport::PRESERVE_PREEXISTING;
            if (isset ($vOptions[xPDOTransport::PREEXISTING_MODE])) {
                $preExistingMode = (integer) $vOptions[xPDOTransport::PREEXISTING_MODE];
            }
            $cacheManager = $transport->xpdo->getCacheManager();
            $path = $fileTarget . $fileName;
            $transport->xpdo->log(xPDO::LOG_LEVEL_INFO, 'Uninstalling files from xPDOFileVehicle: ' . $path);
            if ($this->validate($transport, $object, $vOptions)) {
                if (!isset ($vOptions[xPDOTransport::UNINSTALL_FILES]) || $vOptions[xPDOTransport::UNINSTALL_FILES] == true) {
                    $transport->xpdo->log(xPDO::LOG_LEVEL_INFO,'Removing files from xPDOFileVehicle: '.$path);
                    if ($cacheManager && file_exists($path)) {
                        if (is_dir($path) && $cacheManager->deleteTree($path, array_merge(array('deleteTop' => true, 'skipDirs' => false, 'extensions' => array()), $vOptions))) {
                            $uninstalled = true;
                        } elseif (is_file($path) && unlink($path)) {
                            $uninstalled = true;
                        } else {
                            $transport->xpdo->log(xPDO::LOG_LEVEL_ERROR,'Could not remove files from path: '.$path);
                        }
                    } else {
                        $transport->xpdo->log(xPDO::LOG_LEVEL_ERROR,'Could not find files to remove at path: '.$path);
                    }
                } else {
                    $transport->xpdo->log(xPDO::LOG_LEVEL_INFO,'Skipping removal of files according to vehicle attributes.');
                    $uninstalled = true;
                }
                $preservedArchive = $transport->path . $transport->signature . '/' . $this->payload['class'] . '/' . $this->payload['signature'] . '.preserved.zip';
                if ($preExistingMode === xPDOTransport::RESTORE_PREEXISTING && file_exists($preservedArchive)) {
                    $transport->xpdo->log(xPDO::LOG_LEVEL_ERROR, "Attempting to restore files to {$fileTarget} from archive {$preservedArchive}");
                    $unpackedResult = xPDOTransport::_unpack($transport->xpdo, $preservedArchive, $fileTarget);
                    if ($unpackedResult > 0) {
                        $uninstalled = true;
                    } else {
                        $transport->xpdo->log(xPDO::LOG_LEVEL_ERROR, "Error unpacking preserved files from archive {$preservedArchive}");
                    }
                }
                if (!$this->resolve($transport, $object, $vOptions)) {
                    $transport->xpdo->log(xPDO::LOG_LEVEL_ERROR, 'Could not resolve vehicle for object: ' . print_r($object, true));
                    if ($transport->xpdo->getDebug() === true) $transport->xpdo->log(xPDO::LOG_LEVEL_DEBUG, 'Could not resolve vehicle: ' . print_r($vOptions, true));
                }
            } else {
                $transport->xpdo->log(xPDO::LOG_LEVEL_ERROR, 'Could not validate vehicle for object: ' . print_r($object, true));
                if ($transport->xpdo->getDebug() === true) $transport->xpdo->log(xPDO::LOG_LEVEL_DEBUG, 'Could not validate vehicle: ' . print_r($vOptions, true));
            }
        }
        return $uninstalled;
    }

    /**
     * Adds the file definition object to the payload.
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
                $body['source'] = $transport->signature . '/' . $this->payload['class'] . '/' . $this->payload['signature'] . '/';
                $fileTarget = $transport->path . $body['source'];
                $body['target'] = $object['target'];
                $fileName = isset ($object['name']) ? $object['name'] : basename($fileSource);
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
            }
        }
        if (!empty($body)) {
            $this->payload['object'] = $body;
        }
    }
}
