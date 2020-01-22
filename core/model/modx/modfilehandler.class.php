<?php
/*
 * This file is part of MODX Revolution.
 *
 * Copyright (c) MODX, LLC. All Rights Reserved.
 *
 * For complete copyright and license information, see the COPYRIGHT and LICENSE
 * files found in the top-level directory of this distribution.
 */

/**
 * Assists with directory/file manipulation
 *
 * @package modx
 */
class modFileHandler {
    /**
     * An array of configuration properties for the class
     * @var array $config
     */
    public $config = array();
    /**
     * The current context in which this File Manager instance should operate
     * @var modContext|null $context
     */
    public $context = null;

    /**
     * The constructor for the modFileHandler class
     *
     * @param modX &$modx A reference to the modX object.
     * @param array $config An array of options.
     */
    function __construct(modX &$modx, array $config = array()) {
        $this->modx =& $modx;
        $this->config = array_merge($this->config, $this->modx->_userConfig, $config);
        if (!isset($this->config['context'])) {
            $this->config['context'] = $this->modx->context->get('key');
        }
        $this->context = $this->modx->getContext($this->config['context']);
    }

    /**
     * Dynamically creates a modDirectory or modFile object.
     *
     * The object is created based on the type of resource provided.
     *
     * @param string $path The absolute path to the filesystem resource.
     * @param array $options Optional. An array of options for the object.
     * @param string $overrideClass Optional. If provided, will force creation
     * of the object as the specified class.
     * @return modFile|modDirectory The appropriate modFile/modDirectory object
     */
    public function make($path, array $options = array(), $overrideClass = '') {
        $path = $this->sanitizePath($path);

        if (!empty($overrideClass)) {
            $class = $overrideClass;
        } else {
            if (is_dir($path)) {
                $path = $this->postfixSlash($path);
                $class = 'modDirectory';
            } else {
                $class = 'modFile';
            }
        }

        return new $class($this, $path, $options);
    }

    /**
     * Get the modX base path for the user.
     *
     * @return string The base path
     */
    public function getBasePath() {
        $basePath = $this->context->getOption('filemanager_path', '', $this->config);
        /* expand placeholders */
        $basePath = str_replace(array(
            '{base_path}',
            '{core_path}',
            '{assets_path}',
        ), array(
            $this->context->getOption('base_path', MODX_BASE_PATH, $this->config),
            $this->context->getOption('core_path', MODX_CORE_PATH, $this->config),
            $this->context->getOption('assets_path', MODX_ASSETS_PATH, $this->config),
        ), $basePath);
        return !empty($basePath) ? $this->postfixSlash($basePath) : $basePath;
    }

    /**
     * Get base URL of file manager
     *
     * @return string The base URL
     */
    public function getBaseUrl() {
        $baseUrl = $this->context->getOption('filemanager_url', $this->context->getOption('rb_base_url', MODX_BASE_URL, $this->config), $this->config);

        /* expand placeholders */
        $baseUrl = str_replace(array(
            '{base_url}',
            '{core_url}',
            '{assets_url}',
        ), array(
            $this->context->getOption('base_url', MODX_BASE_PATH, $this->config),
            $this->context->getOption('core_url', MODX_CORE_PATH, $this->config),
            $this->context->getOption('assets_url', MODX_ASSETS_PATH, $this->config),
        ), $baseUrl);
        return !empty($baseUrl) ? $this->postfixSlash($baseUrl) : $baseUrl;
    }

    /**
     * Sanitize the specified path
     *
     * @param string $path The path to clean
     * @return string The sanitized path
     */
    public function sanitizePath($path) {
        return preg_replace(array("/\.*[\/|\\\]/i", "/[\/|\\\]+/i"), array('/', '/'), $path);
    }

    /**
     * Ensures that the passed path has a / at the end
     *
     * @param string $path
     * @return string The postfixed path
     */
    public function postfixSlash($path) {
        $len = strlen($path);
        if (substr($path, $len - 1, $len) != '/') {
            $path .= '/';
        }
        return $path;
    }

