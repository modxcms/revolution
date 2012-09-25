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
            xtype: 'modx-panel-system-info'
            ,renderTo: 'modx-panel-system-info-div'
            ,data: config.data
        }]
    });
    MODx.page.SystemInfo.superclass.constructor.call(this,config);
};
Ext.extend(MODx.page.SystemInfo,MODx.Component);
Ext.reg('modx-page-system-info',MODx.page.SystemInfo);


var viewPHPInfo = function() {
    window.open(MODx.config.connectors_url+'system/phpinfo.php?HTTP_MODAUTH='+MODx.siteId);
};

MODx.panel.SystemInfo = function(config) {
    config = config || {};
    var info = [{
		xtype: 'statictextfield'
		,fieldLabel: _('modx_version')
		,name: 'modx_version'
	},{
		xtype: 'statictextfield'
		,fieldLabel: _('version_codename')
		,name: 'code_name'
	},{
		html: '<a href="javascript:;" onclick="viewPHPInfo();return false;">'+_('view')+'</a>'
		,fieldLabel: 'phpinfo()'
		,name: 'phpinfo'
		,value: ''
	},{
		xtype: 'statictextfield'
		,fieldLabel: _('servertime')
		,name: 'servertime'
	},{
		xtype: 'statictextfield'
		,fieldLabel: _('localtime')
		,name: 'localtime'
	},{
		xtype: 'statictextfield'
		,fieldLabel: _('serveroffset')
		,name: 'serveroffset'
	},{
		html: '<hr />'
	},{
		xtype: 'statictextfield'
		,fieldLabel: _('extjs_version')
		,name: 'extjs_version'
		,value: '3.4.0'
	},{
		xtype: 'statictextfield'
		,fieldLabel: _('smarty_version')
		,name: 'smarty_version'
		,value: '3.0.4'
	},{
		xtype: 'statictextfield'
		,fieldLabel: _('phpmailer_version')
		,name: 'phpmailer_version'
		,value: '2.0.4'
	},{
		xtype: 'statictextfield'
		,fieldLabel: _('magpie_version')
		,name: 'magpie_version'
		,value: '0.72'
	},{
		html: '<hr />'
	},{
		xtype: 'statictextfield'
		,fieldLabel: _('database_type')
		,name: 'database_type'
	},{
		xtype: 'statictextfield'
		,fieldLabel: _('database_version')
		,name: 'database_version'
	},{
		xtype: 'statictextfield'
		,fieldLabel: _('database_charset')
		,name: 'database_charset'
	},{
		xtype: 'statictextfield'
		,fieldLabel: _('database_name')
		,name: 'database_name'
	},{
		xtype: 'statictextfield'
		,fieldLabel: _('database_server')
		,name: 'database_server'
	},{
		xtype: 'statictextfield'
		,fieldLabel: _('table_prefix')
		,name: 'table_prefix'
    }];
    if (config.data) {
        for (var c in config.data) {
            info.push({html: '<hr />'});
            for (var d in config.data[c]) {
                info.push({
                    xtype: 'statictextfield'
                    ,fieldLabel: d
                    ,name: d
                    ,value: config.data[c][d]
                });
            }
        }
    }
	var pnl = [{
        html: '<p>'+_('sysinfo_desc')+'</p>'
        ,id: 'modx-sysinfo-msg'
		,bodyCssClass: 'panel-desc'
    },{
		xtype: 'panel'
		,border: false
		,cls:'main-wrapper'
		,layout: 'form'
		,defaults: { border: false, msgTarget: 'side', anchor: '97%' }
		,items: [info]
	}];
    Ext.applyIf(config,{
        id: 'modx-panel-system-info'
        ,url: MODx.config.connectors_url+'system/index.php'
        ,layout: 'fit'
		,cls: 'container'
        ,items: [{
            html: '<h2>'+_('view_sysinfo')+'</h2>'
            ,id: 'modx-error-log-header'
            ,cls: 'modx-page-header'
            ,border: false
            ,anchor: '100%'
        },MODx.getPageStructure([{
            title: _('view_sysinfo')
            ,layout: 'form'
            ,id: 'modx-plugin-form'
            ,labelWidth: 230
            ,defaults: { border: false }
            ,items: pnl
        },{
            title: _('db_header')
            ,id: 'modx-sysinfo-dbtables'
            ,items: [{
                html: '<p>'+_('db_info_' + MODx.config.dbtype)+'</p>'
                ,id: 'modx-sysinfo-dbtables-msg'
				,bodyCssClass: 'panel-desc'
                ,border: false
            },{
                xtype: 'modx-grid-databasetables'
				,cls:'main-wrapper'
                ,preventRender: true
            }]
        },{
            title: _('recent_docs')
            ,id: 'modx-sysinfo-recent-docs'
            ,items: [{
                html: '<p>'+_('sysinfo_activity_message')+'</p>'
                ,id: 'modx-sysinfo-recent-docs-msg'
				,bodyCssClass: 'panel-desc'
                ,border: false
            },{
                xtype: 'modx-grid-resource-active'
				,cls:'main-wrapper'
                ,title: _('recent_docs')
                ,preventRender: true
            }]
        }])]
        ,listeners: {
            'setup': {fn:this.setup,scope:this}
            ,'success': {fn:this.success,scope:this}
            ,'beforeSubmit': {fn:this.beforeSubmit,scope:this}
        }
    });
    MODx.panel.SystemInfo.superclass.constructor.call(this,config);
}
Ext.extend(MODx.panel.SystemInfo,MODx.FormPanel,{

    initialized: false
    ,setup: function() {
        if (this.config.plugin === '' || this.config.plugin === 0 || this.initialized) {
            this.fireEvent('ready');
            return false;
        }
        MODx.Ajax.request({
            url: this.config.url
            ,params: {
                action: 'info'
            }
            ,listeners: {
            	'success': {fn:function(r) {
                    this.getForm().setValues(r.object);
                    this.initialized = true;
            	},scope:this}
            }
        });
    }
    ,beforeSubmit: function(o) {
    }
    ,success: function(o) {
    }
});
Ext.reg('modx-panel-system-info',MODx.panel.SystemInfo);
