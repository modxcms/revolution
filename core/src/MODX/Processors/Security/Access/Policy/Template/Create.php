<?php

namespace MODX\Processors\Security\Access\Policy\Template;

use MODX\Processors\modObjectCreateProcessor;

/**
 * Create an access policy template
 *
 * @param string $name The name of the policy.
 * @param string $description (optional) A short description
 *
 * @package modx
 * @subpackage processors.security.access.policy.template
 */
class Create extends modObjectCreateProcessor
{
    public $classKey = 'modAccessPolicyTemplate';
    public $languageTopics = ['policy'];
    public $permission = 'policy_template_new';
    public $objectType = 'policy_template';


    public function beforeSet()
    {
        $name = $this->getProperty('name');
        if (empty($name)) {
            $this->addFieldError('name', $this->modx->lexicon('policy_template_err_name_ns'));
        }

        if ($this->doesAlreadyExist(['name' => $name])) {
            $this->addFieldError('name', $this->modx->lexicon('policy_template_err_ae', ['name' => $name]));
        }

        return parent::beforeSet();
    }
}