<?php
/**
 * Create a FC Profile
 *
 * @package modx
 * @subpackage processors.security.forms.profile
 */
class modFormCustomizationProfileCreateProcessor extends modObjectCreateProcessor {
    public $classKey = 'modFormCustomizationProfile';
    public $languageTopics = array('formcustomization');
    public $permission = 'customize_forms';
    public $objectType = 'profile';
}
return 'modFormCustomizationProfileCreateProcessor';