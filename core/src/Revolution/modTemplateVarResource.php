<?php

namespace MODX\Revolution;

use xPDO\Om\xPDOSimpleObject;

/**
 * Stores the value of a TV for a specific Resource
 *
 * @property int    $tmplvarid The ID of the related TV
 * @property int    $contentid The ID of the related Resource
 * @property string $value     The stored value of the TV for the Resource
 *
 * @package MODX\Revolution
 */
class modTemplateVarResource extends xPDOSimpleObject
{
}
