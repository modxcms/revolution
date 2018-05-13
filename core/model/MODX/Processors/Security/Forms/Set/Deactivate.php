<?php

namespace MODX\Processors\Security\Forms\Set;

use MODX\Processors\modObjectUpdateProcessor;

/**
 * Deactivate a FC Set
 *
 * @package modx
 * @subpackage processors.security.forms.set
 */
class Deactivate extends modObjectUpdateProcessor
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
        $this->object->set('active', false);

        return parent::beforeSave();
    }
}
