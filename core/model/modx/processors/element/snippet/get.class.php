<?php
require_once (dirname(dirname(__FILE__)).'/get.class.php');
/**
 * Get a snippet.
 *
 * @param integer $id The ID of the snippet.
 *
 * @package modx
 * @subpackage processors.element.snippet
 */
class modSnippetGetProcessor extends modElementGetProcessor {
    public $classKey = 'modSnippet';
    public $languageTopics = array('snippet','category');
    public $permission = 'view_snippet';
    public $objectType = 'snippet';
}
return 'modSnippetGetProcessor';