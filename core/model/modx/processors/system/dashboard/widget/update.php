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
if (!$modx->hasPermission('dashboards')) return $modx->error->failure($modx->lexicon('permission_denied'));
$modx->lexicon->load('dashboards');

/* get widget */
if (empty($scriptProperties['id'])) return $modx->error->failure($modx->lexicon('widget_err_ns'));
/** @var modDashboardWidget $widget */
$widget = $modx->getObject('modDashboardWidget',$scriptProperties['id']);
if (empty($widget)) {
    return $modx->error->failure($modx->lexicon('widget_err_nf',array('id' => $scriptProperties['id'])));
}

$widget->fromArray($scriptProperties);

/* save dashboard */
if ($widget->save() == false) {
    return $modx->error->failure($modx->lexicon('widget_err_save'));
}

/* log manager action */
$modx->logManagerAction('dashboard_widget_update','modDashboardWidget',$widget->get('id'));

return $modx->error->success('',$widget);