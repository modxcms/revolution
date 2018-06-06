<?php

namespace MODX\Processors\Element\Chunk;

/**
 * Gets a chunk.
 *
 * @param integer $id The ID of the chunk.
 *
 * @package modx
 * @subpackage processors.element.chunk
 */
class Get extends \MODX\Processors\Element\Get
{
    public $classKey = 'modChunk';
    public $languageTopics = ['chunk', 'category'];
    public $permission = 'view_chunk';
    public $objectType = 'chunk';
}