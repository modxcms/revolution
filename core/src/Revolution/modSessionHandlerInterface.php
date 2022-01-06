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
interface modSessionHandlerInterface
{
    function __construct(modX &$modx);

    /**
     * Opens the connection for the session handler.
     *
     * @access public
     * @return boolean Always returns true; actual connection is managed by
     * {@link modX}.
     */
    public function open();

    /**
     * Closes the connection for the session handler.
     *
     * @access public
     * @return boolean Always returns true; actual connection is managed by
     * {@link modX}
     */
    public function close();

    /**
     * Reads a specific {@link modSession} record's data.
     *
     * @access public
     *
     * @param integer $id The pk of the {@link modSession} object.
     *
     * @return string The data read from the {@link modSession} object.
     */
    public function read($id);

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
    public function write($id, $data);

    /**
     * Destroy a specific {@link modSession} record.
     *
     * @access public
     *
     * @param integer $id
     *
     * @return boolean True if the session record was destroyed.
     */
    public function destroy($id);

    /**
     * Remove any expired sessions.
     *
     * @access public
     *
     * @param integer $max The amount of time since now to expire any session
     *                     longer than.
     *
     * @return boolean True if session records were removed.
     */
    public function gc($max);

    /**
     * Removes all sessions.
     *
     * @param modX $modx
     *
     * @access public
     *
     * @return boolean
     */
    public static function flushSessions(modX &$modx);
}
