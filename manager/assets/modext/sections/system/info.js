Ext.onReady(function() {
    MODx.load({ xtype: 'modx-page-system-info' });
});

/**
 * Loads the system info page
 * 
 * @class MODx.page.SystemInfo
 * @extends MODx.Component
 * @param {Object} config An object of config properties
 * @xtype modx-page-system-info
 */
MODx.page.SystemInfo = function(config) {
	config = config || {};
	Ext.applyIf(config,{
        components: [{
            xtype: 'modx-grid-databasetables'
            ,renderTo: 'modx-grid-databasetables-div'
        },{
            xtype: 'modx-grid-resource-active'
            ,renderTo: 'modx-grid-resource-active-div'
        }]
        ,tabs: [{
            contentEl: 'modx-tab-server', title: _('server')
        },{
            contentEl: 'modx-tab-resources', title: _('activity_title')
        },{
            contentEl: 'modx-tab-database', title: _('database_tables')
        },{
            contentEl: 'modx-tab-users', title: _('onlineusers_title')
        }]
	});
	MODx.page.SystemInfo.superclass.constructor.call(this,config);
};
Ext.extend(MODx.page.SystemInfo,MODx.Component);
Ext.reg('modx-page-system-info',MODx.page.SystemInfo);


var viewPHPInfo = function() {
	dontShowWorker = true; // prevent worker from being displayed
	window.location.href= MODx.config.connectors_url+'system/phpinfo.php';
};