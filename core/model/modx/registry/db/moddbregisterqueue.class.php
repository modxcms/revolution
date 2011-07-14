<?php
/**
 * @package modx
 * @subpackage registry.db
 */
/**
 * Represents a database-based registry queue.
 *
 * @property string $name The key name of the queue
 * @property array $options An array of configuration options for the queue
 *
 * @see modDbRegisterTopic
 * @see modDbRegisterMessage
 *
 * @package modx
 * @subpackage registry.db
 */
class modDbRegisterQueue extends xPDOSimpleObject {}