<?php

namespace MODX\Processors\Element\Category;

use MODX\Processors\modObjectUpdateProcessor;

/**
 * Update a category.
 *
 * @param integer $id The ID of the category.
 * @param string $category The new name of the category.
 *
 * @package modx
 * @subpackage processors.element.category
 */
class Update extends modObjectUpdateProcessor
{
    public $classKey = 'modCategory';
    public $languageTopics = ['category'];
    public $permission = 'save_category';
    public $objectType = 'category';
}