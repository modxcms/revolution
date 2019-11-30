<?php
/*
 * This file is part of the MODX Revolution package.
 *
 * Copyright (c) MODX, LLC
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace MODX\Revolution\Processors\Element\Category;


use MODX\Revolution\modCategory;
use MODX\Revolution\Processors\Model\CreateProcessor;

/**
 * Create a category.
 *
 * @property string $category The new name of the category.
 *
 * @package MODX\Revolution\Processors\Element\Category
 */
class Create extends CreateProcessor
{
    public $classKey = modCategory::class;
    public $languageTopics = ['category'];
    public $permission = 'save_category';
    public $objectType = 'category';

    /**
     * Validate the creation
     *
     * @return boolean
     */
    public function beforeSave()
    {
        $name = $this->getProperty('category');
        $parent = $this->getProperty('parent', 0);
        if (empty($name)) {
            $this->addFieldError('category', $this->modx->lexicon('category_err_ns'));
        } else {
            if ($this->alreadyExists($name, $parent)) {
                $this->addFieldError('category', $this->modx->lexicon('category_err_ae'));
            }
        }

        return !$this->hasErrors();
    }

    /**
     * Check to see if a Category with that name and same parent already exists
     *
     * @param string  $name   The name to check against
     * @param integer $parent The parent ID to check against
     *
     * @return boolean
     */
    public function alreadyExists($name, $parent = 0)
    {
        return $this->modx->getCount(modCategory::class, ['category' => $name, 'parent' => $parent]) > 0;
    }
}
