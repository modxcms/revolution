<?php
/*
 * MODx Revolution  *
 * Copyright 2006, 2007, 2008 by the MODx Team.
 * All rights reserved.
 *
 * This program is free software; you can redistribute it and/or modify it under
 * the terms of the GNU General Public License as published by the Free Software
 * Foundation; either version 2 of the License, or (at your option) any later
 * version.
 *
 * This program is distributed in the hope that it will be useful, but WITHOUT
 * ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS
 * FOR A PARTICULAR PURPOSE. See the GNU General Public License for more
 * details.
 *
 * You should have received a copy of the GNU General Public License along with
 * this program; if not, write to the Free Software Foundation, Inc., 59 Temple
 * Place, Suite 330, Boston, MA 02111-1307 USA
 */

/**
 * Default database session handler class for MODx.
 *
 * @package modx
 */
class modSessionHandler {
    /**
     * @var modX A reference to the modX instance controlling this session
     * handler.
     */
    var $modx= null;

    function modSessionHandler(& $modx) {
        $this->__construct($modx);
    }
    function __construct(& $modx) {
        $this->modx= & $modx;
    }

    /**
     * Opens the connection for the session handler.
     *
     * @return boolean Always returns true; actual connection is managed by
     * {@link modX}.
     */
    function open() {
        return true;
    }

    /**
     * Closes the connection for the session handler.
     *
     * @return boolean Always returns true; actual connection is managed by
     * {@link modX}
     */
    function close() {
        return true;
    }

    function read($id) {
        $data= '';
        if ($session= $this->_getSession($id)) {
            $data= $session->get('data');
        } else {
            $data= '';
        }
        return (string) $data;
    }

    function write($id, $data) {
        $written= false;
        $gcMaxlifetime = $this->modx->getOption('session_gc_maxlifetime', array(), @ini_get('session.gc_max_lifetime'));
        $cacheLifetime = $this->modx->getOption('cache_db_session_lifetime', array(), intval($gcMaxlifetime / 4));
        if (!$session= $this->modx->getObject('modSession', array ('id' => $id), $cacheLifetime)) {
            $session= $this->modx->newObject('modSession');
            $session->set('id', $id);
            $session->set('access', time());
        }
        $session->set('data', $data);
        if ($session->isDirty('data') || (time() - $session->get('access', '%s')) > $cacheLifetime) {
            $session->set('access', time());
        }
        $written= $session->save();
        return $written;
    }

    function destroy($id) {
        $destroyed= false;
        if ($session= $this->_getSession($id)) {
            $destroyed= $session->remove();
        } else {
            $destroyed= true;
        }
        return $destroyed;
    }

    function gc($max) {
        $max = (integer) $this->modx->getOption('session_gc_maxlifetime', array(), $max);
        $maxtime= time() - $max;
        $result = $this->modx->removeCollection('modSession', array("`access` < {$maxtime}"));
        return $result;
    }

    function _getSession($id, $autoCreate= false) {
        $session= $this->modx->getObject('modSession', array('id' => $id), $this->modx->getOption('cache_db_session', array(), false));
        if ($autoCreate && !is_object($session)) {
            $session= $this->modx->newObject('modSession');
            $session->set('id', $id);
        }
        if (!is_object($session) || $id != $session->get('id')) {
            $this->modx->log(XPDO_LOG_LEVEL_INFO, 'There was an error retrieving or creating session id: ' . $id);
        }
        return $session;
    }
}
