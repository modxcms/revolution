<?php

namespace MODX\Processors\Security\Access\UserGroup\Source;

use MODX\Processors\modObjectRemoveProcessor;

/**
 * Remove a Media Source ACL for a usergroup
 *
 * @param integer $id The ID of the ACL
 *
 * @package modx
 * @subpackage processors.security.access.source
 */
class Remove extends modObjectRemoveProcessor
{
    public $classKey = 'sources.modAccessMediaSource';
    public $languageTopics = ['source', 'access', 'user'];
    public $permission = 'access_permissions';
    public $objectType = 'access_source';
}