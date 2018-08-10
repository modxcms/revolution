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
 * Removes a policy template
 *
 * @param integer $id The ID of the policy template
 *
 * @package modx
 * @subpackage processors.security.access.policy.template
 */
class modAccessPolicyTemplateRemoveProcessor extends modObjectRemoveProcessor {
    public $classKey = 'modAccessPolicyTemplate';
    public $languageTopics = array('policy');
    public $permission = 'policy_template_delete';
    public $objectType = 'policy_template';
}
return 'modAccessPolicyTemplateRemoveProcessor';
