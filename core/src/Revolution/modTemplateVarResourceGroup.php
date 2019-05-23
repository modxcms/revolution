<?php

namespace MODX\Revolution;

use xPDO\Om\xPDOSimpleObject;

/**
 * A relation between Template Variables and Resource Groups. Only user groups with the specified Resource Groups, if
 * any are set, will be able to edit the TV.
 *
 * @property int $tmplvarid     The ID of the related TV
 * @property int $documentgroup The ID of the related Resource Group
 *
 * @package MODX\Revolution
 */
class modTemplateVarResourceGroup extends xPDOSimpleObject
{
}
