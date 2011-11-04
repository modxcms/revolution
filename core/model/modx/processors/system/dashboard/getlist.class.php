<?php
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
 * @var modX $modx
 * @var array $scriptProperties
 * @var modProcessor $this
 * 
 * @package modx
 * @subpackage processors.security.user
 */
class modDashboardGetListProcessor extends modObjectGetListProcessor {
    public $classKey = 'modDashboard';
    public $languageTopics = array('dashboards');
    public $permission = 'dashboards';

    public function prepareQueryAfterCount(xPDOQuery $c) {
        $query = $this->getProperty('query');
        if (!empty($query)) {
            $c->where(array('modDashboard.name:LIKE' => '%'.$query.'%'));
            $c->orCondition(array('modDashboard.description:LIKE' => '%'.$query.'%'));
        }
        $userGroup = $this->getProperty('usergroup',false);
        if (!empty($userGroup)) {
            $c->innerJoin('modUserGroup','UserGroups');
            $c->where(array(
                'UserGroups.id' => $userGroup,
            ));
        }
        return $c;
    }

    public function prepareRow(xPDOObject $object) {
        $objectArray = $object->toArray();
        $objectArray['cls'] = 'pupdate premove pduplicate';
        return $objectArray;
    }
}
return 'modDashboardGetListProcessor';