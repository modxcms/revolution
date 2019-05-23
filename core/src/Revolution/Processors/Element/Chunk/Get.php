<?php
/*
 * This file is part of the MODX Revolution package.
 *
 * Copyright (c) MODX, LLC
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace MODX\Revolution\Processors\Element\Chunk;


use MODX\Revolution\modChunk;

/**
 * Gets a chunk.
 *
 * @property integer $id The ID of the chunk.
 *
 * @package MODX\Revolution\Processors\Element\Chunk
 */
class Get extends \MODX\Revolution\Processors\Element\Get
{
    public $classKey = modChunk::class;
    public $languageTopics = ['chunk', 'category'];
    public $permission = 'view_chunk';
    public $objectType = 'chunk';
}
