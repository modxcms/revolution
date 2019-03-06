<?php

namespace MODX\Revolution;

use xPDO\Om\xPDOSimpleObject;

/**
 * Stores records of all manager actions.
 *
 * @property int    $user     The user ID of the actor
 * @property string $occurred The time the action occurred.
 * @property string $action   The action key that occurred.
 * @property string $classKey The class name of the object that was acted upon.
 * @property string $item     The PK of the class type defined by classKey that was acted upon.
 *
 * @package MODX\Revolution
 */
class modManagerLog extends xPDOSimpleObject
{
}
