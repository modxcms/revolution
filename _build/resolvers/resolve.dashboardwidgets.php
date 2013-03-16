<?php
/**
 * Resolve DashboardWidgets to the Default Dashboard
 *
 * @var xPDOTransport $transport
 * @package modx
 * @subpackage build
 */
$success= false;

$map= array(
    'w_configcheck',
    'w_newsfeed',
    'w_securityfeed',
    'w_whosonline',
    'w_recentlyeditedresources',
);

/** @var modDashboard $dashboard */
$dashboard = $transport->xpdo->getObject('modDashboard',1);
if (empty($dashboard)) {
    $dashboard = $transport->xpdo->getObject('modDashboard',array('name' => 'Default'));
    if (empty($dashboard)) {
        $transport->xpdo->log(xPDO::LOG_LEVEL_ERROR,'Could not find default Dashboard!');
        return false;
    }
}

$idx = 0;
$widgets = $transport->xpdo->getCollection('modDashboardWidget');
foreach ($map as $widgetName) {
    /** @var modDashboardWidget $widget */
    $widget = $transport->xpdo->getObject('modDashboardWidget',array(
        'name' => $widgetName,
    ));
    if ($widget) {
        /** @var modDashboardWidgetPlacement $placement */
        $placement = $transport->xpdo->getObject('modDashboardWidgetPlacement',array(
            'widget' => $widget->get('id'),
            'dashboard' => $dashboard->get('id'),
        ));
        if (!$placement) {
            $placement = $transport->xpdo->newObject('modDashboardWidgetPlacement');
            $placement->set('widget',$widget->get('id'));
            $placement->set('dashboard',$dashboard->get('id'));
            $placement->set('rank',$idx);
            $success = $placement->save();
        } else {
            $success = true;
        }
        $idx++;
    }
}
return $success;