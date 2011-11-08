/**
 * @class MODx.window.PackageUninstall
 * @extends MODx.Window
 * @param {Object} config An object of configuration parameters
 * @xtype modx-window-package-uninstall
 */
MODx.window.PackageUninstall = function(config) {
    config = config || {};
    Ext.applyIf(config,{
        title: _('package_uninstall')
        ,url: MODx.config.connectors_url+'workspace/packages.php'
        ,action: ''
        ,height: 400
        ,width: 500
        ,id: 'modx-window-package-uninstall'
        ,saveBtnText: _('uninstall')
        ,fields: [{
            html: _('preexisting_mode_select')
            ,border: false
            ,autoHeight: true
        },MODx.PanelSpacer,{
            xtype: 'radio'
            ,name: 'preexisting_mode'
            ,fieldLabel: _('preexisting_mode_preserve')
            ,boxLabel: _('preexisting_mode_preserve_desc')
            ,inputValue: 0
            ,checked: true
        },{
            xtype: 'radio'
            ,name: 'preexisting_mode'
            ,fieldLabel: _('preexisting_mode_remove')
            ,boxLabel: _('preexisting_mode_remove_desc')
            ,inputValue: 1
        },{
            xtype: 'radio'
            ,name: 'preexisting_mode'
            ,fieldLabel: _('preexisting_mode_restore')
            ,boxLabel: _('preexisting_mode_restore_desc')
            ,inputValue: 2
        }]
    });
    MODx.window.PackageUninstall.superclass.constructor.call(this,config);
};
Ext.extend(MODx.window.PackageUninstall,MODx.Window,{
    submit: function() {
        var va = this.fp.getForm().getValues();
        this.fireEvent('success',va);
        this.hide();
    }
});
Ext.reg('modx-window-package-uninstall',MODx.window.PackageUninstall);

/**
 * @class MODx.window.RemovePackage
 * @extends MODx.Window
 * @param {Object} config An object of configuration parameters
 * @xtype modx-window-package-remove
 */
MODx.window.RemovePackage = function(config) {
    config = config || {};
    Ext.applyIf(config,{
        title: _('package_remove')
        ,url: MODx.config.connectors_url+'workspace/packages.php'
        ,baseParams: {
            action: 'uninstall'
        }
        ,defaults: { border: false }
        ,fields: [{
            xtype: 'hidden'
            ,name: 'signature'
            ,id: 'modx-rpack-signature'
            ,value: config.signature
        },{
            html: _('package_remove_confirm')
        },MODx.PanelSpacer,{
            html: _('package_remove_force_desc') 
            ,border: false
        },MODx.PanelSpacer,{
            xtype: 'xcheckbox'
            ,name: 'force'
            ,boxLabel: _('package_remove_force')
            ,id: 'modx-rpack-force'
            ,labelSeparator: ''
            ,inputValue: 'true'
        }]
        ,saveBtnText: _('package_remove')
    });
    MODx.window.RemovePackage.superclass.constructor.call(this,config);
};
Ext.extend(MODx.window.RemovePackage,MODx.Window,{
    submit: function() {
        var r = this.config.record;
        if (this.fp.getForm().isValid()) {            
            Ext.getCmp('modx-package-grid').loadConsole(Ext.getBody(),r.topic);
            this.fp.getForm().baseParams = {
                action: 'remove'
                ,signature: r.signature
                ,register: 'mgr'
                ,topic: r.topic
                ,force: Ext.getCmp('modx-rpack-force').getValue()
            };
            
            this.fp.getForm().submit({ 
                waitMsg: _('saving')
                ,scope: this
                ,failure: function(frm,a) {
                    this.fireEvent('failure',frm,a);
                    var g = Ext.getCmp('modx-package-grid');
                    g.getConsole().fireEvent('complete');
                    g.refresh();
                    Ext.Msg.hide();
                    this.hide();
                }
                ,success: function(frm,a) {
                    this.fireEvent('success',{f:frm,a:a});
                    var g = Ext.getCmp('modx-package-grid');
                    g.getConsole().fireEvent('complete');
                    g.refresh();
                    Ext.Msg.hide();
                    this.hide();
                }
            });
        }
    }
});
Ext.reg('modx-window-package-remove',MODx.window.RemovePackage);
	
/**
 * @class MODx.window.SetupOptions
 * @extends MODx.Window
 * @param {Object} config An object of configuration parameters
 * @xtype modx-window-setupoptions
 */
MODx.window.SetupOptions = function(config) {
    config = config || {};

    Ext.applyIf(config,{
        title: _('setup_options')
		,layout: 'form'
		,items:[{
			xtype: 'modx-template-panel'
			,id: 'modx-setupoptions-panel'
			,bodyCssClass: 'win-desc'
			,markup: '<tpl for="."><p>{text}</p></tpl>'
		},{
			html:''
			,xtype: 'form'
			,bodyCssClass: 'inline-form'
			,id: 'modx-setupoptions-form'
		}] 
		,buttons :[{
			text: config.cancelBtnText || _('cancel')
            ,scope: this
            ,handler: function() { this.hide(); }
		},{
			text: 'Install package'
			,id:'package-setupoptions-install-btn'
			,handler: this.install
			,scope: this
		}]
    });
    MODx.window.SetupOptions.superclass.constructor.call(this,config);
};
Ext.extend(MODx.window.SetupOptions,MODx.Window,{
	fetch: function(content){
		Ext.getCmp('modx-setupoptions-panel').updateDetail({ text: _('setup_options_desc') });
		Ext.getCmp('modx-setupoptions-form').getForm().getEl().update(content);
	}
	
	,install: function(btn, ev){	
		this.hide();
		options =  Ext.getCmp('modx-setupoptions-form').getForm().getValues();					
		Ext.getCmp('modx-panel-packages').install( options );		
	}
});
Ext.reg('modx-package-setupoptions', MODx.window.SetupOptions);