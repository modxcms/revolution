<?php

/**
 * Updates a Dashboard
 *
 * @param integer $id The ID of the dashboard
 *
 * @var modX $this ->modx
 * @var array $scriptProperties
 * @var modProcessor $this
 *
 * @package modx
 * @subpackage processors.system.dashboard
 */
class modDashboardUpdateProcessor extends modObjectUpdateProcessor
{
    public $languageTopics = ['dashboards'];
    public $permission = 'dashboards';
    public $objectType = 'dashboard';
    public $classKey = 'modDashboard';
    /** @var modDashboard $object */
    public $object;


    /**
     * @return bool
     */
    public function afterSave()
    {
        $this->setWidgets();

        return parent::afterSave();
    }


    /**
     * Set the widgets assigned to this Dashboard
     */
    public function setWidgets()
    {
        if ($widgets = $this->getProperty('widgets')) {
            if (!is_array($widgets)) {
                $widgets = json_decode($widgets, true);
            }
            $this->modx->removeCollection('modDashboardWidgetPlacement', [
                'dashboard' => $this->object->get('id'),
                'user' => 0,
            ]);
            foreach ($widgets as $data) {
                $key = [
                    'dashboard' => $this->object->get('id'),
                    'user' => 0,
                    'widget' => $data['widget'],
                ];
                if (!$widget = $this->modx->getObject('modDashboardWidgetPlacement', $key)) {
                    $widget = $this->modx->newObject('modDashboardWidgetPlacement');
                    $widget->fromArray($key, '', true, true);
                }
                $widget->set('rank', $data['rank']);
                $widget->save();
            }

            $this->object->sortWidgets();
        }
    }
}

return 'modDashboardUpdateProcessor';