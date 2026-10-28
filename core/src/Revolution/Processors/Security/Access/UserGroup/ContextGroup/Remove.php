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
use MODX\Revolution\modContext;
use MODX\Revolution\Processors\Model\RemoveProcessor;
use MODX\Revolution\modUserGroup;

/**
 * Remove a Context Group ACL for a User Group.
 *
 * @param integer $id The ID of the ACL
 * @package MODX\Revolution\Processors\Security\Access\UserGroup\ContextGroup
 */
class Remove extends RemoveProcessor
{
    use ContextGroupAclProcessorTrait;

    public $classKey = modAccessContextGroup::class;
    public $objectType = 'access_contextgroup';
    public $languageTopics = ['access'];
    public $permission = 'access_permissions';

    public function beforeRemove()
    {
        $adminGroup = $this->modx->getObject(modUserGroup::class, ['name' => 'Administrator']);
        if (
            $adminGroup
            && (int)$this->object->get('principal') === (int)$adminGroup->get('id')
            && $this->modx->getCount(modContext::class, [
                'context_group' => $this->object->get('target'),
                'key' => 'mgr',
            ])
        ) {
            return $this->failure($this->modx->lexicon('access_err_remove'));
        }

        return parent::beforeRemove();
    }

    public function afterRemove()
    {
        $this->flushPermissionsCache();

        return parent::afterRemove();
    }
}
