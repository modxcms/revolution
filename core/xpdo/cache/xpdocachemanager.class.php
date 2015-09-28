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
 * Classes implementing a default cache implementation for xPDO.
 *
 * @package xpdo
 * @subpackage cache
 */

/**
 * The default cache manager implementation for xPDO.
 *
 * @package xpdo
 * @subpackage cache
 */
class xPDOCacheManager {
    const CACHE_PHP = 0;
    const CACHE_JSON = 1;
    const CACHE_SERIALIZE = 2;
    const CACHE_DIR = 'objects/';
    const LOG_DIR = 'logs/';

    protected $xpdo= null;
    protected $caches= array();
    protected $options= array();
    protected $_umask= null;

    public function __construct(& $xpdo, $options = array()) {
        $this->xpdo= & $xpdo;
        $this->options= $options;
        $this->_umask= umask();
    }

    /**
     * Get an instance of a provider which implements the xPDOCache interface.
     */
    public function & getCacheProvider($key = '', $options = array()) {
        $objCache = null;
        if (empty($key)) {
            $key = $this->getOption(xPDO::OPT_CACHE_KEY, $options, 'default');
        }
        $objCacheClass= 'xPDOFileCache';
        if (!isset($this->caches[$key]) || !is_object($this->caches[$key])) {
            if ($cacheClass = $this->getOption($key . '_' . xPDO::OPT_CACHE_HANDLER, $options, $this->getOption(xPDO::OPT_CACHE_HANDLER, $options))) {
                $cacheClass = $this->xpdo->loadClass($cacheClass, XPDO_CORE_PATH, false, true);
                if ($cacheClass) {
                    $objCacheClass= $cacheClass;
                }
            }
            $options[xPDO::OPT_CACHE_KEY]= $key;
            $this->caches[$key] = new $objCacheClass($this->xpdo, $options);
            if (empty($this->caches[$key]) || !$this->caches[$key]->isInitialized()) {
                $this->caches[$key] = new xPDOFileCache($this->xpdo, $options);
            }
            $objCache = $this->caches[$key];
            $objCacheClass= get_class($objCache);
        } else {
            $objCache =& $this->caches[$key];
            $objCacheClass= get_class($objCache);
        }
        if ($this->xpdo->getDebug() === true) $this->xpdo->log(xPDO::LOG_LEVEL_DEBUG, "Returning {$objCacheClass}:{$key} cache provider from available providers: " . print_r(array_keys($this->caches), 1));
        return $objCache;
    }

