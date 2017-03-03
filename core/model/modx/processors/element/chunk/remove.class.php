<?php
require_once (dirname(__DIR__).'/remove.class.php');
/**
 * Removes a chunk.
 *
 * @param integer $id The ID of the chunk
 *
 * @package modx
 * @subpackage processors.element.chunk
 */
class modChunkRemoveProcessor extends modElementRemoveProcessor {
    public $classKey = 'modChunk';
    public $languageTopics = array('chunk');
    public $permission = 'delete_chunk';
    public $objectType = 'chunk';
    public $beforeRemoveEvent = 'OnBeforeChunkFormDelete';
    public $afterRemoveEvent = 'OnChunkFormDelete';
}
return 'modChunkRemoveProcessor';