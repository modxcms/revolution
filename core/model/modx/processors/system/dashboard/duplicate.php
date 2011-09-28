<?php
/**
 * Duplicates a dashboard.
 *
 * @param integer $id The dashboard to duplicate
 * @param string $name The name of the new chunk.
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

/* @var modDashboard $oldDashboard */
if (empty($scriptProperties['id'])) return $modx->error->failure($modx->lexicon('dashboard_err_ns'));
$oldDashboard = $modx->getObject('modDashboard',$scriptProperties['id']);
if (empty($oldDashboard)) return $modx->error->failure($modx->lexicon('dashboard_err_nf'));

/* check name */
$newName = !empty($scriptProperties['name'])
    ? $scriptProperties['name']
    : $modx->lexicon('duplicate_of',array(
        'name' => $oldDashboard->get('name'),
    ));

$oldPlacements = $modx->getCollection('modDashboardWidgetPlacement',array(
    'dashboard' => $oldDashboard->get('id'),
));
$placements = array();
/** @var modDashboardWidgetPlacement $placement */
foreach ($oldPlacements as $placement) {
    /** @var modDashboardWidgetPlacement $placementCopy */
    $placementCopy = $modx->newObject('modDashboardWidgetPlacement');
    $placementCopy->fromArray(array(
        'widget' => $placement->get('widget'),
        'rank' => $placement->get('rank'),
    ),'',true,true);
    $placements[] = $placementCopy;
}

/* @var modDashboard $newDashboard */
$newDashboard = $modx->newObject('modDashboard');
$newDashboard->fromArray($newDashboard->toArray());
$newDashboard->set('name',$newName);

$newDashboard->addMany($placements);

if ($newDashboard->save() === false) {
    $modx->error->checkValidation($newDashboard);
    return $modx->error->failure($modx->lexicon('dashboard_err_duplicate'));
}

/* log manager action */
$modx->logManagerAction('dashboard_duplicate','modDashboard',$newDashboard->get('id'));

return $modx->error->success('',$newDashboard->get(array('id', 'name', 'description')));