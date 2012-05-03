<?php
/**
 * MODX Revolution
 * Copyright 2006-2012 by MODX, LLC.
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
 *
 * @package modx
 */
/**
 * Default database session handler class for MODX.
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
     * @var int The maximum lifetime of the session
     */
    public $gcMaxLifetime= 0;
    /**
     * @var int The maximum lifetime of the cache of the session
     */
    public $cacheLifetime= false;
    /**
     * @var modSession The Session object
     */
    private $session = null;

    /**
     * Creates an instance of a modSessionHandler class.
     *
     * @param modX &$modx A reference to a {@link modX} instance.
     * @return modSessionHandler
     */
    function __construct(modX &$modx) {
        $this->modx= & $modx;
        $gcMaxlifetime = (integer) $this->modx->getOption('session_gc_maxlifetime');
        if ($gcMaxlifetime > 0) {
            $this->gcMaxLifetime= $gcMaxlifetime;
        } else {
            $this->gcMaxLifetime= (integer) @ini_get('session.gc_maxlifetime');
        }
        if ($this->modx->getOption('cache_db_session', null, false)) {
            $cacheLifetime = $this->modx->getOption('cache_db_session_lifetime', null, false);
            if ((integer) $cacheLifetime > 0) {
                $this->cacheLifetime = (integer) $cacheLifetime;
            } elseif ($cacheLifetime !== false && $this->gcMaxLifetime > 0) {
                $this->cacheLifetime = $this->gcMaxLifetime / 4;
            }
        }
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
        if ($this->_getSession($id)) {
            $data= $this->session->get('data');
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
        if ($this->_getSession($id, true)) {
            $this->session->set('data', $data);
            if ($this->session->isNew() || $this->session->isDirty('data') || ($this->cacheLifetime > 0 && (time() - strtotime($this->session->get('access'))) > $this->cacheLifetime)) {
                $this->session->set('access', time());
            }
            $written= $this->session->save($this->cacheLifetime);
        }
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
        if ($this->_getSession($id)) {
            $destroyed= $this->session->remove();
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
        $maxtime= time() - $this->gcMaxLifetime;
        $result = $this->modx->removeCollection('modSession', array("{$this->modx->escape('access')} < {$maxtime}"));
        return $result;
    }

    /**
     * Gets the {@link modSession} object, respecting the cache flag represented by cacheLifetime.
     *
     * @access protected
     * @param integer $id The PK of the {@link modSession} record.
     * @param boolean $autoCreate If true, will automatically create the session
     * record if none is found.
     * @return modSession|null The modSession instance loaded from db or auto-created; null if it
     * could not be retrieved and/or created.
     */
    protected function _getSession($id, $autoCreate= false) {
        $this->session= $this->modx->getObject('modSession', $id, $this->cacheLifetime);
        if ($autoCreate && !is_object($this->session)) {
            $this->session= $this->modx->newObject('modSession');
            $this->session->set('id', $id);
        }
        if (!($this->session instanceof modSession) || $id != $this->session->get('id')) {
            $this->modx->log(modX::LOG_LEVEL_INFO, 'There was an error retrieving or creating session id: ' . $id);
        }
        return $this->session;
    }
}
