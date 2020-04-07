<?php
/*
 * This file is part of MODX Revolution.
 *
 * Copyright (c) MODX, LLC. All Rights Reserved.
 *
 * For complete copyright and license information, see the COPYRIGHT and LICENSE
 * files found in the top-level directory of this distribution.
 */

namespace MODX\Revolution\Processors\Security\Access\Policy;

use MODX\Revolution\modAccessPermission;
use MODX\Revolution\modAccessPolicy;
use MODX\Revolution\modAccessPolicyTemplate;
use MODX\Revolution\Processors\Model\ExportProcessor;

/**
 * Export a policy template.
 * @package MODX\Revolution\Processors\Security\Access\Policy
 */
class Export extends ExportProcessor
{
    public $classKey = modAccessPolicy::class;
    public $objectType = 'policy';
    public $permission = 'policy_view';
    public $languageTopics = ['policy'];

    public function prepareXml()
    {
        $this->xml->startElement('policy');

        $this->addTemplate();

        $this->xml->writeElement('name', $this->object->get('name'));
        $this->xml->writeElement('description', $this->object->get('description'));
        $this->xml->writeElement('class', $this->object->get('class'));

        $this->addPermissions();

        $this->xml->endElement(); // end policy
    }

    public function addTemplate()
    {
        /** @var modAccessPolicyTemplate $template */
        $template = $this->object->getOne('Template');
        if ($template) {
            $this->xml->startElement('template');
            $this->xml->writeElement('name', $template->get('name'));
            $this->xml->writeElement('description', $template->get('description'));
            $this->xml->writeElement('lexicon', $template->get('lexicon'));

            $templateGroup = $template->getOne('TemplateGroup');
            if ($templateGroup) {
                $this->xml->writeElement('template_group', $templateGroup->get('name'));
            }

            $this->addTemplatePermissions($template);

            $this->xml->endElement();
        }
    }

    public function addPermissions()
    {
        $permissions = $this->object->get('data');

        $this->xml->startElement('permissions');
        foreach ($permissions as $k => $v) {
            $this->xml->startElement('permission');
            $this->xml->writeElement('name', $k);
            $this->xml->writeElement('value', $v);
            $this->xml->endElement();
        }
        $this->xml->endElement();
    }

    /**
     * @param modAccessPolicyTemplate $template
     */
    public function addTemplatePermissions(modAccessPolicyTemplate $template)
    {
        $this->xml->startElement('permissions');
        $permissions = $template->getMany('Permissions');
        /** @var modAccessPermission $permission */
        foreach ($permissions as $permission) {
            $this->xml->startElement('permission');
            $this->xml->writeElement('name', $permission->get('name'));
            $this->xml->writeElement('description', $permission->get('description'));
            $this->xml->writeElement('value', $permission->get('value'));
            $this->xml->endElement();
        }
        $this->xml->endElement();
    }
}
