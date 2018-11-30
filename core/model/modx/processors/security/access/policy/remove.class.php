<?php
/*
 * This file is part of MODX Revolution.
 *
 * Copyright (c) MODX, LLC. All Rights Reserved.
 *
 * For complete copyright and license information, see the COPYRIGHT and LICENSE
 * files found in the top-level directory of this distribution.
 */

/**
 * Removes a policy
 *
 * @param integer $id The ID of the policy
 *
 * @package modx
 * @subpackage processors.security.access.policy
 */
class modAccessPolicyRemoveProcessor extends modObjectRemoveProcessor {
    public $classKey = 'modAccessPolicy';
    public $languageTopics = array('policy');
    public $permission = 'policy_delete';
    public $objectType = 'policy';

    public function afterRemove() {
        $this->modx->cacheManager->flushPermissions();
        return parent::afterRemove();
    }
}
return 'modAccessPolicyRemoveProcessor';
