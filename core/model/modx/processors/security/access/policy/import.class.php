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
 * Import a policy template.
 *
 * @package modx
 * @subpackage processors.security.access.policy.template
 */
class modAccessPolicyImportProcessor extends modObjectImportProcessor {
    public $objectType = 'policy';
    public $classKey = 'modAccessPolicy';
    public $permission = 'policy_view';
    public $languageTopics = array('policy');

    public function beforeSave() {
        $this->object->set('description',(string)$this->xml->description);
        $this->object->set('lexicon',(string)$this->xml->lexicon);

        $this->setTemplate();
        $this->addPermissions();
        return parent::beforeSave();
    }

    public function setTemplate() {
        /** @var modAccessPolicyTemplate $template */
        $template = $this->modx->getObject('modAccessPolicyTemplate',array('name' => (string)$this->xml->template->name));
        if (empty($template)) {
            $template = $this->createTemplateFromImport();
        }
        $this->object->set('template',$template->get('id'));
    }

    public function addPermissions() {
        $permissions = array();
        foreach ($this->xml->permissions->permission as $permissionXml) {
            $v = (integer) $permissionXml->value;
            $permissions[(string)$permissionXml->name] = (!empty($v) ? true : false);
        }
        $this->object->set('data',$permissions);
    }

    public function createTemplateFromImport() {
        /** @var modAccessPolicyTemplate $template */
        $template = $this->modx->newObject('modAccessPolicyTemplate');
        $template->fromArray(array(
            'name' => $this->xml->template->name,
            'description' => $this->xml->template->description,
            'lexicon' => $this->xml->template->lexicon,
        ));
        /** @var modAccessPolicyTemplateGroup $templateGroup */
        $templateGroup = $this->modx->getObject('modAccessPolicyTemplateGroup',array('name' => (string)$this->xml->template->template_group));
        if ($templateGroup) {
            $template->set('template_group',$templateGroup->get('id'));
        } else {
            $template->set('template_group',1);
        }

        $permissions = array();
        foreach ($this->xml->template->permissions->permission as $permissionXml) {
            /** @var modAccessPermission $permission */
            $permission = $this->modx->newObject('modAccessPermission');
            $permission->set('name',(string)$permissionXml->name);
            $permission->set('description',(string)$permissionXml->description);
            $permission->set('value',(string)$permissionXml->value);
            $permissions[] = $permission;
        }
        $template->addMany($permissions);
        $template->save();
        return $template;
    }
}
return 'modAccessPolicyImportProcessor';
