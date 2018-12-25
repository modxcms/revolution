<?php
/*
 * This file is part of MODX Revolution.
 *
 * Copyright (c) MODX, LLC. All Rights Reserved.
 *
 * For complete copyright and license information, see the COPYRIGHT and LICENSE
 * files found in the top-level directory of this distribution.
 */

require_once (dirname(__DIR__).'/get.class.php');
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
