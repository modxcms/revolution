<?php
/**
 * Create an access policy template
 *
 * @param string $name The name of the policy.
 * @param string $description (optional) A short description
 *
 * @package modx
 * @subpackage processors.security.access.policy.template
 */
class modAccessPolicyTemplateCreateProcessor extends modObjectCreateProcessor {
    public $classKey = 'modAccessPolicyTemplate';
    public $languageTopics = array('policy');
    public $permission = 'policy_template_new';
    public $objectType = 'policy_template';

    public function beforeSet() {
        $name = $this->getProperty('name');
        if (empty($name)) {
            $this->addFieldError('name',$this->modx->lexicon('policy_template_err_name_ns'));
        }

        if ($this->doesAlreadyExist(array('name' => $name))) {
            $this->addFieldError('name',$this->modx->lexicon('policy_template_err_ae',array('name' => $name)));
        }

        return parent::beforeSet();
    }
}
return 'modAccessPolicyTemplateCreateProcessor';