<?php

namespace MODX\Processors\System\ContentType;

use MODX\Processors\modObjectGetListProcessor;

/**
 * Gets a list of content types
 *
 * @param integer $start (optional) The record to start at. Defaults to 0.
 * @param integer $limit (optional) The number of records to limit to. Defaults
 * to 10.
 * @param string $sort (optional) The column to sort by. Defaults to name.
 * @param string $dir (optional) The direction of the sort. Defaults to ASC.
 *
 * @package modx
 * @subpackage processors.system.contenttype
 */
class GetList extends modObjectGetListProcessor
{
    public $classKey = 'modContentType';
    public $languageTopics = ['content_type'];
}