MODx.panel.ErrorLog = function(config) {
    config = config || {};
    Ext.applyIf(config,{
        url: MODx.config.connectors_url+'system/errorlog.php'
        ,id: 'modx-panel-error-log'
		,cls: 'container'
        ,baseParams: {
            action: 'clear'
        }
        ,buttonAlign: 'center'
        ,layout: 'fit'
        ,items: [{
            html: '<h2>'+_('error_log')+'</h2>'
            ,id: 'modx-error-log-header'
            ,cls: 'modx-page-header'
            ,border: false
            ,anchor: '100%'
        },{
            layout: 'form'
            ,hideLabels: true
            ,autoHeight: true
            ,border: true
            ,buttonAlign: 'center'
            ,items: [{
                html: '<p>'+_('error_log_desc')+'</p>'
				,bodyCssClass: 'panel-desc'
                ,border: false
            },{
				xtype: 'panel'
				,border: false
				,cls:'main-wrapper'
				,layout: 'form'
				,labelAlign: 'top'
				,items: [{
					xtype: 'textarea'
					,name: 'log'
					,grow: true
					,growMax: 400
					,anchor: '100%'
					,hidden: config.record.tooLarge ? true : false
				},{
				    html: '<p>'+_('error_log_too_large',{
                        name: config.record.name
                    })+'</p>'
                    ,border: false
					,hidden: config.record.tooLarge ? false : true
				},MODx.PanelSpacer,{
				    xtype: 'button'
				    ,text: _('error_log_download',{size: config.record.size})
					,hidden: config.record.tooLarge ? false : true
					,handler: this.download
					,scope: this
				}]
            }]
        }]
        ,buttons: [{
            text: _('clear')
            ,handler: this.clear
            ,scope: this
            ,hidden: MODx.hasEraseErrorLog ? false : true
        }]
    });
    MODx.panel.ErrorLog.superclass.constructor.call(this,config);
    this.setup();
};
Ext.extend(MODx.panel.ErrorLog,MODx.FormPanel,{
    initialized: false
    ,setup: function() {
        if (this.initialized) { this.clearDirty(); return true; }
        this.getForm().setValues(this.config.record);
        this.clearDirty();
        MODx.fireEvent('ready');
        this.initialized = true;
        return true;
    }
    ,clear: function() {
        MODx.Ajax.request({
            url: this.config.url
            ,params: {
                action: 'clear'
            }
            ,listeners: {
                'success': {fn:function(r) {
                    if (this.config.tooLarge) {
                        location.href = location.href;
                    } else {
                        this.getForm().setValues(r.object);
                    }
                },scope:this}
            }
        });
    }
    ,download: function() {
        location.href = this.config.url+'?action=download&HTTP_MODAUTH='+MODx.siteId;
    }
});
Ext.reg('modx-panel-error-log',MODx.panel.ErrorLog);
