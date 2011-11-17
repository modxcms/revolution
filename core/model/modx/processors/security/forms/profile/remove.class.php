<?php
/**
 * Remove FC Profile
 *
 * @package modx
 * @subpackage processors.security.forms.profile
 */
class modFormCustomizationProfileRemoveProcessor extends modObjectRemoveProcessor {
    public $classKey = 'modFormCustomizationProfile';
    public $languageTopics = array('formcustomization');
    public $permission = 'customize_forms';
    public $objectType = 'action';
}
return 'modFormCustomizationProfileRemoveProcessor';