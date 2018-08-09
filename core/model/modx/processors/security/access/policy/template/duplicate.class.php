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
 * Duplicates a policy template
 *
 * @param integer $id The ID of the policy template
 *
 * @package modx
 * @subpackage processors.security.access.policy.template
 */
class modAccessPolicyTemplateDuplicateProcessor extends modObjectDuplicateProcessor {
    public $classKey = 'modAccessPolicyTemplate';
    public $languageTopics = array('policy');
    public $permission = 'policy_template_new';
    public $objectType = 'policy_template';

    public function afterSave() {
        $permissions = $this->object->getMany('Permissions');

        /* save new permissions for template */
        if (!empty($permissions)) {
            /** @var modAccessPermission $permission */
            foreach ($permissions as $permission) {
                /** @var modAccessPermission $newPermission */
                $newPermission = $this->modx->newObject('modAccessPermission');
                $newPermission->set('name',$permission->get('name'));
                $newPermission->set('description',$permission->get('description'));
                $newPermission->set('value',$permission->get('value'));
                $newPermission->set('template',$this->newObject->get('id'));
                $newPermission->save();
            }
        }
        return parent::afterSave();
    }
}
return 'modAccessPolicyTemplateDuplicateProcessor';
