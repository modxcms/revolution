<?php
/*
 * This file is part of the MODX Revolution package.
 *
 * Copyright (c) MODX, LLC
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace MODX\Revolution\Processors\Element\Snippet;


use MODX\Revolution\modSnippet;

/**
 * Get a snippet.
 *
 * @property integer $id The ID of the snippet.
 *
 * @package MODX\Revolution\Processors\Element\Snippet
 */
class Get extends \MODX\Revolution\Processors\Element\Get
{
    public $classKey = modSnippet::class;
    public $languageTopics = ['snippet', 'category'];
    public $permission = 'view_snippet';
    public $objectType = 'snippet';
}
