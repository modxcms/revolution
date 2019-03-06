<?php

namespace MODX\Revolution;

use xPDO\Om\xPDOObject;

/**
 * Represents a relationship between a template and a template variable. All TVs can be assigned to show on specified
 * Templates.
 *
 * @property int $tmplvarid  The ID of the related TV
 * @property int $templateid The ID of the related Template
 * @property int $rank       The rank that this TV will show in relation to other TVs assigned to this Template
 *
 * @package MODX\Revolution
 */
class modTemplateVarTemplate extends xPDOObject
{
}
