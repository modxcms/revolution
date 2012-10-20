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
class modContextCreateProcessor extends modObjectCreateProcessor {
    public $classKey = 'modContext';
    public $languageTopics = array('context');
    public $permission = 'new_context';
    public $objectType = 'context';
    public $primaryKeyField = 'key';

    public function beforeSave() {
        $key = $this->getProperty('key');
        if (empty($key)) {
            $this->addFieldError('key',$this->modx->lexicon('context_err_ns_key'));
        }
        if ($this->alreadyExists($key)) {
            $this->addFieldError('key',$this->modx->lexicon('context_err_ae'));
        }
        $this->object->set('key',$key);
        
        return !$this->hasErrors();
    }
    
    /**
     * {inheritDoc}
     * 
     * @return mixed
     */
    public function afterSave() {
        $this->ensureAdministratorAccess();
        if ($this->getProperty('enableAnonymous', true)) {
            $this->enableAnonymousAccess();
        }
        $this->refreshUserACLs();
        return true;
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
        $adminContextPolicy = $this->modx->getObject('modAccessPolicy',array('name' => 'Context'));
        if ($adminGroup) {
            if ($adminContextPolicy) {
                /** @var modAccessContext $adminAdminAccess */
                $adminAdminAccess = $this->modx->newObject('modAccessContext');
                $adminAdminAccess->set('principal',$adminGroup->get('id'));
                $adminAdminAccess->set('principal_class','modUserGroup');
                $adminAdminAccess->set('target',$this->object->get('key'));
                $adminAdminAccess->set('policy',$adminContextPolicy->get('id'));
                $adminAdminAccess->save();
            }
        }
    }

    /**
     * Enable anonymous Load Only access to a context.
     * 
     * @return void
     */
    public function enableAnonymousAccess() {
        $anonContextPolicy = $this->modx->getObject('modAccessPolicy', array('name' => 'Load Only'));
        $anonACL = $this->modx->getObject('modAccessContext', array(
            'principal' => 0,
            'principal_class' => 'modUserGroup',
            'target' => $this->object->get('key'),
            'authority' => 9999
        ));
        if ($anonContextPolicy && !$anonACL) {
            $anonACL = $this->modx->newObject('modAccessContext');
            $anonACL->set('principal', 0);
            $anonACL->set('principal_class', 'modUserGroup');
            $anonACL->set('target', $this->object->get('key'));
            $anonACL->set('policy', $anonContextPolicy->get('id'));
            $anonACL->set('authority', 9999);
            $anonACL->save();
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
}
return 'modContextCreateProcessor';