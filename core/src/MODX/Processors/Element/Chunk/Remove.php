<?php

namespace MODX\Processors\Element\Chunk;

/**
 * Removes a chunk.
 *
 * @param integer $id The ID of the chunk
 *
 * @package modx
 * @subpackage processors.element.chunk
 */
class Remove extends \MODX\Processors\Element\Remove
{
    public $classKey = 'modChunk';
    public $languageTopics = ['chunk'];
    public $permission = 'delete_chunk';
    public $objectType = 'chunk';
    public $beforeRemoveEvent = 'OnBeforeChunkFormDelete';
    public $afterRemoveEvent = 'OnChunkFormDelete';
}