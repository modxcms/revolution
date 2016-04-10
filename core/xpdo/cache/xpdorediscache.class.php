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
 * Provides a redis-powered xPDOCache implementation.
 *
 * This requires the redis extension for PHP.
 *
 * @package xpdo
 * @subpackage cache
 */
class xPDORedisCache extends xPDOCache {
    protected $redis = null;

    public function __construct(& $xpdo, $options = array()) {
        parent :: __construct($xpdo, $options);
        if (class_exists('Redis', true)) {
            $this->redis= new Redis();
            if ($this->redis) {
                $server = explode(':', $this->getOption($this->key . '_redis_server', $options, $this->getOption('redis_server', $options, 'localhost:6379')));                
                if($this->redis->pconnect($server[0], (integer) $server[1])){                    
                    $redis_auth=$this->getOption('redis_auth', $options, '');
                    if(!empty($redis_auth)){
                        $this->redis->auth($redis_auth);    
                    }
                    $this->redis->select((integer)$this->getOption('redis_db', $options, 0));
                    $this->redis->setOption(Redis::OPT_SERIALIZER, Redis::SERIALIZER_PHP);
                    $this->initialized = true;                        
                }   
            } else {
                $this->redis = null;
                $this->xpdo->log(xPDO::LOG_LEVEL_ERROR, "xPDORedisCache[{$this->key}]: Error creating redis provider for server(s): " . $this->getOption($this->key . '_redisd_server', $options, $this->getOption('redisd_server', $options, 'localhost:6379')));
            }
        } else {
            $this->xpdo->log(xPDO::LOG_LEVEL_ERROR, "xPDORedisCache[{$this->key}]: Error creating redis provider; xPDORedisCache requires the PHP redis extension.");
        }
    }

    public function add($key, $var, $expire= 0, $options= array()) {
        $added= false;
        if(!$this->redis->exists($this->getCacheKey($key))){          
            $added=$this->redis->set($this->getCacheKey($key),$var,$expire); 
        }
        return $added;
    }

    public function set($key, $var, $expire= 0, $options= array()) {
        $set=$this->redis->set($this->getCacheKey($key),$var,$expire); 
        return $set;
    }

    public function replace($key, $var, $expire= 0, $options= array()) {
        $replaced=false;
        if($this->redis->exists($this->getCacheKey($key))){          
            $replaced=$this->redis->set($this->getCacheKey($key),$var,$expire); 
        }
        return $replaced;
    }

    public function delete($key, $options= array()) {
        if (!isset($options['multiple_object_delete']) || empty($options['multiple_object_delete'])) {
            $deleted= $this->redis->delete($this->getCacheKey($key));
        } else {
            $deleted= $this->flush($options);
        }
        return $deleted;
    }

    public function get($key, $options= array()) {
        $value= $this->redis->get($this->getCacheKey($key));
        return $value;
    }

    public function flush($options= array()) {
        return $this->redis->flushDb();
    }
}
