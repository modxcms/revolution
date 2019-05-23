<?php

namespace MODX\Revolution\Registry\Db;

use xPDO\Om\xPDOSimpleObject;

/**
 * A database representation of a registry topic
 *
 * @property string                 $queue   The queue this topic belongs to
 * @property string                 $name    The name of the topic
 * @property string                 $created The time this topic was created
 * @property string                 $updated The last time this topic was updated
 * @property array                  $options An array of configuration options for the topic
 *
 * @property modDbRegisterMessage[] $Messages
 *
 * @package MODX\Revolution\Registry\Db
 */
class modDbRegisterTopic extends xPDOSimpleObject
{
}