    /**
     * Get an option from supplied options, the cacheManager options, or xpdo itself.
     *
     * @param string $key Unique identifier for the option.
     * @param array $options A set of explicit options to override those from xPDO or the
     * xPDOCacheManager implementation.
     * @param mixed $default An optional default value to return if no value is found.
     * @return mixed The value of the option.
     */
    public function getOption($key, $options = array(), $default = null) {
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
            } elseif (is_array($this->options) && !empty($this->options) && array_key_exists($key, $this->options)) {
                $option = $this->options[$key];
            } else {
                $option = $this->xpdo->getOption($key, null, $default);
            }
        }
        return $option;
    }

    /**
     * Get default folder permissions based on umask
     *
     * @return integer The default folder permissions.
     */
    public function getFolderPermissions() {
        $perms = 0777;
        $perms = $perms & (0777 - $this->_umask);
        return $perms;
    }

    /**
     * Get default file permissions based on umask
     *
     * @return integer The default file permissions.
     */
    public function getFilePermissions() {
        $perms = 0666;
        $perms = $perms & (0666 - $this->_umask);
        return $perms;
    }

    /**
     * Get the absolute path to a writable directory for storing files.
     *
     * @access public
     * @return string The absolute path of the xPDO cache directory.
     */
    public function getCachePath() {
        $cachePath= false;
        if (empty($this->xpdo->cachePath)) {
            if (!isset ($this->xpdo->config['cache_path'])) {
                while (true) {
                    if (!empty ($_ENV['TMP'])) {
                        if ($cachePath= strtr($_ENV['TMP'], '\\', '/'))
                            break;
                    }
                    if (!empty ($_ENV['TMPDIR'])) {
                        if ($cachePath= strtr($_ENV['TMPDIR'], '\\', '/'))
                            break;
                    }
                    if (!empty ($_ENV['TEMP'])) {
                        if ($cachePath= strtr($_ENV['TEMP'], '\\', '/'))
                            break;
                    }
                    if ($temp_file= @ tempnam(md5(uniqid(rand(), TRUE)), '')) {
                        $cachePath= strtr(dirname($temp_file), '\\', '/');
                        @ unlink($temp_file);
                    }
                    break;
                }
                if ($cachePath) {
                    if ($cachePath{strlen($cachePath) - 1} != '/') $cachePath .= '/';
                    $cachePath .= '.xpdo-cache';
                }
            }
            else {
                $cachePath= strtr($this->xpdo->config['cache_path'], '\\', '/');
            }
        } else {
            $cachePath= $this->xpdo->cachePath;
        }
        if ($cachePath) {
            $perms = $this->getOption('new_folder_permissions', null, $this->getFolderPermissions());
            if (is_string($perms)) $perms = octdec($perms);
            if (@ $this->writeTree($cachePath, $perms)) {
                if ($cachePath{strlen($cachePath) - 1} != '/') $cachePath .= '/';
                if (!is_writeable($cachePath)) {
                    @ chmod($cachePath, $perms);
                }
            } else {
                $cachePath= false;
            }
        }
        return $cachePath;
    }

    /**
     * Writes a file to the filesystem.
     *
     * @access public
     * @param string $filename The absolute path to the location the file will
     * be written in.
     * @param string $content The content of the newly written file.
     * @param string $mode The php file mode to write in. Defaults to 'wb'. Note that this method always
     * uses a (with b or t if specified) to open the file and that any mode except a means existing file
     * contents will be overwritten.
     * @param array $options An array of options for the function.
     * @return int|bool Returns the number of bytes written to the file or false on failure.
     */
    public function writeFile($filename, $content, $mode= 'wb', $options= array()) {
        $written= false;
        if (!is_array($options)) {
            $options = is_scalar($options) && !is_bool($options) ? array('new_folder_permissions' => $options) : array();
        }
        $dirname= dirname($filename);
        if (!file_exists($dirname)) {
            $this->writeTree($dirname, $options);
        }
        $mode = str_replace('+', '', $mode);
        switch ($mode[0]) {
            case 'a':
                $append = true;
                break;
            default:
                $append = false;
                break;
        }
        $fmode = (strlen($mode) > 1 && in_array($mode[1], array('b', 't'))) ? "a{$mode[1]}" : 'a';
        $file= @fopen($filename, $fmode);
        if ($file) {
            if ($append === true) {
                $written= fwrite($file, $content);
            } else {
                $locked = false;
                $attempt = 1;
                $attempts = (integer) $this->getOption(xPDO::OPT_CACHE_ATTEMPTS, $options, 1);
                $attemptDelay = (integer) $this->getOption(xPDO::OPT_CACHE_ATTEMPT_DELAY, $options, 1000);
                while (!$locked && ($attempts === 0 || $attempt <= $attempts)) {
                    if ($this->getOption('use_flock', $options, true)) {
                        $locked = flock($file, LOCK_EX | LOCK_NB);
                    } else {
                        $lockFile = $this->lockFile($filename, $options);
                        $locked = $lockFile != false;
                    }
                    if (!$locked && $attemptDelay > 0 && ($attempts === 0 || $attempt < $attempts)) {
                        usleep($attemptDelay);
                    }
                    $attempt++;
                }
                if ($locked) {
                    fseek($file, 0);
                    ftruncate($file, 0);
                    $written= fwrite($file, $content);
                    if ($this->getOption('use_flock', $options, true)) {
                        flock($file, LOCK_UN);
                    } else {
                        $this->unlockFile($filename, $options);
                    }
                }
            }
            @fclose($file);
            if ($written !== false && $fileMode = $this->getOption('new_file_permissions', $options, false)) {
                if (is_string($fileMode)) $fileMode = octdec($fileMode);
                @ chmod($filename, $fileMode);
            }
        }
        return ($written !== false);
    }

    /**
     * Add an exclusive lock to a file for atomic write operations in multi-threaded environments.
     *
     * xPDO::OPT_USE_FLOCK must be set to false (or 0) or xPDO will assume flock is reliable.
     *
     * @param string $file The name of the file to lock.
     * @param array $options An array of options for the process.
     * @return boolean True only if the current process obtained an exclusive lock for writing.
     */
    public function lockFile($file, array $options = array()) {
        $locked = false;
        $lockDir = $this->getOption('lock_dir', $options, $this->getCachePath() . 'locks' . DIRECTORY_SEPARATOR);
        if ($this->writeTree($lockDir, $options)) {
            $lockFile = $this->lockFileName($file, $options);
            if (!file_exists($lockFile)) {
                $myPID = (XPDO_CLI_MODE || !isset($_SERVER['SERVER_ADDR']) ? gethostname() : $_SERVER['SERVER_ADDR']) . '.' . getmypid();
                $myPID .= mt_rand();
                $tmpLockFile = "{$lockFile}.{$myPID}";
                if (file_put_contents($tmpLockFile, $myPID)) {
                    if (link($tmpLockFile, $lockFile)) {
                        $locked = true;
                    }
                    @unlink($tmpLockFile);
                }
            }
        } else {
            $this->xpdo->log(xPDO::LOG_LEVEL_ERROR, "The lock_dir at {$lockDir} is not writable and could not be created");
        }
        if (!$locked) $this->xpdo->log(xPDO::LOG_LEVEL_WARN, "Attempt to lock file {$file} failed");
        return $locked;
    }

    /**
     * Release an exclusive lock on a file created by lockFile().
     *
     * @param string $file The name of the file to unlock.
     * @param array $options An array of options for the process.
     */
    public function unlockFile($file, array $options = array()) {
        @unlink($this->lockFileName($file, $options));
    }

    /**
     * Get an absolute path to a lock file for a specified file path.
     *
     * @param string $file The absolute path to get the lock filename for.
     * @param array $options An array of options for the process.
     * @return string The absolute path for the lock file
     */
    protected function lockFileName($file, array $options = array()) {
        $lockDir = $this->getOption('lock_dir', $options, $this->getCachePath() . 'locks' . DIRECTORY_SEPARATOR);
        return $lockDir . preg_replace('/\W/', '_', $file) . $this->getOption(xPDO::OPT_LOCKFILE_EXTENSION, $options, '.lock');
    }

    /**
     * Recursively writes a directory tree of files to the filesystem
     *
     * @access public
     * @param string $dirname The directory to write
     * @param array $options An array of options for the function. Can also be a value representing
     * a permissions mode to write new directories with, though this is deprecated.
     * @return boolean Returns true if the directory was successfully written.
     */
    public function writeTree($dirname, $options= array()) {
        $written= false;
        if (!empty ($dirname)) {
            if (!is_array($options)) $options = is_scalar($options) && !is_bool($options) ? array('new_folder_permissions' => $options) : array();
            $mode = $this->getOption('new_folder_permissions', $options, $this->getFolderPermissions());
            if (is_string($mode)) $mode = octdec($mode);
            $dirname= strtr(trim($dirname), '\\', '/');
            if ($dirname{strlen($dirname) - 1} == '/') $dirname = substr($dirname, 0, strlen($dirname) - 1);
            if (is_dir($dirname) || (is_writable(dirname($dirname)) && @mkdir($dirname, $mode))) {
                $written= true;
            } elseif (!$this->writeTree(dirname($dirname), $options)) {
                $written= false;
            } else {
                $written= @ mkdir($dirname, $mode);
            }
            if ($written) {
                @ chmod($dirname, $mode);
            }
        }
        return $written;
    }

    /**
     * Copies a file from a source file to a target directory.
     *
     * @access public
     * @param string $source The absolute path of the source file.
     * @param string $target The absolute path of the target destination
     * directory.
     * @param array $options An array of options for this function.
     * @return boolean|array Returns true if the copy operation was successful, or a single element
     * array with filename as key and stat results of the successfully copied file as a result.
     */
    public function copyFile($source, $target, $options = array()) {
        $copied= false;
        if (!is_array($options)) $options = is_scalar($options) && !is_bool($options) ? array('new_file_permissions' => $options) : array();
        if (func_num_args() === 4) $options['new_folder_permissions'] = func_get_arg(3);
        if ($this->writeTree(dirname($target), $options)) {
            $existed= file_exists($target);
            if ($existed && $this->getOption('copy_newer_only', $options, false) && (($ttime = filemtime($target)) > ($stime = filemtime($source)))) {
                $this->xpdo->log(xPDO::LOG_LEVEL_INFO, "xPDOCacheManager->copyFile(): Skipping copy of newer file {$target} ({$ttime}) from {$source} ({$stime})");
            } else {
                $copied= copy($source, $target);
            }
            if ($copied) {
                if (!$this->getOption('copy_preserve_permissions', $options, false)) {
                    $fileMode = $this->getOption('new_file_permissions', $options, $this->getFilePermissions());
                    if (is_string($fileMode)) $fileMode = octdec($fileMode);
                    @ chmod($target, $fileMode);
                }
                if ($this->getOption('copy_preserve_filemtime', $options, true)) @ touch($target, filemtime($source));
                if ($this->getOption('copy_return_file_stat', $options, false)) {
                    $stat = stat($target);
                    if (is_array($stat)) {
                        $stat['overwritten']= $existed;
                        $copied = array($target => $stat);
                    }
                }
            }
        }
        if (!$copied) {
            $this->xpdo->log(xPDO::LOG_LEVEL_ERROR, "xPDOCacheManager->copyFile(): Could not copy file {$source} to {$target}");
        }
        return $copied;
    }

    /**
     * Recursively copies a directory tree from a source directory to a target
     * directory.
     *
     * @access public
     * @param string $source The absolute path of the source directory.
     * @param string $target The absolute path of the target destination directory.
     * @param array $options An array of options for this function.
     * @return array|boolean Returns an array of all files and folders that were copied or false.
     */
    public function copyTree($source, $target, $options= array()) {
        $copied= false;
        $source= strtr($source, '\\', '/');
        $target= strtr($target, '\\', '/');
        if ($source{strlen($source) - 1} == '/') $source = substr($source, 0, strlen($source) - 1);
        if ($target{strlen($target) - 1} == '/') $target = substr($target, 0, strlen($target) - 1);
        if (is_dir($source . '/')) {
            if (!is_array($options)) $options = is_scalar($options) && !is_bool($options) ? array('new_folder_permissions' => $options) : array();
            if (func_num_args() === 4) $options['new_file_permissions'] = func_get_arg(3);
            if (!is_dir($target . '/')) {
                $this->writeTree($target . '/', $options);
            }
            if (is_dir($target)) {
                if (!is_writable($target)) {
                    $dirMode = $this->getOption('new_folder_permissions', $options, $this->getFolderPermissions());
                    if (is_string($dirMode)) $dirMode = octdec($dirMode);
                    if (! @ chmod($target, $dirMode)) {
                        $this->xpdo->log(xPDO::LOG_LEVEL_ERROR, "{$target} is not writable and permissions could not be modified.");
                    }
                }
                if ($handle= @ opendir($source)) {
                    $excludeItems = $this->getOption('copy_exclude_items', $options, array('.', '..','.svn','.svn/','.svn\\'));
                    $excludePatterns = $this->getOption('copy_exclude_patterns', $options);
                    $copiedFiles = array();
                    $error = false;
                    while (false !== ($item= readdir($handle))) {
                        $copied = false;
                        if (is_array($excludeItems) && !empty($excludeItems) && in_array($item, $excludeItems)) continue;
                        if (is_array($excludePatterns) && !empty($excludePatterns) && $this->matches($item, $excludePatterns)) continue;
                        $from= $source . '/' . $item;
                        $to= $target . '/' . $item;
                        if (is_dir($from)) {
                            if (!($copied= $this->copyTree($from, $to, $options))) {
                                $this->xpdo->log(xPDO::LOG_LEVEL_ERROR, "Could not copy directory {$from} to {$to}");
                                $error = true;
                            } else {
                                $copiedFiles = array_merge($copiedFiles, $copied);
                            }
                        } elseif (is_file($from)) {
                            if (!$copied= $this->copyFile($from, $to, $options)) {
                                $this->xpdo->log(xPDO::LOG_LEVEL_ERROR, "Could not copy file {$from} to {$to}; could not create directory.");
                                $error = true;
                            } else {
                                $copiedFiles[] = $to;
                            }
                        } else {
                            $this->xpdo->log(xPDO::LOG_LEVEL_ERROR, "Could not copy {$from} to {$to}");
                        }
                    }
                    @ closedir($handle);
                    if (!$error) $copiedFiles[] = $target;
                    $copied = $copiedFiles;
                } else {
                    $this->xpdo->log(xPDO::LOG_LEVEL_ERROR, "Could not read source directory {$source}");
                }
            } else {
                $this->xpdo->log(xPDO::LOG_LEVEL_ERROR, "Could not create target directory {$target}");
            }
        } else {
            $this->xpdo->log(xPDO::LOG_LEVEL_ERROR, "Source directory {$source} does not exist.");
        }
        return $copied;
    }

    /**
     * Recursively deletes a directory tree of files.
     *
     * @access public
     * @param string $dirname An absolute path to the source directory to delete.
     * @param array $options An array of options for this function.
     * @return boolean Returns true if the deletion was successful.
     */
    public function deleteTree($dirname, $options= array('deleteTop' => false, 'skipDirs' => false, 'extensions' => array('.cache.php'))) {
        $result= false;
        if (is_dir($dirname)) { /* Operate on dirs only */
            if (substr($dirname, -1) != '/') {
                $dirname .= '/';
            }
            $result= array ();
            if (!is_array($options)) {
                $numArgs = func_num_args();
                $options = array(
                    'deleteTop' => is_scalar($options) ? (boolean) $options : false
                    ,'skipDirs' => $numArgs > 2 ? func_get_arg(2) : false
                    ,'extensions' => $numArgs > 3 ? func_get_arg(3) : array('.cache.php')
                );
            }
            $hasMore= true;
            if ($handle= opendir($dirname)) {
                $limit= 4;
                $extensions= $this->getOption('extensions', $options, array('.cache.php'));
                $excludeItems = $this->getOption('delete_exclude_items', $options, array('.', '..','.svn','.svn/','.svn\\'));
                $excludePatterns = $this->getOption('delete_exclude_patterns', $options);
                while ($hasMore && $limit--) {
                    if (!$handle) {
                        $handle= opendir($dirname);
                    }
                    $hasMore= false;
                    while (false !== ($file= @ readdir($handle))) {
                        if (is_array($excludeItems) && !empty($excludeItems) && in_array($file, $excludeItems)) continue;
                        if (is_array($excludePatterns) && !empty($excludePatterns) && $this->matches($file, $excludePatterns)) continue;
                        if ($file != '.' && $file != '..') { /* Ignore . and .. */
                            $path= $dirname . $file;
                            if (is_dir($path)) {
                                $suboptions = array_merge($options, array('deleteTop' => !$this->getOption('skipDirs', $options, false)));
                                if ($subresult= $this->deleteTree($path, $suboptions)) {
                                    $result= array_merge($result, $subresult);
                                }
                            }
                            elseif (is_file($path)) {
                                if (is_array($extensions) && !empty($extensions) && !$this->endsWith($file, $extensions)) continue;
                                if (unlink($path)) {
                                    array_push($result, $path);
                                } else {
                                    $hasMore= true;
                                }
                            }
                        }
                    }
                    closedir($handle);
                }
                if ($this->getOption('deleteTop', $options, false)) {
                    if (@ rmdir($dirname)) {
                        array_push($result, $dirname);
                    }
                }
            }
        } else {
            $result= false; /* return false if attempting to operate on a file */
        }
        return $result;
    }

    /**
     * Sees if a string ends with a specific pattern or set of patterns.
     *
     * @access public
     * @param string $string The string to check.
     * @param string|array $pattern The pattern or an array of patterns to check against.
     * @return boolean True if the string ends with the pattern or any of the patterns provided.
     */
    public function endsWith($string, $pattern) {
        $matched= false;
        if (is_string($string) && ($stringLen= strlen($string))) {
            if (is_array($pattern)) {
                foreach ($pattern as $subPattern) {
                    if (is_string($subPattern) && $this->endsWith($string, $subPattern)) {
                        $matched= true;
                        break;
                    }
                }
            } elseif (is_string($pattern)) {
                if (($patternLen= strlen($pattern)) && $stringLen >= $patternLen) {
                    $matched= (substr($string, -$patternLen) === $pattern);
                }
            }
        }
        return $matched;
    }

    /**
     * Sees if a string matches a specific pattern or set of patterns.
     *
     * @access public
     * @param string $string The string to check.
     * @param string|array $pattern The pattern or an array of patterns to check against.
     * @return boolean True if the string matched the pattern or any of the patterns provided.
     */
    public function matches($string, $pattern) {
        $matched= false;
        if (is_string($string) && ($stringLen= strlen($string))) {
            if (is_array($pattern)) {
                foreach ($pattern as $subPattern) {
                    if (is_string($subPattern) && $this->matches($string, $subPattern)) {
                        $matched= true;
                        break;
                    }
                }
            } elseif (is_string($pattern)) {
                $matched= preg_match($pattern, $string);
            }
        }
        return $matched;
    }

    /**
     * Generate a PHP executable representation of an xPDOObject.
     *
     * @todo Complete $generateRelated functionality.
     * @todo Add stdObject support.
     *
     * @access public
     * @param xPDOObject $obj An xPDOObject to generate the cache file for
     * @param string $objName The name of the xPDOObject
     * @param boolean $generateObjVars If true, will also generate maps for all
     * object variables. Defaults to false.
     * @param boolean $generateRelated If true, will also generate maps for all
     * related objects. Defaults to false.
     * @param string $objRef The reference to the xPDO instance, in string
     * format.
     * @param boolean $format The format to cache in. Defaults to
     * xPDOCacheManager::CACHE_PHP, which is set to cache in executable PHP format.
     * @return string The source map file, in string format.
     */
    public function generateObject($obj, $objName, $generateObjVars= false, $generateRelated= false, $objRef= 'this->xpdo', $format= xPDOCacheManager::CACHE_PHP) {
        $source= false;
        if (is_object($obj) && $obj instanceof xPDOObject) {
            $className= $obj->_class;
            $source= "\${$objName}= \${$objRef}->newObject('{$className}');\n";
            $source .= "\${$objName}->fromArray(" . var_export($obj->toArray('', true), true) . ", '', true, true);\n";
            if ($generateObjVars && $objectVars= get_object_vars($obj)) {
                while (list($vk, $vv)= each($objectVars)) {
                    if ($vk === 'modx') {
                        $source .= "\${$objName}->{$vk}= & \${$objRef};\n";
                    }
                    elseif ($vk === 'xpdo') {
                        $source .= "\${$objName}->{$vk}= & \${$objRef};\n";
                    }
                    elseif (!is_resource($vv)) {
                        $source .= "\${$objName}->{$vk}= " . var_export($vv, true) . ";\n";
                    }
                }
            }
            if ($generateRelated && !empty ($obj->_relatedObjects)) {
                foreach ($obj->_relatedObjects as $className => $fk) {
                    foreach ($fk as $key => $relObj) {} /* TODO: complete $generateRelated functionality */
                }
            }
        }
        return $source;
    }

    /**
     * Add a key-value pair to a cache provider if it does not already exist.
     *
     * @param string $key A unique key identifying the item being stored.
     * @param mixed & $var A reference to the PHP variable representing the item.
     * @param integer $lifetime Seconds the item will be valid in cache.
     * @param array $options Additional options for the cache add operation.
     */
    public function add($key, & $var, $lifetime= 0, $options= array()) {
        $return= false;
        if ($cache = $this->getCacheProvider($this->getOption(xPDO::OPT_CACHE_KEY, $options))) {
            $value= null;
            if (is_object($var) && $var instanceof xPDOObject) {
                $value= $var->toArray('', true);
            } else {
                $value= $var;
            }
            $return= $cache->add($key, $value, $lifetime, $options);
        }
        return $return;
    }

    /**
     * Replace a key-value pair in in a cache provider.
     *
     * @access public
     * @param string $key A unique key identifying the item being replaced.
     * @param mixed & $var A reference to the PHP variable representing the item.
     * @param integer $lifetime Seconds the item will be valid in objcache.
     * @param array $options Additional options for the cache replace operation.
     * @return boolean True if the replace was successful.
     */
    public function replace($key, & $var, $lifetime= 0, $options= array()) {
        $return= false;
        if ($cache = $this->getCacheProvider($this->getOption(xPDO::OPT_CACHE_KEY, $options), $options)) {
            $value= null;
            if (is_object($var) && $var instanceof xPDOObject) {
                $value= $var->toArray('', true);
            } else {
                $value= $var;
            }
            $return= $cache->replace($key, $value, $lifetime, $options);
        }
        return $return;
    }

    /**
     * Set a key-value pair in a cache provider.
     *
     * @access public
     * @param string $key A unique key identifying the item being set.
     * @param mixed & $var A reference to the PHP variable representing the item.
     * @param integer $lifetime Seconds the item will be valid in objcache.
     * @param array $options Additional options for the cache set operation.
     * @return boolean True if the set was successful
     */
    public function set($key, & $var, $lifetime= 0, $options= array()) {
        $return= false;
        if ($cache = $this->getCacheProvider($this->getOption(xPDO::OPT_CACHE_KEY, $options), $options)) {
            $value= null;
            if (is_object($var) && $var instanceof xPDOObject) {
                $value= $var->toArray('', true);
            } else {
                $value= $var;
            }
            $return= $cache->set($key, $value, $lifetime, $options);
        } else {
            $this->xpdo->log(xPDO::LOG_LEVEL_ERROR, 'No cache implementation found.');
        }
        return $return;
    }

    /**
     * Delete a key-value pair from a cache provider.
     *
     * @access public
     * @param string $key A unique key identifying the item being deleted.
     * @param array $options Additional options for the cache deletion.
     * @return boolean True if the deletion was successful.
     */
    public function delete($key, $options = array()) {
        $return= false;
        if ($cache = $this->getCacheProvider($this->getOption(xPDO::OPT_CACHE_KEY, $options), $options)) {
            $return= $cache->delete($key, $options);
        }
        return $return;
    }

    /**
     * Get a value from a cache provider by key.
     *
     * @access public
     * @param string $key A unique key identifying the item being retrieved.
     * @param array $options Additional options for the cache retrieval.
     * @return mixed The value of the object cache key
     */
    public function get($key, $options = array()) {
        $return= false;
        if ($cache = $this->getCacheProvider($this->getOption(xPDO::OPT_CACHE_KEY, $options), $options)) {
            $return= $cache->get($key, $options);
        }
        return $return;
    }

    /**
     * Flush the contents of a cache provider.
     *
     * @access public
     * @param array $options Additional options for the cache flush.
     * @return boolean True if the flush was successful.
     */
    public function clean($options = array()) {
        $return= false;
        if ($cache = $this->getCacheProvider($this->getOption(xPDO::OPT_CACHE_KEY, $options), $options)) {
            $return= $cache->flush($options);
        }
        return $return;
    }

    /**
     * Refresh specific or all cache providers.
     *
     * The default behavior is to call clean() with the provided options
     *
     * @param array $providers An associative array with keys representing the cache provider key
     * and the value an array of options.
     * @param array &$results An associative array for collecting results for each provider.
     * @return array An array of results for each provider that is refreshed.
     */
    public function refresh(array $providers = array(), array &$results = array()) {
        if (empty($providers)) {
            foreach ($this->caches as $cacheKey => $cache) {
                $providers[$cacheKey] = array();
            }
        }
        foreach ($providers as $key => $options) {
            if (array_key_exists($key, $this->caches) && !array_key_exists($key, $results)) {
                $results[$key] = $this->clean(array_merge($options, array(xPDO::OPT_CACHE_KEY => $key)));
            }
        }
        return (array_search(false, $results, true) === false);
    }

    /**
     * Escapes all single quotes in a string
     *
     * @access public
     * @param string $s  The string to escape single quotes in.
     * @return string  The string with single quotes escaped.
     */
    public function escapeSingleQuotes($s) {
        $q1= array (
            "\\",
            "'"
        );
        $q2= array (
            "\\\\",
            "\\'"
        );
        return str_replace($q1, $q2, $s);
    }
}

