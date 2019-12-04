<?php
/*
 * This file is part of MODX Revolution.
 *
 * Copyright (c) MODX, LLC. All Rights Reserved.
 *
 * For complete copyright and license information, see the COPYRIGHT and LICENSE
 * files found in the top-level directory of this distribution.
 */

namespace MODX\Revolution\Processors\Security\Forms\Set;

use MODX\Revolution\modActionDom;
use MODX\Revolution\modFormCustomizationSet;
use MODX\Revolution\Processors\Model\DuplicateProcessor;
use MODX\Revolution\modResource;

/**
 * Duplicate a FC Set
 * @package MODX\Revolution\Processors\Security\Forms\Set
 */
class Duplicate extends DuplicateProcessor
{
    public $classKey = modFormCustomizationSet::class;
    public $languageTopics = ['formcustomization'];
    public $permission = 'customize_forms';
    public $objectType = 'profile';
    public $checkSavePermission = false;

    /**
     * @return bool
     */
    public function beforeSave()
    {
        $this->newObject->set('constraint_class', modResource::class);
        $this->newObject->set('active', false);

        return parent::beforeSave();
    }

    /**
     * @return bool
     */
    public function afterSave()
    {
        $this->duplicateRules();
        return parent::afterSave();
    }

    /**
     * Duplicate all the old rules
     * @return void
     */
    public function duplicateRules()
    {
        $rules = $this->object->getMany('Rules');
        /** @var modActionDom $rule */
        foreach ($rules as $rule) {
            /** @var modActionDom $newRule */
            $newRule = $this->modx->newObject(modActionDom::class);
            $newRule->fromArray($rule->toArray());
            $newRule->set('set', $this->newObject->get('id'));
            $newRule->save();
        }
    }
}
