<?php
/**
 * Removes a policy template
 *
 * @param integer $id The ID of the policy template
 *
 * @package modx
 * @subpackage processors.security.access.policy.template
 */
class modAccessPolicyTemplateRemoveProcessor extends modObjectRemoveProcessor {
    public $classKey = 'modAccessPolicyTemplate';
    public $languageTopics = array('policy');
    public $permission = 'access_permissions';
    public $objectType = 'policy_template';
}
return 'modAccessPolicyTemplateRemoveProcessor';