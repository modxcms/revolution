/**
 * Loads a grid of all the resources a user has recently edited.
 *
 * @class MODx.grid.RecentlyEditedResourcesByUser
 * @extends MODx.grid.Grid
 * @param {Object} config An object of options.
 * @xtype modx-grid-user-recent-resource
 */
MODx.grid.RecentlyEditedResourcesByUser = function(config) {
    config = config || {};
    Ext.applyIf(config,{
        title: _('recent_docs')
        ,url: MODx.config.connector_url
        ,baseParams: {
            action: 'security/user/getRecentlyEditedResources'
            ,user: config.user
        }
        ,autosave: true
        ,save_action: 'resource/updatefromgrid'
        ,pageSize: 10
        ,fields: ['id','pagetitle','description','editedon','deleted','published','context_key','menu', 'link']
        ,columns: [{
            header: _('id')
            ,dataIndex: 'id'
            ,width: 75
            ,fixed: true
        },{
            header: _('pagetitle')
            ,dataIndex: 'pagetitle'
        },{
            header: _('published')
            ,dataIndex: 'published'
            ,width: 120
            ,fixed: true
            ,editor: { xtype: 'combo-boolean' ,renderer: 'boolean' }
        }]
        ,paging: true
        ,listeners: {
            afteredit: this.refresh
            ,afterrender: this.onAfterRender
            ,scope: this
        }
    });
    MODx.grid.RecentlyEditedResourcesByUser.superclass.constructor.call(this,config);
};
Ext.extend(MODx.grid.RecentlyEditedResourcesByUser,MODx.grid.Grid,{
    preview: function() {
        window.open(this.menu.record.link);
    }
    ,refresh: function() {
        var tree = Ext.getCmp('modx-resource-tree');
        if (tree && tree.rendered) {
            tree.refresh();
        }
    }
    // Workaround to resize the grid when in a dashboard widget
    ,onAfterRender: function() {
        var cnt = Ext.getCmp('modx-content')
            // Dashboard widget "parent" (renderTo)
            ,parent = Ext.get('modx-grid-user-recent-resource');

        if (cnt && parent) {
            cnt.on('afterlayout', function(elem, layout) {
                var width = parent.getWidth();
                // Only resize when more than 500px (else let's use/enable the horizontal scrolling)
                if (width > 500) {
                    this.setWidth(width);
                }
            }, this);
        }
    }
});
Ext.reg('modx-grid-user-recent-resource',MODx.grid.RecentlyEditedResourcesByUser);
