<?php

namespace MODX\Processors\Element\Category;

use MODX\Processors\modObjectRemoveProcessor;

/**
 * Deletes a category. Resets all elements with that category to 0.
 *
 * @param integer $id The ID of the category.
 *
 * @package modx
 * @subpackage processors.element.category
 */
class Remove extends modObjectRemoveProcessor
{
    public $classKey = 'modCategory';
    public $languageTopics = ['category'];
    public $permission = 'delete_category';
    public $objectType = 'category';
}