<?php
/*
 * This file is part of the MODX Revolution package.
 *
 * Copyright (c) MODX, LLC
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace MODX\Revolution\Processors\Context;


use MODX\Revolution\modContext;
use MODX\Revolution\modContextSetting;
use MODX\Revolution\modLexiconEntry;
use MODX\Revolution\Processors\Model\UpdateProcessor;

/**
 * Updates a context.
 *
 * @property string $key      The key of the context
 * @property string $settings A JSON object of context settings
 *
 * @package MODX\Revolution\Processors\Context
 */
class Update extends UpdateProcessor
{
    public $classKey = modContext::class;
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
        if (empty($settings)) {
            return [];
        }

        $updatedSettings = [];
        $settings = $this->modx->fromJSON($settings);
        foreach ($settings as $id => $settingArray) {
            /** @var modContextSetting $setting */
            $setting = $this->modx->getObject(modContextSetting::class, [
                'context_key' => $this->object->get('key'),
                'key' => $settingArray['key'],
            ]);
            if (!$setting) {
                continue;
            }

            $setting->set('value', $settingArray['value']);

            /* if name changed, change lexicon string */
            /** @var modLexiconEntry $entry */
            $entry = $this->modx->getObject(modLexiconEntry::class, [
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
