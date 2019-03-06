<?php

namespace MODX\Revolution;

use xPDO\Om\xPDOSimpleObject;

/**
 * A many-to-many relationship between Resources and Resource Groups.
 *
 * @property int $document_group The ID of the Resource Group
 * @property int $document       The ID of the Resource
 *
 * @package MODX\Revolution
 */
class modResourceGroupResource extends xPDOSimpleObject
{
}
