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
        ,url: MODx.config.connectors_url+'security/user.php'
        ,saveUrl: MODx.config.connectors_url+'resource/index.php'
        ,baseParams: {
            action: 'getRecentlyEditedResources'
            ,user: config.user
        }
        ,fields: ['id','pagetitle','description','editedon','deleted','published','menu']
        ,columns: this.getColumns()
        ,paging: true
        ,autosave: true
    });
    MODx.grid.RecentlyEditedResourcesByUser.superclass.constructor.call(this,config);
};
Ext.extend(MODx.grid.RecentlyEditedResourcesByUser,MODx.grid.Grid,{
    getColumns: function() {
        return [{
            header: _('id')
            ,dataIndex: 'id'
            ,width: 75
        },{
            header: _('pagetitle')
            ,dataIndex: 'pagetitle'
            ,width: 150
            ,editor: { xtype: 'textfield' ,allowBlank: false }
        },{
            header: _('description')
            ,dataIndex: 'description'
            ,width: 250            
            ,editor: { xtype: 'textfield' }
        },{
            header: _('published')
            ,dataIndex: 'published'
            ,width: 120
            ,editor: { xtype: 'combo-boolean' ,renderer: 'boolean' }
        },{
            header: _('deleted')
            ,dataIndex: 'deleted'
            ,width: 120
            ,editor: { xtype: 'combo-boolean' ,renderer: 'boolean' }
        }];
    }
    
    ,preview: function() {
        window.open(MODx.config.base_url+'index.php?id='+this.menu.record.id);
    }
});
Ext.reg('modx-grid-user-recent-resource',MODx.grid.RecentlyEditedResourcesByUser);