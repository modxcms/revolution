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
 * Removes multiple policy templates
 *
 * @param integer $templates A comma-separated list of policy templates
 *
 * @package modx
 * @subpackage processors.security.access.policy.template
 */
class modAccessPolicyTemplateRemoveMultipleProcessor extends modObjectProcessor {
    public $languageTopics = array('policy');
    public $permission = 'policy_template_delete';
    public $objectType = 'policy_template';

    public function process() {
        $templates = $this->getProperty('templates');
        if (empty($templates)) {
            return $this->failure($this->modx->lexicon('policy_template_err_ns'));
        }

        $templateIds = is_array($templates) ? $templates : explode(',',$templates);
        $core = array('ResourceTemplate','ObjectTemplate','AdministratorTemplate','ElementTemplate','MediaSourceTemplate');

        foreach ($templateIds as $templateId) {
            /** @var modAccessPolicyTemplate $template */
            $template = $this->modx->getObject('modAccessPolicyTemplate',$templateId);
            if (empty($template)) continue;

            if (!in_array($template->get('name'),$core)) {
                continue;
            }

            if ($template->remove() == false) {
                $this->modx->log(modX::LOG_LEVEL_ERROR,$this->modx->lexicon('policy_template_err_remove').print_r($template->toArray(),true));
            }
            $this->logManagerAction($template);
        }

        return $this->success();
    }

    public function logManagerAction(modAccessPolicyTemplate $template) {
        $this->modx->logManagerAction('remove_policy_template','modAccessPolicyTemplate',$template->get('id'));
    }
}
return 'modAccessPolicyTemplateRemoveMultipleProcessor';