    /**
     * Gets the directory path for a given file
     *
     * @param string $fileName The path for a file
     * @return string The directory path of the given file
     */
    public function getDirectoryFromFile($fileName) {
        $dir = dirname($fileName);
        return $this->postfixSlash($dir);
    }

    /**
     * Tells if a file is a binary file or not.
     *
     * @param string $file
     * @return boolean True if a binary file.
     */
    public function isBinary($file) {
        if (!file_exists($file) || !is_file($file)) {
            return false;
        }

        if (filesize($file) > 0 && class_exists('\finfo')) {
            $finfo = new \finfo(FILEINFO_MIME);

            return substr($finfo->file($file), 0, 4) !== 'text';
        }

        $fh = @fopen($file, 'r');
        $blk = @fread($fh, 512);
        @fclose($fh);
        @clearstatcache();
        return (substr_count($blk, "^ -~" /*. "^\r\n"*/) / 512 > 0.3) || (substr_count($blk, "\x00") > 0);
    }
}

/**
 * Abstract class for handling file system resources (files or folders).
 *
 * Not to be instantiated directly - you should implement your own derivative class.
 *
 * @package modx
 */
abstract class modFileSystemResource {
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
    public $options = array();

    /**
     * Constructor for modFileSystemResource
     *
     * @param modFileHandler $fh A reference to the modFileHandler object
     * @param string $path The path to the fs resource
     * @param array $options An array of specific options
     */
    function __construct(modFileHandler &$fh, $path, array $options = array()) {
        $this->fileHandler =& $fh;
        $this->path = $path;
        $this->options = array_merge(array(

        ), $options);
    }

    /**
     * Get the path of the fs resource.
     * @return string The path of the fs resource
     */
    public function getPath() {
        return $this->path;
    }

    /**
     * Validate chmod mode.
     *
     * @param $mode
     * @return bool
     */
    public function isValidMode($mode) {
        if (!preg_match('/^[0-7]{4}$/', $mode)) {
            return false;
        }

        return true;
    }

    /**
     * Chmods the resource to the specified mode.
     *
     * @param string $mode
     * @return boolean True if successful
     */
    public function chmod($mode) {
        $mode = $this->parseMode($mode);

        return @chmod($this->path, $mode);
    }

