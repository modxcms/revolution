<?php
/**
 * Remove an ACL.
 *
 * @param string $type The class_key for the ACL.
 * @param string $id The ID of the ACL.
 *
 * @package modx
 * @subpackage processors.security.access
 */

class modSecurityAccessRemoveAclProcessor extends modObjectRemoveProcessor {
    public $objectType = 'access';
    public $permission = 'access_permissions';
    public $languageTopics = array('access');

    public function initialize() {
        $this->classKey = $this->getProperty('type');
        $id = $this->getProperty('id');
        if (!$this->classKey || !$id) {
            return $this->modx->lexicon($this->objectType.'_type_err_ns');
        }
        $this->object = $this->modx->getObject($this->classKey, $id);
        if (!$this->object) {
            return $this->modx->lexicon($this->objectType.'_err_nf');
        }
        return true;
    }

    /**
     * Reload current user's ACLs
     * @return bool|void
     */
    public function afterRemove() {
        if ($this->modx->getUser()) {
            $this->modx->user->getAttributes(array(), '', true);
        }

        return true;
    }
}

return 'modSecurityAccessRemoveAclProcessor';
