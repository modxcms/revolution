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
class modSystemSettingsUpdateProcessor extends modProcessor {
    /** @var modSystemSetting $setting */
    public $setting;
    public $refreshURIs = false;

    public function checkPermissions() {
        return $this->modx->hasPermission('settings');
    }
    public function getLanguageTopics() {
        return array('setting','namespace');
    }

    public function initialize() {
        $key = $this->getProperty('key');
        if (empty($key)) return $this->modx->lexicon('setting_err_ns');
        $this->setting = $this->modx->getObject('modSystemSetting',$key);
        if (empty($this->setting)) return $this->modx->lexicon('setting_err_nf');
        return true;
    }

    /**
     * {@inheritDoc}
     * 
     * @return mixed
     */
    public function process() {
        $fields = $this->getProperties();
        if (!$this->validate($fields)) {
            return $this->failure();
        }

        $fields = $this->clean($fields);
        $this->setting->fromArray($fields,'',true);
        $this->refreshURIs = $this->checkForRefreshURIs($fields);

        /* save setting */
        if ($this->setting->save() === false) {
            $this->modx->error->checkValidation($this->setting);
            return $this->failure($this->modx->lexicon('setting_err_save'));
        }
        
        $this->updateTranslations($fields);

        $this->refreshURIs();
        $this->clearCache();
        $this->logManagerAction();
        
        return $this->success('',$this->setting);
    }

    /**
     * Validate the properties sent
     * 
     * @param array $fields
     * @return bool
     */
    public function validate(array $fields) {
        /* verify namespace */
        if (empty($fields['namespace'])) {
            $this->addFieldError('namespace',$this->modx->lexicon('namespace_err_ns'));
        } else {
            $namespace = $this->modx->getObject('modNamespace',$fields['namespace']);
            if (empty($namespace)) {
                $this->addFieldError('namespace',$this->modx->lexicon('namespace_err_nf'));
            }
        }

        return !$this->hasErrors();
    }

    /**
     * Parse and clean the sent fields
     *
     * @param array $fields
     * @return array
     */
    public function clean(array $fields) {
        if ($fields['xtype'] == 'combo-boolean' && !is_numeric($fields['value'])) {
            if (in_array($fields['value'], array('yes', 'Yes', $this->modx->lexicon('yes'), 'true', 'True'))) {
                $fields['value'] = 1;
            } else $fields['value'] = 0;
        }
        return $fields;
    }

    /**
     * Check to see if the URIs need to be refreshed
     * 
     * @return boolean
     */
    public function checkForRefreshURIs() {
        $refresh = false;
        if ($this->setting->get('key') === 'friendly_urls' && $this->setting->isDirty('value') && $this->setting->get('value') == '1') {
            $refresh = true;
        } else if ($this->setting->get('key') === 'use_alias_path' && $this->setting->isDirty('value')) {
            $refresh = true;
        } else if ($this->setting->get('key') === 'container_suffix' && $this->setting->isDirty('value')) {
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
        $this->setting->updateTranslation('setting_'.$this->setting->get('key'),$fields['name'],array(
            'namespace' => $this->setting->get('namespace'),
        ));
        $this->setting->updateTranslation('setting_'.$this->setting->get('key').'_desc',$fields['description'],array(
            'namespace' => $this->setting->get('namespace'),
        ));
    }

    /**
     * If friendly_urls is set on or use_alias_path changes, refreshURIs
     * @return boolean
     */
    public function refreshURIs() {
        if ($this->refreshURIs) {
            $this->modx->setOption($this->setting->get('key'), $this->setting->get('value'));
            $this->modx->call('modResource', 'refreshURIs', array(&$this->modx));
        }
        return $this->refreshURIs;
    }

    /**
     * Clear the settings cache and reload the config
     * @return void
     */
    public function clearCache() {
        $this->modx->cacheManager->deleteTree($this->modx->getOption('core_path',null,MODX_CORE_PATH).'cache/mgr/smarty/',array(
           'deleteTop' => false,
            'skipDirs' => false,
            'extensions' => array('.cache.php','.php'),
        ));
        $this->modx->reloadConfig();
    }

    /**
     * @return void
     */
    public function logManagerAction() {
        $this->modx->logManagerAction('setting_update','modSystemSetting',$this->setting->get('key'));
    }
}
return 'modSystemSettingsUpdateProcessor';