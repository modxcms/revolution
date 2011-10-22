<?php
/**
 * Update a Dashboard from the grid. Sent through JSON-encoded 'data' parameter.
 *
 * @param integer $id The ID of the Dashboard
 * @param string $name The new name
 * @param string $description (optional) A short description
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

/* loop through content types */
if (empty($scriptProperties['data'])) return $modx->error->failure($modx->lexicon('dashboard_err_ns'));
$_DATA = $modx->fromJSON($scriptProperties['data']);
if (empty($_DATA['id'])) return $modx->error->failure($modx->lexicon('dashboard_err_ns'));

/** @var modDashboard $dashboard */
$dashboard = $modx->getObject('modDashboard',$_DATA['id']);
if (empty($dashboard)) return $modx->error->failure($modx->lexicon('dashboard_err_nf'));

/* save content type */
$dashboard->fromArray($_DATA);
if ($dashboard->save() == false) {
    $modx->error->checkValidation($dashboard);
    return $modx->error->failure($modx->lexicon('dashboard_err_save'));
}

/* log manager action */
$modx->logManagerAction('dashboard_save','modDashboard',$dashboard->get('id'));

return $modx->error->success('',$dashboard);