/**
 * An abstract class that defines the methods a cache provider must implement.
 *
 * @package xpdo
 * @subpackage cache
 */
abstract class xPDOCache {
    public $xpdo= null;
    protected $options= array();
    protected $key= '';
    protected $initialized= false;

    public function __construct(& $xpdo, $options = array()) {
        $this->xpdo= & $xpdo;
        $this->options= $options;
        $this->key = $this->getOption(xPDO::OPT_CACHE_KEY, $options, 'default');
    }

    /**
     * Indicates if this xPDOCache instance has been properly initialized.
     *
     * @return boolean true if the implementation was initialized successfully.
     */
    public function isInitialized() {
        return (boolean) $this->initialized;
    }

    /**
     * Get an option from supplied options, the cache options, or the xpdo config.
     *
     * @param string $key Unique identifier for the option.
     * @param array $options A set of explicit options to override those from xPDO or the xPDOCache
     * implementation.
     * @param mixed $default An optional default value to return if no value is found.
     * @return mixed The value of the option.
     */
    public function getOption($key, $options = array(), $default = null) {
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
            } elseif (is_array($this->options) && !empty($this->options) && array_key_exists($key, $this->options)) {
                $option = $this->options[$key];
            } else {
                $option = $this->xpdo->cacheManager->getOption($key, null, $default);
            }
        }
        return $option;
    }

    /**
     * Get the actual cache key the implementation will use.
     *
     * @param string $key The identifier the application uses.
     * @param array $options Additional options for the operation.
     * @return string The identifier with any implementation specific prefixes or other
     * transformations applied.
     */
    public function getCacheKey($key, $options = array()) {
        $prefix = $this->getOption('cache_prefix', $options);
        if (!empty($prefix)) $key = $prefix . $key;
        return $this->key . '/' . $key;
    }

    /**
     * Adds a value to the cache.
     *
     * @access public
     * @param string $key A unique key identifying the item being set.
     * @param string $var A reference to the PHP variable representing the item.
     * @param integer $expire The amount of seconds for the variable to expire in.
     * @param array $options Additional options for the operation.
     * @return boolean True if successful
     */
    abstract public function add($key, $var, $expire= 0, $options= array());

    /**
     * Sets a value in the cache.
     *
     * @access public
     * @param string $key A unique key identifying the item being set.
     * @param string $var A reference to the PHP variable representing the item.
     * @param integer $expire The amount of seconds for the variable to expire in.
     * @param array $options Additional options for the operation.
     * @return boolean True if successful
     */
    abstract public function set($key, $var, $expire= 0, $options= array());

    /**
     * Replaces a value in the cache.
     *
     * @access public
     * @param string $key A unique key identifying the item being set.
     * @param string $var A reference to the PHP variable representing the item.
     * @param integer $expire The amount of seconds for the variable to expire in.
     * @param array $options Additional options for the operation.
     * @return boolean True if successful
     */
    abstract public function replace($key, $var, $expire= 0, $options= array());

    /**
     * Deletes a value from the cache.
     *
     * @access public
     * @param string $key A unique key identifying the item being deleted.
     * @param array $options Additional options for the operation.
     * @return boolean True if successful
     */
    abstract public function delete($key, $options= array());

    /**
     * Gets a value from the cache.
     *
     * @access public
     * @param string $key A unique key identifying the item to fetch.
     * @param array $options Additional options for the operation.
     * @return mixed The value retrieved from the cache.
     */
    public function get($key, $options= array()) {}

    /**
     * Flush all values from the cache.
     *
     * @access public
     * @param array $options Additional options for the operation.
     * @return boolean True if successful.
     */
    abstract public function flush($options= array());
}

