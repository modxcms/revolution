<?php
/**
 * @package modx
 * @subpackage dashboard
 */
/**
 * Renders a grid of recently edited resources by the active user
 * 
 * @package modx
 * @subpackage dashboard
 */
class modDashboardWidgetRecentlyEditedResources extends modDashboardWidgetInterface {
    public function render() {
        $this->modx->controller->addHtml('<script type="text/javascript">Ext.onReady(function() {
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