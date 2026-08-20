<?php

/*
 * This file is part of MODX Revolution.
 *
 * Copyright (c) MODX, LLC. All Rights Reserved.
 *
 * For complete copyright and license information, see the COPYRIGHT and LICENSE
 * files found in the top-level directory of this distribution.
 */

namespace MODX\Revolution\Processors\Security\Access\UserGroup\ContextGroup;

use MODX\Revolution\modAccessContextGroup;
use MODX\Revolution\Processors\Model\UpdateProcessor;

/**
 * Update a Context Group ACL for a User Group.
 *
 * @package MODX\Revolution\Processors\Security\Access\UserGroup\ContextGroup
 */
class Update extends UpdateProcessor
{
    use ContextGroupAclProcessorTrait;

    public $classKey = modAccessContextGroup::class;
    public $objectType = 'access_contextgroup';
    public $languageTopics = ['access', 'user', 'context'];
    public $permission = 'access_permissions';

    public function beforeSet()
    {
        $this->validateContextGroupAclFields();

        return parent::beforeSet();
    }

    public function beforeSave()
    {
        $this->validateContextGroupAclReferences();
        $this->validateContextGroupAclUnique((int)$this->object->get($this->primaryKeyField));
        $this->setContextGroupPrincipalClass();

        return parent::beforeSave();
    }

    public function afterSave()
    {
        $this->ensureAdministratorGroupAcl();
        $this->flushPermissionsCache();

        return parent::afterSave();
    }
}
