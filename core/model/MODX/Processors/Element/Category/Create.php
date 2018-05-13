<?php

namespace MODX\Processors\Element\Category;

use MODX\Processors\modObjectCreateProcessor;

/**
 * Create a category.
 *
 * @param string $category The new name of the category.
 *
 * @package modx
 * @subpackage processors.element.category
 */
class Create extends modObjectCreateProcessor
{
    public $classKey = 'modCategory';
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
        } elseif ($this->alreadyExists($name, $parent)) {
            $this->addFieldError('category', $this->modx->lexicon('category_err_ae'));
        }

        return !$this->hasErrors();
    }


    /**
     * Check to see if a Category with that name and same parent already exists
     *
     * @param string $name The name to check against
     * @param integer $parent The parent ID to check against
     *
     * @return boolean
     */
    public function alreadyExists($name, $parent = 0)
    {
        return $this->modx->getCount('modCategory', ['category' => $name, 'parent' => $parent]) > 0;
    }
}