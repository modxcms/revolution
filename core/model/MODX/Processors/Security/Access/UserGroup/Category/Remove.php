<?php

namespace MODX\Processors\Security\Access\UserGroup\Category;

use MODX\Processors\modObjectRemoveProcessor;

/**
 * Remove a Resource Group ACL for a usergroup
 *
 * @param integer $id The ID of the ACL
 *
 * @package modx
 * @subpackage processors.security.access.category
 */
class Remove extends modObjectRemoveProcessor
{
    public $classKey = 'modAccessCategory';
    public $objectType = 'access_category';
    public $languageTopics = ['access'];
    public $permission = 'access_permissions';
}
