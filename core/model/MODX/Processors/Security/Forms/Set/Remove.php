<?php

namespace MODX\Processors\Security\Forms\Set;

use MODX\Processors\modObjectRemoveProcessor;

/**
 * Remove a FC Set
 *
 * @package modx
 * @subpackage processors.security.forms.set
 */
class Remove extends modObjectRemoveProcessor
{
    public $classKey = 'modFormCustomizationSet';
    public $languageTopics = ['formcustomization'];
    public $permission = 'customize_forms';
    public $objectType = 'action';
}