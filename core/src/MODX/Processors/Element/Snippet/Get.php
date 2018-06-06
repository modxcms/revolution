<?php

namespace MODX\Processors\Element\Snippet;

/**
 * Get a snippet.
 *
 * @param integer $id The ID of the snippet.
 *
 * @package modx
 * @subpackage processors.element.snippet
 */
class Get extends \MODX\Processors\Element\Get
{
    public $classKey = 'modSnippet';
    public $languageTopics = ['snippet', 'category'];
    public $permission = 'view_snippet';
    public $objectType = 'snippet';
}