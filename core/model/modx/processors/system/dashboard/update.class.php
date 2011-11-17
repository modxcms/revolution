<?php
/**
 * Updates a Dashboard
 *
 * @param integer $id The ID of the dashboard
 *
 * @var modX $this->modx
 * @var array $scriptProperties
 * @var modProcessor $this
 *
 * @package modx
 * @subpackage processors.system.dashboard
 */
class modDashboardUpdateProcessor extends modObjectUpdateProcessor {
    public $classKey = 'modDashboard';
    public $languageTopics = array('dashboards');
    public $permission = 'dashboards';
    public $objectType = 'dashboard';
    
    public function afterSave() {
        $this->setWidgets();
        return parent::afterSave();
    }

    /**
     * Set the widgets assigned to this Dashboard
     * @return void
     */
    public function setWidgets() {
        $widgets = $this->getProperty('widgets',null);
        if ($widgets !== null) {
            /** @var array $widgets */
            $widgets = is_array($widgets) ? $widgets : $this->modx->fromJSON($widgets);

            $oldPlacements = $this->modx->getCollection('modDashboardWidgetPlacement',array(
                'dashboard' => $this->object->get('id'),
            ));
            /** @var $oldPlacement modDashboardWidgetPlacement */
            foreach ($oldPlacements as $oldPlacement) {
                $oldPlacement->remove();
            }

            /** @var array $widget */
            foreach ($widgets as $widget) {
                /** @var modDashboardWidgetPlacement $placement */
                $placement = $this->modx->newObject('modDashboardWidgetPlacement');
                $placement->set('dashboard',$this->object->get('id'));
                $placement->set('widget',$widget['widget']);
                $placement->set('rank',$widget['rank']);
                $placement->save();
            }
        }
    }
}
return 'modDashboardUpdateProcessor';