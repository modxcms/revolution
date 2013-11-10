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
        ,fields: ['id','pagetitle','description','editedon','deleted','published','menu']
        ,columns: [{
            header: _('id')
            ,dataIndex: 'id'
            ,width: 75
        },{
            header: _('pagetitle')
            ,dataIndex: 'pagetitle'
            ,width: 150
            ,editor: { xtype: 'textfield' ,allowBlank: false }
        },{
            header: _('published')
            ,dataIndex: 'published'
            ,width: 120
            ,editor: { xtype: 'combo-boolean' ,renderer: 'boolean' }
        }]
        ,paging: true
        ,listeners: {
            'afteredit': {fn: this.refresh, scope: this}
        }
    });
    MODx.grid.RecentlyEditedResourcesByUser.superclass.constructor.call(this,config);
};
Ext.extend(MODx.grid.RecentlyEditedResourcesByUser,MODx.grid.Grid,{    
    preview: function() {
        window.open(MODx.config.base_url+'index.php?id='+this.menu.record.id);
    }
    ,refresh: function() {
        var tree = Ext.getCmp('modx-resource-tree');
        if (tree) {
            Ext.getCmp('modx-leftbar-tabpanel').setActiveTab(tree);
            tree.refresh();
        }
    }
});
Ext.reg('modx-grid-user-recent-resource',MODx.grid.RecentlyEditedResourcesByUser);
