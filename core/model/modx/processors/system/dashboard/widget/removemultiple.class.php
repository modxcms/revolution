<?php
/**
 * Removes multiple Dashboard Widgets
 *
 * @var modX $modx
 * @var array $scriptProperties
 * @var modProcessor $this
 *
 * @package modx
 * @subpackage processors.system.dashboard.widget
 */
class modDashboardWidgetRemoveMultipleProcessor extends modProcessor {
    public function checkPermissions() {
        return $this->modx->hasPermission('dashboards');
    }

    public function getLanguageTopics() {
        return array('dashboards');
    }

    public function process() {
        $widgets = $this->getProperty('widgets',null);

        if (empty($widgets)) {
            return $this->failure($this->modx->lexicon('widget_err_ns'));
        }

        $widgetIds = is_array($widgets) ? $widgets : explode(',',$widgets);
        foreach ($widgetIds as $widgetId) {
            /** @var modDashboardWidget $widget */
            $widget = $this->modx->getObject('modDashboardWidget',$widgetId);
            if (empty($widget)) { continue; }

            if ($widget->remove() == false) {
                $this->modx->log(modX::LOG_LEVEL_ERROR,$this->modx->lexicon('widget_err_remove'));
                continue;
            }
            $this->modx->logManagerAction('dashboard_widget_remove','modDashboardWidget',$widget->get('id'));
        }

        return $this->success();
    }
}
return 'modDashboardWidgetRemoveMultipleProcessor';