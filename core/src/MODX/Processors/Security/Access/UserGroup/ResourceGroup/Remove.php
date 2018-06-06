<?php

namespace MODX\Processors\Security\Access\UserGroup\ResourceGroup;

use MODX\Processors\modObjectRemoveProcessor;

/**
 * Remove a Resource Group ACL for a usergroup
 *
 * @param integer $id The ID of the ACL
 *
 * @package modx
 * @subpackage processors.security.access.resourcegroup
 */
class Remove extends modObjectRemoveProcessor
{
    public $classKey = 'modAccessResourceGroup';
    public $objectType = 'access_rgroup';
    public $languageTopics = ['access'];
    public $permission = 'access_permissions';
}