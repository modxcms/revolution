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

use MODX\Revolution\modFormCustomizationProfile;
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
    protected $profileId;

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
        $this->profileId = $properties['profile'];
        $properties = array_intersect_key($properties, array_flip($this->gridFields));
        $this->setProperties($properties);
        $this->unsetProperty('data');

        return parent::initialize();
    }

    public function process()
    {
        foreach (['description', 'constraint_field', 'constraint'] as $field) {
            $value = $this->getProperty($field, '');
            $previousValue = $this->object->get($field);
            if ($value && $value !== $previousValue) {
                if ($field === 'constraint') {
                    $value = trim($value, ', ');
                    $value = preg_replace('/\s*,\s*/', ',', $value);
                    $value = preg_replace('/[,]+/', ',', $value);
                    $value = str_replace(',', ', ', $value);
                }
                $value = preg_replace('/\s+/', ' ', $value);
                $this->setProperty($field, trim($value));
            }
        }

        return parent::process();
    }

    public function beforeSave()
    {
        $constraintField = $this->getProperty('constraint_field', '');
        $constraint = $this->getProperty('constraint', '');
        $hasConstraintField = !empty($constraintField);
        if (!$hasConstraintField xor (empty($constraint) && $constraint !== 0)) {
            $profile = $this->modx->getObject(modFormCustomizationProfile::class, $this->profileId)->get('name');
            $set = $this->getProperty('id');
            $lexiconEntry = !$hasConstraintField ? 'constraint_incomplete_field_warn' : 'constraint_incomplete_constraint_warn' ;
            $message = sprintf($this->modx->lexicon($lexiconEntry), $set, $profile);
            $this->modx->log(\modX::LOG_LEVEL_WARN, "\r\t Validation Warning: " . $message);
        }

        return parent::beforeSave();
    }
}
