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
 * Provides a memcached-powered xPDOCache implementation.
 *
 * This requires the memcached extension for PHP.
 *
 * @package xpdo
 * @subpackage cache
 */
class xPDOMemCached extends xPDOCache {
    protected $memcached = null;

    public function __construct(& $xpdo, $options = array()) {
        parent :: __construct($xpdo, $options);
        if (class_exists('Memcached', true)) {
            $this->memcached = new Memcached();
            if ($this->memcached) {
                $servers = explode(',', $this->getOption($this->key . '_memcached_server', $options, $this->getOption('memcached_server', $options, 'localhost:11211')));
                foreach ($servers as $server) {
                    $server = explode(':', $server);
                    $this->memcached->addServer($server[0], (integer) $server[1]);
                }
                $this->memcached->setOption(Memcached::OPT_COMPRESSION, (boolean) $this->getOption($this->key . '_memcached_compression', $options, $this->getOption('memcached_compression', $options, $this->getOption(Memcached::OPT_COMPRESSION, $options, true))));
                $this->initialized = true;
            } else {
                $this->memcached = null;
                $this->xpdo->log(xPDO::LOG_LEVEL_ERROR, "xPDOMemCached[{$this->key}]: Error creating memcached provider for server(s): " . $this->getOption($this->key . '_memcached_server', $options, $this->getOption('memcached_server', $options, 'localhost:11211')));
            }
        } else {
            $this->xpdo->log(xPDO::LOG_LEVEL_ERROR, "xPDOMemCached[{$this->key}]: Error creating memcached provider; xPDOMemCached requires the PHP memcached extension.");
        }
    }

    public function add($key, $var, $expire= 0, $options= array()) {
        $added= $this->memcached->add(
            $this->getCacheKey($key),
            $var,
            $expire
        );
        return $added;
    }

    public function set($key, $var, $expire= 0, $options= array()) {
        $set= $this->memcached->set(
            $this->getCacheKey($key),
            $var,
            $expire
        );
        return $set;
    }

    public function replace($key, $var, $expire= 0, $options= array()) {
        $replaced= $this->memcached->replace(
            $this->getCacheKey($key),
            $var,
            $expire
        );
        return $replaced;
    }

    public function delete($key, $options= array()) {
        if (!isset($options['multiple_object_delete']) || empty($options['multiple_object_delete'])) {
            $deleted= $this->memcached->delete($this->getCacheKey($key));
        } else {
            $deleted= $this->flush($options);
        }
        return $deleted;
    }

    public function get($key, $options= array()) {
        return $this->memcached->get($this->getCacheKey($key));
    }

    public function flush($options= array()) {
        return $this->memcached->flush();
    }
}
