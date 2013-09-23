<?php
/*
 * Copyright 2010-2013 by MODX, LLC.
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
 * Represents a transportable package of related data and other resources.
 *
 * @package xpdo
 * @subpackage transport
 */

/**
 * Represents xPDOObject and related data in a serialized format for exchange.
 *
 * @package xpdo
 * @subpackage transport
 */
class xPDOTransport {
    /**#@+
     * Attributes of the package that can be used to control behavior.
     * @var string
     */
    const PRESERVE_KEYS = 'preserve_keys';
    const NATIVE_KEY = 'native_key';
    const UNIQUE_KEY = 'unique_key';
    const UPDATE_OBJECT = 'update_object';
    const RESOLVE_FILES = 'resolve_files';
    const RESOLVE_FILES_REMOVE = 'resolve_files_remove';
    const RESOLVE_PHP = 'resolve_php';
    const PACKAGE_ACTION = 'package_action';
    const PACKAGE_STATE = 'package_state';
    const RELATED_OBJECTS = 'related_objects';
    const RELATED_OBJECT_ATTRIBUTES = 'related_object_attributes';
    const MANIFEST_ATTRIBUTES = 'manifest-attributes';
    const MANIFEST_VEHICLES = 'manifest-vehicles';
    const MANIFEST_VERSION = 'manifest-version';
    const PREEXISTING_MODE = 'preexisting_mode';
    const INSTALL_FILES = 'install_files';
    const UNINSTALL_FILES = 'uninstall_files';
    const UNINSTALL_OBJECT = 'uninstall_object';
    const ARCHIVE_WITH = 'archive_with';
    const ABORT_INSTALL_ON_VEHICLE_FAIL = 'abort_install_on_vehicle_fail';
    const PACKAGE_NAME = 'package_name';
    const PACKAGE_VERSION = 'package_version';
    /**
     * Indicates how pre-existing objects are treated on install/uninstall.
     * @var integer
     */
    const PRESERVE_PREEXISTING = 0;
    const REMOVE_PREEXISTING = 1;
    const RESTORE_PREEXISTING = 2;
    /**
     * Indicates the physical state of the package.
     * @var integer
     */
    const STATE_UNPACKED = 0;
    const STATE_PACKED = 1;
    const STATE_INSTALLED = 2;
    /**
     * Indicates an action that can be performed on the package.
     * @var integer
     */
    const ACTION_INSTALL = 0;
    const ACTION_UPGRADE = 1;
    const ACTION_UNINSTALL = 2;
    /**#@-*/
    /**
     * Indicates which archiving tool to use for pack()'ing and unpack()'ing the transport.
     * @var integer
     */
    const ARCHIVE_WITH_DEFAULT = 0;
    const ARCHIVE_WITH_PCLZIP = 1;
    const ARCHIVE_WITH_ZIPARCHIVE = 2;
    /**
     * An {@link xPDO} reference controlling this transport instance.
     * @var xPDO
     * @access public
     */
    public $xpdo= null;
    /**
     * A unique signature to identify the package.
     * @var string
     * @access public
     */
    public $signature= null;
    /**
     * A unique name used to identify the package without the version.
     * @var string
     */
    public $name= null;
    /**
     * The package version, as a PHP-standardized version number string.
     * @var string
     */
    public $version= null;
    /**
     * Indicates the state of the xPDOTransport instance.
     * @var integer
     */
    public $state= null;
    /**
     * Stores various attributes about the transport package.
     * @var array
     */
    public $attributes= array ();
    /**
     * A map of object vehicles containing payloads of data for transport.
     * @var array
     */
    public $vehicles= array ();
    /**
     * The physical location of the transport package.
     * @var string
     */
    public $path= null;
    /**
     * The current manifest version for this transport.
     * @var string
     */
    public $manifestVersion = '1.1';
    /**
     * An map of preserved objects from an install used by uninstall.
     * @var array
     */
    public $_preserved = array();

