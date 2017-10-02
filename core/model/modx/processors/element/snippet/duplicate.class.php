<?php
require_once (dirname(__DIR__).'/duplicate.class.php');
/**
 * Duplicate a snippet.
 *
 * @param integer $id The ID of the snippet to duplicate.
 * @param string $name (optional) The name of the new snippet. Defaults to
 * Untitled Snippet.
 *
 * @package modx
 * @subpackage processors.element.snippet
 */
class modSnippetDuplicateProcessor extends modElementDuplicateProcessor {
    public $classKey = 'modSnippet';
    public $languageTopics = array('snippet');
    public $permission = 'new_snippet';
    public $objectType = 'snippet';
}
return 'modSnippetDuplicateProcessor';