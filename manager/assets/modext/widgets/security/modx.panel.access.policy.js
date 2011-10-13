/**
 * 
 * @class MODx.panel.AccessPolicy
 * @extends MODx.FormPanel
 * @param {Object} config An object of config properties
 * @xtype modx-panel-access-policy
 */
MODx.panel.AccessPolicy = function(config) {
    config = config || {};
    Ext.applyIf(config,{
        url: MODx.config.connectors_url+'security/access/policy.php'
        ,baseParams: {
            action: 'update'
            ,id: MODx.request.id
        }
        ,id: 'modx-panel-access-policy'
        ,class_key: 'modAccessPolicy'
        ,plugin: ''
        ,bodyStyle: ''
        ,defaults: { collapsible: false ,autoHeight: true }
        ,items: [{
            html: '<h2>'+_('policy')+(config.record ? ': '+config.record.name : '')+'</h2>'
            ,border: false
            ,cls: 'modx-page-header'
            ,id: 'modx-policy-header'
        },{
            xtype: 'modx-tabs'
            ,defaults: {
                bodyStyle: 'padding: 15px'
                ,autoHeight: true
                ,border: true
            }
            ,forceLayout: true
            ,deferredRender: false
            ,items: [{
                title: _('policy')
                ,layout: 'form'
                ,items: [{
                    html: '<p>'+_('policy_desc')+'</p>'
                    ,border: false
                },{
                    xtype: 'hidden'
                    ,name: 'id'
                    ,value: config.plugin
                },{
                    xtype: 'textfield'
                    ,fieldLabel: _('name')
                    ,name: 'name'
                    ,width: 300
                    ,maxLength: 255
                    ,enableKeyEvents: true
                    ,allowBlank: false
                    ,listeners: {
                        'keyup': {scope:this,fn:function(f,e) {
                            Ext.getCmp('modx-policy-header').getEl().update('<h2>'+_('policy')+': '+f.getValue()+'</h2>');
                        }}
                    }
                },{
                    xtype: 'textarea'
                    ,fieldLabel: _('description')
                    ,name: 'description'
                    ,width: 300
                    ,grow: true
                },{
                    xtype: 'textfield'
                    ,fieldLabel: _('lexicon')
                    ,name: 'lexicon'
                    ,width: 300
                    ,allowBlank: true
                    ,value: 'permissions'
                },{
                    html: '<hr /><p>'+_('permissions_desc')+'</p>'
                    ,border: false
                },{
                    xtype: 'modx-grid-policy-permissions'
                    ,policy: MODx.request.id
                    ,autoHeight: true
                    ,preventRender: true
                }]
            }]
        }]
        ,listeners: {
            'setup': {fn:this.setup,scope:this}
            ,'success': {fn:this.success,scope:this}
            ,'beforeSubmit': {fn:this.beforeSubmit,scope:this}
        }
    });
    MODx.panel.AccessPolicy.superclass.constructor.call(this,config);
};
Ext.extend(MODx.panel.AccessPolicy,MODx.FormPanel,{
    initialized: false
    ,setup: function() {
        if (this.config.policy === '' || this.config.policy === 0) {
            this.fireEvent('ready');
            return false;
        }
        if (!this.initialized) {
            var r = this.config.record;

            this.getForm().setValues(r);
            var g = Ext.getCmp('modx-grid-policy-permissions');
            if (g) { g.getStore().loadData(r.permissions); }

            this.fireEvent('ready');
            MODx.fireEvent('ready');
            this.initialized = true;
        }
    }
    ,beforeSubmit: function(o) {
        var g = Ext.getCmp('modx-grid-policy-permissions');
        Ext.apply(o.form.baseParams,{
            permissions: g ? g.encode() : {}
        });
    }
    
    ,success: function(o) {
        Ext.getCmp('modx-grid-policy-permissions').getStore().commitChanges();
    }
});
Ext.reg('modx-panel-access-policy',MODx.panel.AccessPolicy);



MODx.grid.PolicyPermissions = function(config) {
    config = config || {};
    var ac = new Ext.ux.grid.CheckColumn({
        header: _('enabled')
        ,dataIndex: 'enabled'
        ,width: 40
        ,sortable: false
    });
    Ext.applyIf(config,{
        id: 'modx-grid-policy-permissions'
        ,url: MODx.config.connectors_url+'security/access/policy/index.php'
        ,baseParams: {
            action: 'getAttributes'
        }
        ,cls: 'modx-grid modx-policy-permissions-grid'
        ,fields: ['name','description','description_trans','value','enabled']
        ,plugins: ac
        ,columns: [{
            header: _('name')
            ,dataIndex: 'name'
            ,width: 100
            ,editor: { xtype: 'textfield', renderer: true }
        },{
            header: _('description')
            ,dataIndex: 'description_trans'
            ,width: 250
            ,editable: false
        },ac]
        ,data: []
        ,width: '90%'
        ,height: 300
        ,maxHeight: 300
        ,autosave: false
        ,autoExpandColumn: 'name'
    });
    MODx.grid.PolicyPermissions.superclass.constructor.call(this,config);
    this.propRecord = new Ext.data.Record.create(['name','description','access','value']);
    this.on('rowclick',this.onPermRowClick,this);
};
Ext.extend(MODx.grid.PolicyPermissions,MODx.grid.LocalGrid,{
    onPermRowClick: function(g,ri,e) {
        var s = this.getStore();
        if (!s) { return; }

        var r = s.getAt(ri);
        r.set('enabled',r.get('enabled') ? false : true);
        r.commit();
    }
});
Ext.reg('modx-grid-policy-permissions',MODx.grid.PolicyPermissions);


MODx.combo.AccessPolicyTemplate = function(config) {
    config = config || {};
    Ext.applyIf(config,{
        name: 'template'
        ,hiddenName: 'template'
        ,fields: ['id','name','description']
        ,forceSelection: true
        ,typeAhead: false
        ,editable: false
        ,allowBlank: false
        ,listWidth: 300
        ,url: MODx.config.connectors_url+'security/access/policy/template.php'
        ,tpl: new Ext.XTemplate('<tpl for="."><div class="x-combo-list-item"><span style="font-weight: bold">{name}</span>'
            ,'<p style="margin: 0; font-size: 11px; color: gray;">{description}</p></div></tpl>')
    });
    MODx.combo.AccessPolicyTemplate.superclass.constructor.call(this,config);
};
Ext.extend(MODx.combo.AccessPolicyTemplate,MODx.combo.ComboBox);
Ext.reg('modx-combo-access-policy-template',MODx.combo.AccessPolicyTemplate);