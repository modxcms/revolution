<?php

namespace MODX\Revolution;

use xPDO\Om\xPDOSimpleObject;

/**
 * A collection of modAccessPermission records that are used as a Template for custom modAccessPolicy objects. Is
 * grouped into Access Policy Template Groups to provide targeted policy access implementations.
 *
 * @property int                   $template_group The group that this template is a part of, used for targeting usage of applied Policies
 * @property string                $name           The name of the Policy Template
 * @property string                $description    A description of the Policy Template
 * @property string                $lexicon        Optional. A lexicon that may be loaded to provide translations for all included Permissions
 *
 * @property modAccessPermission[] $Permissions
 * @property modAccessPolicy[]     $Policies
 *
 * @package MODX\Revolution
 */
class modAccessPolicyTemplate extends xPDOSimpleObject
{
}
