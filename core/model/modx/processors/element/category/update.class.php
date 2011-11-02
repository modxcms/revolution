<?php
/**
 * Update a category.
 *
 * @param integer $id The ID of the category.
 * @param string $category The new name of the category.
 *
 * @package modx
 * @subpackage processors.element.category
 */
class modElementCategoryUpdateProcessor extends modObjectUpdateProcessor {
    public $classKey = 'modCategory';
    public $languageTopics = array('category');
    public $permission = 'save_category';
    public $objectType = 'category';
}
return 'modElementCategoryUpdateProcessor';