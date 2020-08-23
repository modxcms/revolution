<?php
/*
 * This file is part of the MODX Revolution package.
 *
 * Copyright (c) MODX, LLC
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace MODX\Revolution\File;


/**
 * Abstract class for handling file system resources (files or folders).
 *
 * Not to be instantiated directly - you should implement your own derivative class.
 *
 * @abstract
 *
 * @package MODX\Revolution\File
 */
abstract class modFileSystemResource
{
    /**
     * @var string The absolute path of the file system resource
     */
    protected $path;
    /**
     * @var modFileHandler A reference to a modFileHandler instance
     */
    public $fileHandler;
    /**
     * @var array An array of file system resource specific options
     */
    public $options = [];

    /**
     * Constructor for modFileSystemResource
     *
     * @param modFileHandler $fh      A reference to the modFileHandler object
     * @param string         $path    The path to the fs resource
     * @param array          $options An array of specific options
     */
    function __construct(modFileHandler &$fh, $path, array $options = [])
    {
        $this->fileHandler =& $fh;
        $this->path = $path;
        $this->options = array_merge([

        ], $options);
    }

    /**
     * Get the path of the fs resource.
     *
     * @return string The path of the fs resource
     */
    public function getPath()
    {
        return $this->path;
    }

    /**
     * Validate chmod mode.
     *
     * @param $mode
     *
     * @return bool
     */
    public function isValidMode($mode)
    {
        if (!preg_match('/^[0-7]{4}$/', $mode)) {
            return false;
        }

        return true;
    }

    /**
     * Chmods the resource to the specified mode.
     *
     * @param string $mode
     *
     * @return boolean True if successful
     */
    public function chmod($mode)
    {
        $mode = $this->parseMode($mode);

        return @chmod($this->path, $mode);
    }

    /**
     * Sets the group permission for the fs resource
     *
     * @param mixed $grp
     *
     * @return boolean True if successful
     */
    public function chgrp($grp)
    {
        if ($this->isLink() && function_exists('lchgrp')) {
            return @lchgrp($this->path, $grp);
        } else {
            return @chgrp($this->path, $grp);
        }
    }

    /**
     * Sets the owner for the fs resource
     *
     * @param mixed $owner
     *
     * @return boolean True if successful
     */
    public function chown($owner)
    {
        if ($this->isLink() && function_exists('lchown')) {
            return @lchown($this->path, $owner);
        } else {
            return @chown($this->path, $owner);
        }
    }

    /**
     * Check to see if the fs resource exists
     *
     * @return boolean True if exists
     */
    public function exists()
    {
        return file_exists($this->path);
    }

    /**
     * Check to see if the fs resource is readable
     *
     * @return boolean True if readable
     */
    public function isReadable()
    {
        return is_readable($this->path);
    }

    /**
     * Check to see if the fs resource is writable
     *
     * @return boolean True if writable
     */
    public function isWritable()
    {
        return is_writable($this->path);
    }

    /**
     * Check to see if fs resource is symlink
     *
     * @return boolean True if symlink
     */
    public function isLink()
    {
        return is_link($this->path);
    }

    /**
     * Gets the permission group for the fs resource
     *
     * @return string The group name of the fs resource
     */
    public function getGroup()
    {
        return filegroup($this->path);
    }

    /**
     * Alias for chgrp
     *
     * @see chgrp
     *
     * @param string $grp
     *
     * @return boolean
     */
    public function setGroup($grp)
    {
        return $this->chgrp($grp);
    }

    /**
     * Renames the file/folder
     *
     * @param string $newPath The new path for the fs resource
     *
     * @return boolean True if successful
     */
    public function rename($newPath)
    {
        $newPath = $this->fileHandler->sanitizePath($newPath);

        if (!$this->isWritable()) {
            return false;
        }
        if (file_exists($newPath)) {
            return false;
        }

        return @rename($this->path, $newPath);
    }

    /**
     * Alias for rename
     *
     * @param string $newPath The new path to move fs resource
     *
     * @return boolean True if successful
     */
    public function move($newPath)
    {
        return $this->rename($newPath);
    }

    /**
     * Parses a string mode into octal format
     *
     * @param string $mode The octal to parse
     *
     * @return string The new mode in decimal format
     */
    protected function parseMode($mode = '')
    {
        return octdec($mode);
    }

    /**
     * Gets the parent containing directory of this fs resource
     *
     * @param boolean $raw Whether or not to return a modDirectory or string path
     *
     * @return modDirectory|string Returns either a modDirectory object of the
     * parent directory, or the absolute path of the parent, depending on
     * whether or not $raw is set to true.
     */
    public function getParentDirectory($raw = false)
    {
        $ppath = dirname($this->path) . '/';
        $ppath = str_replace('//', '/', $ppath);
        if ($raw) {
            return $ppath;
        }

        $directory = $this->fileHandler->make($ppath, [], modDirectory::class);

        return $directory;
    }
}
