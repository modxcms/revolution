<?php
require_once (dirname(__DIR__).'/remove.class.php');
/**
 * Delete a snippet.
 *
 * @param integer $id The ID of the snippet.
 *
 * @package modx
 * @subpackage processors.element.snippet
 */
class modSnippetRemoveProcessor extends modElementRemoveProcessor {
    public $classKey = 'modSnippet';
    public $languageTopics = array('snippet');
    public $permission = 'delete_snippet';
    public $objectType = 'snippet';
    public $beforeRemoveEvent = 'OnBeforeSnipFormDelete';
    public $afterRemoveEvent = 'OnSnipFormDelete';
}
return 'modSnippetRemoveProcessor';