<?php
/**
 * Deactivate a FC Set
 *
 * @package modx
 * @subpackage processors.security.forms.set
 */
class modFormCustomizationSetDeactivateProcessor extends modObjectUpdateProcessor {
    public $classKey = 'modFormCustomizationSet';
    public $objectType = 'set';
    public $languageTopics = array('formcustomization');
    public $permission = 'customize_forms';

    public function beforeSet() {
        $this->unsetProperty('action');
        return parent::beforeSet();
    }

    public function beforeSave() {
        $this->object->set('active', false);
        return parent::beforeSave();
    }
}

return 'modFormCustomizationSetDeactivateProcessor';
