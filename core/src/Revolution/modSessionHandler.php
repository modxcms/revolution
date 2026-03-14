<?php
/*
 * This file is part of the MODX Revolution package.
 *
 * Copyright (c) MODX, LLC
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace MODX\Revolution;


/**
 * Default database session handler class for MODX.
 *
 * @package MODX\Revolution
 */
class modSessionHandler implements \SessionHandlerInterface
{
    /**
     * @var modX A reference to the modX instance controlling this session
     * handler.
     * @access public
     */
    public $modx = null;
    /**
     * @var int The maximum lifetime of the session
     */
    public $gcMaxLifetime = 0;
    /**
     * @var int The maximum lifetime of the cache of the session
     */
    public $cacheLifetime = false;
    /**
     * @var modSession The Session object
     */
    private $session = null;

    /**
     * Creates an instance of a modSessionHandler class.
     *
     * @param modX &$modx A reference to a {@link modX} instance.
     */
    public function __construct(modX &$modx)
    {
        $this->modx = &$modx;
        $gcMaxlifetime = (int)$this->modx->getOption('session_gc_maxlifetime');
        if ($gcMaxlifetime > 0) {
            $this->gcMaxLifetime = $gcMaxlifetime;
        } else {
            $this->gcMaxLifetime = (int)@ini_get('session.gc_maxlifetime');
        }
        if ($this->modx->getOption('cache_db_session', null, false)) {
            $cacheLifetime = $this->modx->getOption('cache_db_session_lifetime', null, false);
            if ((int)$cacheLifetime > 0) {
                $this->cacheLifetime = (int)$cacheLifetime;
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
    #[\ReturnTypeWillChange]
    public function open($path, $name)
    {
        $this->tryFallbackGc();
        return true;
    }

    /**
     * Runs session GC periodically when PHP never calls gc()
     * (e.g. session.gc_probability = 0 on Ubuntu/Debian). Prevents the session
     * table from growing indefinitely (see issue #16275, #775).
     */
    protected function tryFallbackGc(): void
    {
        if (!$this->modx->getOption('session_gc_fallback_enabled', null, true)) {
            return;
        }
        $interval = (int)$this->modx->getOption('session_gc_fallback_interval', null, 3600);
        if ($interval <= 0) {
            return;
        }
        $cacheKey = $this->modx->getOption('session_gc_fallback_cache_key', null, 'session_gc_fallback_last');
        $cache = $this->modx->getCacheManager();
        if (!$cache) {
            // When cache is unavailable, fallback GC does not run on this node until cache is available.
            return;
        }
        $lastRun = $cache->get($cacheKey);
        if ($lastRun !== false && (time() - (int)$lastRun) < $interval) {
            return;
        }
        $cache->set($cacheKey, (string)time());
        try {
            $this->gc($this->gcMaxLifetime);
        } catch (\Throwable $e) {
            $this->modx->log(
                modX::LOG_LEVEL_ERROR,
                'Session fallback GC failed: ' . $e->getMessage(),
                '',
                __METHOD__,
                __FILE__,
                __LINE__
            );
        }
    }

    /**
     * Closes the connection for the session handler.
     *
     * @access public
     * @return boolean Always returns true; actual connection is managed by
     * {@link modX}
     */
    #[\ReturnTypeWillChange]
    public function close()
    {
        return true;
    }

    /**
     * Reads a specific {@link modSession} record's data.
     *
     * @access public
     *
     * @param integer $id The pk of the {@link modSession} object.
     *
     * @return string The data read from the {@link modSession} object.
     */
    #[\ReturnTypeWillChange]
    public function read($id)
    {
        if ($this->_getSession($id)) {
            $data = $this->session->get('data');
        } else {
            $data = '';
        }

        return (string)$data;
    }

    /**
     * Writes data to a specific {@link modSession} object.
     *
     * @access public
     *
     * @param integer $id   The PK of the modSession object.
     * @param mixed   $data The data to write to the session.
     *
     * @return boolean True if successfully written.
     */
    #[\ReturnTypeWillChange]
    public function write($id, $data)
    {
        $written = false;
        if ($this->_getSession($id, true)) {
            $this->session->set('data', $data);
            if ($this->session->isNew() || $this->session->isDirty('data') || ($this->cacheLifetime > 0 && (time() - strtotime($this->session->get('access'))) > $this->cacheLifetime)) {
                $this->session->set('access', time());
            }
            $written = $this->session->save($this->cacheLifetime);
        }

        return $written;
    }

    /**
     * Destroy a specific {@link modSession} record.
     *
     * @access public
     *
     * @param integer $id
     *
     * @return boolean True if the session record was destroyed.
     */
    #[\ReturnTypeWillChange]
    public function destroy($id)
    {
        if ($this->_getSession($id)) {
            $destroyed = $this->session->remove();
        } else {
            $destroyed = true;
        }

        return $destroyed;
    }

    /**
     * Remove any expired sessions.
     *
     * @access public
     *
     * @param integer $max The max lifetime in seconds (from PHP session.gc_maxlifetime).
     *                     Used for interface compliance; falls back to gcMaxLifetime if <= 0.
     *
     * @return int|false Number of removed session records, or false on failure.
     */
    #[\ReturnTypeWillChange]
    public function gc($max)
    {
        $lifetime = (int)$max;
        if ($lifetime <= 0) {
            $lifetime = $this->gcMaxLifetime;
        }
        $maxtime = time() - $lifetime;

        $result = $this->modx->removeCollection(modSession::class, ["{$this->modx->escape('access')} < {$maxtime}"]);
        return $result === false ? false : (int)$result;
    }

    /**
     * Removes all sessions, logging out all users.
     *
     * @param modX $modx
     * @return boolean
     */
    public static function flushSessions(modX $modx)
    {
        $sessionTable = $modx->getTableName(modSession::class);
        if ($modx->query("TRUNCATE TABLE {$sessionTable}") == false) {
            return false;
        }

        $modx->user->endSession();
        return true;
    }

    /**
     * Gets the {@link modSession} object, respecting the cache flag represented by cacheLifetime.
     *
     * @access protected
     *
     * @param integer $id         The PK of the {@link modSession} record.
     * @param boolean $autoCreate If true, will automatically create the session
     *                            record if none is found.
     *
     * @return modSession|null The modSession instance loaded from db or auto-created; null if it
     * could not be retrieved and/or created.
     */
    protected function _getSession($id, $autoCreate = false)
    {
        $this->session = $this->modx->getObject(modSession::class, ['id' => $id], $this->cacheLifetime);
        if ($autoCreate && !is_object($this->session)) {
            $this->session = $this->modx->newObject(modSession::class);
            $this->session->set('id', $id);
        }
        if (!($this->session instanceof modSession) || $id != $this->session->get('id') || !$this->session->validate()) {
            if ($this->modx->getSessionState() == modX::SESSION_STATE_INITIALIZED) {
                $this->modx->log(modX::LOG_LEVEL_INFO, 'There was an error retrieving or creating session id: ' . $id);
            }
        }

        return $this->session;
    }
}
