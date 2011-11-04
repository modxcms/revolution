<?php
/**
 * Remove a FC Set
 *
 * @package modx
 * @subpackage processors.security.forms.set
 */
class modFormCustomizationSetRemoveProcessor extends modObjectRemoveProcessor {
    public $classKey = 'modFormCustomizationSet';
    public $languageTopics = array('formcustomization');
    public $permission = 'customize_forms';
    public $objectType = 'action';
}
return 'modFormCustomizationSetRemoveProcessor';