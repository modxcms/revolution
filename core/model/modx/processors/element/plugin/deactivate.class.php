<?php
/**
 * Deactivate a plugin.
 *
 * @param integer $id The ID of the plugin.
 *
 * @package modx
 * @subpackage processors.element.plugin
 */
class modPluginDeactivateProcessor extends modObjectUpdateProcessor {
    public $classKey = 'modPlugin';
    public $languageTopics = array('plugin','category','element');
    public $permission = 'save_plugin';
    public $objectType = 'plugin';
    public $checkViewPermission = false;

    public function beforeSave() {
        $this->object->set('disabled',true);
        return parent::beforeSave();
    }

    public function afterSave() {
        $this->modx->cacheManager->refresh();
        return parent::afterSave();
    }

    public function cleanup() {
        return $this->success('',array($this->object->get('id')));
    }
}
return 'modPluginDeactivateProcessor';
