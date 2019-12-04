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
use MODX\Revolution\Processors\ModelProcessor;
use MODX\Revolution\modX;

/**
 * Removes multiple policy templates
 * @param integer $templates A comma-separated list of policy templates
 * @package MODX\Revolution\Processors\Security\Access\Policy\Template
 */
class RemoveMultiple extends ModelProcessor
{
    public $languageTopics = ['policy'];
    public $permission = 'policy_template_delete';
    public $objectType = 'policy_template';

    /**
     * @return mixed
     */
    public function process()
    {
        $templates = $this->getProperty('templates');
        if (empty($templates)) {
            return $this->failure($this->modx->lexicon('policy_template_err_ns'));
        }

        $templateIds = is_array($templates) ? $templates : explode(',', $templates);
        $core = [
            'ResourceTemplate',
            'ObjectTemplate',
            'AdministratorTemplate',
            'ElementTemplate',
            'MediaSourceTemplate'
        ];

        foreach ($templateIds as $templateId) {
            /** @var modAccessPolicyTemplate $template */
            $template = $this->modx->getObject(modAccessPolicyTemplate::class, $templateId);
            if (empty($template)) {
                continue;
            }

            if (!in_array($template->get('name'), $core)) {
                continue;
            }

            if ($template->remove() === false) {
                $this->modx->log(modX::LOG_LEVEL_ERROR,
                    $this->modx->lexicon('policy_template_err_remove') . print_r($template->toArray(), true));
            }
            $this->logManagerAction($template);
        }

        return $this->success();
    }

    /**
     * @param modAccessPolicyTemplate $template
     */
    public function logManagerAction(modAccessPolicyTemplate $template)
    {
        $this->modx->logManagerAction('remove_policy_template', modAccessPolicyTemplate::class, $template->get('id'));
    }
}
