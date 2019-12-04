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
use MODX\Revolution\Processors\Model\UpdateProcessor;
use MODX\Revolution\modPropertySet;

/**
 * Updates a property set
 *
 * @package MODX\Revolution\Processors\Element\PropertySet
 */
class Update extends UpdateProcessor
{
    public $classKey = modPropertySet::class;
    public $languageTopics = ['propertyset', 'category'];
    public $permission = 'save_propertyset';
    public $objectType = 'propertyset';


    public function beforeSet()
    {
        $name = $this->getProperty('name');
        if ($this->alreadyExists($name)) {
            $this->addFieldError('name', $this->modx->lexicon('propertyset_err_ns_name'));
        }
        $name = $this->stripInvalidCharacters($name);
        $this->setProperty('name', $name);

        $category = $this->getProperty('category');
        if (!empty($category)) {
            /** @var modCategory $category */
            $category = $this->modx->getObject(modCategory::class, $category);
            if (empty($category)) {
                $this->addFieldError('category', $this->modx->lexicon('category_err_nf'));
            }
        } else {
            $this->setProperty('category', 0);
        }

        return parent::beforeSet();
    }

    public function stripInvalidCharacters($name)
    {
        $invalidCharacters = ['!', '@', '?', '`', '&', '&amp;'];
        $name = str_replace($invalidCharacters, '', $name);

        return $name;
    }

    public function alreadyExists($name)
    {
        return $this->modx->getCount($this->classKey, [
                'name' => $name,
                'id:!=' => $this->getProperty('id'),
            ]) > 0;
    }
}
