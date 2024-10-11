<?php

namespace MODX\Revolution;

use xPDO\Om\xPDOSimpleObject;

/**
 * Represents a Role that a User can have within a specific User Group. Roles are sorted into authority levels, where
 * lower authority numbers will automatically inherit Permissions owned by higher authority numbers.
 *
 * For example, an Administrator with authority of 1 will automatically inherit any Permissions assigned to a Member
 * role with authority 9999, since 1 is less than 9999. However, the reverse will not be true.
 *
 * @property string $name        The name of the Role
 * @property string $description A user-provided description of this Role
 * @property int    $authority   The authority of the role. Lower authority numbers have more power
 * than higher ones, and lower numbers will inherit the Permissions of higher numbers.
 *
 * @package MODX\Revolution
 */
class modUserGroupRole extends xPDOSimpleObject
{
    public const ROLE_SUPERUSER = 'Super User';
    public const ROLE_MEMBER = 'Member';

    /**
     * Returns a list of core Roles
     *
     * @return array
     */
    public static function getCoreRoles()
    {
        return [
            self::ROLE_SUPERUSER,
            self::ROLE_MEMBER
        ];
    }

    /**
     * @param string $name The name of the Role
     *
     * @return bool
     */
    public function isCoreRole($name)
    {
        return in_array($name, static::getCoreRoles(), true);
    }
}
