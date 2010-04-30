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
            html: '<h2>'+_('chunk_new')+'</h2>'
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
                }]
            },{
                title: _('permissions')
                ,layout: 'form'
                ,items: [{
                    html: '<p>'+_('permissions_desc')+'</p>'
                    ,border: false
                },{
                    xtype: 'modx-grid-permissions'
                    ,policy: MODx.request.id
                    ,autoHeight: true
                    ,preventRender: true
                    ,frame: true
                }]
            }]
        }]
        ,useLoadingMask: true
        ,listeners: {
            'setup': {fn:this.setup,scope:this}
            ,'success': {fn:this.success,scope:this}
            ,'beforeSubmit': {fn:this.beforeSubmit,scope:this}
        }
    });
    MODx.panel.AccessPolicy.superclass.constructor.call(this,config);
};
Ext.extend(MODx.panel.AccessPolicy,MODx.FormPanel,{
    setup: function() {
        if (this.config.policy === '' || this.config.policy === 0) {
            this.fireEvent('ready');
            return false;
        }
        MODx.Ajax.request({
            url: this.config.url
            ,params: {
                action: 'get'
                ,id: this.config.policy
            }
            ,listeners: {
            	'success':{fn:function(r) {
                    this.getForm().setValues(r.object);
                    var g = Ext.getCmp('modx-grid-permissions');
                    if (g) { g.getStore().loadData(r.object.permissions); }
                    
                    Ext.getCmp('modx-policy-header').getEl().update('<h2>'+_('policy')+': '+r.object.name+'</h2>');
                    this.fireEvent('ready');
            	},scope:this}
            }
        });
    }
    ,beforeSubmit: function(o) {
        var g = Ext.getCmp('modx-grid-permissions');
        Ext.apply(o.form.baseParams,{
            permissions: g ? g.encode() : {}
        });
    }
    
    ,success: function(o) {
        Ext.getCmp('modx-grid-permissions').getStore().commitChanges();
    }
});
Ext.reg('modx-panel-access-policy',MODx.panel.AccessPolicy);



MODx.grid.Permissions = function(config) {
    config = config || {};
    Ext.applyIf(config,{
        id: 'modx-grid-permissions'
        ,url: MODx.config.connectors_url+'security/access/policy/index.php'
        ,baseParams: {
            action: 'getAttributes'
        }
        ,fields: ['name','description','description_trans','value','menu']
        ,columns: [{
            header: _('name')
            ,dataIndex: 'name'
            ,width: 150
            ,editor: { xtype: 'textfield', renderer: true }
        },{
            header: _('description')
            ,dataIndex: 'description_trans'
            ,width: 250
            ,editable: false
        }]
        ,data: []
        ,width: '90%'
        ,height: 300
        ,maxHeight: 300
        ,autosave: false
        ,autoExpandColumn: 'name'
        ,tbar: [{
            text: _('permission_new')
            ,scope: this
            ,handler: this.createAttribute
        }]
    });
    MODx.grid.Permissions.superclass.constructor.call(this,config);
    this.propRecord = new Ext.data.Record.create([{name: 'name'},{name: 'description'},{name:'value'}]);
};
Ext.extend(MODx.grid.Permissions,MODx.grid.LocalGrid,{
    createAttribute: function(btn,e) {        
        this.loadWindow(btn,e,{
            xtype: 'modx-window-permission-create'
            ,record: {}
            ,blankValues: true
            ,listeners: {
                'success': {fn:function(r) {
                    var s = this.getStore();
                    var rec = new this.propRecord(r);
                    s.add(rec);
                    
                    Ext.getCmp('modx-panel-access-policy').fireEvent('fieldChange');
                },scope:this}
            }
        });
        return true;
    }
    
    ,remove: function() {
        var r = this.getSelectionModel().getSelected();
        if (this.fireEvent('beforeRemoveRow',r)) {
            this.getStore().remove(r);
            this.fireEvent('afterRemoveRow',r);
        }
    }
        
    ,_showMenu: function(g,ri,e) {
        e.stopEvent();
        e.preventDefault();
        var m = this.menu;
        m.recordIndex = ri;
        m.record = this.getStore().getAt(ri).data;
        if (!this.getSelectionModel().isSelected(ri)) {
            this.getSelectionModel().selectRow(ri);
        }
        m.removeAll();
        m.add({
            text: _('permission_remove')
            ,scope: this
            ,handler: this.remove
        });        
        m.show(e.target);
    }
});
Ext.reg('modx-grid-permissions',MODx.grid.Permissions);


MODx.window.NewPermission = function(config) {
    config = config || {};
    this.ident = config.ident || 'polpc'+Ext.id();
    Ext.applyIf(config,{
        title: _('permission_new')
        ,height: 150
        ,width: 475
        ,url: MODx.config.connectors_url+'security/access/policy/index.php'
        ,action: 'addProperty'
        ,saveBtnText: _('add')
        ,fields: [{
            xtype: 'textfield'
            ,fieldLabel: _('name')
            ,name: 'name'
            ,id: 'modx-'+this.ident+'-name'
            ,width: 250
            ,allowBlank: false
        },{
            xtype: 'textarea'
            ,fieldLabel: _('description')
            ,name: 'description'
            ,id: 'modx-'+this.ident+'-description'
            ,width: 250
            ,grow: true
        }]
    });
    MODx.window.NewPermission.superclass.constructor.call(this,config);
};
Ext.extend(MODx.window.NewPermission,MODx.Window,{
    submit: function() {
        var r = this.fp.getForm().getValues();
        
        var g = Ext.getCmp('modx-grid-permissions');
        var s = g.getStore();
        var v = s.findExact('name',r.name);
        if (v != -1) {
            MODx.msg.alert(_('error'),_('permission_err_ae'));
            return false;
        }
        this.fireEvent('success',r);
        this.hide();
        return false;
    }
});
Ext.reg('modx-window-permission-create',MODx.window.NewPermission);