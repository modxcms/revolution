<?php

namespace MODX\Registry\Db;

use xPDO\Om\xPDOSimpleObject;

/**
 * A database representation of a registry topic
 *
 * @property string $queue The queue this topic belongs to
 * @property string $name The name of the topic
 * @property string $created The time this topic was created
 * @property string $updated The last time this topic was updated
 * @property array $options An array of configuration options for the topic
 *
 * @see modDbRegisterQueue
 * @see modDbRegisterMessage
 * @package modx
 * @subpackage registry.db
 */
class modDbRegisterTopic extends xPDOSimpleObject
{
}
