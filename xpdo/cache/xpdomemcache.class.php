<?php
/*
 * Copyright 2010-2013 by MODX, LLC.
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
 * Provides a memcache-powered xPDOCache implementation.
 *
 * This requires the memcache extension for PHP.
 *
 * @package xpdo
 * @subpackage cache
 */
class xPDOMemCache extends xPDOCache {
    protected $memcache = null;

    public function __construct(& $xpdo, $options = array()) {
        parent :: __construct($xpdo, $options);
        if (class_exists('Memcache', true)) {
            $this->memcache= new Memcache();
            if ($this->memcache) {
                $servers = explode(',', $this->getOption($this->key . '_memcached_server', $options, $this->getOption('memcached_server', $options, 'localhost:11211')));
                foreach ($servers as $server) {
                    $server = explode(':', $server);
                    $this->memcache->addServer($server[0], (integer) $server[1]);
                }
                $compressThreshold = $this->getOption($this->key . '_memcached_compress_threshold', $options, $this->getOption('memcached_compress_threshold', array(), '20000:0.2'));
                if (!empty($compressThreshold)) {
                    $threshold = explode(':', $compressThreshold);
                    if (count($threshold) == 2) {
                        $minValue = (integer) $threshold[0];
                        $minSaving = (float) $threshold[1];
                        if ($minSaving >= 0 && $minSaving <= 1) {
                            $this->memcache->setCompressThreshold($minValue, $minSaving);
                        }
                    }
                }
                $this->initialized = true;
            } else {
                $this->memcache = null;
                $this->xpdo->log(xPDO::LOG_LEVEL_ERROR, "xPDOMemCache[{$this->key}]: Error creating memcache provider for server(s): " . $this->getOption($this->key . '_memcached_server', $options, $this->getOption('memcached_server', $options, 'localhost:11211')));
            }
        } else {
            $this->xpdo->log(xPDO::LOG_LEVEL_ERROR, "xPDOMemCache[{$this->key}]: Error creating memcache provider; xPDOMemCache requires the PHP memcache extension.");
        }
    }

    public function add($key, $var, $expire= 0, $options= array()) {
        $added= $this->memcache->add(
            $this->getCacheKey($key),
            $var,
            $this->getOption($this->key . xPDO::OPT_CACHE_COMPRESS, $options, $this->getOption(xPDO::OPT_CACHE_COMPRESS, $options, false)),
            $expire
        );
        return $added;
    }

    public function set($key, $var, $expire= 0, $options= array()) {
        $set= $this->memcache->set(
            $this->getCacheKey($key),
            $var,
            $this->getOption($this->key . xPDO::OPT_CACHE_COMPRESS, $options, $this->getOption(xPDO::OPT_CACHE_COMPRESS, $options, false)),
            $expire
        );
        return $set;
    }

    public function replace($key, $var, $expire= 0, $options= array()) {
        $replaced= $this->memcache->replace(
            $this->getCacheKey($key),
            $var,
            $this->getOption($this->key . xPDO::OPT_CACHE_COMPRESS, $options, $this->getOption(xPDO::OPT_CACHE_COMPRESS, $options, false)),
            $expire
        );
        return $replaced;
    }

    public function delete($key, $options= array()) {
        if (!isset($options['multiple_object_delete']) || empty($options['multiple_object_delete'])) {
            $deleted= $this->memcache->delete($this->getCacheKey($key));
        } else {
            $deleted= $this->flush($options);
        }
        return $deleted;
    }

    public function get($key, $options= array()) {
        $value= $this->memcache->get($this->getCacheKey($key));
        return $value;
    }

    public function flush($options= array()) {
        return $this->memcache->flush();
    }
}