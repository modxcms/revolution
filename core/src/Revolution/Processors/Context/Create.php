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
use MODX\Revolution\modTemplateVar;
use MODX\Revolution\modUserGroup;
use MODX\Revolution\modX;
use MODX\Revolution\Processors\Model\CreateProcessor;
use MODX\Revolution\Sources\modMediaSource;
use MODX\Revolution\Sources\modMediaSourceElement;

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
        $this->assignDefaultMediaSourceToTvs();

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

    /**
     * Materialize default media source bindings for TVs that already have at least one
     * media_sources_elements row, matching the Manager TV UI fallback for empty contexts.
     *
     * Uses one INSERT…SELECT instead of per-TV queries. Does not copy custom sources
     * (that belongs to Context Duplicate).
     *
     * @return void
     */
    private function assignDefaultMediaSourceToTvs()
    {
        $defaultSourceId = (int)$this->modx->getOption('default_media_source', null, 1);
        if ($defaultSourceId < 1) {
            return;
        }
        if ($this->modx->getCount(modMediaSource::class, ['id' => $defaultSourceId]) < 1) {
            return;
        }

        $contextKey = $this->object->get('key');
        $table = $this->modx->getTableName(modMediaSourceElement::class);
        $tvClass = modTemplateVar::class;

        $sql = "INSERT INTO {$table} (`source`, `object_class`, `object`, `context_key`)
            SELECT DISTINCT ?, mse.`object_class`, mse.`object`, ?
            FROM {$table} AS mse
            WHERE mse.`object_class` = ?
              AND NOT EXISTS (
                SELECT 1 FROM {$table} AS existing
                WHERE existing.`object` = mse.`object`
                  AND existing.`object_class` = mse.`object_class`
                  AND existing.`context_key` = ?
              )";

        $stmt = $this->modx->prepare($sql);
        if (!$stmt || !$stmt->execute([$defaultSourceId, $contextKey, $tvClass, $contextKey])) {
            $this->modx->log(
                modX::LOG_LEVEL_ERROR,
                '[Context\\Create] Failed to assign default media sources for context `' . $contextKey . '`.'
            );
        }
    }
}
