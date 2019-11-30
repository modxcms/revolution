<?php
/*
 * This file is part of MODX Revolution.
 *
 * Copyright (c) MODX, LLC. All Rights Reserved.
 *
 * For complete copyright and license information, see the COPYRIGHT and LICENSE
 * files found in the top-level directory of this distribution.
 */

namespace MODX\Revolution\Processors\Security\Access\UserGroup\Category;

use MODX\Revolution\modAccessCategory;
use MODX\Revolution\Processors\Model\RemoveProcessor;

/**
 * Remove a Resource Group ACL for a user group
 * @param integer $id The ID of the ACL
 * @package MODX\Revolution\Processors\Security\Access\UserGroup\Category
 */
class Remove extends RemoveProcessor
{
    public $classKey = modAccessCategory::class;
    public $objectType = 'access_category';
    public $languageTopics = ['access'];
    public $permission = 'access_permissions';
}
