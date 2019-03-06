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
use MODX\Revolution\modObjectGetProcessor;

/**
 * Gets a category.
 *
 * @property integer $id The ID of the category.
 *
 * @package MODX\Revolution\Processors\Element\Category
 */
class Get extends modObjectGetProcessor
{
    public $classKey = modCategory::class;
    public $languageTopics = ['category'];
    public $permission = 'view_category';
    public $objectType = 'category';
}
