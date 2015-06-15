<?php
/**
 * Create a system setting
 *
 * @param string $key The key to create
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
class modSystemSettingsCreateProcessor extends modObjectCreateProcessor {
    public $classKey = 'modSystemSetting';
    public $languageTopics = array('setting','namespace');
    public $permission = 'settings';
    public $objectType = 'setting';
    public $primaryKeyField = 'key';

    /**
     * Verify the Namespace passed is a valid Namespace
     * @return string|null
     */
    public function verifyNamespace() {
        $namespace = $this->getProperty('namespace', '');
        if (empty($namespace)) {
            $this->addFieldError('namespace', $this->modx->lexicon('namespace_err_ns'));
        }
        $namespace = $this->modx->getObject('modNamespace', $namespace);
        if (!$namespace) {
            $this->addFieldError('namespace', $this->modx->lexicon('namespace_err_nf'));
        }
    }

    /**
     * Verify setting key
     */
    public function verifySettingKey() {
        /* prevent empty or already existing settings */
        $key = trim($this->getProperty('key', ''));
        if (empty($key)) {
            $this->addFieldError('key', $this->modx->lexicon($this->objectType.'_err_ns'));
        }

        /* prevent keys starting with numbers */
        $numbers = explode(',', '1,2,3,4,5,6,7,8,9,0');
        if (in_array(substr($key, 0, 1), $numbers)) {
            $this->addFieldError('key',$this->modx->lexicon($this->objectType.'_err_startint'));
        }
        $this->setProperty('key', $key);
        $this->object->set('key', $key);
    }

    /**
     * If this is a Boolean setting, ensure the value of the setting is 1/0
     * @return mixed
     */
    public function checkForBooleanValue() {
        $xtype = $this->getProperty('xtype', 'textfield');
        $value = $this->getProperty('value');
        if ($xtype == 'combo-boolean' && !is_numeric($value)) {
            $value = in_array($value, array('yes', 'Yes', $this->modx->lexicon('yes'), 'true', 'True')) ? 1 : 0;
            $this->object->set('value',$value);
        }
        return $value;
    }

    public function beforeSave() {
        $this->checkForBooleanValue();
        $this->verifyNamespace();
        $this->verifySettingKey();

        if ($this->alreadyExists()) {
            $this->addFieldError('key',$this->modx->lexicon($this->objectType.'_err_ae'));
        }

        return !$this->hasErrors();

    }
    /**
     * {@inheritDoc}
     * @return mixed
     */
    public function afterSave() {
        $this->setLexiconEntries($this->object->toArray());
        $this->modx->reloadConfig();
        return true;
    }

    /**
     * Check to see if a Setting already exists with this key
     * @return boolean
     */
    public function alreadyExists() {
        return $this->doesAlreadyExist(array(
            'key' => $this->getProperty('key')
        ));
    }

    /**
     * @param array $fields
     * @return void
     */
    public function setLexiconEntries(array $fields) {
        /* set lexicon name/description */
        if (!empty($fields['name'])) {
            /** @var modLexiconEntry $entry */
            $entry = $this->modx->getObject('modLexiconEntry',array(
                'namespace' => $this->object->get('namespace'),
                'topic' => 'default',
                'name' => 'setting_'.$this->object->get('key'),
            ));
            if ($entry == null) {
                $entry = $this->modx->newObject('modLexiconEntry');
                $entry->set('namespace',$this->object->get('namespace'));
                $entry->set('name','setting_'.$this->object->get('key'));
                $entry->set('value',$fields['name']);
                $entry->set('topic','default');
                $entry->set('language',$this->modx->cultureKey);
                $entry->save();

                $entry->clearCache();
            }
        }
        if (!empty($fields['description'])) {
            /** @var modLexiconEntry $description */
            $description = $this->modx->getObject('modLexiconEntry',array(
                'namespace' => $this->object->get('namespace'),
                'topic' => 'default',
                'name' => 'setting_'.$this->object->get('key').'_desc',
            ));
            if ($description == null) {
                $description = $this->modx->newObject('modLexiconEntry');
                $description->set('namespace',$this->object->get('namespace'));
                $description->set('name','setting_'.$this->object->get('key').'_desc');
                $description->set('value',$fields['description']);
                $description->set('topic','default');
                $description->set('language',$this->modx->cultureKey);
                $description->save();

                $description->clearCache();
            }
        }
    }
}
return 'modSystemSettingsCreateProcessor';
