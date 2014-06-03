<?php
/**
 * MODX Revolution
 * Copyright 2006-2014 by MODX, LLC.
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
 * Represents a client session managed by MODX.
 *
 * @property string $id The PHP session ID
 * @property int $access The last time this session was accessed
 * @property string $data The serialized data of this session
 * @see modSessionHandler
 * @package modx
 * @extends xPDOObject
 */
class modSession extends xPDOObject
{
    /**
     * Wipe the whole session without logging out the related user
     */
    public function refresh()
    {
        // Retrieve stored session data
        $old = $this->readData();

        $data = array();
        // Keep only context sessions
        $data['modx.user.contextTokens'] = $old['modx.user.contextTokens'];

        $this->setData($data);
        $this->save();
        //$this->xpdo->log(modX::LOG_LEVEL_INFO, 'modSession refreshed : '. print_r($data, true));
    }

    /**
     * Read this session data
     *
     * @return mixed|Array
     */
    public function readData()
    {
        // Copy my session data
        $mySession = $_SESSION;
        // Decode this session
        session_decode($this->data);
        // Store its data
        $sessionData = $_SESSION;
        // Restore my session data
        $_SESSION = $mySession;

        return $sessionData;
    }

    /**
     * Encode the given session data array
     *
     * @param array $data
     */
    public function setData(array $data = array())
    {
        // Copy my session data
        $mySession = $_SESSION;
        $_SESSION = $data;
        // Encode this session
        $encoded = session_encode();
        // Restore my session data
        $_SESSION = $mySession;

        $this->set('data', $encoded);
    }

    /**
     * @return null|modUser
     */
    public function getUser()
    {
        /** @var modUserProfile $profile */
        $profile = $this->xpdo->getObjectGraph(
            'modUserProfile',
            array(
                'User' => array()
            ),
            array(
                'sessionid' => $this->id,
            )
        );

        if ($profile && $profile->User) {
            return $profile->User;
        }
    }
}
