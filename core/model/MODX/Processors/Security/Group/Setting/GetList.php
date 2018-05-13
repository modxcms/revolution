<?php

namespace MODX\Processors\Security\Group\Setting;

/**
 * Gets a list of user group settings
 *
 * @param integer $group The group to grab from
 * @param integer $start (optional) The record to start at. Defaults to 0.
 * @param integer $limit (optional) The number of records to limit to. Defaults
 * to 10.
 * @param string $sort (optional) The column to sort by. Defaults to key.
 * @param string $dir (optional) The direction of the sort. Defaults to ASC.
 *
 * @package modx
 * @subpackage processors.system.settings
 */
class GetList extends \MODX\Processors\System\Settings\GetList
{
    public $classKey = 'modUserGroupSetting';


    public function initialize()
    {
        $this->setDefaultProperties([
            'group' => 0,
        ]);

        return parent::initialize();
    }


    /**
     * Filter by usergroup
     *
     * @return array
     */
    public function prepareCriteria()
    {
        $criteria = [];
        $criteria[] = ['group' => (int)$this->getProperty('group')];

        return $criteria;
    }

}