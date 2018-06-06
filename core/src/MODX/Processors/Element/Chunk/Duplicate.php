<?php

namespace MODX\Processors\Element\Chunk;

/**
 * Duplicates a chunk.
 *
 * @param integer $id The chunk to duplicate
 * @param string $name The name of the new chunk.
 *
 * @package modx
 * @subpackage processors.element.chunk
 */
class Duplicate extends \MODX\Processors\Element\Duplicate
{
    public $classKey = 'modChunk';
    public $languageTopics = ['chunk'];
    public $permission = 'new_chunk';
    public $objectType = 'chunk';
}