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
 * Provides a wincache-powered xPDOCache implementation.
 *
 * This requires the wincache extension for PHP, version 1.1.0 or later. Earlier versions
 * did not have user cache methods.
 *
 * @package xpdo
 * @subpackage cache
 */
class xPDOWinCache extends xPDOCache {
    public function __construct(& $xpdo, $options = array()) {
        parent :: __construct($xpdo, $options);
        if (function_exists('wincache_ucache_info')) {
            $this->initialized = true;
        } else {
            $this->xpdo->log(xPDO::LOG_LEVEL_ERROR, "xPDOWinCache[{$this->key}]: Error creating wincache provider; xPDOWinCache requires the PHP wincache extension, version 1.1.0 or later.");
        }
    }

    public function add($key, $var, $expire= 0, $options= array()) {
        $added= wincache_ucache_add(
            $this->getCacheKey($key),
            $var,
            $expire
        );
        return $added;
    }

    public function set($key, $var, $expire= 0, $options= array()) {
        $set= wincache_ucache_set(
            $this->getCacheKey($key),
            $var,
            $expire
        );
        return $set;
    }

    public function replace($key, $var, $expire= 0, $options= array()) {
        $replaced = false;
        if (wincache_ucache_exists($key)) {
            $replaced= wincache_ucache_set(
                $this->getCacheKey($key),
                $var,
                $expire
            );
        }
        return $replaced;
    }

    public function delete($key, $options= array()) {
        $deleted = false;
        if (!isset($options['multiple_object_delete']) || empty($options['multiple_object_delete'])) {
            $deleted= wincache_ucache_delete($this->getCacheKey($key));
        } else {
            $deleted= $this->flush($options);
        }
        return $deleted;
    }

    public function get($key, $options= array()) {
        $value= wincache_ucache_get($this->getCacheKey($key));
        return $value;
    }

    public function flush($options= array()) {
        return wincache_ucache_clear();
    }
}
