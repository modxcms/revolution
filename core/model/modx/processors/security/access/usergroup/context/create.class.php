<?php
/*
 * This file is part of MODX Revolution.
 *
 * Copyright (c) MODX, LLC. All Rights Reserved.
 *
 * For complete copyright and license information, see the COPYRIGHT and LICENSE
 * files found in the top-level directory of this distribution.
 */

/**
 * @package modx
 * @subpackage processors.security.group.context
 */

class modUserGroupAccessContextCreateProcessor extends modObjectCreateProcessor {
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
        ))) {
            $this->addFieldError('target', $this->modx->lexicon($this->objectType.'_err_ae'));
        }

        $this->object->set('principal_class', 'modUserGroup');

        return parent::beforeSave();
    }

    /**
     * Ensure that Admin UserGroup always has access to this context, if not adding Admin ACL
     * @return bool
     */
    public function afterSave() {
        $adminGroup = $this->modx->getObject('modUserGroup', array('name' => 'Administrator'));
        if ($adminGroup && $this->object->get('principal') !== $adminGroup->get('id')) {
            $adminContextPolicy = $this->modx->getObject('modAccessPolicy',array('name' => 'Context'));
            if ($adminContextPolicy) {
                $adminContextAccess = $this->modx->getObject($this->classKey, array(
                    'principal' => $adminGroup->get('id'),
                    'principal_class' => 'modUserGroup',
                    'target' => $this->object->get('target'),
                ));
                if (!$adminContextAccess) {
                    $adminContextAccess = $this->modx->newObject('modAccessContext');
                    $adminContextAccess->set('principal', $adminGroup->get('id'));
                    $adminContextAccess->set('principal_class', 'modUserGroup');
                    $adminContextAccess->set('target', $this->object->get('target'));
                    $adminContextAccess->set('policy', $adminContextPolicy->get('id'));
                    $adminContextAccess->save();
                }
            }
        }

        if ($this->modx->getUser()) {
            $this->modx->user->getAttributes(array(), '', true);
        }

        return parent::afterSave();
    }
}

return 'modUserGroupAccessContextCreateProcessor';
