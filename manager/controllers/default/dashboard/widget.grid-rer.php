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
 * Renders a grid of recently edited resources by the active user
 *
 * @package modx
 * @subpackage dashboard
 */
class modDashboardWidgetRecentlyEditedResources extends modDashboardWidgetInterface {
    public function render() {
        $this->controller->addJavascript($this->modx->getOption('manager_url').'assets/modext/widgets/security/modx.grid.user.recent.resource.js');
        $this->controller->addHtml('<script>Ext.onReady(function() {
    MODx.load({
        xtype: "modx-grid-user-recent-resource"
        ,user: "'.$this->modx->user->get('id').'"
        ,renderTo: "modx-grid-user-recent-resource"
    });
});</script>');

        return $this->getFileChunk('dashboard/recentlyeditedresources.tpl');
    }
}
return 'modDashboardWidgetRecentlyEditedResources';
