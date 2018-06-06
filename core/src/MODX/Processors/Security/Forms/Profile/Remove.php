<?php

namespace MODX\Processors\Security\Forms\Profile;

use MODX\Processors\modObjectRemoveProcessor;

/**
 * Remove FC Profile
 *
 * @package modx
 * @subpackage processors.security.forms.profile
 */
class Remove extends modObjectRemoveProcessor
{
    public $classKey = 'modFormCustomizationProfile';
    public $languageTopics = ['formcustomization'];
    public $permission = 'customize_forms';
    public $objectType = 'action';
}