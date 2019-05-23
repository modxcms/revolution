<?php

namespace MODX\Revolution;

use xPDO\Om\xPDOObject;

/**
 * Represents a client session managed by MODX.
 *
 * @property string $id     The PHP session ID
 * @property int    $access The last time this session was accessed
 * @property string $data   The serialized data of this session
 *
 * @see     modSessionHandler
 *
 * @package MODX\Revolution
 */
class modSession extends xPDOObject
{
}
