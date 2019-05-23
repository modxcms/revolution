<?php

namespace MODX\Revolution;

use xPDO\Om\xPDOSimpleObject;

/**
 * A grouping class used for classifying what targets Access Policies (and their Templates) should be applied towards.
 *
 * @property string                    $name        The name of the Group
 * @property string                    $description A description of the Group
 *
 * @property modAccessPolicyTemplate[] $Templates
 *
 * @package MODX\Revolution
 */
class modAccessPolicyTemplateGroup extends xPDOSimpleObject
{
}
