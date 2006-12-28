<?php
/**
 * Classes for default cache implementation.
 * 
 * @package xpdo.cache
 */
if (!defined('XPDO_CACHE_PHP')) {
    define('XPDO_CACHE_PHP', 0);
    define('XPDO_CACHE_JSON', 1);
}
/**
 * The default cache manager implementation for xPDO.
 * 
 * @package xpdo.cache
 */
class xPDOCacheManager {
    var $xpdo= null;
    var $objcache= null;
    
    function xPDOCacheManager(& $xpdo) {
        $this->__construct($xpdo);
    }
    
    function __construct(& $xpdo) {
        $this->xpdo= & $xpdo;
        $objCacheClass= 'xPDOFileCache';
        if (isset ($xpdo->config['cache_db_handler']) && $xpdo->config['cache_db_handler']) {
            if (class_exists($xpdo->config['cache_db_handler']) || $xpdo->loadClass($xpdo->config['cache_db_handler'], XPDO_CORE_PATH, true, true)) {
                $objCacheClass= $xpdo->config['cache_db_handler'];
            }
        }
        $this->objcache= new $objCacheClass($xpdo);
    }

    function generateObject($obj, $objName, $generateObjVars= false, $generateRelated= false, $objRef= 'this->xpdo', $format= XPDO_CACHE_PHP) {
        $source= false;
        if (is_object($obj) && is_a($obj, 'xPDOObject')) {
            $className= get_class($obj);
            $source= "\${$objName}= \${$objRef}->newObject('{$className}');\n";
            $source .= "\${$objName}->fromArray(" . var_export($obj->toArray(), true) . ", '', true, true);\n";
            if ($generateObjVars && $objectVars= get_object_vars($obj)) {
                while (list($vk, $vv)= each($objectVars)) {
                    if ($vk === 'modx') {
                        $source .= "\${$objName}->{$vk}= & \${$objRef};\n";
                    } elseif ($vk === 'xpdo') {
                        $source .= "\${$objName}->{$vk}= & \${$objRef};\n";
                    } elseif (!is_resource($vv)) {
                        $source .= "\${$objName}->{$vk}= " . var_export($vv, true) . ";\n";
                    }
                }
            }
//            if ($generateRelated && !empty ($obj->_relatedObjects)) {
//                foreach ($obj->_relatedObjects as $className => $fk) {
//                    foreach ($fk as $key => $relObj) {}
//                }
//            }
        }
        return $source;
    }
    
    /**
     * Add a key-value pair to the server if it does not already exist.
     * 
     * @param string $key A unique key identifying the item being stored.
     * @param mixed $var The PHP variable representing the item.
     * @param integer $lifetime Seconds the item will be valid in objcache.
     * @param integer $format The serialization format for arrays and objects.
     * @param integer $flag Flag indicating if the item should be stored
     * in a compressed format (uses zlib).
     */
    function add($key, & $var, $lifetime= 0, $compressed= false) {
        $return= false;
        if ($this->objcache) {
            $value= null;
            if (is_object($var) && is_a($var, 'xPDOObject')) {
                $value= $var->toJSON();
            }
            else {
                $value= $this->xpdo->toJSON($var);
            }
            $return= $this->objcache->add($key, $value, $compressed, $lifetime);
        }
        return $return;
    }
    function replace($key, & $var, $lifetime= 0, $compressed= false) {
        $return= false;
        if ($this->objcache) {
            $value= null;
            if (is_object($var) && is_a($var, 'xPDOObject')) {
                $value= $var->toJSON();
            }
            else {
                $value= $this->xpdo->toJSON($var);
            }
            $return= $this->objcache->replace($key, $value, $compressed, $lifetime);
        }
        return $return;
    }
    function set($key, & $var, $lifetime= 0, $compressed= false) {
        $return= false;
        if ($this->objcache) {
            $value= null;
            if ($var === null) {
                return $this->objcache->delete($key);
            }
            elseif (is_object($var) && is_a($var, 'xPDOObject')) {
                $value= $var->toArray();
            }
            else {
                $value= $var;
            }
            $return= $this->objcache->set($key, $value, $compressed, $lifetime);
        }
        return $return;
    }
    function delete($key, $delay= 0) {
        $return= false;
        if ($this->objcache) {
            $return= $this->objcache->delete($key);
        }
        return $return;
    }
    function get($key) {
        $return= false;
        if ($this->objcache) {
            $return= $this->objcache->get($key);
        }
        return $return;
    }
    function clean() {
        $return= false;
        if ($this->objcache) {
            $return= $this->objcache->flush();
        }
        return $return;
    }
    
    /**
     * Escapes all single quotes in a string
     * 
     * @param string $s  The string to escape single quotes in.
     * @return string  The string with single quotes escaped.
     */
    function escapeSingleQuotes($s) {
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
 * A simple php file-based caching implementation.
 * 
 * This can be used to relieve database loads, though the overall performance is
 * about the same as without the file-based cache.  For maximum performance and
 * scalability, use a server with memcached and the PHP memcache extension
 * configured (the default caching mechanism, if available).
 * 
 * @package xpdo.cache
 */
class xPDOFileCache {
    var $xpdo= null;
    
    function xPDOFileCache(& $xpdo) {
        $this->xpdo= & $xpdo;
    }
    
    function getCacheFilename($key) {
        return $this->xpdo->cachePath . 'objects/' . $key . '.cache.php';
    }
    
    function add($key, $var, $flag= false, $expire= 0) {
        $added= false;
        if (!file_exists($this->getCacheFilename($key))) {
            if ($expire === true) $expire= 0;
            $added= $this->set($key, $var, $flag, $expire);
        }
        return $added;
    }
    
    function set($key, $var, $flag= false, $expire= 0) {
        $set= false;
        if ($var !== null) {
            if ($expire === true) $expire= 0;
            $expirationTS= $expire ? time() + $expire : 0;
            $expireContent= '';
            if ($expirationTS) {
                $expireContent= 'if(time() > ' . $expirationTS . '){return null;}';
            }
            $fileName= $this->getCacheFilename($key);
            $content= '<?php ' . $expireContent . ' return ' . var_export($var, true) . ';';
            $file= @fopen($fileName, 'wb');
            $set= @fwrite($file, $content);
            @fclose($file);
        }
        return $set;
    }
    
    function replace($key, $var, $flag= false, $expire= 0) {
        $replaced= false;
        if (file_exists($this->getCacheFilename($key))) {
            if ($expire === true) $expire= 0;
            $replaced= $this->set($key, $var, $flag, $expire);
        }
        return $replaced;
    }
    
    function delete($key) {
        $deleted= false;
        $cacheFilename= $this->getCacheFileName($key);
        if (file_exists($cacheFilename)) {
            $deleted= unlink($cacheFilename);
        }
        return $deleted;
    }
    
    function get($key) {
        $value= null;
        $cacheFilename= $this->getCacheFileName($key);
        if (file_exists($cacheFilename)) {
            $value= @ include ($cacheFilename);
            if ($value === null) {
                @unlink($cacheFilename);
            }
        }
        return $value;
    }
    
    function flush() {
        
    }
}
?>