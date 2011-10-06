<?php
/**
 * Remove a system setting
 *
 * @param string $key The key of the setting
 *
 * @package modx
 * @subpackage processors.system.settings
 */
class modSystemSettingsRemoveProcessor extends modProcessor {
    /** @var modSystemSetting $setting */
    public $setting;

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
     * @return mixed
     */
    public function process() {
        /* remove setting */
        if ($this->setting->remove() == false) {
            return $this->failure($this->modx->lexicon('setting_err_remove'));
        }

        $this->removeRelatedLexiconEntries();

        $this->modx->reloadConfig();
        $this->logManagerAction();
        return $this->success('',$this->setting);
    }

    /**
     * Remove all Lexicon Entries related to the setting
     * @return void
     */
    public function removeRelatedLexiconEntries() {
        /** @var modLexiconEntry $entry */
        $entry = $this->modx->getObject('modLexiconEntry',array(
            'namespace' => $this->setting->get('namespace'),
            'name' => 'setting_'.$this->setting->get('key'),
        ));
        if (!empty($entry)) {
            $entry->remove();
        }

        /** @var modLexiconEntry $description */
        $description = $this->modx->getObject('modLexiconEntry',array(
            'namespace' => $this->setting->get('namespace'),
            'name' => 'setting_'.$this->setting->get('key').'_desc',
        ));
        if (!empty($description)) {
            $description->remove();
        }
    }

    /**
     * @return void
     */
    public function logManagerAction() {
        $this->modx->logManagerAction('setting_delete','modSystemSetting',$this->setting->get('key'));
    }
}
return 'modSystemSettingsRemoveProcessor';