<?php
/**
 * Assists with directory/file manipulation
 * 
 * @package modx
 */
class modFileHandler {
    function __construct(modX &$modx,array $config = array()) {
        $this->modx =& $modx;
        $this->config = array_merge(array(

        ),$config);
    }

    public function getBasePath($prependBasePath = true) {
        $root = $this->modx->getOption('filemanager_path',null,false);
        if (empty($root)) {
            $root = $this->modx->getOption('rb_base_dir');
        }
        $root = ($prependBasePath ? $this->modx->getOption('base_path') : '').$root;
        return $this->postfixSlash($root);
    }

    public function sanitizePath($path) {
        $path = str_replace(array('../','./'),'',$path);
        $path = trim($path,'/');
        $path = strtr($path,'\\','/');

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

