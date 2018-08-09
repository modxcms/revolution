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
