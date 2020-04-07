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
use MODX\Revolution\modAccessPolicyTemplateGroup;
use MODX\Revolution\Processors\Model\ImportProcessor;

/**
 * Import a policy template.
 * @package MODX\Revolution\Processors\Security\Access\Policy
 */
class Import extends ImportProcessor
{
    public $classKey = modAccessPolicy::class;
    public $objectType = 'policy';
    public $permission = 'policy_view';
    public $languageTopics = ['policy'];

    /**
     * @return bool
     */
    public function beforeSave()
    {
        $this->object->set('description', (string)$this->xml->description);
        $this->object->set('lexicon', (string)$this->xml->lexicon);

        $this->setTemplate();
        $this->addPermissions();

        return parent::beforeSave();
    }

    public function setTemplate()
    {
        /** @var modAccessPolicyTemplate $template */
        $template = $this->modx->getObject(modAccessPolicyTemplate::class, ['name' => (string)$this->xml->template->name]);
        if (empty($template)) {
            $template = $this->createTemplateFromImport();
        }
        $this->object->set('template', $template->get('id'));
    }

    public function addPermissions()
    {
        $permissions = [];
        foreach ($this->xml->permissions->permission as $permissionXml) {
            $v = (integer)$permissionXml->value;
            $permissions[(string)$permissionXml->name] = (!empty($v) ? true : false);
        }
        $this->object->set('data', $permissions);
    }

    /**
     * @return modAccessPolicyTemplate
     */
    public function createTemplateFromImport()
    {
        /** @var modAccessPolicyTemplate $template */
        $template = $this->modx->newObject(modAccessPolicyTemplate::class);
        $template->fromArray([
            'name' => $this->xml->template->name,
            'description' => $this->xml->template->description,
            'lexicon' => $this->xml->template->lexicon,
        ]);
        /** @var modAccessPolicyTemplateGroup $templateGroup */
        $templateGroup = $this->modx->getObject(modAccessPolicyTemplateGroup::class, ['name' => (string)$this->xml->template->template_group]);
        if ($templateGroup) {
            $template->set('template_group', $templateGroup->get('id'));
        } else {
            $template->set('template_group', 1);
        }

        $permissions = [];
        foreach ($this->xml->template->permissions->permission as $permissionXml) {
            /** @var modAccessPermission $permission */
            $permission = $this->modx->newObject(modAccessPermission::class);
            $permission->set('name', (string)$permissionXml->name);
            $permission->set('description', (string)$permissionXml->description);
            $permission->set('value', (string)$permissionXml->value);
            $permissions[] = $permission;
        }
        $template->addMany($permissions);
        $template->save();

        return $template;
    }
}
