<?php

namespace MODX\Processors\System\Dashboard\Widget;

use MODX\Processors\modObjectUpdateProcessor;

/**
 * Updates a Dashboard Widget
 *
 * @package modx
 * @subpackage processors.system.dashboard.widget
 */
class Update extends modObjectUpdateProcessor
{
    public $classKey = 'modDashboardWidget';
    public $languageTopics = ['dashboards'];
    public $permission = 'dashboards';
    public $objectType = 'widget';


    /**
     * @return bool
     */
    public function beforeSet()
    {
        if ($properties = $this->getProperty('properties')) {
            if (is_string($properties)) {
                $properties = json_decode($properties, true);
            }
            $this->setProperty('properties', $properties);
        }

        return parent::beforeSet();
    }

}