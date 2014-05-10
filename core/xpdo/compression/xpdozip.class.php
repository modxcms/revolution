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
 * A class to abstract compression support for ZIP format.
 *
 * This file contains the xPDOZip class to represent ZIP archives.
 *
 * @package xpdo
 * @subpackage compression
 */

/**
 * Represents a compressed archive in ZIP format.
 *
 * @package xpdo
 * @subpackage compression
 */
class xPDOZip {
    const CREATE = 'create';
    const OVERWRITE = 'overwrite';
    const ZIP_TARGET = 'zip_target';

    public $xpdo = null;
    protected $_file = '';
    protected $_options = array();
    protected $_archive = null;
    protected $_errors = array();

    /**
     * Construct an instance representing a specific archive.
     *
     * @param xPDO &$xpdo A reference to an xPDO instance.
     * @param string $file The name of the archive the instance will represent.
     * @param array $options An array of options for this instance.
     */
    public function __construct(xPDO &$xpdo, $file, array $options = array()) {
        $this->xpdo =& $xpdo;
        $this->_options = !empty($options) ? $options : (is_array($file) ? $file : array());
        $this->_file = is_string($file) ? $file : (isset($this->_options['file']) ? $this->_options['file'] : '');
        $this->_archive = new ZipArchive();
        if (!empty($this->_file) && file_exists(dirname($this->_file))) {
            if (file_exists($this->_file)) {
                if ($this->getOption(xPDOZip::OVERWRITE, null, false) && is_writable($this->_file)) {
                    if ($this->_archive->open($this->_file, ZIPARCHIVE::OVERWRITE) !== true) {
                        $this->xpdo->log(xPDO::LOG_LEVEL_ERROR, "xPDOZip: Error opening archive at {$this->_file} for OVERWRITE");
                    }
                } else {
                    if ($this->_archive->open($this->_file) !== true) {
                        $this->xpdo->log(xPDO::LOG_LEVEL_ERROR, "xPDOZip: Error opening archive at {$this->_file}");
                    }
                }
            } elseif ($this->getOption(xPDOZip::CREATE, null, false) && is_writable(dirname($this->_file))) {
                if ($this->_archive->open($this->_file, ZIPARCHIVE::CREATE) !== true) {
                    $this->xpdo->log(xPDO::LOG_LEVEL_ERROR, "xPDOZip: Could not create archive at {$this->_file}");
                }
            } else {
                $this->xpdo->log(xPDO::LOG_LEVEL_ERROR, "xPDOZip: The location specified is not writable: {$this->_file}");
            }
        } else {
            $this->xpdo->log(xPDO::LOG_LEVEL_ERROR, "xPDOZip: The location specified does not exist: {$this->_file}");
        }
    }

    /**
     * Pack the contents from the source into the archive.
     *
     * @todo Implement custom file filtering options
     *
     * @param string $source The path to the source file(s) to pack.
     * @param array $options An array of options for the operation.
     * @return array An array of results for the operation.
     */
    public function pack($source, array $options = array()) {
        $results = array();
        if ($this->_archive) {
            $target = $this->getOption(xPDOZip::ZIP_TARGET, $options, '');
            if (is_dir($source)) {
                if ($dh = opendir($source)) {
                    if ($source[strlen($source) - 1] !== '/') $source .= '/';
                    $targetDir = rtrim($target, '/');
                    if (!empty($targetDir)) {
                        if ($this->_archive->addEmptyDir($targetDir)) {
                            $results[$target] = "Successfully added directory {$target} from {$source}";
                        } else {
                            $results[$target] = "Error adding directory {$target} from {$source}";
                            $this->_errors[] = $results[$target];
                        }
                    }
                    while (($file = readdir($dh)) !== false) {
                        if (is_dir($source . $file)) {
                            if (($file !== '.') && ($file !== '..')) {
                                $results = $results + $this->pack($source . $file . '/', array_merge($options, array(xPDOZip::ZIP_TARGET => $target . $file . '/')));
                            }
                        } elseif (is_file($source . $file)) {
                            if ($this->_archive->addFile($source . $file, $target . $file)) {
                                $results[$target . $file] = "Successfully packed {$target}{$file} from {$source}{$file}";
                            } else {
                                $results[$target . $file] = "Error packing {$target}{$file} from {$source}{$file}";
                                $this->_errors[] = $results[$target . $file];
                            }
                        } else {
                            $results[$target . $file] = "Error packing {$target}{$file} from {$source}{$file}";
                            $this->_errors[] = $results[$target . $file];
                        }
                    }
                }
            } elseif (is_file($source)) {
                $file = basename($source);
                if ($this->_archive->addFile($source, $target . $file)) {
                    $results[$target . $file] = "Successfully packed {$target}{$file} from {$source}";
                } else {
                    $results[$target . $file] = "Error packing {$target}{$file} from {$source}";
                    $this->_errors[] = $results[$target . $file];
                }
            } else {
                $this->_errors[]= "Invalid source specified: {$source}";
            }
        }
        return $results;
    }

    /**
     * Unpack the compressed contents from the archive to the target.
     *
     * @param string $target The path of the target location to unpack the files.
     * @param array $options An array of options for the operation.
     * @return array An array of results for the operation.
     */
    public function unpack($target, $options = array()) {
        $results = false;
        if ($this->_archive) {
            if (is_dir($target) && is_writable($target) || $this->xpdo->cacheManager->writeTree($target)) {
                if ($this->_archive->extractTo($target)) {
                    for ($i = 0; $i < $this->_archive->numFiles; $i++ ){ 
                        $entry = $this->_archive->statIndex($i); 
                        $results[] = dirname($target) . DIRECTORY_SEPARATOR . $entry['name'];
                    }
                }
            } 
        }
        return $results;
    }

    /**
     * Close the archive.
     */
    public function close() {
        if ($this->_archive) {
            $this->_archive->close();
        }
    }

    /**
     * Get an option from supplied options, the xPDOZip instance options, or xpdo itself.
     *
     * @param string $key Unique identifier for the option.
     * @param array $options A set of explicit options to override those from xPDO or the
     * xPDOZip instance.
     * @param mixed $default An optional default value to return if no value is found.
     * @return mixed The value of the option.
     */
    public function getOption($key, $options = null, $default = null) {
        $option = $default;
        if (is_array($key)) {
            if (!is_array($option)) {
                $default= $option;
                $option= array();
            }
            foreach ($key as $k) {
                $option[$k]= $this->getOption($k, $options, $default);
            }
        } elseif (is_string($key) && !empty($key)) {
            if (is_array($options) && !empty($options) && array_key_exists($key, $options)) {
                $option = $options[$key];
            } elseif (is_array($this->_options) && !empty($this->_options) && array_key_exists($key, $this->_options)) {
                $option = $this->_options[$key];
            } else {
                $option = $this->xpdo->getOption($key, null, $default);
            }
        }
        return $option;
    }

    /**
     * Detect if errors occurred during any operations.
     *
     * @return boolean True if errors exist, otherwise false.
     */
    public function hasError() {
        return !empty($this->_errors);
    }

    /**
     * Return any errors from operations on the archive.
     */
    public function getErrors() {
        return $this->_errors;
    }
}