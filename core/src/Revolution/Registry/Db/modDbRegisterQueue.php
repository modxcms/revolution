<?php

namespace MODX\Revolution\Registry\Db;

use xPDO\Om\xPDOSimpleObject;

/**
 * Represents a database-based registry queue.
 *
 * @property string               $name    The key name of the queue
 * @property array                $options An array of configuration options for the queue
 *
 * @property modDbRegisterTopic[] $Topics
 *
 * @package MODX\Revolution\Registry\Db
 */
class modDbRegisterQueue extends xPDOSimpleObject
{
}
