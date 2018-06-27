<?php
/**
 * Updates a Dashboard Widget
 *
 * @param integer $id The ID of the dashboard widget
 *
 * @var modX $modx
 * @var array $scriptProperties
 * @var modProcessor $this
 *
 * @package modx
 * @subpackage processors.system.dashboard.widget
 */
class modDashboardWidgetUpdateProcessor extends modObjectUpdateProcessor {
    public $classKey = 'modDashboardWidget';
    public $languageTopics = array('dashboards');
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

return 'modDashboardWidgetUpdateProcessor';