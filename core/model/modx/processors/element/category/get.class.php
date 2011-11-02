<?php
/**
 * Gets a category.
 *
 * @param integer $id The ID of the category.
 *
 * @package modx
 * @subpackage processors.element.category
 */
class modElementCategoryGetProcessor extends modObjectGetProcessor {
    public $classKey = 'modCategory';
    public $languageTopics = array('category');
    public $permission = 'view_category';
    public $objectType = 'category';
}
return 'modElementCategoryGetProcessor';