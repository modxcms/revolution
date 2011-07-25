<?php
/**
 * Removes a Dashboard
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

/* get dashboard */
if (empty($scriptProperties['id'])) return $modx->error->failure($modx->lexicon('dashboard_err_ns'));
/** @var modDashboard $dashboard */
$dashboard = $modx->getObject('modDashboard',$scriptProperties['id']);
if (empty($dashboard)) {
    return $modx->error->failure($modx->lexicon('dashboard_err_nf',array('id' => $scriptProperties['id'])));
}

if ($dashboard->get('id') == 1) return $modx->error->failure($modx->lexicon('dashboard_err_remove_default'));

/* remove dashboard */
if ($dashboard->remove() == false) {
    return $modx->error->failure($modx->lexicon('dashboard_err_remove'));
}

/* log manager action */
$modx->logManagerAction('dashboard_delete','modDashboard',$dashboard->get('id'));

return $modx->error->success('',$dashboard);