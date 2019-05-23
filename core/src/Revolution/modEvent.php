<?php

namespace MODX\Revolution;

use xPDO\Om\xPDOObject;

/**
 * Represents a system or user-defined event that can be invoked.
 *
 * @property string  $name      The name of the Event
 * @property boolean $service   Whether or not this is a service event
 * @property string  $groupname The group of the event
 *
 * @package MODX\Revolution
 */
class modEvent extends xPDOObject
{
}
