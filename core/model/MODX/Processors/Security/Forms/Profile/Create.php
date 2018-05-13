<?php

namespace MODX\Processors\Security\Forms\Profile;

use MODX\Processors\modObjectCreateProcessor;

/**
 * Create a FC Profile
 *
 * @package modx
 * @subpackage processors.security.forms.profile
 */
class Create extends modObjectCreateProcessor
{
    public $classKey = 'modFormCustomizationProfile';
    public $languageTopics = ['formcustomization'];
    public $permission = 'customize_forms';
    public $objectType = 'profile';
}