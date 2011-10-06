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
class modContextUpdateProcessor extends modProcessor {
    /** @var modContext $context */
    public $context;

    public function checkPermissions() {
        return $this->modx->hasPermission('edit_context');
    }

    public function getLanguageTopics() {
        return array('context');
    }

    /**
     * {@inheritDoc}
     *
     * @return mixed
     */
    public function initialize() {
        $key = $this->getProperty('key');
        if (empty($key)) {
            return $this->modx->lexicon('context_err_ns');
        }
        $this->context = $this->modx->getObject('modContext',$key);
        if (empty($this->context)) {
            return $this->modx->lexicon('context_err_nf');
        }
        return true;
    }

    /**
     * {@inheritDoc}
     *
     * @return array|string
     */
    public function process() {
        /* set values */
        $this->context->fromArray($this->getProperties());

        /* save context */
        if ($this->context->save() === false) {
            $this->modx->error->checkValidation($this->context);
            return $this->failure($this->modx->lexicon('context_err_save'));
        }
        
        $this->updateContextSettings();
        $this->runOnUpdateEvent();
        $this->logManagerAction();

        return $this->success('', $this->context);

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
                'context_key' => $this->context->get('key'),
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
        return $updatedSettings;
    }
    
    /**
     * Run the OnContextUpdate event
     * @return void
     */
    public function runOnUpdateEvent() {
        $this->modx->invokeEvent('OnContextUpdate',array(
            'context' => &$this->context,
            'properties' => $this->getProperties(),
        ));
    }

    /**
     * Log manager action for the updating of this Context
     * @return void
     */
    public function logManagerAction() {
        $this->modx->logManagerAction('context_update','modContext',$this->context->get('key'));
    }
}
return 'modContextUpdateProcessor';