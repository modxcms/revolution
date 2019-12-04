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

use MODX\Revolution\modAccessPolicy;
use MODX\Revolution\Processors\Model\CreateProcessor;
use MODX\Revolution\modUserGroup;
use MODX\Revolution\Sources\modAccessMediaSource;
use MODX\Revolution\Sources\modMediaSource;

/**
 * @package MODX\Revolution\Processors\Security\Access\UserGroup\Source
 */
class Create extends CreateProcessor
{
    public $classKey = modAccessMediaSource::class;
    public $languageTopics = ['source', 'access', 'user'];
    public $permission = 'access_permissions';
    public $objectType = 'source';

    /**
     * @return mixed
     */
    public function beforeSet()
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
            if (empty($mediaSource)) {
                $this->addFieldError('target', $this->modx->lexicon('source_err_nf'));
            }
            if (!$mediaSource->checkPolicy('view')) {
                $this->addFieldError('target', $this->modx->lexicon('access_denied'));
            }
        }

        $policy = $this->modx->getObject(modAccessPolicy::class, $policyId);
        if (empty($policy)) {
            $this->addFieldError('policy', $this->modx->lexicon('access_policy_err_nf'));
        }

        return parent::beforeSave();
    }

    /**
     * @return mixed
     */
    public function beforeSave()
    {
        if ($this->doesAlreadyExist([
            'principal' => $this->getProperty('principal'),
            'principal_class' => modUserGroup::class,
            'target' => $this->getProperty('target'),
            'policy' => $this->getProperty('policy'),
            'context_key' => $this->getProperty('context_key'),
        ])) {
            $this->addFieldError('target', $this->modx->lexicon('access_source_err_ae'));
        }

        $this->object->set('principal_class', modUserGroup::class);
        return parent::beforeSave();
    }

}
