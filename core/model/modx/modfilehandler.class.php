<?php
/**
 * Assists with directory/file manipulation
 * 
 * @package modx
 */
class modFileHandler {
    /**
     * The constructor for the modFileHandler class
     * 
     * @param modX &$modx A reference to the modX object
     * @param array $config An array of options
     */
    function __construct(modX &$modx,array $config = array()) {
        $this->modx =& $modx;
        $this->config = array_merge(array(),$config);
    }

    /**
     * Dynamically creates a modDirectory or modFile object based on the
     * type of the path provided.
     * 
     * @param string $path The absolute path to the filesystem resource.
     * @param array $options Optional. An array of options for the object.
     * @param string $overrideClass Optional. If provided, will force creation
     * of the object as the specified class.
     * @return mixed The appropriate modFile/modDirectory object
     */
    public function make($path,array $options = array(),$overrideClass = '') {
        $path = $this->sanitizePath($path);
        $obj = null;

        $class = 'modFile';
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

        return new $class($this,$path,$options);
    }

    /**
     * Get the modX base path for the user.
     *
     * @param string $prependBasePath If true, will prepend the modX base path
     * to the return value. Defaults to true.
     * @return string The base path
     */
    public function getBasePath($prependBasePath = false) {
        $root = $this->modx->getOption('filemanager_path',null,'');
        if (empty($root)) {
            $root = $this->modx->getOption('rb_base_dir');
        }
        /* expand placeholders */
        $root = str_replace(array(
            '{base_path}',
            '{core_path}',
            '{assets_path}',
        ),array(
            $this->modx->getOption('base_path'),
            $this->modx->getOption('core_path'),
            $this->modx->getOption('assets_path'),
        ),$root);

        /* check for absolute/relative */
        if (substr($root,0,1) != '/') {
            $root = $this->modx->getOption('base_path',null,MODX_BASE_PATH).$root;
        }

        $root = ($prependBasePath ? $this->modx->getOption('base_path') : '').$root;
        return $this->postfixSlash($root);
    }

    /**
     * Sanitize the specified path
     *
     * @param string $path The path to clean
     * @return string The sanitized path
     */
    public function sanitizePath($path) {
        $path = str_replace(array('../','./'),'',$path);
        $path = strtr($path,'\\','/');
        $path = str_replace('//','/',$path);

        return $path;
    }

    public function postfixSlash($path) {
        $len = strlen($path);
        if (substr($path,$len-1,$len) != '/') {
            $path .= '/';
        }
        return $path;
    }

    public function getDirectoryFromFile($fileName) {
        $dir = dirname($fileName);
        return $this->postfixSlash($dir);
    }
}

/**
 * Abstract class for handling file system resources (files or folders). Not to
 * be instantiated directly - you should implement your own derivative class.
 */
abstract class modFileSystemResource {
    /**
     * @var string The absolute path of the file system resource
     */
    protected $path;
    /**
     * @var modX A reference to a modX instance
     */
    public $modx;
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
    function __construct(modFileHandler &$fh,$path,array $options = array()) {
        $this->fileHandler =& $fh;
        $this->modx =& $fh->modx;
        $this->path = $path;
        $this->options = array_merge(array(
            
        ),$options);
    }

    /**
     * Get the path of the fs resource.
     * @return string The path of the fs resource
     */
    public function getPath() {
        return $this->path;
    }

    /**
     * Chmods the resource to the specified mode.
     *
     * @param octal $mode
     * @return boolean True if successful
     */
    public function chmod($mode) {
        $mode = $this->parseMode($mode);
        return @chmod($this->path,$mode);
    }

