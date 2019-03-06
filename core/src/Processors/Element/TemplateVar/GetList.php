<?php
/*
 * This file is part of the MODX Revolution package.
 *
 * Copyright (c) MODX, LLC
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace MODX\Revolution\Processors\Element\TemplateVar;


use MODX\Revolution\modTemplateVar;

/**
 * Grabs a list of TVs.
 *
 * @property integer $start (optional) The record to start at. Defaults to 0.
 * @property integer $limit (optional) The number of records to limit to.
 * @property string  $sort  (optional) The column to sort by. Defaults to name.
 * @property string  $dir   (optional) The direction of the sort. Defaults to ASC.
 *
 * @package MODX\Revolution\Processors\Element\TemplateVar
 */
class GetList extends \MODX\Revolution\Processors\Element\GetList
{
    public $classKey = modTemplateVar::class;
    public $languageTopics = ['tv', 'category'];
    public $permission = 'view_tv';
}
