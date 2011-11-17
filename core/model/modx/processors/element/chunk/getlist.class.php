<?php
require_once (dirname(dirname(__FILE__)).'/getlist.class.php');
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
class modChunkGetListProcessor extends modElementGetListProcessor {
    public $classKey = 'modChunk';
    public $languageTopics = array('chunk','category');
}
return 'modChunkGetListProcessor';