    /**
     * Sets the group permission for the fs resource
     * @param mixed $grp
     * @return boolean True if successful
     */
    public function chgrp($grp) {
        if ($this->isLink() && function_exists('lchgrp')) {
            return @lchgrp($this->path,$grp);
        } else {
            return @chgrp($this->path,$grp);
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
            return @lchown($this->path,$owner);
        } else {
            return @chown($this->path,$owner);
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

        return @rename($this->path,$newPath);
    }

    /**
     * Parses a string mode into octal format
     * 
     * @param string $mode The octal to parse
     * @return octal The new mode in octal format
     */
    protected function parseMode($mode = '') {
        return octdec($mode);
    }

    /**
     * Gets the parent containing directory of this fs resource
     *
     * @param <type> $raw Whether or not to return a modDirectory or string path
     * @return modDirectory/string Returns either a modDirectory object of the
     * parent directory, or the absolute path of the parent, depending on
     * whether or not $raw is set to true.
     */
    public function getParentDirectory($raw = false) {
        $ppath = dirname($this->path).'/';
        $ppath = str_replace('//','/',$ppath);
        if ($raw) return $ppath;

        $directory = $this->fileHandler->make($ppath);
        return $directory;
    }
}

/**
 * File implementation of modFileSystemResource
 */
class modFile extends modFileSystemResource {
    /**
     * @var string The content of the resource
     */
    protected $content = '';

    /**
     * @see modFileSystemResource.parseMode
     */
    protected function parseMode($mode = '') {
        if (empty($mode)) $mode = $this->modx->getOption('new_file_permissions',null,0644);
        return parent::parseMode($mode);
    }

    /**
     * Actually create the file on the file system
     * 
     * @param string $content The content of the file to write
     * @param string $mode The perms to write with the file
     * @return boolean True if successful
     */
    public function create($content = '',$mode = 'w+') {
        if ($this->exists()) return false;
        $result = false;

        $fp = @fopen($this->path, 'w+');
        if ($fp) {
            $result = @fwrite($fp,$content);
            @fclose($fp);
        }
        return $result;
    }

    /**
     * Temporarly set (but not save) the content of the file
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
        return @file_get_contents($this->path);
    }

    /**
     * Alias for save()
     *
     * @see modDirectory::write
     */
    public function write($content = null,$mode = 'w+') {
        return $this->save($content,$mode);
    }

    /**
     * Writes the content of the modFile object to the actual file.
     *
     * @param string $content Optional. If not using setContent, this will set
     * the content to write.
     * @return mixed The result of the fwrite
     */
    public function save($content = null,$mode = 'w+') {
        if ($content !== null) $this->content = $content;
        $result = false;

        $fp = @fopen($this->path,$mode);
        if ($fp) {
            $result = @fwrite($fp,$this->content);
            @fclose($fp);
        }

        return $result;
    }

    /**
     * Gets the size of the file
     * 
     * @return int The size of the file, in bytes
     */
    public function getSize() {
        return filesize($this->path);
    }

    /**
     * Gets the last accessed time of the file
     *
     * @param string $timeFormat The format, in strftime format, of the time
     * @return string The formatted time
     */
    public function getLastAccessed($timeFormat = '%b %d, %Y %H:%I:%S %p') {
        return strftime($timeFormat,fileatime($this->path));
    }

    /**
     * Gets the last modified time of the file
     * 
     * @param string $timeFormat The format, in strftime format, of the time
     * @return string The formatted time
     */
    public function getLastModified($timeFormat = '%b %d, %Y %H:%I:%S %p') {
        return strftime($timeFormat,filemtime($this->path));
    }

    /**
     * Gets the file extension of the file
     * 
     * @return string The file extension of the file
     */
    public function getExtension() {
        return pathinfo($this->path,PATHINFO_EXTENSION);
    }

    /**
     * Gets the basename, or only the filename without the path, of the file
     * 
     * @return string The basename of the file
     */
    public function getBaseName() {
        return ltrim(strrchr($this->path,'/'),'/');
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
}

/**
 * Representation of a directory
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
        if ($this->exists()) return false;

        return $this->modx->cacheManager->writeTree($this->path);
    }

    /**
     * @see modFileSystemResource::parseMode
     */
    protected function parseMode($mode = '') {
        if (empty($mode)) $mode = $this->modx->getOption('new_folder_permissions',null,'0755');
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
            'extensions' => '',
        ),$options);
        
        $this->modx->getCacheManager();
        return $this->modx->cacheManager->deleteTree($this->path,$options);
    }
}