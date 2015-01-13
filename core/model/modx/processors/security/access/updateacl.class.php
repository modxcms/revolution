<?php
/**
 * Update an ACL.
 *
 * @param string $type The type of ACL object
 * @param string $target (optional) The target of the ACL. Defauls to 0.
 * @param string $principal_class The class_key for the principal. Defaults to
 * modUserGroup.
 * @param string $principal (optional) The principal ID. Defaults to 0.
 * @param integer $authority (optional) The authority of the acl role. Defaults
 * to 9999.
 * @param integer $policy (optional) The ID of the policy. Defaults to 0.
 * @param string $context_key (optional) The context to assign this ACL to.
 *
 * @package modx
 * @subpackage processors.security.access
 */

class modSecurityAccessUpdateAclProcessor extends modObjectUpdateProcessor {
    public $objectType = 'access';
    public $permission = 'access_permissions';
    public $languageTopics = array('access');

    public function initialize() {
        $this->classKey = $this->getProperty('type');
        $primaryKey = $this->getProperty('id');
        if (!$this->classKey || !$primaryKey) {
            return $this->modx->lexicon($this->objectType.'_type_err_ns');
        }

        $this->object = $this->modx->getObject($this->classKey, $primaryKey);
        if (empty($this->object)) return $this->modx->lexicon($this->objectType.'_err_nf');

        return true;
    }

    /**
     * Reload current user's ACLs
     * @return bool|void
     */
    public function afterSave() {
        if ($this->modx->getUser()) {
            $this->modx->user->getAttributes(array(), '', true);
        }

        return true;
    }
}

return 'modSecurityAccessUpdateAclProcessor';
