<?php
/**
 * Activate a FC Profile
 *
 * @package modx
 * @subpackage processors.security.forms.profile
 */
class modFormCustomizationProfileActivateProcessor extends modObjectUpdateProcessor {
    public $classKey = 'modFormCustomizationProfile';
    public $objectType = 'profile';
    public $languageTopics = array('formcustomization');
    public $permission = 'customize_forms';

    public function beforeSave() {
        $this->object->set('active', true);
        return parent::beforeSave();
    }
}

return 'modFormCustomizationProfileActivateProcessor';
