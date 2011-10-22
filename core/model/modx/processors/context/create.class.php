<?php
/**
 * Creates a context
 *
 * @param string $key The key of the context
 *
 * @var modX $this->modx
 * @var array $scriptProperties
 *
 * @package modx
 * @subpackage processors.context
 */
class modContextCreateProcessor extends modProcessor {
    /** @var modContext $context */
    public $context;

    public function checkPermissions() {
        return $this->modx->hasPermission('new_context');
    }

    public function getLanguageTopics() {
        return array('context');
    }

    /**
     * {inheritDoc}
     * 
     * @return mixed
     */
    public function process() {
        if (!$this->validate()) {
            return $this->failure();
        }

        /* @var modContext $context */
        $this->context= $this->modx->newObject('modContext');
        $this->context->fromArray($this->getProperties(), '', true);
        if ($this->context->save() == false) {
            $this->modx->error->checkValidation($this->context);
            return $this->failure($this->modx->lexicon('context_err_create'));
        }

        $this->ensureAdministratorAccess();
        $this->refreshUserACLs();
        $this->logManagerAction();
        return $this->success('',$this->context);
    }

    /**
     * Validate the passed properties
     * 
     * @return boolean
     */
    public function validate() {
        $key = $this->getProperty('key');
        if (empty($key)) {
            $this->addFieldError('key',$this->modx->lexicon('context_err_ns_key'));
        }
        if ($this->alreadyExists($key)) {
            $this->addFieldError('key',$this->modx->lexicon('context_err_ae'));
        }
        return !$this->hasErrors();
    }

    /**
     * Check to see if the context already exists
     *
     * @param string $key
     * @return boolean
     */
    public function alreadyExists($key) {
        return $this->modx->getCount('modContext',$key) > 0;
    }
    /**
     * Ensure that Admin User Group always has access to this context, so that it never loses the ability
     * to remove or edit it.
     *
     * @return void
     */
    public function ensureAdministratorAccess() {
        /** @var modUserGroup $adminGroup */
        $adminGroup = $this->modx->getObject('modUserGroup',array('name' => 'Administrator'));
        /** @var modAccessPolicy $adminContextPolicy */
        $adminContextPolicy = $this->modx->getObject('modAccessPolicy',array('name' => 'Object'));
        if ($adminGroup) {
            if ($adminContextPolicy) {
                /** @var modAccessContext $adminAdminAccess */
                $adminAdminAccess = $this->modx->newObject('modAccessContext');
                $adminAdminAccess->set('principal',$adminGroup->get('id'));
                $adminAdminAccess->set('principal_class','modUserGroup');
                $adminAdminAccess->set('target',$this->context->get('key'));
                $adminAdminAccess->set('policy',$adminContextPolicy->get('id'));
                $adminAdminAccess->save();
            }
        }
    }

    /**
     * Refresh the mgr user ACLs to accurately update the context's permissions
     * @return void
     */
    public function refreshUserACLs() {
        if ($this->modx->getUser()) {
            $this->modx->user->getAttributes(array(), '', true);
        }
    }

    /**
     * Log the action of creating the context
     * @return void
     */
    public function logManagerAction() {
        $this->modx->logManagerAction('context_create','modContext',$this->context->get('id'));
    }
}
return 'modContextCreateProcessor';