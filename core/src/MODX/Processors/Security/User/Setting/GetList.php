<?php

namespace MODX\Processors\Security\User\Settings;
/**
 * Gets a list of user settings
 *
 * @param integer $user The user to grab from
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
    public $classKey = 'modUserSetting';


    public function initialize()
    {
        $this->setDefaultProperties([
            'user' => 0,
        ]);

        return parent::initialize();
    }


    /**
     * Filter by user
     *
     * @return array
     */
    public function prepareCriteria()
    {
        $criteria = [];
        $criteria[] = ['user' => (int)$this->getProperty('user')];

        return $criteria;
    }
}