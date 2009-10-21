<?php
/*
 * Copyright 2006, 2007, 2008, 2009 by Jason Coward <xpdo@opengeek.com>
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
    public $xpdo = null;
    protected $_filename = '';
    protected $_options = array();
    protected $_archive = null;
    protected $_errors = array();

    public function __construct(& $xpdo, $filename, $options = array()) {
        $this->xpdo =& $xpdo;
        $this->_filename = is_string($filename) ? $filename : '';
        $this->_options = is_array($options) ? $options : array();
        $this->_archive = new ZipArchive();
        if (!empty($this->_filename) && file_exists(dirname($this->_filename))) {
            if (is_writable(dirname($this->_filename))) {
                if ($this->getOption('create', null, false)) {
                    if ($this->_archive->open($this->_filename, ZIPARCHIVE::CREATE | ZIPARCHIVE::OVERWRITE) !== true) {
                        $this->xpdo->log(xPDO::LOG_LEVEL_ERROR, "xPDOZip: Could not create archive at {$this->_filename}");
                    }
                } else {
                    if ($this->_archive->open($this->_filename) !== true) {
                        $this->xpdo->log(xPDO::LOG_LEVEL_ERROR, "xPDOZip: Could not open archive at {$this->_filename}");
                    }
                }
            }
        }
    }

    public function pack($source, $options = array()) {
        $results = array();
        if ($this->_archive) {
            $target = $this->getOption('zip_target', $options, '');
            if (is_dir($source)) {
                if ($dh = opendir($source)) {
                    while (($file = readdir($dh)) !== false) {
                        if (is_dir($source . $file)) {
                            if (($file !== '.') && ($file !== '..')) {
                                $this->create($source . $file . '/', array_merge($options, array('zip_target' => $target . $file . '/')));
                            }
                        } elseif (is_file($source . $file)) {
                            if ($this->_archive->addFile($source . $file, $target . $file)) {
                                $results[$target . $file] = "Successfully packed {$target}{$file} from {$source}{$file}";
                            } else {
                                $results[$target . $file] = "Error packing {$target}{$file} from {$source}{$file}";
                                $this->_errors[] = $results[$target . $file];
                            }
                        }
                    }
                }
            } elseif (is_file($source)) {
                $file = basename($file);
                if ($this->_archive->addFile($source, $target . $file)) {
                    $results[$target . $file] = "Successfully packed {$target}{$file} from {$source}";
                } else {
                    $results[$target . $file] = "Error packing {$target}{$file} from {$source}";
                    $this->_errors[] = $results[$target . $file];
                }
            }
        }
        return $results;
    }

    public function unpack($target, $options = array()) {
        $results = false;
        if ($this->_archive) {
            if (is_dir($target) && is_writable($target)) {
                $results = $this->_archive->extractTo($target);
            }
        }
        return $results;
    }

    public function close() {
        if ($this->_archive) {
            $this->_archive->close();
        }
    }

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

    public function hasError() {
        return !empty($this->_errors);
    }
}