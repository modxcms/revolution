<?php
/**
 * @package modx
 */
/**
 * Represents a user message.
 *
 * @property string $type The type of message.
 * @property string $subject The subject of the message
 * @property string $message The body text of the message
 * @property int $sender The ID of the User that sent this message
 * @property int $recipient The ID of the User that was the recipient of the message
 * @property boolean $private Whether or not this message is private. Deprecated.
 * @property datetime $date_sent The date and time this message was sent
 * @property boolean $read Whether or not this message has been read by the User
 *
 * @see modUser
 * @package modx
 */
class modUserMessage extends xPDOSimpleObject {}
