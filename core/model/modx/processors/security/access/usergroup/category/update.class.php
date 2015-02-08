<?php
/**
 * @package modx
 * @subpackage processors.security.group.category
 */

class modUserGroupAccessCategoryUpdateProcessor extends modObjectUpdateProcessor {
    public $classKey = 'modAccessCategory';
    public $objectType = 'access_category';
    public $languageTopics = array('access', 'user', 'category');
    public $permission = 'access_permissions';

    public function beforeSet() {
        $principal = $this->getProperty('principal');
        if (!$principal) {
            $this->addFieldError('principal', $this->modx->lexicon('usergroup_err_ns'));
        }

        $policy = $this->getProperty('policy', '');
        if (empty($policy)) {
            $this->addFieldError('policy', $this->modx->lexicon('access_policy_err_ns'));
        }

        $authority = $this->getProperty('authority');
        if ($authority === null) {
            $this->addFieldError('authority', $this->modx->lexicon('authority_err_ns'));
        }

        $category = $this->getProperty('target');
        if (!$category) {
            $this->addFieldError('target', $this->modx->lexicon('category_err_ns'));
        }

        return parent::beforeSet();
    }

    public function beforeSave() {
        $policy = $this->modx->getObject('modAccessPolicy', $this->getProperty('policy'));
        if (!$policy) {
            $this->addFieldError('policy', $this->modx->lexicon('access_policy_err_nf'));
        }

        $category = $this->modx->getObject('modCategory', $this->getProperty('target'));
        if (!$category) {
            $this->addFieldError('target', $this->modx->lexicon('category_err_nf'));
        } else {
            if (!$category->checkPolicy('view')) {
                $this->addFieldError('target', $this->modx->lexicon('access_denied'));
            }
        }

        if ($this->doesAlreadyExist(array(
            'principal' => $this->object->get('principal'),
            'principal_class' => 'modUserGroup',
            'target' => $this->object->get('target'),
            'policy' => $this->object->get('policy'),
            'context_key' => $this->object->get('context_key'),
            'id:!=' => $this->object->get('id'),
        ))) {
            $this->addFieldError('target', $this->modx->lexicon($this->objectType.'_err_ae'));
        }
        $this->object->set('principal_class', 'modUserGroup');

        return parent::beforeSave();
    }
}

return 'modUserGroupAccessCategoryUpdateProcessor';