    /**
     * Parse the name and version from a package signature.
     *
     * @static
     * @param string $signature The package signature to parse.
     * @return array An array with two elements containing the name and version respectively.
     */
    public static function parseSignature($signature) {
        $exploded = explode('-', $signature);
        $name = current($exploded);
        $version = '';
        $part = next($exploded);
        while ($part !== false) {
            $dotPos = strpos($part, '.');
            if ($dotPos > 0 && is_numeric(substr($part, 0, $dotPos))) {
                $version = $part;
                while (($part = next($exploded)) !== false) {
                    $version .= '-' . $part;
                }
                break;
            } else {
                $name .= '-' . $part;
                $part = next($exploded);
            }
        }
        return array(
            $name,
            $version
        );
    }

    /**
     * Compares two package versions by signature.
     *
     * @static
     * @param string $signature1 A package signature.
     * @param string $signature2 Another package signature to compare.
     * @return bool|int Returns -1 if the first version is lower than the second, 0 if they
     * are equal, and 1 if the second is lower if the package names in the provided
     * signatures are equal; otherwise returns false.
     */
    public static function compareSignature($signature1, $signature2) {
        $value = false;
        $parsed1 = self::parseSignature($signature1);
        $parsed2 = self::parseSignature($signature2);
        if ($parsed1[0] === $parsed2[0]) {
            $value = version_compare($parsed1[1], $parsed2[1]);
        }
        return $value;
    }

    /**
     * Prepares and returns a new xPDOTransport instance.
     *
     * @param xPDO &$xpdo The xPDO instance accessing this package.
     * @param string $signature The unique signature of the package.
     * @param string $path Valid path to the physical transport package.
     * @param array $options An optional array of attributes for constructing the instance.
     */
    public function __construct(& $xpdo, $signature, $path, array $options = array()) {
        $this->xpdo= & $xpdo;
        $this->signature= $signature;
        $this->path= $path;
        if (!empty($options) && array_key_exists(self::PACKAGE_NAME, $options) && array_key_exists(self::PACKAGE_VERSION, $options)) {
            $this->name= $options[self::PACKAGE_NAME];
            $this->version= $options[self::PACKAGE_VERSION];
        } else {
            $nameAndVersion= self::parseSignature($this->signature);
            if (count($nameAndVersion) == 2) {
                $this->name= $nameAndVersion[0];
                $this->version= $nameAndVersion[1];
            }
        }
        $xpdo->loadClass('transport.xPDOVehicle', XPDO_CORE_PATH, true, true);
    }

    /**
     * Get an {@link xPDOVehicle} instance from an unpacked transport package.
     *
     * @param string $objFile Full path to a payload file to import.  The
     * payload file, when included must return a valid {@link xPDOVehicle::$payload}.
     * @param array $options An array of options to be applied when getting the
     * object.
     * @return xPDOVehicle The vehicle represented in the file.
     */
    public function get($objFile, $options= array ()) {
        $vehicle = null;
        $objFile = $this->path . $this->signature . '/' . $objFile;
        $vehiclePackage = isset($options['vehicle_package']) ? $options['vehicle_package'] : '';
        $vehiclePackagePath = isset($options['vehicle_package_path']) ? $options['vehicle_package_path'] : '';
        $vehicleClass = isset($options['vehicle_class']) ? $options['vehicle_class'] : '';
        if (empty($vehiclePackage)) $vehiclePackage = $options['vehicle_package'] = 'transport';
        if (empty($vehicleClass)) $vehicleClass = $options['vehicle_class'] = 'xPDOObjectVehicle';
        if ($className = $this->xpdo->loadClass("{$vehiclePackage}.{$vehicleClass}", $vehiclePackagePath, true, true)) {
            $vehicle = new $className();
        if (file_exists($objFile)) {
                $payload = include ($objFile);
                if ($payload) {
                    $vehicle->payload = $payload;
                }
            }
        } else {
            $this->xpdo->log(xPDO::LOG_LEVEL_ERROR, "The specified xPDOVehicle class ({$vehiclePackage}.{$vehicleClass}) could not be loaded.");
        }
        return $vehicle;
    }

