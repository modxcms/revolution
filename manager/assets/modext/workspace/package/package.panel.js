
MODx.panel.Package = function(config) {
    config = config || {};
    Ext.applyIf(config,{
        url: MODx.config.connector_url
        ,baseParams: {}
        ,id: 'modx-panel-package'
		,cls: 'container'
        ,chunk: ''
        ,bodyStyle: ''
        ,items: [{
            html: '<h2>'+_('package')+'</h2>'
            ,border: false
            ,cls: 'modx-page-header'
            ,id: 'modx-package-header'
        },MODx.getPageStructure([{
            title: _('package')
            ,defaults: { border: false ,msgTarget: 'side' }
            ,layout: 'form'
            ,id: 'modx-package-form'
            ,labelWidth: 150
            ,items: [{
				xtype: 'panel'
				,border: false
				,cls:'main-wrapper'
				,layout: 'form'
				,items: [{
					xtype: 'statictextfield'
					,fieldLabel: _('package')
					,name: 'package_name'
					,width: 300
				},{
					xtype: 'statictextfield'
					,fieldLabel: _('signature')
					,name: 'signature'
					,width: 300
					,submitValue: true
				},{
					xtype: 'statictextfield'
					,fieldLabel: _('uploaded_on')
					,name: 'created'
					,width: 300
				},{
					xtype: 'statictextfield'
					,fieldLabel: _('installed')
					,name: 'installed'
					,width: 300
				},{
					xtype: 'statictextfield'
					,fieldLabel: _('last_updated')
					,name: 'updated'
					,width: 300
				},{
					xtype: 'modx-combo-provider'
					,fieldLabel: _('provider')
					,name: 'provider'
					,width: 300
				},{
					xtype: 'textarea'
					,readOnly: true
					,fieldLabel: _('readme')
					,name: 'readme'
					,width: '80%'
					,height: 200
				},{
					xtype: 'textarea'
					,readOnly: true
					,fieldLabel: _('license')
					,name: 'license'
					,width: '80%'
					,height: 200
				},{
					xtype: 'textarea'
					,readOnly: true
					,fieldLabel: _('changelog')
					,name: 'changelog'
					,width: '80%'
					,height: 200
				}]
			}]
        },{
            title: _('uploaded_versions')
            ,defaults: { border: false ,msgTarget: 'side' }
            ,items: [{
                xtype: 'modx-grid-package-versions'
				,cls: 'main-wrapper'
                ,signature: config.signature
                ,package_name: config.package_name
                ,preventRender: true
            }]
        }])]
        ,listeners: {
            'setup': {fn:this.setup,scope:this}
            ,'success': {fn:this.success,scope:this}
            ,'beforeSubmit': {fn:this.beforeSubmit,scope:this}
        }
    });
    MODx.panel.Package.superclass.constructor.call(this,config);
};
Ext.extend(MODx.panel.Package,MODx.FormPanel,{
    initialized: false
    ,setup: function() {
        if (this.config.signature === '' || this.config.signature === 0 || this.initialized) {
            this.fireEvent('ready');
            return false;
        }
        MODx.Ajax.request({
            url: this.config.url
            ,params: {
                action: 'workspace/packages/get'
                ,signature: this.config.signature
            }
            ,listeners: {
                'success': {fn:function(r) {
                    this.getForm().setValues(r.object);
                    Ext.getCmp('modx-package-header').getEl().update('<h2>'+_('package')+': '+r.object.package_name+'</h2>');
                    this.fireEvent('ready',r.object);

                    this.initialized = true;
                },scope:this}
            }
        });
    }
    ,beforeSubmit: function(o) {
        return this.fireEvent('save',{
            values: this.getForm().getValues()
        });
    }
    ,success: function(r) {
    }
});
Ext.reg('modx-panel-package',MODx.panel.Package);
