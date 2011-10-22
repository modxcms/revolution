<?php
/**
 * @package modx
 * @subpackage registry.db
 */
/**
 * A database representation of a registry topic
 *
 * @property string $queue The queue this topic belongs to
 * @property string $name The name of the topic
 * @property datetime $created The time this topic was created
 * @property timestamp $updated The last time this topic was updated
 * @property array $options An array of configuration options for the topic
 *
 * @see modDbRegisterQueue
 * @see modDbRegisterMessage
 * @package modx
 * @subpackage registry.db
 */
class modDbRegisterTopic extends xPDOSimpleObject {}