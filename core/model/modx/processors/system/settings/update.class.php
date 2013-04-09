<?php
/**
 * Update a system setting
 *
 * @param string $key The key of the setting
 * @param string $value The value of the setting
 * @param string $xtype The xtype for the setting, for rendering purposes
 * @param string $area The area for the setting
 * @param string $namespace The namespace for the setting
 * @param string $name The lexicon name for the setting
 * @param string $description The lexicon description for the setting
 *
 * @package modx
 * @subpackage processors.system.settings
 */
class modSystemSettingsUpdateProcessor extends modObjectUpdateProcessor {
    public $classKey = 'modSystemSetting';
    public $languageTopics = array('setting','namespace');
    public $permission = 'settings';
    public $objectType = 'setting';
    public $primaryKeyField = 'key';
    
    /** @var modSystemSetting $object */
    public $object;
    /** @var boolean $refreshURIs */
    public $refreshURIs = false;

    public function beforeSave() {
        $this->verifyNamespace();
        $this->checkForBooleanValue();
        $this->refreshURIs = $this->checkForRefreshURIs();
        return parent::beforeSave();
    }

    public function afterSave() {
        $this->updateTranslations($this->getProperties());
        $this->refreshURIs();
        $this->clearCache();
        return parent::afterSave();
    }

    /**
     * Verify the Namespace passed is a valid Namespace
     * @return string|null
     */
    public function verifyNamespace() {
        $namespaceKey = $this->getProperty('namespace');
        if (empty($namespaceKey)) {
            $this->addFieldError('namespace',$this->modx->lexicon('namespace_err_ns'));
        } else {
            $namespace = $this->modx->getObject('modNamespace',$namespaceKey);
            if (empty($namespace)) {
                $this->addFieldError('namespace',$this->modx->lexicon('namespace_err_nf'));
            }
        }
        return $namespaceKey;
    }

    /**
     * If this is a Boolean setting, ensure the value of the setting is 1/0
     * @return mixed
     */
    public function checkForBooleanValue() {
        $xtype = $this->getProperty('xtype');
        $value = $this->getProperty('value');
        if ($xtype == 'combo-boolean' && !is_numeric($value)) {
            $value = in_array($value, array('yes', 'Yes', $this->modx->lexicon('yes'), 'true', 'True')) ? 1 : 0;
            $this->object->set('value',$value);
        }
        return $value;
    }
    
    /**
     * Check to see if the URIs need to be refreshed
     * 
     * @return boolean
     */
    public function checkForRefreshURIs() {
        $refresh = false;
        if ($this->object->get('key') === 'friendly_urls' && $this->object->isDirty('value') && $this->object->get('value') == '1') {
            $refresh = true;
        } else if ($this->object->get('key') === 'use_alias_path' && $this->object->isDirty('value')) {
            $refresh = true;
        } else if ($this->object->get('key') === 'container_suffix' && $this->object->isDirty('value')) {
            $refresh = true;
        }
        return $refresh;
    }

    /**
     * Update lexicon name/description
     * 
     * @param array $fields
     * @return void
     */
    public function updateTranslations(array $fields) {
        if(isset($fields['name'])){
            $this->object->updateTranslation('setting_'.$this->object->get('key'),$fields['name'],array(
                'namespace' => $this->object->get('namespace'),
            ));
        }

        if(isset($fields['description'])){
            $this->object->updateTranslation('setting_'.$this->object->get('key').'_desc',$fields['description'],array(
                'namespace' => $this->object->get('namespace'),
            ));
        }
    }

    /**
     * If friendly_urls is set on or use_alias_path changes, refreshURIs
     * @return boolean
     */
    public function refreshURIs() {
        if ($this->refreshURIs) {
            $this->modx->setOption($this->object->get('key'), $this->object->get('value'));
            $this->modx->call('modResource', 'refreshURIs', array(&$this->modx));
        }
        return $this->refreshURIs;
    }

    /**
     * Clear the settings cache and reload the config
     * @return void
     */
    public function clearCache() {
        $this->modx->reloadConfig();
    }
}
return 'modSystemSettingsUpdateProcessor';