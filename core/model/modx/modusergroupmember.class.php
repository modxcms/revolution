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
 * Represents a user membership in a user group.
 *
 * @property int $user_group The ID of the related User Group
 * @property int $member The ID of the related User
 * @property int $role The ID of the Role the User has for this User Group
 * @property int $rank Used when sorting memberships within a User Group
 *
 * @property modUser $User
 * @property modUserGroup $UserGroup
 * @property modUserGroupRole $UserGroupRole
 *
 * @see modUser
 * @see modUserGroup
 * @see modUserGroupRole
 * @package modx
 */
class modUserGroupMember extends xPDOSimpleObject {}
