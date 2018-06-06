<?php

namespace MODX\Processors\Element\Chunk;

/**
 * Grabs a list of chunks.
 *
 * @param integer $start (optional) The record to start at. Defaults to 0.
 * @param integer $limit (optional) The number of records to limit to. Defaults
 * to 10.
 * @param string $sort (optional) The column to sort by. Defaults to name.
 * @param string $dir (optional) The direction of the sort. Defaults to ASC.
 *
 * @package modx
 * @subpackage processors.element.chunk
 */
class GetList extends \MODX\Processors\Element\GetList
{
    public $classKey = 'modChunk';
    public $languageTopics = ['chunk', 'category'];
    public $permission = 'view_chunk';
}
