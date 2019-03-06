<?php

namespace MODX\Revolution;

use xPDO\Om\xPDOObject;

/**
 * Closure tables used for grabbing modCategory trees in one query.
 *
 * @property int $ancestor   The ancestor of this closure record
 * @property int $descendant The descendant of this closure record
 * @property int $depth      The depth this closure rests at
 *
 * @package MODX\Revolution
 */
class modCategoryClosure extends xPDOObject
{
}
