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
class modSystemSettingsCreateProcessor extends modProcessor {
    /** @var modSystemSetting $setting */
    public $setting;
    
    public function checkPermissions() {
        return $this->modx->hasPermission('settings');
    }
    public function getLanguageTopics() {
        return array('setting','namespace');
    }

    public function initialize() {
        $this->setting = $this->modx->newObject('modSystemSetting');
        return true;
    }

    /**
     * {@inheritDoc}
     * @return mixed
     */
    public function process() {
        $fields = $this->getProperties();
        
        if (!$this->validate($fields)) {
            return $this->failure();
        }

        /* value parsing */
        if ($fields['xtype'] == 'combo-boolean' && !is_numeric($fields['value'])) {
            if ($fields['value'] == 'yes' || $fields['value'] == 'Yes' || $fields['value'] == $this->modx->lexicon('yes')) {
                $fields['value'] = 1;
            } else {
                $fields['value'] = 0;
            }
        }
        $this->setting->fromArray($fields,'',true);

        if ($this->setting->save() === false) {
            $this->modx->error->checkValidation($this->setting);
            return $this->failure($this->modx->lexicon('setting_err_save'));
        }

        $this->setLexiconEntries($fields);
        $this->logManagerAction();

        $this->modx->reloadConfig();

        return $this->success('',$this->setting);
    }

    /**
     * Validate the properties sent
     * 
     * @param array $fields
     * @return bool
     */
    public function validate(array $fields) {
        /* get namespace */
        if (empty($fields['namespace'])) $this->addFieldError('namespace',$this->modx->lexicon('namespace_err_ns'));
        $namespace = $this->modx->getObject('modNamespace',$fields['namespace']);
        if (empty($namespace)) $this->addFieldError('namespace',$this->modx->lexicon('namespace_err_nf'));

        /* prevent empty or already existing settings */
        if (empty($fields['key'])) $this->modx->error->addField('key',$this->modx->lexicon('setting_err_ns'));
        if ($this->alreadyExists($fields['key'])) $this->addFieldError('key',$this->modx->lexicon('setting_err_ae'));

        /* prevent keys starting with numbers */
        $numbers = explode(',','1,2,3,4,5,6,7,8,9,0');
        if (in_array(substr($fields['key'],0,1),$numbers)) {
            $this->addFieldError('key',$this->modx->lexicon('setting_err_startint'));
        }

        return !$this->hasErrors();
    }

    /**
     * Check to see if a Setting already exists with this key
     * @param string $key
     * @return boolean
     */
    public function alreadyExists($key) {
        return $this->modx->getCount('modSystemSetting',array(
            'key' => $key,
        )) > 0;
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
                'namespace' => $this->setting->get('namespace'),
                'topic' => 'default',
                'name' => 'setting_'.$this->setting->get('key'),
            ));
            if ($entry == null) {
                $entry = $this->modx->newObject('modLexiconEntry');
                $entry->set('namespace',$this->setting->get('namespace'));
                $entry->set('name','setting_'.$this->setting->get('key'));
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
                'namespace' => $this->setting->get('namespace'),
                'topic' => 'default',
                'name' => 'setting_'.$this->setting->get('key').'_desc',
            ));
            if ($description == null) {
                $description = $this->modx->newObject('modLexiconEntry');
                $description->set('namespace',$this->setting->get('namespace'));
                $description->set('name','setting_'.$this->setting->get('key').'_desc');
                $description->set('value',$fields['description']);
                $description->set('topic','default');
                $description->set('language',$this->modx->cultureKey);
                $description->save();

                $description->clearCache();
            }
        }
    }

    /**
     * @return void
     */
    public function logManagerAction() {
        $this->modx->logManagerAction('setting_create','modSystemSetting',$this->setting->get('key'));
    }
}
return 'modSystemSettingsCreateProcessor';