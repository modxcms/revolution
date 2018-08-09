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
 * Gets a chunk.
 *
 * @param integer $id The ID of the chunk.
 *
 * @package modx
 * @subpackage processors.element.chunk
 */
class modChunkGetProcessor extends modElementGetProcessor {
    public $classKey = 'modChunk';
    public $languageTopics = array('chunk','category');
    public $permission = 'view_chunk';
    public $objectType = 'chunk';
}
return 'modChunkGetProcessor';
