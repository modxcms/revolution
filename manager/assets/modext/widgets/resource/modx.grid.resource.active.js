/**
 * Loads a grid of recently-edited modResources.
 * 
 * @class MODx.grid.ActiveResources
 * @extends MODx.grid.Grid
 * @param {Object} config An object of options.
 * @xtype modx-grid-resource-active
 */
MODx.grid.ActiveResources = function(config) {
    config = config || {};
	Ext.applyIf(config,{
		title: _('resources_active')
        ,id: 'modx-grid-resource-active'
        ,url: MODx.config.connectors_url+'system/activeresource.php'
		,fields: ['id','pagetitle','user','editedon']
        ,columns: [
            { header: _('id') ,dataIndex: 'id' ,width: 50 }
            ,{ header: _('page_title') ,dataIndex: 'pagetitle' ,width: 240 }
            ,{ header: _('sysinfo_userid') ,dataIndex: 'user' ,width: 180 }
            ,{ header: _('datechanged') ,dataIndex: 'editedon' ,width: 140 }]
		,paging: true
	});
	MODx.grid.ActiveResources.superclass.constructor.call(this,config);
};
Ext.extend(MODx.grid.ActiveResources,MODx.grid.Grid);
Ext.reg('modx-grid-resource-active',MODx.grid.ActiveResources);