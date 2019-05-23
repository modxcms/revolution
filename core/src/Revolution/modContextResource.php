<?php

namespace MODX\Revolution;

use xPDO\Om\xPDOObject;

/**
 * Represents a relationship between a modContext and a modResource.
 *
 * @property string $context_key The key of the Context
 * @property int    $resource    The ID of the related Resource
 *
 * @todo    Work this relationship into use in the manager and the logic of each {@link modResource::process()} implementation.
 *
 * @package MODX\Revolution
 */
class modContextResource extends xPDOObject
{
}
