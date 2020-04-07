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

    /**
     * @return bool
     */
    public function beforeSave()
    {
        $this->object->set('constraint_class', modResource::class);
        $actionId = $this->getProperty('action_id');
        if ($actionId !== null) {
            $this->object->set('action', $actionId);
        }
        return parent::beforeSave();
    }
}
