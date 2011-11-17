<?php
/**
 * Updates a context.
 *
 * @param string $key The key of the context
 * @param json $settings A json array of context settings
 *
 * @var modX $this->modx
 * @var modProcessor $this
 * @var array $scriptProperties
 *
 * @package modx
 * @subpackage processors.context
 */
class modContextUpdateProcessor extends modObjectUpdateProcessor {
    public $classKey = 'modContext';
    public $languageTopics = array('context');
    public $permission = 'edit_context';
    public $objectType = 'context';
    public $primaryKeyField = 'key';

    public function afterSave() {
        $this->updateContextSettings();
        $this->runOnUpdateEvent();
        return parent::afterSave();
    }

    /**
     * Update the context settings for this Context
     * @return array
     */
    public function updateContextSettings() {
        $settings = $this->getProperty('settings');
        if (empty($settings)) return array();

        $updatedSettings = array();
        $settings = $this->modx->fromJSON($settings);
        foreach ($settings as $id => $settingArray) {
            /** @var modContextSetting $setting */
            $setting = $this->modx->getObject('modContextSetting',array(
                'context_key' => $this->object->get('key'),
                'key' => $settingArray['key'],
            ));
            if (!$setting) continue;

            $setting->set('value',$settingArray['value']);

            /* if name changed, change lexicon string */
            /** @var modLexiconEntry $entry */
            $entry = $this->modx->getObject('modLexiconEntry',array(
                'namespace' => 'core',
                'name' => 'setting_'.$settingArray['key'],
            ));
            if ($entry != null) {
                $entry->set('value',$settingArray['name']);
                $entry->save();
                $entry->clearCache();
            }

            if ($setting->save()) {
                $updatedSettings[] = $setting;
            }
        }

        if (!empty($updatedSettings)) {
            $this->modx->cacheManager->refresh(array(
                'db' => array(),
                'resource' => array('contexts' => array($this->object->get('key'))),
                'context_settings' => array('contexts' => array($this->object->get('key'))),
            ));
        }
        return $updatedSettings;
    }
    
    /**
     * Run the OnContextUpdate event
     * @return void
     */
    public function runOnUpdateEvent() {
        $this->modx->invokeEvent('OnContextUpdate',array(
            'context' => &$this->object,
            'properties' => $this->getProperties(),
        ));
    }
}
return 'modContextUpdateProcessor';