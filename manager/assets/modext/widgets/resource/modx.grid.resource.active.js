MODx.grid.ActiveResources = function(config) {
    config = config || {};
	Ext.applyIf(config,{
		title: _('resources_active')
        ,id: 'modx-grid-resource-active'
        ,url: MODx.config.connector_url
        ,baseParams: {
            action: 'System/ActiveResource/GetList'
        }
		,fields: ['id','pagetitle','username','editedon']
        ,showActionsColumn: false
        ,columns: [{
            header: _('id')
            ,dataIndex: 'id'
            ,width: 50
            ,sortable: true
        },{
            header: _('page_title')
            ,dataIndex: 'pagetitle'
            ,width: 240
            ,sortable: true
        },{
            header: _('sysinfo_userid')
            ,dataIndex: 'username'
            ,width: 180
            ,sortable: true
        },{
            header: _('datechanged')
            ,dataIndex: 'editedon'
            ,width: 140
            ,sortable: true
        }]
        ,remoteSort: true
		,paging: true
	});
	MODx.grid.ActiveResources.superclass.constructor.call(this,config);
};
Ext.extend(MODx.grid.ActiveResources,MODx.grid.Grid);
Ext.reg('modx-grid-resource-active',MODx.grid.ActiveResources);
