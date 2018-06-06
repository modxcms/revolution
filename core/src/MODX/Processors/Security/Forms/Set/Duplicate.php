<?php

namespace MODX\Processors\Security\Forms\Set;

use MODX\modActionDom;
use MODX\Processors\modObjectDuplicateProcessor;

/**
 * Duplicate a FC Set
 *
 * @package modx
 * @subpackage processors.security.forms.set
 */
class Duplicate extends modObjectDuplicateProcessor
{
    public $classKey = 'modFormCustomizationSet';
    public $languageTopics = ['formcustomization'];
    public $permission = 'customize_forms';
    public $objectType = 'profile';
    public $checkSavePermission = false;


    public function beforeSave()
    {
        $this->newObject->set('constraint_class', 'modResource');
        $this->newObject->set('active', false);

        return parent::beforeSave();
    }


    public function afterSave()
    {
        $this->duplicateRules();

        return parent::afterSave();
    }


    /**
     * Duplicate all the old rules
     *
     * @return void
     */
    public function duplicateRules()
    {
        $rules = $this->object->getMany('Rules');
        /** @var modActionDom $rule */
        foreach ($rules as $rule) {
            /** @var modActionDom $newRule */
            $newRule = $this->modx->newObject('modActionDom');
            $newRule->fromArray($rule->toArray());
            $newRule->set('set', $this->newObject->get('id'));
            $newRule->save();
        }
    }
}