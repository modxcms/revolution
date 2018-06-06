<?php

namespace MODX\Processors\Security\Access\Policy\Template;

use MODX\modAccessPermission;
use MODX\Processors\modObjectDuplicateProcessor;

/**
 * Duplicates a policy template
 *
 * @param integer $id The ID of the policy template
 *
 * @package modx
 * @subpackage processors.security.access.policy.template
 */
class Duplicate extends modObjectDuplicateProcessor
{
    public $classKey = 'modAccessPolicyTemplate';
    public $languageTopics = ['policy'];
    public $permission = 'policy_template_new';
    public $objectType = 'policy_template';


    public function afterSave()
    {
        $permissions = $this->object->getMany('Permissions');

        /* save new permissions for template */
        if (!empty($permissions)) {
            /** @var modAccessPermission $permission */
            foreach ($permissions as $permission) {
                /** @var modAccessPermission $newPermission */
                $newPermission = $this->modx->newObject('modAccessPermission');
                $newPermission->set('name', $permission->get('name'));
                $newPermission->set('description', $permission->get('description'));
                $newPermission->set('value', $permission->get('value'));
                $newPermission->set('template', $this->newObject->get('id'));
                $newPermission->save();
            }
        }

        return parent::afterSave();
    }
}