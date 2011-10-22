<?php
/**
 * Creates a new Dashboard Widget
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

/* validate name field */
if (empty($scriptProperties['name'])) {
    $modx->error->addField('name',$modx->lexicon('widget_err_ns_name'));
} else {
    $alreadyExists = $modx->getCount('modDashboardWidget',array(
        'name' => $scriptProperties['name'],
    ));
    if ($alreadyExists > 0) {
        $modx->error->addField('name',$modx->lexicon('widget_err_ae_name',array(
            'name' => $scriptProperties['name']
        )));
    }
}

if ($modx->error->hasError()) {
    return $modx->error->failure();
}

/** @var modDashboardWidget $widget */
$widget = $modx->newObject('modDashboardWidget');
$widget->fromArray($scriptProperties);

/* save dashboard */
if ($widget->save() == false) {
    return $modx->error->failure($modx->lexicon('widget_err_save'));
}

/* log manager action */
$modx->logManagerAction('dashboard_widget_create','modDashboardWidget',$widget->get('id'));

return $modx->error->success('',$widget);