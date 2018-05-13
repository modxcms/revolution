<?php

namespace MODX\Processors\System\Dashboard;

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
 * @subpackage processors.security.user
 */
class GetList extends modObjectGetListProcessor
{
    public $classKey = 'modDashboard';
    public $languageTopics = ['dashboards'];
    public $permission = 'dashboards';


    public function prepareQueryAfterCount(xPDOQuery $c)
    {
        $query = $this->getProperty('query');
        if (!empty($query)) {
            $c->where(['modDashboard.name:LIKE' => '%' . $query . '%']);
            $c->orCondition(['modDashboard.description:LIKE' => '%' . $query . '%']);
        }
        $userGroup = $this->getProperty('usergroup', false);
        if (!empty($userGroup)) {
            $c->innerJoin('modUserGroup', 'UserGroups');
            $c->where([
                'UserGroups.id' => $userGroup,
            ]);
        }

        return $c;
    }


    public function prepareRow(xPDOObject $object)
    {
        $objectArray = $object->toArray();
        $objectArray['cls'] = 'pupdate premove pduplicate';

        return $objectArray;
    }
}
