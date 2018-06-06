<?php

namespace MODX\Processors\Security\Role;

use MODX\Processors\modObjectGetProcessor;

/**
 * Gets a role
 *
 * @param integer $id The ID of the role
 *
 * @package modx
 * @subpackage processors.security.role
 */
class Get extends modObjectGetProcessor
{
    public $classKey = 'modUserGroupRole';
    public $languageTopics = ['user'];
    public $permission = 'view_role';
    public $objectType = 'role';
}