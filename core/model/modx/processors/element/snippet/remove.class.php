<?php
/*
 * This file is part of MODX Revolution.
 *
 * Copyright (c) MODX, LLC. All Rights Reserved.
 *
 * For complete copyright and license information, see the COPYRIGHT and LICENSE
 * files found in the top-level directory of this distribution.
 */

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
