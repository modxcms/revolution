<?php

namespace MODX\Processors\Element\Category;

use MODX\Processors\modObjectGetProcessor;

/**
 * Gets a category.
 *
 * @param integer $id The ID of the category.
 *
 * @package modx
 * @subpackage processors.element.category
 */
class Get extends modObjectGetProcessor
{
    public $classKey = 'modCategory';
    public $languageTopics = ['category'];
    public $permission = 'view_category';
    public $objectType = 'category';
}