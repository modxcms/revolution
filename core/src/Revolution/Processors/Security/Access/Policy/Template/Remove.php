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
use MODX\Revolution\Processors\Model\RemoveProcessor;

/**
 * Removes a policy template
 * @param integer $id The ID of the policy template
 * @package MODX\Revolution\Processors\Security\Access\Policy\Template
 */
class Remove extends RemoveProcessor
{
    public $classKey = modAccessPolicyTemplate::class;
    public $languageTopics = ['policy'];
    public $permission = 'policy_template_delete';
    public $objectType = 'policy_template';
}
