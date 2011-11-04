<?php
/**
 * Duplicates a policy
 *
 * @param integer $id The ID of the policy
 *
 * @package modx
 * @subpackage processors.security.access.policy
 */
class modAccessPolicyDuplicateProcessor extends modObjectDuplicateProcessor {
    public $classKey = 'modAccessPolicy';
    public $languageTopics = array('policy');
    public $permission = 'access_permissions';
    public $objectType = 'policy';
}
return 'modAccessPolicyDuplicateProcessor';