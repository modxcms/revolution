<?php
/*
 * This file is part of MODX Revolution.
 *
 * Copyright (c) MODX, LLC. All Rights Reserved.
 *
 * For complete copyright and license information, see the COPYRIGHT and LICENSE
 * files found in the top-level directory of this distribution.
 */

namespace MODX\Revolution\Processors\Security\Access;

use MODX\Revolution\Processors\Model\CreateProcessor;
use MODX\Revolution\modUserGroup;

/**
 * Adds an ACL
 * @param string $type The type of ACL object
 * @param string $target (optional) The target of the ACL. Defaults to 0.
 * @param string $principal_class The class_key for the principal. Defaults to modUserGroup.
 * @param string $principal (optional) The principal ID. Defaults to 0.
 * @param integer $authority (optional) The authority of the acl role. Defaults to 9999.
 * @param integer $policy (optional) The ID of the policy. Defaults to 0.
 * @param string $context_key (optional) The context to assign this ACL to.
 * @package MODX\Revolution\Processors\Security\Access
 */
class AddAcl extends CreateProcessor
{
    public $objectType = 'access';
    public $permission = 'access_permissions';
    public $languageTopics = ['access'];

    /**
     * @return bool|string|null
     */
    public function initialize()
    {
        $this->classKey = $this->getProperty('type');
        if (!$this->classKey) {
            return $this->modx->lexicon($this->objectType . '_type_err_ns');
        }

        return parent::initialize();
    }

    /**
     * @return bool|string|null
     */
    public function beforeSet()
    {
        $this->setDefaultProperties([
            'target' => 0,
            'principal_class' => modUserGroup::class,
            'principal' => 0,
            'authority' => 9999,
            'policy' => 0,
        ]);

        if (!$this->getProperty('target') || !$this->getProperty('principal_class')) {
            return $this->modx->lexicon($this->objectType . '_err_create_md');
        }

        return parent::beforeSet();
    }

    /**
     * @return bool|string|null
     */
    public function beforeSave()
    {
        $c = [
            'target' => $this->object->get('target'),
            'principal_class' => $this->object->get('principal_class'),
            'principal' => $this->object->get('principal'),
            'authority' => $this->object->get('authority'),
            'policy' => $this->object->get('policy'),
        ];

        if ($this->getProperty('context_key')) {
            $c['context_key'] = $this->getProperty('context_key');
        }

        if ($this->doesAlreadyExist($c)) {
            return $this->modx->lexicon($this->objectType . '_err_ae');
        }

        return parent::beforeSave();
    }

    /**
     * Reload current user's ACLs
     * @return bool
     */
    public function afterSave()
    {
        if ($this->modx->getUser()) {
            $this->modx->user->getAttributes([], '', true);
        }

        return true;
    }

}
