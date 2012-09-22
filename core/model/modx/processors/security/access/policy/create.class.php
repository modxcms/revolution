<?php
/**
 * Create an access policy.
 *
 * @param string $name The name of the policy.
 * @param string $description (optional) A short description
 * @param integer $parent (optional) A parent policy
 * @param string $class
 * @param json $data The JSON-encoded policy data
 *
 * @package modx
 * @subpackage processors.security.access.policy
 */

class modAccessPolicyCreateProcessor extends modObjectCreateProcessor {
    public $classKey = 'modAccessPolicy';
    public $languageTopics = array('policy');
    public $permission = 'policy_new';
    public $objectType = 'policy';
    
    public function beforeSet() {
        $name = $this->getProperty('name');
        if (empty($name)) {
            $this->addFieldError('name',$this->modx->lexicon('policy_err_name_ns'));
        }

        if ($this->doesAlreadyExist(array('name' => $name))) {
            $this->addFieldError('name',$this->modx->lexicon('policy_err_ae',array('name' => $name)));
        }

        return parent::beforeSet();
    }

    public function beforeSave() {
        /** @var modAccessPolicyTemplate $template */
        $template = $this->modx->getObject('modAccessPolicyTemplate',$this->getProperty('template'));
        if (empty($template)) {
            return $this->addFieldError('template',$this->modx->lexicon('policy_template_err_nf'));
        } else {
            $permissions = $template->getMany('Permissions');
            $permList = array();
            /** @var modAccessPermission $permission */
            foreach ($permissions as $permission) {
                $permList[$permission->get('name')] = true;
            }
            $this->object->set('data',$permList);
        }
        return parent::beforeSave();
    }
}
return 'modAccessPolicyCreateProcessor';