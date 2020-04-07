<?php
/*
 * This file is part of MODX Revolution.
 *
 * Copyright (c) MODX, LLC. All Rights Reserved.
 *
 * For complete copyright and license information, see the COPYRIGHT and LICENSE
 * files found in the top-level directory of this distribution.
 */

namespace MODX\Revolution\Processors\Security\Access\Policy;

use MODX\Revolution\modAccessPolicy;
use MODX\Revolution\Processors\Model\RemoveProcessor;

/**
 * Removes a policy
 * @param integer $id The ID of the policy
 * @package MODX\Revolution\Processors\Security\Access\Policy
 */
class Remove extends RemoveProcessor
{
    public $classKey = modAccessPolicy::class;
    public $languageTopics = ['policy'];
    public $permission = 'policy_delete';
    public $objectType = 'policy';

    public function afterRemove()
    {
        $this->modx->cacheManager->flushPermissions();
        
        return parent::afterRemove();
    }
}
