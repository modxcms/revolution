<?php
/*
 * This file is part of MODX Revolution.
 *
 * Copyright (c) MODX, LLC. All Rights Reserved.
 *
 * For complete copyright and license information, see the COPYRIGHT and LICENSE
 * files found in the top-level directory of this distribution.
 */

namespace MODX\Revolution\Processors\Security\Role;


use MODX\Revolution\Processors\Model\GetProcessor;
use MODX\Revolution\modUserGroupRole;

/**
 * Gets a role
 * @param integer $id The ID of the role
 * @package MODX\Revolution\Processors\Security\Role
 */
class Get extends GetProcessor
{
    public $classKey = modUserGroupRole::class;
    public $languageTopics = ['user'];
    public $permission = 'view_role';
    public $objectType = 'role';
}

