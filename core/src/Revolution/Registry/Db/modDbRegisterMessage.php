<?php

namespace MODX\Revolution\Registry\Db;

use xPDO\Om\xPDOObject;

/**
 * Represents a database-based registry message.
 *
 * @param int     $topic    The topic this message belongs to
 * @param string  $id       The ID of the message
 * @param string  $created  The time this message was created
 * @param string  $valid    The time this message was validated
 * @param int     $accessed The UNIX timestamp when this message was accessed
 * @param int     $expires  The UNIX timestamp when this message will automatically expire
 * @param string  $payload  The payload of this message
 * @param boolean $kill     Whether or not to kill the message
 *
 * @package MODX\Revolution\Registry\Db
 */
class modDbRegisterMessage extends xPDOObject
{
}
