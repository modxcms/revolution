<?php

namespace MODX\Processors\Element\Snippet;

/**
 * Delete a snippet.
 *
 * @param integer $id The ID of the snippet.
 *
 * @package modx
 * @subpackage processors.element.snippet
 */
class Remove extends \MODX\Processors\Element\Remove
{
    public $classKey = 'modSnippet';
    public $languageTopics = ['snippet'];
    public $permission = 'delete_snippet';
    public $objectType = 'snippet';
    public $beforeRemoveEvent = 'OnBeforeSnipFormDelete';
    public $afterRemoveEvent = 'OnSnipFormDelete';
}