    /**
     * Install vehicles in the package into the sponsor {@link xPDO} instance.
     *
     * @param array $options Install options to be applied to the process.
     * @return boolean true if the vehicles were successfully installed.
     */
    public function install($options= array ()) {
        $installed= false;
        $saved = array();
        $this->_preserved = array();
        if (!is_array($options)) {
            $options= array(xPDOTransport::PACKAGE_ACTION => xPDOTransport::ACTION_INSTALL);
        } elseif (!isset($options[xPDOTransport::PACKAGE_ACTION])) {
            $options[xPDOTransport::PACKAGE_ACTION]= xPDOTransport::ACTION_INSTALL;
        }
        if (!empty ($this->vehicles)) {
            foreach ($this->vehicles as $vIndex => $vehicleMeta) {
                $vOptions = array_merge($options, $vehicleMeta);
                if ($vehicle = $this->get($vehicleMeta['filename'], $vOptions)) {
                    $vehicleInstalled = $vehicle->install($this, $vOptions);
                    if (!$vehicleInstalled && isset($vehicle->payload[xPDOTransport::ABORT_INSTALL_ON_VEHICLE_FAIL]) && !empty($vehicle->payload[xPDOTransport::ABORT_INSTALL_ON_VEHICLE_FAIL])) {
                        $this->xpdo->log(xPDO::LOG_LEVEL_ERROR, "Vehicle {$vehicle->payload['guid']} in transport {$this->signature} failed to install and indicated the process should be aborted.");
                        return false;
                    } else {
                        $saved[$vehicle->payload['guid']] = $vehicleInstalled;
                    }
                }
            }
            $this->writePreserved();
            if (!empty($saved)) {
                $installed = true;
            }
        } else {
            $this->xpdo->log(xPDO::LOG_LEVEL_WARN, 'No vehicles are defined in the transport package (' . $this->signature . ') manifest for installation');
        }
        return $installed;
    }

    /**
     * Uninstall vehicles in the package from the sponsor {@link xPDO} instance.
     *
     * @param array $options Uninstall options to be applied to the process.
     * @return boolean true if the vehicles were successfully uninstalled.
     */
    public function uninstall($options = array ()) {
        $processed = array();
        if (!is_array($options)) {
            $options= array(xPDOTransport::PACKAGE_ACTION => xPDOTransport::ACTION_UNINSTALL);
        } elseif (!isset($options[xPDOTransport::PACKAGE_ACTION])) {
            $options[xPDOTransport::PACKAGE_ACTION]= xPDOTransport::ACTION_UNINSTALL;
                        }
        if (!empty ($this->vehicles)) {
            $this->_preserved = $this->loadPreserved();
            $vehicleArray = array_reverse($this->vehicles, true);
            foreach ($vehicleArray as $vIndex => $vehicleMeta) {
                $vOptions = array_merge($options, $vehicleMeta);
                if ($this->xpdo->getDebug() === true) {
                    $this->xpdo->log(xPDO::LOG_LEVEL_DEBUG, "Removing Vehicle: " . print_r($vOptions, true));
                    }
                if ($vehicle = $this->get($vehicleMeta['filename'], $vOptions)) {
                    $processed[$vehicleMeta['guid']] = $vehicle->uninstall($this, $vOptions);
                } else {
                    $this->xpdo->log(xPDO::LOG_LEVEL_ERROR, 'Could not load vehicle: ' . print_r($vOptions, true));
                }
            }
        } else {
            $this->xpdo->log(xPDO::LOG_LEVEL_WARN, 'No vehicles are defined in the transport package (' . $this->signature . ') for removal');
        }
        $uninstalled = (array_search(false, $processed, true) === false);
        return $uninstalled;
    }

