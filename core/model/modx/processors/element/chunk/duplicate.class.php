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
 * Duplicates a chunk.
 *
 * @param integer $id The chunk to duplicate
 * @param string $name The name of the new chunk.
 *
 * @package modx
 * @subpackage processors.element.chunk
 */
class modChunkDuplicateProcessor extends modElementDuplicateProcessor {
    public $classKey = 'modChunk';
    public $languageTopics = array('chunk');
    public $permission = 'new_chunk';
    public $objectType = 'chunk';
}
return 'modChunkDuplicateProcessor';
