<?php
/*
 * This file is part of the MODX Revolution package.
 *
 * Copyright (c) MODX, LLC
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace MODX\Revolution\Processors\Element\PropertySet;


use MODX\Revolution\modCategory;
use MODX\Revolution\Processors\Model\CreateProcessor;
use MODX\Revolution\modPropertySet;

/**
 * Creates a property set
 *
 * @package MODX\Revolution\Processors\Element\PropertySet
 */
class Create extends CreateProcessor
{
    public $classKey = modPropertySet::class;
    public $languageTopics = ['propertyset'];
    public $permission = 'new_propertyset';
    public $objectType = 'propertyset';

    public function beforeSet()
    {
        $name = $this->getProperty('name');
        if (empty($name)) {
            $this->addFieldError('name', $this->modx->lexicon('propertyset_err_ns_name'));
        } else {
            if ($this->doesAlreadyExist(['name' => $name])) {
                $this->addFieldError('name', $this->modx->lexicon('propertyset_err_ae'));
            }
        }

        return parent::beforeSet();
    }

    public function beforeSave()
    {
        /* set category if specified */
        $category = $this->getProperty('category', null);
        if (!empty($category)) {
            /** @var modCategory $category */
            $category = $this->modx->getObject(modCategory::class, $category);
            if (empty($category)) {
                $this->addFieldError('category', $this->modx->lexicon('category_err_nf'));
            } else {
                $this->object->set('category', $category->get('id'));
            }
        }
        $this->stripInvalidCharacters();

        return parent::beforeSave();
    }

    public function stripInvalidCharacters()
    {
        $invalidCharacters = ['!', '@', '?', '`', '&', '&amp;'];
        $name = $this->object->get('name');
        $name = str_replace($invalidCharacters, '', $name);
        $this->object->set('name', $name);
    }
}
