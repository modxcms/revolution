<?php

namespace MODX\Processors\Workspace;

use MODX\Processors\modObjectGetListProcessor;

/**
 * Grabs a list of workspaces
 *
 * @param integer $start (optional) The record to start at. Defaults to 0.
 * @param integer $limit (optional) The number of records to limit to. Defaults
 * to 10.
 * @param string $sort (optional) The column to sort by. Defaults to name.
 * @param string $dir (optional) The direction of the sort. Defaults to ASC.
 *
 * @package modx
 * @subpackage processors.workspace
 */
class GetList extends modObjectGetListProcessor
{
    public $classKey = 'modWorkspace';
    public $defaultSortField = 'name';
    public $defaultSortDirection = 'ASC';
    public $permission = 'workspaces';
    public $languageTopics = ['workspace'];
}

