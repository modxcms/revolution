<?php
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
}
return 'modAccessPolicyRemoveProcessor';