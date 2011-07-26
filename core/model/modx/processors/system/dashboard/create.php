<?php
/**
 * Creates a Dashboard
 *
 * @param integer $id The ID of the dashboard
 *
 * @var modX $modx
 * @var array $scriptProperties
 * @var modProcessor $this
 *
 * @package modx
 * @subpackage processors.system.dashboard
 */
if (!$modx->hasPermission('dashboards')) return $modx->error->failure($modx->lexicon('permission_denied'));
$modx->lexicon->load('dashboards');


/* validate name field */
if (empty($scriptProperties['name'])) {
    $modx->error->addField('name',$modx->lexicon('dashboard_err_ns_name'));
} else {
    $alreadyExists = $modx->getCount('modDashboard',array(
        'name' => $scriptProperties['name'],
    ));
    if ($alreadyExists > 0) {
        $modx->error->addField('name',$modx->lexicon('dashboard_err_ae_name',array(
            'name' => $scriptProperties['name']
        )));
    }
}

if ($modx->error->hasError()) {
    return $modx->error->failure();
}

/** @var modDashboard $dashboard */
$dashboard = $modx->newObject('modDashboard');
$dashboard->fromArray($scriptProperties);

/* save dashboard */
if ($dashboard->save() == false) {
    return $modx->error->failure($modx->lexicon('dashboard_err_save'));
}

/* assign widgets to this dashboard */
if (isset($scriptProperties['widgets'])) {
    /** @var array $widgets */
    $widgets = is_array($scriptProperties['widgets']) ? $scriptProperties['widgets'] : $modx->fromJSON($scriptProperties['widgets']);
    
    /** @var array $widget */
    foreach ($widgets as $widget) {
        /** @var modDashboardWidgetPlacement $placement */
        $placement = $modx->newObject('modDashboardWidgetPlacement');
        $placement->set('dashboard',$dashboard->get('id'));
        $placement->set('widget',$widget['widget']);
        $placement->set('rank',$widget['rank']);
        $placement->save();
    }
}

/* log manager action */
$modx->logManagerAction('dashboard_create','modDashboard',$dashboard->get('id'));

return $modx->error->success('',$dashboard);