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
if (!$modx->hasPermission('dashboards')) return $modx->error->failure($modx->lexicon('permission_denied'));
$modx->lexicon->load('dashboards');

if (empty($scriptProperties['widgets'])) {
    return $modx->error->failure($modx->lexicon('widget_err_ns'));
}

$widgetIds = explode(',',$scriptProperties['widgets']);

foreach ($widgetIds as $widgetId) {
    /** @var modDashboardWidget $widget */
    $widget = $modx->getObject('modDashboardWidget',$widgetId);
    if (empty($widget)) { continue; }

    if ($widget->remove() == false) {
        $modx->log(modX::LOG_LEVEL_ERROR,$modx->lexicon('widget_err_remove'));
        continue;
    }
    $modx->logManagerAction('dashboard_widget_remove','modDashboardWidget',$widget->get('id'));
}

return $modx->error->success();