<?php

/*
 * This file is part of the MODX Revolution package.
 *
 * Copyright (c) MODX, LLC
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace MODX\Revolution\Processors\Context;

use MODX\Revolution\modAccessContext;
use MODX\Revolution\modAccessPolicy;
use MODX\Revolution\modContext;
use MODX\Revolution\Processors\Model\CreateProcessor;
use MODX\Revolution\modUserGroup;

/**
 * Creates a context
 *
 * @property string $key The key of the context
 *
 * @package MODX\Revolution\Processors\Context
 */
class Create extends CreateProcessor
{
    public $classKey = modContext::class;
    public $languageTopics = ['context'];
    public $permission = 'new_context';
    public $objectType = 'context';
    public $primaryKeyField = 'key';

    public function beforeSave()
    {
        $key = $this->getProperty('key');

        switch (true) {
            case empty($key):
                $this->addFieldError('key', $this->modx->lexicon('context_err_ns_key'));
                break;
            case in_array(strtolower($key), $this->classKey::RESERVED_KEYS):
                $this->addFieldError('key', $this->modx->lexicon('context_err_reserved'));
                break;
            case $this->alreadyExists($key):
                $this->addFieldError('key', $this->modx->lexicon('context_err_ae'));
            // no default
        }
        if ($this->hasErrors()) {
            return false;
        }
        $this->object->set('key', $key);

        return true;
    }

    /**
     * {inheritDoc}
     *
     * @return mixed
     */
    public function afterSave()
    {
        $this->ensureAdministratorAccess();
        if ($this->getProperty('enableAnonymous', true)) {
            $this->enableAnonymousAccess();
        }
        $this->refreshUserACLs();

        return true;
    }

    /**
     * Check to see if the context already exists
     *
     * @param string $key
     *
     * @return boolean
     */
    public function alreadyExists($key)
    {
        return $this->modx->getCount(modContext::class, $key) > 0;
    }

    /**
     * Ensure that Admin User Group always has access to this context, so that it never loses the ability
     * to remove or edit it.
     *
     * @return void
     */
    public function ensureAdministratorAccess()
    {
        /** @var modUserGroup $adminGroup */
        $adminGroup = $this->modx->getObject(modUserGroup::class, ['name' => 'Administrator']);
        /** @var modAccessPolicy $adminContextPolicy */
        $adminContextPolicy = $this->modx->getObject(modAccessPolicy::class, ['name' => 'Context']);
        if ($adminGroup) {
            if ($adminContextPolicy) {
                /** @var modAccessContext $adminAdminAccess */
                $adminAdminAccess = $this->modx->newObject(modAccessContext::class);
                $adminAdminAccess->set('principal', $adminGroup->get('id'));
                $adminAdminAccess->set('principal_class', modUserGroup::class);
                $adminAdminAccess->set('target', $this->object->get('key'));
                $adminAdminAccess->set('policy', $adminContextPolicy->get('id'));
                $adminAdminAccess->save();
            }
        }
    }

    /**
     * Enable anonymous Load Only access to a context.
     *
     * @return void
     */
    public function enableAnonymousAccess()
    {
        $anonContextPolicy = $this->modx->getObject(modAccessPolicy::class, ['name' => 'Load Only']);
        $anonACL = $this->modx->getObject(modAccessContext::class, [
            'principal' => 0,
            'principal_class' => modUserGroup::class,
            'target' => $this->object->get('key'),
            'authority' => 9999,
        ]);
        if ($anonContextPolicy && !$anonACL) {
            $anonACL = $this->modx->newObject(modAccessContext::class);
            $anonACL->set('principal', 0);
            $anonACL->set('principal_class', modUserGroup::class);
            $anonACL->set('target', $this->object->get('key'));
            $anonACL->set('policy', $anonContextPolicy->get('id'));
            $anonACL->set('authority', 9999);
            $anonACL->save();
        }
    }

    /**
     * Refresh the mgr user ACLs to accurately update the context's permissions
     *
     * @return void
     */
    public function refreshUserACLs()
    {
        if ($this->modx->getUser()) {
            $this->modx->user->getAttributes([], '', true);
        }
    }
}
