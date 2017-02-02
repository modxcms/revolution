<?php
require_once (dirname(__DIR__).'/getlist.class.php');
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
class modSnippetGetListProcessor extends modElementGetListProcessor {
    public $classKey = 'modSnippet';
    public $languageTopics = array('snippet','category');
    public $permission = 'view_snippet';
}
return 'modSnippetGetListProcessor';
