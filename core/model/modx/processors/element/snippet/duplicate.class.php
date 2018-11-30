<?php
/*
 * This file is part of MODX Revolution.
 *
 * Copyright (c) MODX, LLC. All Rights Reserved.
 *
 * For complete copyright and license information, see the COPYRIGHT and LICENSE
 * files found in the top-level directory of this distribution.
 */

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
