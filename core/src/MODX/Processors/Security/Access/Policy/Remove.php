<?php

namespace MODX\Processors\Security\Access\Policy;

use MODX\Processors\modObjectRemoveProcessor;

/**
 * Removes a policy
 *
 * @param integer $id The ID of the policy
 *
 * @package modx
 * @subpackage processors.security.access.policy
 */
class Remove extends modObjectRemoveProcessor
{
    public $classKey = 'modAccessPolicy';
    public $languageTopics = ['policy'];
    public $permission = 'policy_delete';
    public $objectType = 'policy';


    public function afterRemove()
    {
        $this->modx->cacheManager->flushPermissions();

        return parent::afterRemove();
    }
}