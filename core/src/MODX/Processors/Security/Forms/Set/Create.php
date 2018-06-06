<?php

namespace MODX\Processors\Security\Forms\Set;

use MODX\Processors\modObjectCreateProcessor;

/**
 * Create a FC Set
 *
 * @package modx
 * @subpackage processors.security.forms.set
 */
class Create extends modObjectCreateProcessor
{
    public $classKey = 'modFormCustomizationSet';
    public $languageTopics = ['formcustomization'];
    public $permission = 'customize_forms';
    public $objectType = 'set';


    public function beforeSave()
    {
        $this->object->set('constraint_class', 'modResource');
        $actionId = $this->getProperty('action_id', null);
        if ($actionId !== null) {
            $this->object->set('action', $actionId);
        }

        return parent::beforeSave();
    }
}