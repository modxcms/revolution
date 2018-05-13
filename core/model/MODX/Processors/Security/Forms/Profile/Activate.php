<?php

namespace MODX\Processors\Security\Forms\Profile;

use MODX\Processors\modObjectUpdateProcessor;

/**
 * Activate a FC Profile
 *
 * @package modx
 * @subpackage processors.security.forms.profile
 */
class Activate extends modObjectUpdateProcessor
{
    public $classKey = 'modFormCustomizationProfile';
    public $objectType = 'profile';
    public $languageTopics = ['formcustomization'];
    public $permission = 'customize_forms';


    public function beforeSave()
    {
        $this->object->set('active', true);

        return parent::beforeSave();
    }
}
