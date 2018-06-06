<?php

namespace MODX;
/**
 * Assists with directory/file manipulation
 *
 * @property MODX modx
 * @package modx
 */
class modFileHandler
{
    /**
     * An array of configuration properties for the class
     *
     * @var array $config
     */
    public $config = [];
    /**
     * The current context in which this File Manager instance should operate
     *
     * @var modContext|null $context
     */
    public $context = null;


    /**
     * The constructor for the modFileHandler class
     *
     * @param MODX &$modx A reference to the MODX object.
     * @param array $config An array of options.
     */
    function __construct(MODX &$modx, array $config = [])
    {
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
     *
     * @return mixed The appropriate modFile/modDirectory object
     */
    public function make($path, array $options = [], $overrideClass = '')
    {
        $path = $this->sanitizePath($path);

        if (!empty($overrideClass)) {
            $class = $overrideClass;
        } else {
            if (is_dir($path)) {
                $path = $this->postfixSlash($path);
                $class = 'MODX\modDirectory';
            } else {
                $class = 'MODX\modFile';
            }
        }

        return new $class($this, $path, $options);
    }


    /**
     * Get the MODX base path for the user.
     *
     * @return string The base path
     */
    public function getBasePath()
    {
        $basePath = $this->context->getOption('filemanager_path', '', $this->config);
        /* expand placeholders */
        $basePath = str_replace([
            '{base_path}',
            '{core_path}',
            '{assets_path}',
        ], [
            $this->context->getOption('base_path', MODX_BASE_PATH, $this->config),
            $this->context->getOption('core_path', MODX_CORE_PATH, $this->config),
            $this->context->getOption('assets_path', MODX_ASSETS_PATH, $this->config),
        ], $basePath);

        return !empty($basePath) ? $this->postfixSlash($basePath) : $basePath;
    }


    /**
     * Get base URL of file manager
     *
     * @return string The base URL
     */
    public function getBaseUrl()
    {
        $baseUrl = $this->context->getOption('filemanager_url', $this->context->getOption('rb_base_url', MODX_BASE_URL, $this->config), $this->config);

        /* expand placeholders */
        $baseUrl = str_replace([
            '{base_url}',
            '{core_url}',
            '{assets_url}',
        ], [
            $this->context->getOption('base_url', MODX_BASE_PATH, $this->config),
            $this->context->getOption('core_url', MODX_CORE_PATH, $this->config),
            $this->context->getOption('assets_url', MODX_ASSETS_PATH, $this->config),
        ], $baseUrl);

        return !empty($baseUrl) ? $this->postfixSlash($baseUrl) : $baseUrl;
    }


    /**
     * Sanitize the specified path
     *
     * @param string $path The path to clean
     *
     * @return string The sanitized path
     */
    public function sanitizePath($path)
    {
        return preg_replace(["/\.*[\/|\\\]/i", "/[\/|\\\]+/i"], ['/', '/'], $path);
    }


    /**
     * Ensures that the passed path has a / at the end
     *
     * @param string $path
     *
     * @return string The postfixed path
     */
    public function postfixSlash($path)
    {
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
     *
     * @return string The directory path of the given file
     */
    public function getDirectoryFromFile($fileName)
    {
        $dir = dirname($fileName);

        return $this->postfixSlash($dir);
    }


    /**
     * Tells if a file is a binary file or not.
     *
     * @param string $file
     *
     * @return boolean True if a binary file.
     */
    public function isBinary($file)
    {
        if (file_exists($file)) {
            if (!is_file($file)) return false;
            $fh = @fopen($file, 'r');
            $blk = @fread($fh, 512);
            @fclose($fh);
            @clearstatcache();

            return (substr_count($blk, "^ -~" /*. "^\r\n"*/) / 512 > 0.3) || (substr_count($blk, "\x00") > 0) ? false
                : true;
        }

        return false;
    }
}

