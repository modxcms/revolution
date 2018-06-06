<?php

namespace MODX\Processors\Element\Snippet;

/**
 * Grabs a list of snippets.
 *
 * @param integer $start (optional) The record to start at. Defaults to 0.
 * @param integer $limit (optional) The number of records to limit to. Defaults
 * to 10.
 * @param string $sort (optional) The column to sort by. Defaults to name.
 * @param string $dir (optional) The direction of the sort. Defaults to ASC.
 *
 * @package modx
 * @subpackage processors.element.snippet
 */
class GetList extends \MODX\Processors\Element\GetList
{
    public $classKey = 'modSnippet';
    public $languageTopics = ['snippet', 'category'];
    public $permission = 'view_snippet';
}
