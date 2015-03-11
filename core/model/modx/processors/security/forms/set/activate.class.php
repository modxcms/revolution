<?php
/**
 * Activate a FC Set
 *
 * @package modx
 * @subpackage processors.security.forms.set
 */
class modFormCustomizationSetActivateProcessor extends modObjectUpdateProcessor {
    public $classKey = 'modFormCustomizationSet';
    public $objectType = 'set';
    public $languageTopics = array('formcustomization');
    public $permission = 'customize_forms';

    public function beforeSet() {
        $this->unsetProperty('action');
        return parent::beforeSet();
    }

    public function beforeSave() {
        $this->object->set('active', true);
        return parent::beforeSave();
    }
}

return 'modFormCustomizationSetActivateProcessor';
