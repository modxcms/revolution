<?php
/*
 * This file is part of MODX Revolution.
 *
 * Copyright (c) MODX, LLC. All Rights Reserved.
 *
 * For complete copyright and license information, see the COPYRIGHT and LICENSE
 * files found in the top-level directory of this distribution.
 */

namespace MODX\Revolution\Processors\Security\Access\UserGroup\Source;

use MODX\Revolution\modAccessCategory;
use MODX\Revolution\modAccessPolicy;
use MODX\Revolution\Processors\Model\UpdateProcessor;
use MODX\Revolution\modUserGroup;
use MODX\Revolution\Sources\modAccessMediaSource;
use MODX\Revolution\Sources\modMediaSource;

/**
 * @package MODX\Revolution\Processors\Security\Access\UserGroup\Source
 */
class Update extends UpdateProcessor
{
    public $classKey = modAccessMediaSource::class;
    public $languageTopics = ['source', 'access', 'user'];
    public $permission = 'access_permissions';
    public $objectType = 'source';

    /**
     * @return bool
     */
    public function beforeSave()
    {
        $policyId = $this->getProperty('policy');
        $principalId = $this->getProperty('principal');
        $target = $this->getProperty('target');

        if ($principalId === null) {
            $this->addFieldError('principal', $this->modx->lexicon('usergroup_err_ns'));
        }
        if (empty($policyId)) {
            $this->addFieldError('policy', $this->modx->lexicon('access_policy_err_ns'));
        }

        /* validate for invalid data */
        if (!empty($target)) {
            /** @var modMediaSource $mediaSource */
            $mediaSource = $this->modx->getObject(modMediaSource::class, $target);
            if ($mediaSource === null) {
                $this->addFieldError('target', $this->modx->lexicon('source_err_nf'));
            }
            if (!$mediaSource->checkPolicy('view')) {
                $this->addFieldError('target', $this->modx->lexicon('access_denied'));
            }
        }

        $policy = $this->modx->getObject(modAccessPolicy::class, $policyId);
        if ($policy === null) {
            $this->addFieldError('policy', $this->modx->lexicon('access_policy_err_nf'));
        }

        $alreadyExists = $this->modx->getObject(modAccessCategory::class, [
            'principal' => $principalId,
            'principal_class' => modUserGroup::class,
            'target' => $target,
            'policy' => $policyId,
            'context_key' => $this->getProperty('context_key'),
            'id:!=' => $this->object->get('id'),
        ]);
        if ($alreadyExists) {
            $this->addFieldError('context_key', $this->modx->lexicon('access_source_err_ae'));
        }

        $this->object->set('principal_class', modUserGroup::class);
        return parent::beforeSave();
    }
}
