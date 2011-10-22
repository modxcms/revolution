<?php
/**
 * Duplicates a context.
 *
 * @param string $key The key of the context
 * @param string $newkey The new key of the duplicated context
 *
 * @package modx
 * @subpackage processors.context
 */
class modContextDuplicateProcessor extends modProcessor {
    /** @var modContext $oldContext */
    public $oldContext;
    /** @var modContext $newContext */
    public $newContext;

    public function checkPermissions() {
        return $this->modx->hasPermission('new_context');
    }
    public function getLanguageTopics() {
        return array('context');
    }

    public function initialize() {
        $key = $this->getProperty('key');
        if (empty($key)) return $this->modx->lexicon('context_err_ns');
        $this->oldContext = $this->modx->getObject('modContext',$key);
        if (!$this->oldContext) {
            return $this->modx->lexicon('context_err_nfs',array('key' => $key));
        }
        return true;
    }

    public function process() {
        if (!$this->validate()) {
            return $this->failure();
        }

        /* create new context */
        $this->newContext = $this->modx->newObject('modContext');
        $this->newContext->set('key',$this->getProperty('newKey'));
        if ($this->newContext->save() == false) {
            return $this->failure($this->modx->lexicon('context_err_duplicate'));
        }

        $this->duplicateSettings();
        $this->duplicateAccessControlLists();
        $this->reloadPermissions();
        $this->duplicateResources();

        return $this->success('',$this->newContext);

    }

    /**
     * Validate the passed properties for the new context
     * @return boolean
     */
    public function validate() {
        $newKey = $this->getProperty('newkey');
        /* make sure the new key is a valid PHP identifier with no underscore characters */
        if (empty($newKey) || !preg_match('/^[a-zA-Z\x7f-\xff][a-zA-Z0-9\x2d-\x2f\x7f-\xff]*$/', $newKey)) {
            $this->addFieldError('newkey', $this->modx->lexicon('context_err_ns_key'));
        }

        if ($this->alreadyExists($newKey)) {
            $this->addFieldError('newkey',$this->modx->lexicon('context_err_ae'));
        }

        return !$this->hasErrors();
    }

    /**
     * Checks to see if a context with specified key already exists
     * @param string $key
     * @return boolean
     */
    public function alreadyExists($key) {
        return $this->modx->getCount('modContext',array('key' => $key)) > 0;
    }

    /**
     * Duplicate the settings of the old Context to the new one
     * @return array
     */
    public function duplicateSettings() {
        $duplicatedSettings = array();
        $settings = $this->modx->getCollection('modContextSetting',array(
            'context_key' => $this->oldContext->get('key'),
        ));
        /** @var modContextSetting $setting */
        foreach ($settings as $setting) {
            /** @var $newSetting modContextSetting */
            $newSetting = $this->modx->newObject('modContextSetting');
            $newSetting->fromArray($setting->toArray(),'',true,true);
            $newSetting->set('context_key',$this->newContext->get('key'));
            $newSetting->save();
            $duplicatedSettings[] = $newSetting;
        }
        return $duplicatedSettings;
    }

    /**
     * Duplicate the ACLs of the old Context into the new one
     * @return array
     */
    public function duplicateAccessControlLists() {
        $duplicatedACLs = array();
        $permissions = $this->modx->getCollection('modAccessContext', array(
            'target' => $this->oldContext->get('key')
        ));
        /** @var modAccessContext $acl */
        foreach ($permissions as $acl) {
            /** @var modAccessContext $newAcl */
            $newAcl = $this->modx->newObject('modAccessContext');
            $newAcl->fromArray($acl->toArray(),'',false,true);
            $newAcl->set('target', $this->newContext->get('key'));
            $newAcl->save();
            $duplicatedACLs[] = $newAcl;
        }
        return $duplicatedACLs;
    }

    /**
     * Flush permissions for the mgr user to properly handle the new context
     * @return void
     */
    public function reloadPermissions() {
        if ($this->modx->getUser()) {
            $this->modx->user->getAttributes(array(), '', true);
        }
    }

    /**
     * Duplicate the Resources of the old Context into the new one
     * @return void
     */
    public function duplicateResources() {
        $resources = $this->modx->getCollection('modResource',array(
            'context_key' => $this->oldContext->get('key'),
            'parent' => 0,
        ));
        if (count($resources) > 0) {
            /** @var modResource $resource */
            foreach ($resources as $resource) {
                $resource->duplicate(array(
                    'prefixDuplicate' => false,
                    'duplicateChildren' => true,
                    'overrides' => array(
                        'context_key' => $this->newContext->get('key'),
                    ),
                ));
            }
        }
    }
}
return 'modContextDuplicateProcessor';