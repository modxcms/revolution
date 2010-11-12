/**
 * @class MODx.panel.FCProfile
 * @extends MODx.FormPanel
 * @param {Object} config An object of configuration properties
 * @xtype modx-panel-fc-profile
 */
MODx.panel.FCProfile = function(config) {
    config = config || {};
    Ext.applyIf(config,{
        url: MODx.config.connectors_url+'security/forms/profile.php'
        ,baseParams: {}
        ,id: 'modx-panel-fc-profile'
        ,class_key: 'modFormCustomizationProfile'
        ,bodyStyle: ''
        ,items: [{
            html: '<h2>'+_('profile_new')+'</h2>'
            ,border: false
            ,cls: 'modx-page-header'
            ,id: 'modx-fcp-header'
        },MODx.getPageStructure([{
            title: _('profile')
            ,bodyStyle: 'padding: 15px;'
            ,defaults: { border: false ,msgTarget: 'side' }
            ,layout: 'form'
            ,id: 'modx-chunk-form'
            ,labelWidth: 150
            ,items: [{
                html: '<p>'+_('profile_msg')+'</p>'
                ,id: 'modx-fcp-msg'
                ,border: false
            },{
                xtype: 'hidden'
                ,name: 'id'
                ,id: 'modx-fcp-id'
                ,value: config.record.id || MODx.request.id
            },{
                xtype: 'textfield'
                ,fieldLabel: _('name')
                ,name: 'name'
                ,id: 'modx-fcp-name'
                ,anchor: '90%'
                ,maxLength: 255
                ,enableKeyEvents: true
                ,allowBlank: false
                ,value: config.record.name
                ,listeners: {
                    'keyup': {scope:this,fn:function(f,e) {
                        Ext.getCmp('modx-fcp-header').getEl().update('<h2>'+_('profile')+': '+f.getValue()+'</h2>');
                    }}
                }
            },{
                xtype: 'textarea'
                ,fieldLabel: _('description')
                ,name: 'description'
                ,id: 'modx-chunk-description'
                ,anchor: '90%'
                ,maxLength: 255
                ,grow: false
                ,value: config.record.description
            },{ html: '<hr />' },{
                xtype: 'modx-grid-fc-set'
                ,baseParams: {
                    action: 'getList'
                    ,profile: config.record.id
                }
                ,preventRender: true
            }]
        }],{
            id: 'modx-fc-profile-tabs'
        })]
        ,useLoadingMask: true
        ,listeners: {
            'setup': {fn:this.setup,scope:this}
            ,'success': {fn:this.success,scope:this}
            ,'beforeSubmit': {fn:this.beforeSubmit,scope:this}
        }
    });
    MODx.panel.FCProfile.superclass.constructor.call(this,config);
};
Ext.extend(MODx.panel.FCProfile,MODx.FormPanel,{
    initialized: false
    ,setup: function() {
        if (!this.initialized) { this.getForm().setValues(this.config.record); }
        if (!Ext.isEmpty(this.config.record.name)) {
            Ext.getCmp('modx-fcp-header').getEl().update('<h2>'+_('profile')+': '+this.config.record.name+'</h2>');
        }
        /*
        if (!Ext.isEmpty(this.config.record.properties)) {
            var d = this.config.record.properties;
            var g = Ext.getCmp('modx-grid-element-properties');
            if (g) {
                g.defaultProperties = d;
                g.getStore().loadData(d);
            }
        }*/
        this.fireEvent('ready',this.config.record);
        this.clearDirty();
        this.initialized = true;
        MODx.fireEvent('ready');
        return true;
    }
    ,beforeSubmit: function(o) {
        Ext.apply(o.form.baseParams,{
            //propdata: Ext.getCmp('modx-grid-element-properties').encode()
        });
        return this.fireEvent('save',{
            values: this.getForm().getValues()
        });
    }
    ,success: function(r) {
        //if (MODx.request.id) Ext.getCmp('modx-grid-').save();
        this.getForm().setValues(r.result.object);
    }
});
Ext.reg('modx-panel-fc-profile',MODx.panel.FCProfile);