    /**
     * Wrap artifact with an {@link xPDOVehicle} and register in the transport.
     *
     * @param mixed $artifact An artifact to load into the transport.
     * @param array $attributes A set of attributes related to the artifact; these
     * can be anything from rules describing how to pack or unpack the artifact,
     * or any other data that might be useful when dealing with a transportable
     * artifact.
     * @return bool TRUE if the artifact is successfully registered in the transport.
     */
    public function put($artifact, $attributes = array ()) {
        $added= false;
        if (!empty($artifact)) {
            $vehiclePackage = isset($attributes['vehicle_package']) ? $attributes['vehicle_package'] : '';
            $vehiclePackagePath = isset($attributes['vehicle_package_path']) ? $attributes['vehicle_package_path'] : '';
            $vehicleClass = isset($attributes['vehicle_class']) ? $attributes['vehicle_class'] : '';
            if (empty($vehiclePackage)) $vehiclePackage = $attributes['vehicle_package'] = 'transport';
            if (empty($vehicleClass)) $vehicleClass = $attributes['vehicle_class'] = 'xPDOObjectVehicle';
            if ($className = $this->xpdo->loadClass("{$vehiclePackage}.{$vehicleClass}", $vehiclePackagePath, true, true)) {
                /** @var xPDOVehicle $vehicle */
                $vehicle = new $className();
                $vehicle->put($this, $artifact, $attributes);
                if ($added= $vehicle->store($this)) {
                    $this->registerVehicle($vehicle);
                }
            } else {
                $this->xpdo->log(xPDO::LOG_LEVEL_ERROR, "The specified xPDOVehicle class ({$vehiclePackage}.{$vehicleClass}) could not be loaded.");
            }
        }
        return $added;
    }

    /**
     * Pack the {@link xPDOTransport} instance in preparation for distribution.
     *
     * @return boolean Indicates if the transport was packed successfully.
     */
    public function pack() {
        if (empty($this->vehicles)) {
            $this->xpdo->log(xPDO::LOG_LEVEL_ERROR, 'Attempt to pack a transport package with no vehicles.');
            return false;
        }
        $this->writeManifest();
        $fileName = $this->path . $this->signature . '.transport.zip';
        return xPDOTransport::_pack($this->xpdo, $fileName, $this->path, $this->signature);
    }

    /**
     * Pack the resources from path relative to source into an archive with filename.
     *
     * @uses compression.xPDOZip OR compression.PclZip
     * @todo Refactor this to be implemented in a service class external to xPDOTransport.
     *
     * @param xPDO &$xpdo A reference to an xPDO instance.
     * @param string $filename A valid zip archive filename.
     * @param string $path An absolute file system path location of the resources to pack.
     * @param string $source A relative portion of path to include in the archive.
     * @return boolean True if packed successfully.
     */
    public static function _pack(& $xpdo, $filename, $path, $source) {
        $packed = false;
        $packResults = false;
        $errors = array();
        if ($xpdo->getOption(xPDOTransport::ARCHIVE_WITH, null, 0) != xPDOTransport::ARCHIVE_WITH_PCLZIP && class_exists('ZipArchive', true) && $xpdo->loadClass('compression.xPDOZip', XPDO_CORE_PATH, true, true)) {
            if ($xpdo->getDebug() === true) {
                $xpdo->log(xPDO::LOG_LEVEL_DEBUG, "Using xPDOZip / native ZipArchive", null, __METHOD__, __FILE__, __LINE__);
            }
            $archive = new xPDOZip($xpdo, $filename, array(xPDOZip::CREATE => true, xPDOZip::OVERWRITE => true));
            if ($archive) {
                $packResults = $archive->pack("{$path}{$source}", array(xPDOZip::ZIP_TARGET => "{$source}/"));
                $archive->close();
                if (!$archive->hasError() && !empty($packResults)) {
                    $packed = true;
                } else {
                    $errors = $archive->getErrors();
                }
            }
        } elseif (class_exists('PclZip') || include(XPDO_CORE_PATH . 'compression/pclzip.lib.php')) {
            $archive = new PclZip($filename);
            if ($archive) {
                $packResults = $archive->create("{$path}{$source}", PCLZIP_OPT_REMOVE_PATH, "{$path}");
                if ($packResults) {
                    $packed = true;
                } else {
                    $errors = $archive->errorInfo($xpdo->getDebug() === true);
                }
            }
        }
        if (!$packed) {
            $xpdo->log(xPDO::LOG_LEVEL_ERROR, "Error packing {$path}{$source} to {$filename}: " . print_r($errors, true));
        }
        if ($xpdo->getDebug() === true) {
            $xpdo->log(xPDO::LOG_LEVEL_DEBUG, "Results of packing {$path}{$source} to {$filename}: " . print_r($packResults, true), null, __METHOD__, __FILE__, __LINE__);
        }
        return $packed;
    }

