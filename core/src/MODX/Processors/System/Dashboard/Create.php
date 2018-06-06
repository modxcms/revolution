<?php

namespace MODX\Processors\System\Dashboard;

use MODX\modDashboardWidgetPlacement;
use MODX\Processors\modObjectCreateProcessor;

/**
 * Creates a Dashboard
 *
 * @param integer $id The ID of the dashboard
 *
 * @package modx
 * @subpackage processors.system.dashboard
 */
class Create extends modObjectCreateProcessor
{
    public $classKey = 'modDashboard';
    public $languageTopics = ['dashboards'];
    public $permission = 'dashboards';
    public $objectType = 'dashboard';


    public function beforeSave()
    {
        /* validate name field */
        $name = $this->getProperty('name');
        if (empty($name)) {
            $this->addFieldError('name', $this->modx->lexicon('dashboard_err_ns_name'));

        } elseif ($this->alreadyExists($name)) {
            $this->addFieldError('name', $this->modx->lexicon('dashboard_err_ae_name', [
                'name' => $name,
            ]));
        }

        return !$this->hasErrors();
    }


    public function afterSave()
    {
        $widgets = $this->getProperty('widgets', null);
        if ($widgets != null) {
            $this->assignWidgets($widgets);
        }

        return parent::afterSave();
    }


    /**
     * See if a Dashboard already exists with this name
     *
     * @param string $name
     *
     * @return bool
     */
    public function alreadyExists($name)
    {
        return $this->modx->getCount('modDashboard', ['name' => $name]) > 0;
    }


    /**
     * Assign widgets to this dashboard
     *
     * @param array|string $widgets
     *
     * @return array An array of placement objects
     */
    public function assignWidgets($widgets)
    {
        $placements = [];

        $widgets = !is_array($widgets) ? $widgets : json_decode($widgets, true);

        /** @var array $widget */
        foreach ($widgets as $widget) {
            /** @var modDashboardWidgetPlacement $placement */
            $placement = $this->modx->newObject('modDashboardWidgetPlacement');
            $placement->set('dashboard', $this->object->get('id'));
            $placement->set('widget', $widget['widget']);
            $placement->set('rank', $widget['rank']);
            $placement->save();
            $placements[] = $placement;
        }

        return $placements;
    }
}