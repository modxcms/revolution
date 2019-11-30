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
use MODX\Revolution\Processors\Model\RemoveProcessor;

/**
 * Deletes a category. Resets all elements with that category to 0.
 *
 * @param integer $id The ID of the category.
 *
 * @package MODX\Revolution\Processors\Element\Category
 */
class Remove extends RemoveProcessor
{
    public $classKey = modCategory::class;
    public $languageTopics = ['category'];
    public $permission = 'delete_category';
    public $objectType = 'category';
}
