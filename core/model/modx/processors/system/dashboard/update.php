<?php
/**
 * Updates a Dashboard
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

$dashboard->fromArray($scriptProperties);

/* save dashboard */
if ($dashboard->save() == false) {
    return $modx->error->failure($modx->lexicon('dashboard_err_save'));
}

/* assign widgets to this dashboard */
if (isset($scriptProperties['widgets'])) {
    /** @var array $widgets */
    $widgets = is_array($scriptProperties['widgets']) ? $scriptProperties['widgets'] : $modx->fromJSON($scriptProperties['widgets']);
    
    $oldPlacements = $modx->getCollection('modDashboardWidgetPlacement',array(
        'dashboard' => $dashboard->get('id'),
    ));
    /** @var $oldPlacement modDashboardWidgetPlacement */
    foreach ($oldPlacements as $oldPlacement) {
        $oldPlacement->remove();
    }

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

/* manage usergroups attached to this dashboard */
if (isset($scriptProperties['usergroups'])) {
    $userGroupIds = array();
    $userGroups = $modx->fromJSON($scriptProperties['usergroups']);
    /** @var array $userGroupArray */
    foreach ($userGroups as $userGroupArray) {
        $userGroupIds[] = $userGroupArray['id'];
    }
    $userGroupIds = array_unique($userGroupIds);

    /** @var modDashboard $defaultDashboard */
    $defaultDashboard = modDashboard::getDefaultDashboard($modx);

    /* update usergroups assigned to this Dashboard */
    $oldUserGroups = $modx->getCollection('modUserGroup',array(
        'dashboard' => $dashboard->get('id'),
    ));
    /** @var modUserGroup $userGroup */
    foreach ($oldUserGroups as $userGroup) {
        if (!in_array($userGroup->get('id'),$userGroupIds)) {
            $userGroup->set('dashboard',$defaultDashboard->get('id'));
            $userGroup->save();
        }
    }

}

/* log manager action */
$modx->logManagerAction('dashboard_update','modDashboard',$dashboard->get('id'));

return $modx->error->success('',$dashboard);