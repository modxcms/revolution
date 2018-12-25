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
class modAccessPolicyTemplateImportProcessor extends modObjectImportProcessor {
    public $objectType = 'policy_template';
    public $classKey = 'modAccessPolicyTemplate';
    public $permission = 'policy_template_view';
    public $languageTopics = array('policy');

    public function beforeSave() {
        /** @var modAccessPolicyTemplate $template */
        $this->object->set('description',(string)$this->xml->description);
        $this->object->set('lexicon',(string)$this->xml->lexicon);

        $this->setTemplateGroup();
        $this->addPermissions();
        return parent::beforeSave();
    }

    public function setTemplateGroup() {
        /** @var modAccessPolicyTemplateGroup $templateGroup */
        $templateGroup = $this->modx->getObject('modAccessPolicyTemplateGroup',array('name' => (string)$this->xml->template_group));
        if ($templateGroup) {
            $this->object->set('template_group',$templateGroup->get('id'));
        } else {
            $this->object->set('template_group',1);
        }
    }

    public function addPermissions() {
        $permissions = array();
        foreach ($this->xml->permissions->permission as $permissionXml) {
            /** @var modAccessPermission $permission */
            $permission = $this->modx->newObject('modAccessPermission');
            $permission->set('name',(string)$permissionXml->name);
            $permission->set('description',(string)$permissionXml->description);
            $permission->set('value',(string)$permissionXml->value);
            $permissions[] = $permission;
        }

        $this->object->addMany($permissions);
    }
}
return 'modAccessPolicyTemplateImportProcessor';
