<?php
/*
 * This file is part of MODX Revolution.
 *
 * Copyright (c) MODX, LLC. All Rights Reserved.
 *
 * For complete copyright and license information, see the COPYRIGHT and LICENSE
 * files found in the top-level directory of this distribution.
 */

/**
 * A collection of modFormCustomizationSet objects that can be restricted to certain User Groups.
 *
 * @property string $name The name of the Profile
 * @property string $description A user-provided description of this Profile
 * @property boolean $active Whether or not this Profile's Sets and Rules will be applied
 * @property int $rank The order in which the Profile will be executed relative to other Profiles
 * @see modFormCustomizationSet
 * @see modFormCustomizationProfileUserGroup
 * @package modx
 */
class modFormCustomizationProfile extends xPDOSimpleObject {}
