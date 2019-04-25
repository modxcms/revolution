<?php
/**
 * Resolve DashboardWidgets to the Default Dashboard
 *
 * @var xPDOTransport $transport
 * @package modx
 * @subpackage build
 */
$success = false;

$map = [
    'w_buttons' => [],
    'w_configcheck' => [],
    'w_newsfeed' => [],
    'w_securityfeed' => [],
    'w_updates' => [
        'permission' => 'workspaces',
    ],
    'w_whosonline' => [],
    'w_recentlyeditedresources' => [
        'permission' => 'view_document',
    ],
    'w_welcome' => []
];

/** @var modDashboard $dashboard */
$dashboard = $transport->xpdo->getObject('modDashboard', 1);
if (empty($dashboard)) {
    $dashboard = $transport->xpdo->getObject('modDashboard', ['name' => 'Default']);
    if (empty($dashboard)) {
        $transport->xpdo->log(xPDO::LOG_LEVEL_ERROR, 'Could not find default Dashboard!');

        return false;
    }
}

$success = true;
$idx = 0;
foreach ($map as $name => $params) {
    /** @var modDashboardWidget $widget */
    $widget = $transport->xpdo->getObject('modDashboardWidget', [
        'name' => $name,
    ]);
    if ($widget) {
        if (!empty($params)) {
            $widget->fromArray($params);
            $widget->save();
        }
        /** @var modDashboardWidgetPlacement $placement */
        $placement = $transport->xpdo->getObject('modDashboardWidgetPlacement', [
            'widget' => $widget->get('id'),
            'dashboard' => $dashboard->get('id'),
            'user' => 0,
        ]);
        if (!$placement) {
            $placement = $transport->xpdo->newObject('modDashboardWidgetPlacement');
            $placement->set('widget', $widget->get('id'));
            $placement->set('dashboard', $dashboard->get('id'));
            $placement->set('rank', $idx);
            $placement->set('size', $widget->get('size'));
            if (!$placement->save()) {
                $success = false;
            }
        }
        $idx++;
    }
}
$dashboard->sortWidgets();

return $success;