/**
 * A simple file-based caching implementation using executable PHP.
 *
 * This can be used to relieve database loads, though the overall performance is
 * about the same as without the file-based cache.  For maximum performance and
 * scalability, use a server with memcached and the PHP memcache extension
 * configured.
 *
 * @package xpdo
 * @subpackage cache
 */
class xPDOFileCache extends xPDOCache {
    public function __construct(& $xpdo, $options = array()) {
        parent :: __construct($xpdo, $options);
        $this->initialized = true;
    }

    public function getCacheKey($key, $options = array()) {
        $cachePath = $this->getOption('cache_path', $options);
        $cacheExt = $this->getOption('cache_ext', $options, '.cache.php');
        $key = parent :: getCacheKey($key, $options);
        return $cachePath . $key . $cacheExt;
    }

    public function add($key, $var, $expire= 0, $options= array()) {
        $added= false;
        if (!file_exists($this->getCacheKey($key, $options))) {
            if ($expire === true)
                $expire= 0;
            $added= $this->set($key, $var, $expire, $options);
        }
        return $added;
    }

    public function set($key, $var, $expire= 0, $options= array()) {
        $set= false;
        if ($var !== null) {
            if ($expire === true)
                $expire= 0;
            $expirationTS= $expire ? time() + $expire : 0;
            $expireContent= '';
            if ($expirationTS) {
                $expireContent= 'if(time() > ' . $expirationTS . '){return null;}';
            }
            $fileName= $this->getCacheKey($key, $options);
            $format = (integer) $this->getOption(xPDO::OPT_CACHE_FORMAT, $options, xPDOCacheManager::CACHE_PHP);
            switch ($format) {
                case xPDOCacheManager::CACHE_SERIALIZE:
                    $content= serialize(array('expires' => $expirationTS, 'content' => $var));
                    break;
                case xPDOCacheManager::CACHE_JSON:
                    $content= $this->xpdo->toJSON(array('expires' => $expirationTS, 'content' => $var));
                    break;
                case xPDOCacheManager::CACHE_PHP:
                default:
                    $content= '<?php ' . $expireContent . ' return ' . var_export($var, true) . ';';
                    break;
            }
            $folderMode = $this->getOption('new_cache_folder_permissions', $options, false);
            if ($folderMode) $options['new_folder_permissions'] = $folderMode;
            $fileMode = $this->getOption('new_cache_file_permissions', $options, false);
            if ($fileMode) $options['new_file_permissions'] = $fileMode;
            $set= $this->xpdo->cacheManager->writeFile($fileName, $content, 'wb', $options);
        }
        return $set;
    }

