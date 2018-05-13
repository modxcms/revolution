<?php

namespace MODX\Processors\Security\Forms\Set;

use MODX\Processors\modObjectUpdateProcessor;

/**
 * Activate a FC Set
 *
 * @package modx
 * @subpackage processors.security.forms.set
 */
class Activate extends modObjectUpdateProcessor
{
    public $classKey = 'modFormCustomizationSet';
    public $objectType = 'set';
    public $languageTopics = ['formcustomization'];
    public $permission = 'customize_forms';


    public function beforeSet()
    {
        $this->unsetProperty('action');

        return parent::beforeSet();
    }


    public function beforeSave()
    {
        $this->object->set('active', true);

        return parent::beforeSave();
    }
}
