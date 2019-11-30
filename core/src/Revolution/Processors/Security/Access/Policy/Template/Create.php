<?php
/*
 * This file is part of MODX Revolution.
 *
 * Copyright (c) MODX, LLC. All Rights Reserved.
 *
 * For complete copyright and license information, see the COPYRIGHT and LICENSE
 * files found in the top-level directory of this distribution.
 */

namespace MODX\Revolution\Processors\Security\Access\Policy\Template;

use MODX\Revolution\modAccessPolicyTemplate;
use MODX\Revolution\Processors\Model\CreateProcessor;

/**
 * Create an access policy template
 * @param string $name The name of the policy.
 * @param string $description (optional) A short description
 * @package MODX\Revolution\Processors\Security\Access\Policy\Template
 */
class Create extends CreateProcessor
{
    public $classKey = modAccessPolicyTemplate::class;
    public $languageTopics = ['policy'];
    public $permission = 'policy_template_new';
    public $objectType = 'policy_template';

    /**
     * @return bool
     */
    public function beforeSet()
    {
        $name = $this->getProperty('name');
        if (empty($name)) {
            $this->addFieldError('name', $this->modx->lexicon('policy_template_err_name_ns'));
        }

        if ($this->doesAlreadyExist(['name' => $name])) {
            $this->addFieldError('name', $this->modx->lexicon('policy_template_err_ae', ['name' => $name]));
        }

        return parent::beforeSet();
    }
}
