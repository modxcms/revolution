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
use MODX\Revolution\Processors\Model\UpdateProcessor;

/**
 * Update a FC Profile from grid
 * @package MODX\Revolution\Processors\Security\Forms\Set
 */
class UpdateFromGrid extends UpdateProcessor
{
    public $classKey = modFormCustomizationSet::class;
    public $languageTopics = ['formcustomization'];
    public $permission = 'customize_forms';
    public $objectType = 'set';

    protected $gridFields = ['id', 'action', 'description', 'template', 'constraint_field', 'constraint'];

    /**
     * @return bool|string|null
     * @throws \xPDO\xPDOException
     */
    public function initialize()
    {
        $data = $this->getProperty('data');
        if (empty($data)) {
            return $this->modx->lexicon('invalid_data');
        }
        $properties = $this->modx->fromJSON($data);
        $properties = array_intersect_key($properties, array_flip($this->gridFields));
        $this->setProperties($properties);
        $this->unsetProperty('data');

        return parent::initialize();
    }
}