    public function replace($key, $var, $expire= 0, $options= array()) {
        $replaced= false;
        if (file_exists($this->getCacheKey($key, $options))) {
            if ($expire === true)
                $expire= 0;
            $replaced= $this->set($key, $var, $expire, $options);
        }
        return $replaced;
    }

    public function delete($key, $options= array()) {
        $deleted= false;
        $cacheKey= $this->getCacheKey($key, array_merge($options, array('cache_ext' => '')));
        if (file_exists($cacheKey) && is_dir($cacheKey)) {
            $results = $this->xpdo->cacheManager->deleteTree($cacheKey, array_merge(array('deleteTop' => false, 'skipDirs' => false, 'extensions' => array('.cache.php')), $options));
            if ($results !== false) {
                $deleted = true;
            }
        } else {
            $cacheKey= $this->getCacheKey($key, $options);
            if (file_exists($cacheKey)) {
                $deleted= @ unlink($cacheKey);
            }
        }
        return $deleted;
    }

    public function get($key, $options= array()) {
        $value= null;
        $cacheKey= $this->getCacheKey($key, $options);
        if (file_exists($cacheKey)) {
            if ($file = @fopen($cacheKey, 'rb')) {
                $format = (integer) $this->getOption(xPDO::OPT_CACHE_FORMAT, $options, xPDOCacheManager::CACHE_PHP);
                if (flock($file, LOCK_SH)) {
                    switch ($format) {
                        case xPDOCacheManager::CACHE_PHP:
                            $value= @include $cacheKey;
                            break;
                        case xPDOCacheManager::CACHE_JSON:
                            $payload = stream_get_contents($file);
                            if ($payload !== false) {
                                $payload = $this->xpdo->fromJSON($payload);
                                if (is_array($payload) && isset($payload['expires']) && (empty($payload['expires']) || time() < $payload['expires'])) {
                                    if (array_key_exists('content', $payload)) {
                                        $value= $payload['content'];
                                    }
                                }
                            }
                            break;
                        case xPDOCacheManager::CACHE_SERIALIZE:
                            $payload = stream_get_contents($file);
                            if ($payload !== false) {
                                $payload = unserialize($payload);
                                if (is_array($payload) && isset($payload['expires']) && (empty($payload['expires']) || time() < $payload['expires'])) {
                                    if (array_key_exists('content', $payload)) {
                                        $value= $payload['content'];
                                    }
                                }
                            }
                            break;
                    }
                    flock($file, LOCK_UN);
                    if ($value === null && $this->getOption('removeIfEmpty', $options, true)) {
                        fclose($file);
                        @ unlink($cacheKey);
                        return $value;
                    }
                }
                @fclose($file);
            }
        }
        return $value;
    }

    public function flush($options= array()) {
        $cacheKey= $this->getCacheKey('', array_merge($options, array('cache_ext' => '')));
        $results = $this->xpdo->cacheManager->deleteTree($cacheKey, array_merge(array('deleteTop' => false, 'skipDirs' => false, 'extensions' => array('.cache.php')), $options));
        return ($results !== false);
    }
}