    /**
     * Write the package manifest file.
     *
     * @return boolean Indicates if the manifest was successfully written.
     */
    public function writeManifest() {
        $written = false;
        if (!empty ($this->vehicles)) {
            if (!empty($this->attributes['setup-options']) && is_array($this->attributes['setup-options'])) {
                $cacheManager = $this->xpdo->getCacheManager();
                $cacheManager->copyFile($this->attributes['setup-options']['source'],$this->path . $this->signature . '/setup-options.php');

                $this->attributes['setup-options'] = $this->signature . '/setup-options.php';
            }
            $manifest = array(
                xPDOTransport::MANIFEST_VERSION => $this->manifestVersion,
                xPDOTransport::MANIFEST_ATTRIBUTES => $this->attributes,
                xPDOTransport::MANIFEST_VEHICLES => $this->vehicles
            );
            $content = var_export($manifest, true);
            $cacheManager = $this->xpdo->getCacheManager();
            if ($content && $cacheManager) {
                $fileName = $this->path . $this->signature . '/manifest.php';
                $content = "<?php return {$content};";
                if (!($written = $cacheManager->writeFile($fileName, $content))) {
                    $this->xpdo->log(xPDO::LOG_LEVEL_ERROR, 'Error writing manifest to ' . $fileName);
                }
            }
        }
        return $written;
    }

    /**
     * Write objects preserved during install() to file for use by uninstall().
     *
     * @return boolean Indicates if the preserved file was successfully written.
     */
    public function writePreserved() {
        $written = false;
        if (!empty($this->_preserved)) {
            $content = var_export($this->_preserved, true);
            $cacheManager = $this->xpdo->getCacheManager();
            if ($content && $cacheManager) {
                $fileName = $this->path . $this->signature . '/preserved.php';
                $content = "<?php return {$content};";
                if (!($written = $cacheManager->writeFile($fileName, $content))) {
                    $this->xpdo->log(xPDO::LOG_LEVEL_ERROR, 'Error writing preserved objects to ' . $fileName);
                }
            }
        }
        return $written;
    }

    /**
     * Load preserved objects from the previous install().
     *
     * @return array An array of preserved objects, or an empty array.
     */
    public function loadPreserved() {
        $preserved = array();
        $fileName = $this->path . $this->signature . '/preserved.php';
        if (file_exists($fileName)) {
            $content = include($fileName);
            if (is_array($content)) {
                $preserved = $content;
            } else {
                $this->xpdo->log(xPDO::LOG_LEVEL_ERROR, 'Error loading preserved objects from ' . $fileName);
            }
        }
        return $preserved;
    }

    /**
     * Register an xPDOVehicle with this transport instance.
     *
     * @param xPDOVehicle &$vehicle A reference to the vehicle being registered.
     */
    public function registerVehicle(& $vehicle) {
        $this->vehicles[] = $vehicle->register($this);
    }

    /**
     * Get an attribute of the package manifest.
     *
     * @param string $key The key of the attribute to retrieve.
     * @return mixed The value of the attribute or null if it is not set.
     */
    public function getAttribute($key) {
        $value = null;
        if (array_key_exists($key, $this->attributes)) $value = $this->attributes[$key];
        return $value;
    }

