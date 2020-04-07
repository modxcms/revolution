<?php
/*
 * This file is part of MODX Revolution.
 *
 * Copyright (c) MODX, LLC. All Rights Reserved.
 *
 * For complete copyright and license information, see the COPYRIGHT and LICENSE
 * files found in the top-level directory of this distribution.
 */

namespace MODX\Revolution\Processors\Security\Access\UserGroup\Source;

use MODX\Revolution\Processors\Model\RemoveProcessor;
use MODX\Revolution\Sources\modAccessMediaSource;

/**
 * Remove a Media Source ACL for a user group
 * @param integer $id The ID of the ACL
 * @package MODX\Revolution\Processors\Security\Access\UserGroup\Source
 */
class Remove extends RemoveProcessor
{
    public $classKey = modAccessMediaSource::class;
    public $languageTopics = ['source', 'access', 'user'];
    public $permission = 'access_permissions';
    public $objectType = 'access_source';
}
