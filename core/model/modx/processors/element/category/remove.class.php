<?php
/**
 * Deletes a category. Resets all elements with that category to 0.
 *
 * @param integer $id The ID of the category.
 *
 * @package modx
 * @subpackage processors.element.category
 */
class modElementCategoryRemoveProcessor extends modObjectRemoveProcessor {
    public $classKey = 'modCategory';
    public $languageTopics = array('category');
    public $permission = 'delete_category';
    public $objectType = 'category';
}
return 'modElementCategoryRemoveProcessor';