    /**
     * Set an attribute of the package manifest.
     *
     * @param string $key The key identifying the attribute to set.
     * @param mixed $value The value to set the attribute to.
     */
    public function setAttribute($key, $value) {
        $this->attributes[$key]= $value;
    }

    /**
     * Get an existing {@link xPDOTransport} instance.
     */
    public static function retrieve(& $xpdo, $source, $target, $state= xPDOTransport::STATE_PACKED) {
        $instance= null;
        $signature = basename($source, '.transport.zip');
        if (file_exists($source)) {
            if (is_writable($target)) {
                $manifest = xPDOTransport :: unpack($xpdo, $source, $target, $state);
                if ($manifest) {
                    $instance = new xPDOTransport($xpdo, $signature, $target);
                    if (!$instance) {
                        $xpdo->log(xPDO::LOG_LEVEL_ERROR, "Could not instantiate a valid xPDOTransport object from the package {$source} to {$target}. SIG: {$signature} MANIFEST: " . print_r($manifest, 1));
                    }
                    $manifestVersion = xPDOTransport :: manifestVersion($manifest);
                    switch ($manifestVersion) {
                        case '0.1':
                            $instance->vehicles = xPDOTransport :: _convertManifestVer1_1(xPDOTransport :: _convertManifestVer1_0($manifest));
                        case '0.2':
                            $instance->vehicles = xPDOTransport :: _convertManifestVer1_1(xPDOTransport :: _convertManifestVer1_0($manifest[xPDOTransport::MANIFEST_VEHICLES]));
                            $instance->attributes = $manifest[xPDOTransport::MANIFEST_ATTRIBUTES];
                            break;
                        case '1.0':
                            $instance->vehicles = xPDOTransport :: _convertManifestVer1_1($manifest[xPDOTransport::MANIFEST_VEHICLES]);
                            $instance->attributes = $manifest[xPDOTransport::MANIFEST_ATTRIBUTES];
                            break;
                        default:
                            $instance->vehicles = $manifest[xPDOTransport::MANIFEST_VEHICLES];
                            $instance->attributes = $manifest[xPDOTransport::MANIFEST_ATTRIBUTES];
                            break;
                    }
                } else {
                    $xpdo->log(xPDO::LOG_LEVEL_ERROR, "Could not unpack package {$source} to {$target}. SIG: {$signature}");
                }
            } else {
                $xpdo->log(xPDO::LOG_LEVEL_ERROR, "Could not unpack package: {$target} is not writable. SIG: {$signature}");
            }
        } else {
            $xpdo->log(xPDO::LOG_LEVEL_ERROR, "Package {$source} not found. SIG: {$signature}");
        }
        return $instance;
    }

    /**
     * Store the package to a specified resource location.
     *
     * @todo Implement ability to store a package to a specified location, supporting various
     * transport methods.
     * @param mixed $location The location to store the package.
     * @return bool TRUE if the package is stored successfully.
     */
    public function store($location) {
        $stored= false;
        if ($this->state === xPDOTransport::STATE_PACKED) {}
        return $stored;
    }

    /**
     * Unpack the package to prepare for installation and return a manifest.
     *
     * @param xPDO &$xpdo A reference to an xPDO instance.
     * @param string $from Filename of the archive containing the transport package.
     * @param string $to The root path where the contents of the archive should be extracted.  This
     * path must be writable by the user executing the PHP process on the server.
     * @param integer $state The current state of the package, i.e. packed or unpacked.
     * @return array The manifest which is included after successful extraction.
     */
    public static function unpack(& $xpdo, $from, $to, $state = xPDOTransport::STATE_PACKED) {
        $manifest= null;
        if ($state !== xPDOTransport::STATE_UNPACKED) {
            $resources = xPDOTransport::_unpack($xpdo, $from, $to);
        } else {
            $resources = true;
        }
        if ($resources) {
            $manifestFilename = $to . basename($from, '.transport.zip') . '/manifest.php';
            if (file_exists($manifestFilename)) {
                $manifest= @include ($manifestFilename);
            } else {
                $xpdo->log(xPDO::LOG_LEVEL_ERROR, "Could not find package manifest at {$manifestFilename}");
            }
        }
        return $manifest;
    }

