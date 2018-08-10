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
 * A many-to-one relationship with modFormCustomizationProfile that determines which User Groups will have activated
 * the rules of sets found in the corresponding FC Profile.
 *
 * @property int $usergroup The ID of the modUserGroup object this applies to
 * @property int $profile The ID of the modFormCustomizationProfile object this applies to
 * @see modFormCustomizationProfile
 * @package modx
 */
class modFormCustomizationProfileUserGroup extends xPDOObject {}
