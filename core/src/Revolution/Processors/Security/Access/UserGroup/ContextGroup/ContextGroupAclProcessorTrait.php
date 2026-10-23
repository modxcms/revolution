<?php

/*
 * This file is part of MODX Revolution.
 *
 * Copyright (c) MODX, LLC. All Rights Reserved.
 *
 * For complete copyright and license information, see the COPYRIGHT and LICENSE
 * files found in the top-level directory of this distribution.
 */

namespace MODX\Revolution\Processors\Security\Access\UserGroup\ContextGroup;

use MODX\Revolution\modAccessContextGroup;
use MODX\Revolution\modAccessPolicy;
use MODX\Revolution\modContextGroup;
use MODX\Revolution\modUserGroup;

/**
 * Shared validation and side effects for Context Group ACL processors.
 */
trait ContextGroupAclProcessorTrait
{
    protected function validateContextGroupAclFields(): void
    {
        if ($this->getProperty('principal') === null) {
            $this->addFieldError('principal', $this->modx->lexicon('usergroup_err_ns'));
        }

        if (!$this->getProperty('target')) {
            $this->addFieldError('target', $this->modx->lexicon('context_group_err_ns'));
        }

        if (!$this->getProperty('policy')) {
            $this->addFieldError('policy', $this->modx->lexicon('access_policy_err_ns'));
        }

        if ($this->getProperty('authority') === null) {
            $this->addFieldError('authority', $this->modx->lexicon('authority_err_ns'));
        }
    }

    protected function validateContextGroupAclReferences(): void
    {
        $groupId = (int)$this->getProperty('target');
        if ($groupId <= 0 || !$this->modx->getCount(modContextGroup::class, $groupId)) {
            $this->addFieldError('target', $this->modx->lexicon('context_group_err_nf'));
        }

        if (!$this->modx->getObject(modAccessPolicy::class, $this->getProperty('policy'))) {
            $this->addFieldError('policy', $this->modx->lexicon('access_policy_err_nf'));
        }
    }

    protected function validateContextGroupAclUnique(?int $excludeId = null): void
    {
        $criteria = [
            'principal' => $this->object->get('principal'),
            'principal_class' => modUserGroup::class,
            'target' => $this->object->get('target'),
            'policy' => $this->object->get('policy'),
        ];
        if ($excludeId !== null) {
            $criteria['id:!='] = $excludeId;
        }

        if ($this->doesAlreadyExist($criteria)) {
            $this->addFieldError('target', $this->modx->lexicon($this->objectType . '_err_ae'));
        }
    }

    protected function setContextGroupPrincipalClass(): void
    {
        $this->object->set('principal_class', modUserGroup::class);
    }

    protected function flushPermissionsCache(): void
    {
        if ($this->modx->getCacheManager()) {
            $this->modx->cacheManager->flushPermissions();
        }
    }

    /**
     * Ensure Administrator retains access when the first group ACL closes member Contexts.
     */
    protected function ensureAdministratorGroupAcl(): void
    {
        $adminGroup = $this->modx->getObject(modUserGroup::class, ['name' => 'Administrator']);
        if (!$adminGroup || (int)$this->object->get('principal') === (int)$adminGroup->get('id')) {
            return;
        }

        $adminContextPolicy = $this->modx->getObject(modAccessPolicy::class, ['name' => 'Context']);
        if (!$adminContextPolicy) {
            return;
        }

        $adminAccess = $this->modx->getObject(modAccessContextGroup::class, [
            'principal' => $adminGroup->get('id'),
            'principal_class' => modUserGroup::class,
            'target' => $this->object->get('target'),
        ]);
        if ($adminAccess) {
            return;
        }

        $adminAccess = $this->modx->newObject(modAccessContextGroup::class);
        $adminAccess->fromArray([
            'principal' => $adminGroup->get('id'),
            'principal_class' => modUserGroup::class,
            'target' => $this->object->get('target'),
            'policy' => $adminContextPolicy->get('id'),
        ]);
        if (!$adminAccess->save()) {
            $this->modx->log(
                \xPDO\xPDO::LOG_LEVEL_ERROR,
                'Failed to create Administrator Context Group ACL for target ' . $this->object->get('target')
            );
        }
    }
}