    /**
     * Sets the group permission for the fs resource
     * @param mixed $grp
     * @return boolean True if successful
     */
    public function chgrp($grp) {
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
     * @return boolean True if successful
     */
    public function chown($owner) {
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
    public function exists() {
        return file_exists($this->path);
    }

    /**
     * Check to see if the fs resource is readable
     *
     * @return boolean True if readable
     */
    public function isReadable() {
        return is_readable($this->path);
    }

    /**
     * Check to see if the fs resource is writable
     *
     * @return boolean True if writable
     */
    public function isWritable() {
        return is_writable($this->path);
    }

    /**
     * Check to see if fs resource is symlink
     *
     * @return boolean True if symlink
     */
    public function isLink() {
        return is_link($this->path);
    }

    /**
     * Gets the permission group for the fs resource
     *
     * @return string The group name of the fs resource
     */
    public function getGroup() {
        return filegroup($this->path);
    }

    /**
     * Alias for chgrp
     *
     * @see chgrp
     * @param string $grp
     * @return boolean
     */
    public function setGroup($grp) {
        return $this->chgrp($grp);
    }

    /**
     * Renames the file/folder
     *
     * @param string $newPath The new path for the fs resource
     * @return boolean True if successful
     */
    public function rename($newPath) {
        $newPath = $this->fileHandler->sanitizePath($newPath);

        if (!$this->isWritable()) return false;
        if (file_exists($newPath)) return false;

        return @rename($this->path, $newPath);
    }

    /**
     * Alias for rename
     *
     * @param string $newPath The new path to move fs resource
     * @return boolean True if successful
     */
    public function move($newPath) {
        return $this->rename($newPath);
    }

    /**
     * Parses a string mode into octal format
     *
     * @param string $mode The octal to parse
     * @return string The new mode in decimal format
     */
    protected function parseMode($mode = '') {
        return octdec($mode);
    }

    /**
     * Gets the parent containing directory of this fs resource
     *
     * @param boolean $raw Whether or not to return a modDirectory or string path
     * @return modDirectory|string Returns either a modDirectory object of the
     * parent directory, or the absolute path of the parent, depending on
     * whether or not $raw is set to true.
     */
    public function getParentDirectory($raw = false) {
        $ppath = dirname($this->path) . '/';
        $ppath = str_replace('//', '/', $ppath);
        if ($raw) return $ppath;

        $directory = $this->fileHandler->make($ppath,array(),'modDirectory');
        return $directory;
    }
}

/**
 * File implementation of modFileSystemResource
 *
 * @package modx
 */
class modFile extends modFileSystemResource {
    /**
     * @var string The content of the resource
     */
    protected $content = '';

    /**
     * @see modFileSystemResource.parseMode
     * @param string $mode
     * @return string
     */
    protected function parseMode($mode = '') {
        if (empty($mode)) {
            $mode = $this->fileHandler->context->getOption('new_file_permissions', '0644', $this->fileHandler->config);
        }
        return parent::parseMode($mode);
    }

    /**
     * Actually create the file on the file system
     *
     * @param string $content The content of the file to write
     * @param string $mode The perms to write with the file
     * @return boolean True if successful
     */
    public function create($content = '', $mode = 'w+') {
        if ($this->exists()) return false;
        $result = false;

        $fp = @fopen($this->path, 'w+');
        if ($fp) {
            @fwrite($fp, $content);
            @fclose($fp);

            $result = file_exists($this->path);
            if ($result) {
                $mode = $this->parseMode();
                if (empty($mode)) {
                    $mode = octdec($this->fileHandler->modx->getOption('new_file_permissions', null, '0644'));
                }
                @chmod($this->path, $mode);
            }
        }
        return $result;
    }

    /**
     * Temporarily set (but not save) the content of the file
     * @param string $content The content
     */
    public function setContent($content) {
        $this->content = $content;
    }

    /**
     * Get the contents of the file
     *
     * @return string The contents of the file
     */
    public function getContents() {
        $content = @file_get_contents($this->path);

        if ($content === false) {
            $content = $this->content;
        }

        return $content;
    }

    /**
     * Alias for save()
     *
     * @see modDirectory::write
     * @param string $content
     * @param string $mode
     * @return boolean
     */
    public function write($content = null, $mode = 'w+') {
        return $this->save($content, $mode);
    }

    /**
     * Writes the content of the modFile object to the actual file.
     *
     * @param string $content Optional. If not using setContent, this will set
     * the content to write.
     * @param string $mode The mode in which to write
     * @return boolean The result of the fwrite
     */
    public function save($content = null, $mode = 'w+') {
        if ($content !== null) $this->content = $content;
        $result = false;

        $fp = @fopen($this->path, $mode);
        if ($fp) {
            $result = @fwrite($fp, $this->content);
            @fclose($fp);
        }

        return $result;
    }

    /**
     * Unpack a zip archive to a specified location.
     *
     * @uses compression.xPDOZip OR compression.PclZip
     *
     * @param string $this->getPath() An absolute file system location to a valid zip archive.
     * @param string $to A file system location to extract the contents of the archive to.
     * @param array $options an array of optional options, primarily for the xPDOZip class
     * @return array|string|boolean An array of unpacked files, a string in case of cli functions or false on failure.
     */
    public function unpack($to = '', $options = array()) {

        $results = false;

        /** @var xPDOZip $archive */
        $archive = $this->fileHandler->modx->getService('archive', 'compression.xPDOZip', XPDO_CORE_PATH, $this->path);
        if ($archive) {
            if (isset($options['check_filetype']) && $options['check_filetype'] == true) {
                $options[xPDOZip::ALLOWED_EXTENSIONS] = $this->getAllowedExtensions();
            }
            $results = $archive->unpack($to, $options);
        }

        return $results;
    }

    /**
     * Gets the size of the file
     *
     * @return int The size of the file, in bytes
     */
    public function getSize() {
        $size = @filesize($this->path);

        if ($size === false) {
            if ( function_exists('mb_strlen') ) {
                $size = mb_strlen($this->content, '8bit');
            } else {
                $size = strlen($this->content);
            }
        }

        return $size;
    }

    /**
     * Gets the last accessed time of the file
     *
     * @param string $timeFormat The format, in strftime format, of the time
     * @return string The formatted time
     */
    public function getLastAccessed($timeFormat = '%b %d, %Y %I:%M:%S %p') {
        return strftime($timeFormat, fileatime($this->path));
    }

    /**
     * Gets the last modified time of the file
     *
     * @param string $timeFormat The format, in strftime format, of the time
     * @return string The formatted time
     */
    public function getLastModified($timeFormat = '%b %d, %Y %I:%M:%S %p') {
        return strftime($timeFormat, filemtime($this->path));
    }

    /**
     * Gets the file extension of the file
     *
     * @return string The file extension of the file
     */
    public function getExtension() {
        return pathinfo($this->path, PATHINFO_EXTENSION);
    }

    /**
     * Gets the basename, or only the filename without the path, of the file
     *
     * @return string The basename of the file
     */
    public function getBaseName() {
        return ltrim(strrchr($this->path, '/'), '/');
    }

    /**
     * Sends the file as a download
     *
     * @param array $options Optional configuration options like mimetype and filename
     *
     * @noreturn downloadable file
     */
    public function download($options = array()) {
        $options = array_merge(array(
            'mimetype' => 'application/octet-stream',
            'filename' => '"' . $this->getBasename() . '"',
        ), $options);

        $output = $this->getContents();

        header('Content-type: ' . $options['mimetype']);
        header('Content-Disposition: attachment; filename=' . $options['filename']);
        header('Content-Length: ' . $this->getSize());

        echo $output;
        die();
    }

    /**
     * Deletes the file from the filesystem
     *
     * @return boolean True if successful
     */
    public function remove() {
        if (!$this->exists()) return false;
        return @unlink($this->path);
    }

    /**
     * Get allowed extensions
     * @TODO use this for an upload check too
     *
     * @return mixed
     */
    public function getAllowedExtensions() {
        if (!$this->fileHandler->modx->getOption('allowedExtensions')) {
            $allowedFiles = $this->fileHandler->modx->getOption('upload_files') ? explode(',', $this->fileHandler->modx->getOption('upload_files')) : array();
            $allowedImages = $this->fileHandler->modx->getOption('upload_images') ? explode(',', $this->fileHandler->modx->getOption('upload_images')) : array();
            $allowedMedia = $this->fileHandler->modx->getOption('upload_media') ? explode(',', $this->fileHandler->modx->getOption('upload_media')) : array();
            $allowedFlash = $this->fileHandler->modx->getOption('upload_flash') ? explode(',', $this->fileHandler->modx->getOption('upload_flash')) : array();
            $allowedExtensions = array_unique(array_merge($allowedFiles, $allowedImages, $allowedMedia, $allowedFlash));
            $this->fileHandler->modx->setOption('allowedExtensions', $allowedExtensions);
        }
        return $this->fileHandler->modx->getOption('allowedExtensions');
    }
}

/**
 * Representation of a directory
 *
 * @package modx
 */
class modDirectory extends modFileSystemResource {
    /**
     * Actually creates the new directory on the file system.
     *
     * @param string $mode Optional. The permissions of the new directory.
     * @return boolean True if successful
     */
    public function create($mode = '') {
        $mode = $this->parseMode($mode);
        if (empty($mode)) {
            $mode = octdec($this->fileHandler->modx->getOption('new_folder_permissions',null,'0775'));
        }
        if ($this->exists()) return false;

        return $this->fileHandler->modx->cacheManager->writeTree($this->path,array(
            'new_folder_permissions' => $mode,
        ));
    }

    /**
     * @see modFileSystemResource::parseMode
     *
     * @param string $mode
     * @return boolean
     */
    protected function parseMode($mode = '') {
        if (empty($mode)) {
            $mode = $this->fileHandler->context->getOption('new_folder_permissions', '0755', $this->fileHandler->config);
        }
        return parent::parseMode($mode);
    }

    /**
     * Removes the directory from the file system, recursively removing
     * subdirectories and files.
     *
     * @param array $options Options for removal.
     * @return boolean True if successful
     */
    public function remove($options = array()) {
        if ($this->path == '/') return false;

        $options = array_merge(array(
            'deleteTop' => true,
            'skipDirs' => false,
            'extensions' => array(),
        ), $options);

        $this->fileHandler->modx->getCacheManager();
        return $this->fileHandler->modx->cacheManager->deleteTree($this->path, $options);
    }

    /**
     * Iterates over a modDirectory object and returns an array of all containing files and optionally directories,
     * can run recursive, filter by file extension(s) or filenames and sort the resulting list with the specified sort options
     * an anonymous callback function can be passed to modify the output on the fly, by default an array of paths is returned
     *
     * @param array $options Options for iterating the directory.
     * @option boolean recursive If subdirectories should be scanned as well
     * @option boolean sort If the resulting array should be sorted
     * @option string sortdir What sort order should be applied: SORT_ASC|SORT_DESC
     * @optoin string sortflag What sort flag should be applied: SORT_REGULAR, SORT_NATURAL, SORT_NUMERIC etc
     * @option boolean skiphidden If hidden directories and files should be ignored, defaults to true
     * @option boolean skipdirs If directories should be skipped in the resulting array, defaults to true
     * @option string|array skip Comma separated list or array of filenames (including extension) that should be ignored
     * @option string|array extensions Comma separated list or array of file extensions to filter files by
     * @option boolean|function callback Anonymous function to modify each output item, $item will be passed as argument
     *
     * @return array
     */
    public function getList($options = array()) {
        $options = array_merge(array(
            'recursive' => false,
            'sort' => false,
            'sortdir' => SORT_ASC,
            'sortflag' => SORT_REGULAR,
            'skiphidden' => true,
            'skipdirs' => true,
            'skip' => array(),
            'extensions' => array(),
            'callback' => false,
        ), $options);

        $items = array();

        $mb = $this->fileHandler->modx->getOption('use_multibyte', null, false);
        $mbencoding = $this->fileHandler->modx->getOption('modx_charset', null, 'UTF-8');
        $extensions = !is_array($options['extensions']) ? explode(',', $options['extensions']) : $options['extensions'];
        $skip = !is_array($options['skip']) ? explode(',', $options['skip']) : $options['skip'];
        $iterator = $options['recursive'] ? new RecursiveIteratorIterator(new RecursiveDirectoryIterator($this->path, FilesystemIterator::CURRENT_AS_SELF)) : new DirectoryIterator($this->path);

        foreach ($iterator as $item) {
            $skipfile = !empty($skip) ? in_array($item->getFilename(), $skip) : false;
            $ishidden = false;

            if ($options['skiphidden']) {
                // check for hidden folder, also hide with visible ones inside
                // but don't skip weird filenames like "...and-there-was-silence.avi"
                if ($item->isDot() || preg_match('/(\/\.\w+|\\\.\w+)/', $item->getPath())) {
                    continue;
                }
                // check for hidden file (probably works only on UNIX filesystems)
                $ishidden = preg_match('/^(\.\w+)/i', $item->getFilename());
            } else if (!$options['skipdirs']) {
                // always exclude . and .. directory navigators, only relevant when including folders
                $ishidden = $item->isDot();
            }

            if (($item->isFile() || $item->isDir() && !$options['skipdirs']) && !$ishidden && !$skipfile) {
                $additem = true;

                if (!empty($options['extensions'])) {
                    // if min PHP version is 5.3.6 we can use $item->getExtension()
                    $extension = pathinfo($item->getPathname(), PATHINFO_EXTENSION);
                    $extension = $mb ? mb_strtolower($extension, $mbencoding) : strtolower($extension);

                    if (!in_array($extension, $extensions)) {
                        $additem = false;
                    }
                }

                if (!$additem) {
                    continue;
                } else if (is_callable($options['callback'])) {
                    $callback = call_user_func($options['callback'], $item);

                    if (!empty($callback)) {
                        $items[] = $callback;
                    }
                } else {
                    $items[] = $item->isDir() ? $item->getPathname() . DIRECTORY_SEPARATOR : $item->getPathname();
                }
            }
        }

        if (!empty($options['sort'])) {
            array_multisort($items, $options['sortdir'], $options['sortflag'], $items);
        }

        return $items;
    }
}
