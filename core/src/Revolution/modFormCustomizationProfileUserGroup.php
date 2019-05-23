<?php

namespace MODX\Revolution;

use xPDO\Om\xPDOObject;

/**
 * A many-to-one relationship with modFormCustomizationProfile that determines which User Groups will have activated
 * the rules of sets found in the corresponding FC Profile.
 *
 * @property int $usergroup The ID of the modUserGroup object this applies to
 * @property int $profile   The ID of the modFormCustomizationProfile object this applies to
 *
 * @package MODX\Revolution
 */
class modFormCustomizationProfileUserGroup extends xPDOObject
{
}
