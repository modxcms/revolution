<?php
/*
 * MODx Revolution
 * Copyright 2006-2010 by the MODx Team.
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
     * @access public
     */
    public $modx= null;

    /**
     * Creates an instance of a modSessionHandler class.
     *
     * @param modX &$modx A reference to a {@link modX} instance.
     * @return modSessionHandler
     */
    function __construct(modX &$modx) {
        $this->modx= & $modx;
    }

    /**
     * Opens the connection for the session handler.
     *
     * @access public
     * @return boolean Always returns true; actual connection is managed by
     * {@link modX}.
     */
    public function open() {
        return true;
    }

    /**
     * Closes the connection for the session handler.
     *
     * @access public
     * @return boolean Always returns true; actual connection is managed by
     * {@link modX}
     */
    public function close() {
        return true;
    }

    /**
     * Reads a specific {@link modSession} record's data.
     *
     * @access public
     * @param integer $id The pk of the {@link modSession} object.
     * @return string The data read from the {@link modSession} object.
     */
    public function read($id) {
        $data= '';
        if ($session= $this->_getSession($id)) {
            $data= $session->get('data');
        } else {
            $data= '';
        }
        return (string) $data;
    }

    /**
     * Writes data to a specific {@link modSession} object.
     *
     * @access public
     * @param integer $id The PK of the modSession object.
     * @param mixed $data The data to write to the session.
     * @return boolean True if successfully written.
     */
    public function write($id, $data) {
        $written= false;
        $gcMaxlifetime = $this->modx->getOption('session_gc_maxlifetime', array(), @ini_get('session.gc_max_lifetime'));
        $cacheLifetime = $this->modx->getOption('cache_db_session_lifetime', array(), intval($gcMaxlifetime / 4));
        if (!$session= $this->modx->getObject('modSession', array ('id' => $id), $cacheLifetime)) {
            $session= $this->modx->newObject('modSession');
            $session->set('id', $id);
            $session->set('access', time());
        }
        $session->set('data', $data);
        if ($session->isDirty('data') || (time() - strtotime($session->get('access'))) > $cacheLifetime) {
            $session->set('access', time());
        }
        $written= $session->save();
        return $written;
    }

    /**
     * Destroy a specific {@link modSession} record.
     *
     * @access public
     * @param integer $id
     * @return boolean True if the session record was destroyed.
     */
    public function destroy($id) {
        $destroyed= false;
        if ($session= $this->_getSession($id)) {
            $destroyed= $session->remove();
        } else {
            $destroyed= true;
        }
        return $destroyed;
    }

    /**
     * Remove any expired sessions.
     *
     * @access public
     * @param integer $max The amount of time since now to expire any session
     * longer than.
     * @return boolean True if session records were removed.
     */
    public function gc($max) {
        $max = (integer) $this->modx->getOption('session_gc_maxlifetime', array(), $max);
        $maxtime= time() - $max;
        $result = $this->modx->removeCollection('modSession', array("`access` < {$maxtime}"));
        return $result;
    }

    /**
     * Gets the {@link modSession} object, respecting cache values by the
     * cache_db_session value.
     *
     * @access protected
     * @param integer $id The PK of the {@link modSession} record.
     * @param boolean $autoCreate If true, will automatically create the session
     * record if none is found.
     * @return modSession The {@link modSession} instance related to the passed
     * ID.
     */
    protected function _getSession($id, $autoCreate= false) {
        $session= $this->modx->getObject('modSession', array('id' => $id), $this->modx->getOption('cache_db_session', array(), false));
        if ($autoCreate && !is_object($session)) {
            $session= $this->modx->newObject('modSession');
            $session->set('id', $id);
        }
        if (!is_object($session) || $id != $session->get('id')) {
            $this->modx->log(modX::LOG_LEVEL_INFO, 'There was an error retrieving or creating session id: ' . $id);
        }
        return $session;
    }
}