    /**
     * Unpack a zip archive to a specified location.
     *
     * @uses compression.xPDOZip OR compression.PclZip
     * @todo Refactor this to be implemented in a service class external to xPDOTransport.
     *
     * @param xPDO &$xpdo A reference to an xPDO instance.
     * @param string $from An absolute file system location to a valid zip archive.
     * @param string $to A file system location to extract the contents of the archive to.
     * @return array|boolean An array of unpacked resources or false on failure.
     */
    public static function _unpack(& $xpdo, $from, $to) {
        $resources = false;
        if ($xpdo->getOption(xPDOTransport::ARCHIVE_WITH, null, 0) != xPDOTransport::ARCHIVE_WITH_PCLZIP && class_exists('ZipArchive', true) && $xpdo->loadClass('compression.xPDOZip', XPDO_CORE_PATH, true, true)) {
            $archive = new xPDOZip($xpdo, $from);
            if ($archive) {
                $resources = $archive->unpack($to);
                $archive->close();
            }
        } elseif (class_exists('PclZip') || include(XPDO_CORE_PATH . 'compression/pclzip.lib.php')) {
            $archive = new PclZip($from);
            if ($archive) {
                $resources = $archive->extract(PCLZIP_OPT_PATH, $to);
            }
        }
        return $resources;
    }

    /**
     * Returns the structure version of the given manifest array.
     *
     * @static
     * @param array $manifest A valid xPDOTransport manifest array.
     * @return string Version string of the manifest structure.
     */
    public static function manifestVersion($manifest) {
        $version = false;
        if (is_array($manifest)) {
            if (isset($manifest[xPDOTransport::MANIFEST_VERSION])) {
                $version = $manifest[xPDOTransport::MANIFEST_VERSION];
            }
            elseif (isset($manifest[xPDOTransport::MANIFEST_VEHICLES])) {
                $version = '0.2';
            }
            else {
                $version = '0.1';
            }
        }
        return $version;
    }

    /**
     * Converts older manifest vehicles to 1.0 format.
     *
     * @static
     * @access private
     * @param array $manifestVehicles A structure representing vehicles from a pre-1.0 manifest
     * format.
     * @return array Vehicle definition structures converted to 1.0 format.
     */
    protected static function _convertManifestVer1_0($manifestVehicles) {
        $manifest = array();
        foreach ($manifestVehicles as $vClass => $vehicles) {
            foreach ($vehicles as $vKey => $vehicle) {
                $entry = array(
                    'class' => $vClass,
                    'native_key' => $vehicle['native_key'],
                    'filename' => $vehicle['filename'],
                );
                if (isset($vehicle['namespace'])) {
                    $entry['namespace'] = $vehicle['namespace'];
                }
                $manifest[] = $entry;
            }
        }
        return $manifest;
    }

    /**
     * Converts 1.0 manifest vehicles to 1.1 format.
     *
     * @static
     * @access private
     * @param array $vehicles A structure representing vehicles from a pre-1.1 manifest format.
     * @return array Vehicle definition structures converted to 1.1 format.
     */
    protected static function _convertManifestVer1_1($vehicles) {
        $manifest = array();
        foreach ($vehicles as $vKey => $vehicle) {
            $entry = $vehicle;
            if (!isset($vehicle['vehicle_class'])) {
                $entry['vehicle_class'] = 'xPDOObjectVehicle';
            }
            if (!isset($vehicle['vehicle_package'])) {
                $entry['vehicle_package'] = 'transport';
            }
            $manifest[] = $entry;
        }
        return $manifest;
    }
}
