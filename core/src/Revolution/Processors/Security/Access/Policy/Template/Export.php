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

use MODX\Revolution\modAccessPermission;
use MODX\Revolution\modAccessPolicyTemplate;
use MODX\Revolution\Processors\Model\ExportProcessor;

/**
 * Export a policy template.
 * @package MODX\Revolution\Processors\Security\Access\Policy\Template
 */
class Export extends ExportProcessor
{
    public $classKey = modAccessPolicyTemplate::class;
    public $objectType = 'policy_template';
    public $permission = 'policy_template_view';
    public $languageTopics = ['policy'];

    public function prepareXml()
    {
        $this->xml->startElement('policy_template');

        $this->addTemplateGroup();

        $this->xml->writeElement('name', $this->object->get('name'));
        $this->xml->writeElement('description', $this->object->get('description'));
        $this->xml->writeElement('lexicon', $this->object->get('lexicon'));

        $this->addPermissions();

        $this->xml->endElement(); // end policy_template
    }

    public function addTemplateGroup()
    {
        $templateGroup = $this->object->getOne('TemplateGroup');
        if ($templateGroup) {
            $this->xml->writeElement('template_group', $templateGroup->get('name'));
        }
    }

    public function addPermissions()
    {
        $this->xml->startElement('permissions');
        $permissions = $this->object->getMany('Permissions');
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
