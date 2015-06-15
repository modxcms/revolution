<?php
/**
 * Update ACL for Context
 * @package modx
 * @subpackage processors.security.group.context
 */

class modUserGroupAccessContextUpdateProcessor extends modObjectUpdateProcessor {
    public $classKey = 'modAccessContext';
    public $objectType = 'access_context';
    public $languageTopics = array('access', 'user', 'context');
    public $permission = 'access_permissions';

    public function beforeSet() {
        if ($this->getProperty('principal') == null) {
            $this->addFieldError('principal', $this->modx->lexicon('usergroup_err_ns'));
        }

        if (!$this->getProperty('target')) {
            $this->addFieldError('target', $this->modx->lexicon('context_err_ns'));
        }

        if (!$this->getProperty('policy')) {
            $this->addFieldError('policy', $this->modx->lexicon('access_policy_err_ns'));
        }

        if ($this->getProperty('authority') === null ) {
            $this->addFieldError('authority', $this->modx->lexicon('authority_err_ns'));
        }

        return parent::beforeSet();
    }

    public function beforeSave() {
        $context = $this->modx->getObject('modContext', $this->getProperty('target'));
        if (!$context) {
            $this->addFieldError('target', $this->modx->lexicon('context_err_nf'));
        }

        $policy = $this->modx->getObject('modAccessPolicy', $this->getProperty('policy'));
        if (!$policy) {
            $this->addFieldError('policy', $this->modx->lexicon('access_policy_err_nf'));
        }

        if ($this->doesAlreadyExist(array(
            'principal' => $this->object->get('principal'),
            'principal_class' => 'modUserGroup',
            'target' => $this->object->get('target'),
            'policy' => $this->object->get('policy'),
            'id:!=' => $this->object->get($this->primaryKeyField),
        ))) {
            $this->addFieldError('target', $this->modx->lexicon($this->objectType.'_err_ae'));
        }

        $this->object->set('principal_class', 'modUserGroup');

        return parent::beforeSave();
    }
}

return 'modUserGroupAccessContextUpdateProcessor';
