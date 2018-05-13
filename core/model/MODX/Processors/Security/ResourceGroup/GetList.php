<?php

namespace MODX\Processors\Security\ResourceGroup;

use MODX\Processors\modObjectGetListProcessor;

/**
 * Gets a list of resource groups
 *
 * @param integer $start (optional) The record to start at. Defaults to 0.
 * @param integer $limit (optional) The number of records to limit to. Defaults
 * to 10.
 * @param string $sort (optional) The column to sort by. Defaults to name.
 * @param string $dir (optional) The direction of the sort. Defaults to ASC.
 *
 *
 * @package modx
 * @subpackage processors.security.resourcegroup
 */
class GetList extends modObjectGetListProcessor
{
    public $classKey = 'modResourceGroup';
    public $languageTopics = ['access'];
    public $permission = 'resourcegroup_view';
}