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
use MODX\Revolution\Processors\ModelProcessor;
use MODX\Revolution\modX;

/**
 * Removes multiple policies
 * @param integer $policies A comma-separated list of policies
 * @package MODX\Revolution\Processors\Security\Access\Policy
 */
class RemoveMultiple extends ModelProcessor
{
    public $languageTopics = ['policy'];
    public $permission = 'policy_delete';
    public $objectType = 'policy';

    public function process()
    {
        $policies = $this->getProperty('policies');
        if (empty($policies)) {
            return $this->failure($this->modx->lexicon('policy_err_ns'));
        }

        $policyIds = is_array($policies) ? $policies : explode(',', $policies);
        $core = ['Resource', 'Object', 'Administrator', 'Element', 'Load Only', 'Load, List and View'];

        foreach ($policyIds as $policyId) {
            /** @var modAccessPolicy $policy */
            $policy = $this->modx->getObject(modAccessPolicy::class, $policyId);
            if (empty($policy)) {
                continue;
            }

            if (in_array($policy->get('name'), $core)) {
                continue;
            }

            if (!$policy->remove()) {
                $this->modx->log(modX::LOG_LEVEL_ERROR,
                    $this->modx->lexicon('policy_err_remove') . print_r($policy->toArray(), true));
            }
            $this->logManagerAction($policy);
        }

        return $this->success();
    }

    public function logManagerAction(modAccessPolicy $policy)
    {
        $this->modx->logManagerAction('remove_policy', modAccessPolicy::class, $policy->get('id'));
    }
}
