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
 * Duplicates a chunk.
 *
 * @property integer $id   The chunk to duplicate
 * @property string  $name The name of the new chunk.
 *
 * @package MODX\Revolution\Processors\Element\Chunk
 */
class Duplicate extends \MODX\Revolution\Processors\Element\Duplicate
{
    public $classKey = modChunk::class;
    public $languageTopics = ['chunk'];
    public $permission = 'new_chunk';
    public $objectType = 'chunk';
}
