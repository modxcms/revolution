<?php
/*
 * This file is part of MODX Revolution.
 *
 * Copyright (c) MODX, LLC. All Rights Reserved.
 *
 * For complete copyright and license information, see the COPYRIGHT and LICENSE
 * files found in the top-level directory of this distribution.
 */

/**
 * @package modx
 * @subpackage mysql
 */
class modDashboard extends xPDOSimpleObject {
    /**
     * Get the default MODX dashboard
     * @static
     * @param xPDO $xpdo A reference to an xPDO instance
     * @return An|null|object
     */
    public static function getDefaultDashboard(xPDO &$xpdo) {
        /** @var modDashboard $defaultDashboard */
        $defaultDashboard = $xpdo->getObject('modDashboard',array(
            'id' => 1,
        ));
        if (empty($defaultDashboard)) {
            $defaultDashboard = $xpdo->getObject('modDashboard',array(
                'name' => 'Default',
            ));
        }
        return $defaultDashboard;
    }

    /**
     * Override xPDOObject::remove() to revert to the default dashboard any user groups using this Dashboard
     *
     * @see xPDOObject::remove()
     * @param array $ancestors
     * @return boolean
     */
    public function remove(array $ancestors= array ()) {
        $dashboardId = $this->get('id');
        $removed = parent::remove($ancestors);
        if ($removed) {
            $defaultDashboard = modDashboard::getDefaultDashboard($this->xpdo);
            if (empty($defaultDashboard)) {
                /** @var modDashboard $defaultDashboard */
                $defaultDashboard = $this->xpdo->newObject('modDashboard');
                $defaultDashboard->set('id',0);
            }
            $userGroups = $this->xpdo->getCollection('modUserGroup',array(
                'dashboard' => $dashboardId,
            ));
            /** @var modUserGroup $userGroup */
            foreach ($userGroups as $userGroup) {
                $userGroup->set('dashboard',$defaultDashboard->get('id'));
                $userGroup->save();
            }
        }
        return $removed;
    }

    /**
     * Render the Dashboard
     *
     * @param modManagerController $controller
     * @return string
     */
    public function render(modManagerController $controller) {
        $c = $this->xpdo->newQuery('modDashboardWidgetPlacement');
        $c->where(array(
            'dashboard' => $this->get('id'),
        ));
        $c->sortby($this->xpdo->escape('rank'), 'ASC');
        $placements = $this->getMany('Placements', $c);
        $output = array();
        /** @var modDashboardWidgetPlacement $placement */
        foreach ($placements as $placement) {
            /** @var modDashboardWidget $widget */
            $widget = $placement->getOne('Widget');
            if ($widget) {
                $content = $widget->getContent($controller);
                if (!empty($content)) {
                    $output[] = $content;
                }
            }
        }
        return implode("\n", $output);
    }
}
