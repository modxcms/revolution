<?php

namespace MODX\Processors\Security\Access\Policy\Template;

use MODX\Processors\modObjectRemoveProcessor;

/**
 * Removes a policy template
 *
 * @param integer $id The ID of the policy template
 *
 * @package modx
 * @subpackage processors.security.access.policy.template
 */
class Remove extends modObjectRemoveProcessor
{
    public $classKey = 'modAccessPolicyTemplate';
    public $languageTopics = ['policy'];
    public $permission = 'policy_template_delete';
    public $objectType = 'policy_template';
}