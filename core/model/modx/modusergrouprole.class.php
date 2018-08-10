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
 * Represents a Role that a User can have within a specific User Group. Roles are sorted into authority levels, where
 * lower authority numbers will automatically inherit Permissions owned by higher authority numbers.
 *
 * For example, an Administrator with authority of 1 will automatically inherit any Permissions assigned to a Member
 * role with authority 9999, since 1 is less than 9999. However, the reverse will not be true.
 *
 * @property string $name The name of the Role
 * @property string $description A user-provided description of this Role
 * @property int $authority The authority of the role. Lower authority numbers have more power than higher ones, and
 * lower numbers will inherit the Permissions of higher numbers.
 *
 * @see modUser
 * @see modUserGroup
 * @package modx
 */
class modUserGroupRole extends xPDOSimpleObject {}
