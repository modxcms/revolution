<?php
/**
 * Deactivate a FC Profile
 *
 * @package modx
 * @subpackage processors.security.forms.profile
 */
class modFormCustomizationProfileDeactivateProcessor extends modObjectUpdateProcessor {
    public $classKey = 'modFormCustomizationProfile';
    public $objectType = 'profile';
    public $languageTopics = array('formcustomization');
    public $permission = 'customize_forms';

    public function beforeSave() {
        $this->object->set('active', false);
        return parent::beforeSave();
    }
}

return 'modFormCustomizationProfileDeactivateProcessor';
