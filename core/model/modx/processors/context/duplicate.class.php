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
class modContextDuplicateProcessor extends modObjectDuplicateProcessor {
    public $classKey = 'modContext';
    public $languageTopics = array('context');
    public $permission = 'new_context';
    public $objectType = 'context';
    public $primaryKeyField = 'key';
    public $nameField = 'key';
    public $newNameField = 'newkey';

    public function afterSave() {
        $this->duplicateSettings();
        $this->duplicateAccessControlLists();
        $this->reloadPermissions();
        $this->duplicateMediaSourceElements();
        if (($this->getProperty('preserve_resources') == 'on')) {
            $this->duplicateResources();
        }
        return parent::afterSave();
    }

    /**
     * Validate the passed properties for the new context
     * @return boolean
     */
    public function beforeSave() {
        $newKey = $this->getProperty($this->newNameField);
        /* make sure the new key is a valid PHP identifier with no underscore characters */
        if (empty($newKey) || !preg_match('/^[a-zA-Z\x7f-\xff][a-zA-Z0-9\x2d-\x2f\x7f-\xff]*$/', $newKey)) {
            $this->addFieldError($this->newNameField,$this->modx->lexicon('context_err_ns_key'));
        }

        return parent::beforeSave();
    }

    /**
     * Get the new name for the duplicate
     * @return string
     */
    public function getNewName() {
        $name = $this->getProperty($this->newNameField);
        $newName = !empty($name) ? $name : $this->modx->lexicon('duplicate_of',array('name' => $this->object->get($this->nameField)));
        return $newName;
    }

    /**
     * Duplicate the settings of the old Context to the new one
     * @return array
     */
    public function duplicateSettings() {
        $duplicatedSettings = array();
        $settings = $this->modx->getCollection('modContextSetting',array(
            'context_key' => $this->object->get('key'),
        ));
        /** @var modContextSetting $setting */
        foreach ($settings as $setting) {
            /** @var $newSetting modContextSetting */
            $newSetting = $this->modx->newObject('modContextSetting');
            $newSetting->fromArray($setting->toArray(),'',true,true);
            $newSetting->set('context_key',$this->newObject->get('key'));
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
            'target' => $this->object->get('key')
        ));
        /** @var modAccessContext $acl */
        foreach ($permissions as $acl) {
            /** @var modAccessContext $newAcl */
            $newAcl = $this->modx->newObject('modAccessContext');
            $newAcl->fromArray($acl->toArray(),'',false,true);
            $newAcl->set('target', $this->newObject->get('key'));
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
     * Duplicate the MediaSourceElements of the old Context into the new one
     * @return array
     */
    public function duplicateMediaSourceElements() {
        $duplicatedElements = array();
        $mediaSourcesElements = $this->modx->getCollection('sources.modMediaSourceElement', array(
            'context_key'  => $this->object->get('key'),
        ));

        /** @var modMediaSourceElement $mediaSourcesElement */
        foreach ($mediaSourcesElements as $mediaSourcesElement) {
            /** @var modMediaSourceElement $newMediaSourcesElement */
            $newMediaSourcesElement = $this->modx->newObject('sources.modMediaSourceElement');
            $newMediaSourcesElement->set('source', $mediaSourcesElement->get('source'));
            $newMediaSourcesElement->set('object_class', $mediaSourcesElement->get('object_class'));
            $newMediaSourcesElement->set('object', $mediaSourcesElement->get('object'));
            $newMediaSourcesElement->set('context_key', $this->newObject->get('key'));
            $newMediaSourcesElement->save();
            $duplicatedElements[] = $mediaSourcesElement;
        }
        return $duplicatedElements;
    }

    /**
     * Duplicate the Resources of the old Context into the new one
     * @return void
     */
    public function duplicateResources() {
        $criteria = array(
            'context_key' => $this->object->get('key'),
            'parent' => 0,
        );
        $count = $this->modx->getCount('modResource',$criteria);
        
        if ($count > 0) {
            $resources = $this->modx->getIterator('modResource',$criteria);

            /** @var modResource $resource */
            foreach ($resources as $resource) {
                $resource->duplicate(array(
                    'prefixDuplicate' => false,
                    'duplicateChildren' => true,
                    'overrides' => array(
                        'context_key' => $this->newObject->get('key'),
                    ),
                    'preserve_alias' => ($this->getProperty('preserve_alias') == 'on') ? true : false,
                    'preserve_menuindex' => ($this->getProperty('preserve_menuindex') == 'on') ? true: false,
                ));
            }
        }
    }
}
return 'modContextDuplicateProcessor';
