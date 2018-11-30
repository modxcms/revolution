<?php
/*
 * This file is part of MODX Revolution.
 *
 * Copyright (c) MODX, LLC. All Rights Reserved.
 *
 * For complete copyright and license information, see the COPYRIGHT and LICENSE
 * files found in the top-level directory of this distribution.
 */

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
