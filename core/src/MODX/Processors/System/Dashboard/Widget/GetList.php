<?php

namespace MODX\Processors\System\Dashboard\Widget;

use MODX\Processors\modObjectGetListProcessor;

use xPDO\Om\xPDOObject;
use xPDO\Om\xPDOQuery;

/**
 * Gets a list of dashboards
 *
 * @param string $username (optional) Will filter the grid by searching for this
 * username
 * @param integer $start (optional) The record to start at. Defaults to 0.
 * @param integer $limit (optional) The number of records to limit to. Defaults
 * to 10.
 * @param string $sort (optional) The column to sort by. Defaults to name.
 * @param string $dir (optional) The direction of the sort. Defaults to ASC.
 *
 * @package modx
 * @subpackage processors.system.dashboard.widget
 */
class GetList extends modObjectGetListProcessor
{
    public $classKey = 'modDashboardWidget';
    public $languageTopics = ['dashboards'];
    public $permission = 'dashboards';


    public function prepareQueryBeforeCount(xPDOQuery $c)
    {
        $query = $this->getProperty('query');
        if (!empty($query)) {
            $c->where(['modDashboardWidget.name:LIKE' => '%' . $query . '%']);
            $c->orCondition(['modDashboardWidget.description:LIKE' => '%' . $query . '%']);
        }

        return $c;
    }


    public function prepareRow(xPDOObject $object)
    {
        $objectArray = $object->toArray();
        $objectArray['cls'] = 'pupdate premove';

        return $objectArray;
    }
}