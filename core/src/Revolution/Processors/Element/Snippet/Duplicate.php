<?php
/*
 * This file is part of the MODX Revolution package.
 *
 * Copyright (c) MODX, LLC
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace MODX\Revolution\Processors\Element\Snippet;


use MODX\Revolution\modSnippet;

/**
 * Duplicate a snippet.
 *
 * @property integer $id   The ID of the snippet to duplicate.
 * @property string  $name (optional) The name of the new snippet. Defaults to
 * Untitled Snippet.
 *
 * @package MODX\Revolution\Processors\Element\Snippet
 */
class Duplicate extends \MODX\Revolution\Processors\Element\Duplicate
{
    public $classKey = modSnippet::class;
    public $languageTopics = ['snippet'];
    public $permission = 'new_snippet';
    public $objectType = 'snippet';
}
