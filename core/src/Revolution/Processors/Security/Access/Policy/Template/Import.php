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
use MODX\Revolution\modAccessPolicyTemplateGroup;
use MODX\Revolution\Processors\Model\ImportProcessor;

/**
 * Import a policy template.
 * @package MODX\Revolution\Processors\Security\Access\Policy\Template
 */
class Import extends ImportProcessor
{
    public $classKey = modAccessPolicyTemplate::class;
    public $objectType = 'policy_template';
    public $permission = 'policy_template_view';
    public $languageTopics = ['policy'];

    /**
     * @return bool
     */
    public function beforeSave()
    {
        /** @var modAccessPolicyTemplate $template */
        $this->object->set('description', (string)$this->xml->description);
        $this->object->set('lexicon', (string)$this->xml->lexicon);

        $this->setTemplateGroup();
        $this->addPermissions();

        return parent::beforeSave();
    }

    public function setTemplateGroup()
    {
        /** @var modAccessPolicyTemplateGroup $templateGroup */
        $templateGroup = $this->modx->getObject(modAccessPolicyTemplateGroup::class, [
            'name' => (string)$this->xml->template_group
        ]);
        if ($templateGroup) {
            $this->object->set('template_group', $templateGroup->get('id'));
        } else {
            $this->object->set('template_group', 1);
        }
    }

    public function addPermissions()
    {
        $permissions = [];
        foreach ($this->xml->permissions->permission as $permissionXml) {
            /** @var modAccessPermission $permission */
            $permission = $this->modx->newObject(modAccessPermission::class);
            $permission->set('name', (string)$permissionXml->name);
            $permission->set('description', (string)$permissionXml->description);
            $permission->set('value', (string)$permissionXml->value);
            $permissions[] = $permission;
        }

        $this->object->addMany($permissions);
    }
}
