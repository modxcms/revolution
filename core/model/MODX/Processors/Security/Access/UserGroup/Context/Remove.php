<?php

namespace MODX\Processors\Security\Access\UserGroup\Context;

use MODX\Processors\modObjectRemoveProcessor;

/**
 * Remove a context ACL for a usergroup
 *
 * @param integer $id The ID of the ACL
 *
 * @package modx
 * @subpackage processors.security.access.usergroup
 */
class Remove extends modObjectRemoveProcessor
{
    public $classKey = 'modAccessContext';
    public $objectType = 'access_context';
    public $languageTopics = ['access'];
    public $permission = 'access_permissions';
}
