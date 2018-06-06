<?php

namespace MODX\Processors\Element\Snippet;

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
class Duplicate extends \MODX\Processors\Element\Duplicate
{
    public $classKey = 'modSnippet';
    public $languageTopics = ['snippet'];
    public $permission = 'new_snippet';
    public $objectType = 'snippet';
}