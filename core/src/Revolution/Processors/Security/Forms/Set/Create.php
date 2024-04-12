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

use MODX\Revolution\modFormCustomizationSet;
use MODX\Revolution\Processors\Model\CreateProcessor;
use MODX\Revolution\modResource;

/**
 * Create a FC Set
 * @package MODX\Revolution\Processors\Security\Forms\Set
 */
class Create extends CreateProcessor
{
    public $classKey = modFormCustomizationSet::class;
    public $languageTopics = ['formcustomization'];
    public $permission = 'customize_forms';
    public $objectType = 'set';

    protected $fcSetId;
    protected $fcSetAction;
    protected $fcSetConstraintClass = modResource::class;
    protected $fcSetConstraintField;
    protected $fcSetConstraint;

    /**
     * @return bool
     */
    public function beforeSet()
    {
        $this->fcSetId = $this->object->get('id');
        $this->fcSetAction = $this->object->get('action');
        $this->fcSetConstraintField = trim($this->getProperty('constraint_field', ''));
        $this->fcSetConstraint = trim($this->getProperty('constraint', ''));

        $this->setProperty('constraint_field', $this->fcSetConstraintField);
        $this->setProperty('constraint', $this->fcSetConstraint);
        $this->setProperty('description', trim($this->getProperty('description', '')));

        return parent::beforeSet();
    }

    /**
     * @return bool
     */
    public function beforeSave()
    {
        $this->object->set('constraint_class', $this->fcSetConstraintClass);
        $actionId = $this->getProperty('action_id');
        if ($actionId !== null) {
            $this->object->set('action', $actionId);
        }
        $hasConstraintField = !empty($this->fcSetConstraintField);
        if (!$hasConstraintField xor (empty($this->fcSetConstraint) && $this->fcSetConstraint !== 0)) {
            if (!$hasConstraintField) {
                $this->addFieldError('constraint_field', $this->modx->lexicon('constraint_incomplete_field_err'));
            } else {
                $this->addFieldError('constraint', $this->modx->lexicon('constraint_incomplete_constraint_err'));
            }
        }

        return parent::beforeSave();
    }
}
