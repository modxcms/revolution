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
 * Grabs a list of snippets.
 *
 * @property integer $start (optional) The record to start at. Defaults to 0.
 * @property integer $limit (optional) The number of records to limit to. Defaults
 * to 10.
 * @property string  $sort  (optional) The column to sort by. Defaults to name.
 * @property string  $dir   (optional) The direction of the sort. Defaults to ASC.
 *
 * @package MODX\Revolution\Processors\Element\Snippet
 */
class GetList extends \MODX\Revolution\Processors\Element\GetList
{
    public $classKey = modSnippet::class;
    public $languageTopics = ['snippet', 'category'];
    public $permission = 'view_snippet';
}
