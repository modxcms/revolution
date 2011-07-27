<?php
/**
 * Removes multiple Dashboards
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

if (empty($scriptProperties['dashboards'])) {
    return $modx->error->failure($modx->lexicon('dashboard_err_ns'));
}

$dashboardIds = explode(',',$scriptProperties['dashboards']);

foreach ($dashboardIds as $dashboardId) {
    /** @var modDashboard $dashboard */
    $dashboard = $modx->getObject('modDashboard',$dashboardId);
    if (empty($dashboard)) { continue; }

    if ($dashboard->remove() == false) {
        $modx->log(modX::LOG_LEVEL_ERROR,$modx->lexicon('dashboard_err_remove'));
        continue;
    }
    $modx->logManagerAction('dashboard_remove','modDashboardWidget',$dashboard->get('id'));
}

return $modx->error->success();