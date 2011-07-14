<?php
/**
 * @package modx
 * @subpackage registry.db
 */
/**
 * Represents a database-based registry message.
 *
 * @param int $topic The topic this message belongs to
 * @param string $id The ID of the message
 * @param datetime $created The time this message was created
 * @param datetime $valid The time this message was validated
 * @param timestamp $accessed The last time this message was accessed
 * @param int $expires The UNIX timestamp when this message will automatically expire
 * @param string $payload The payload of this message
 * @param boolean Whether or not to kill the message
 *
 * @see modDbRegisterQueue
 * @see modDbRegisterTopic
 * 
 * @package modx
 * @subpackage registry.db
 */
class modDbRegisterMessage extends xPDOObject {}