<?php

namespace MODX\Processors\Security\Access\Policy;

use MODX\Processors\modObjectDuplicateProcessor;

/**
 * Duplicates a policy
 *
 * @param integer $id The ID of the policy
 *
 * @package modx
 * @subpackage processors.security.access.policy
 */
class Duplicate extends modObjectDuplicateProcessor
{
    public $classKey = 'modAccessPolicy';
    public $languageTopics = ['policy'];
    public $permission = 'policy_new';
    public $objectType = 'policy';
}