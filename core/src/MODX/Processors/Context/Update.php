<?php

namespace MODX\Processors\Context;

use MODX\modContextSetting;
use MODX\modLexiconEntry;
use MODX\Processors\modObjectUpdateProcessor;

/**
 * Updates a context.
 *
 * @param string $key The key of the context
 * @param string $settings A json array of context settings
 *
 * @package modx
 * @subpackage processors.context
 */
class Update extends modObjectUpdateProcessor
{
    public $classKey = 'modContext';
    public $languageTopics = ['context'];
    public $permission = 'edit_context';
    public $objectType = 'context';
    public $primaryKeyField = 'key';


    public function afterSave()
    {
        $this->updateContextSettings();
        $this->runOnUpdateEvent();

        return parent::afterSave();
    }


    /**
     * Update the context settings for this Context
     *
     * @return array
     */
    public function updateContextSettings()
    {
        $settings = $this->getProperty('settings');
        if (empty($settings)) return [];

        $updatedSettings = [];
        $settings = json_decode($settings, true);
        foreach ($settings as $id => $settingArray) {
            /** @var modContextSetting $setting */
            $setting = $this->modx->getObject('modContextSetting', [
                'context_key' => $this->object->get('key'),
                'key' => $settingArray['key'],
            ]);
            if (!$setting) continue;

            $setting->set('value', $settingArray['value']);

            /* if name changed, change lexicon string */
            /** @var modLexiconEntry $entry */
            $entry = $this->modx->getObject('modLexiconEntry', [
                'namespace' => 'core',
                'name' => 'setting_' . $settingArray['key'],
            ]);
            if ($entry != null) {
                $entry->set('value', $settingArray['name']);
                $entry->save();
                $entry->clearCache();
            }

            if ($setting->save()) {
                $updatedSettings[] = $setting;
            }
        }

        if (!empty($updatedSettings)) {
            $this->modx->cacheManager->refresh([
                'db' => [],
                'resource' => ['contexts' => [$this->object->get('key')]],
                'context_settings' => ['contexts' => [$this->object->get('key')]],
            ]);
        }

        return $updatedSettings;
    }


    /**
     * Run the OnContextUpdate event
     *
     * @return void
     */
    public function runOnUpdateEvent()
    {
        $this->modx->invokeEvent('OnContextUpdate', [
            'context' => &$this->object,
            'properties' => $this->getProperties(),
        ]);
    }
}