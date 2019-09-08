<?php

namespace MODX\Revolution;

use xPDO\Om\xPDOSimpleObject;

/**
 * A collection of modFormCustomizationSet objects that can be restricted to certain User Groups.
 *
 * @property string                                 $name        The name of the Profile
 * @property string                                 $description A user-provided description of this Profile
 * @property boolean                                $active      Whether or not this Profile's Sets and Rules will be applied
 * @property int                                    $rank        The order in which the Profile will be executed relative to other Profiles
 *
 * @property modFormCustomizationSet[]              $Sets
 * @property modFormCustomizationProfileUserGroup[] $UserGroups
 *
 * @package MODX\Revolution
 */
class modFormCustomizationProfile extends xPDOSimpleObject
{
}
