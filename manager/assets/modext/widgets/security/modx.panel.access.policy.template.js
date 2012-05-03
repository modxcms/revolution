/**
 * Loads panel for editing an Access Policy Template
 *
 * @class MODx.panel.AccessPolicyTemplate
 * @extends MODx.FormPanel
 * @param {Object} config An object of config properties
 * @xtype modx-panel-access-policy-template
 */
MODx.panel.AccessPolicyTemplate = function(config) {
    config = config || {};
    var r = config.record || {};
    Ext.applyIf(config,{
        url: MODx.config.connectors_url+'security/access/policy/template.php'
        ,baseParams: {
            action: 'update'
            ,id: MODx.request.id
        }
        ,id: 'modx-panel-access-policy-template'
		,cls: 'container form-with-labels'
        ,class_key: 'modAccessPolicyTemplate'
        ,plugin: ''
        ,bodyStyle: ''
        ,defaults: { collapsible: false ,autoHeight: true }
        ,items: [{
            html: '<h2>'+_('policy_template')+(config.record ? ': '+config.record.name : '')+'</h2>'
            ,border: false
            ,cls: 'modx-page-header'
            ,id: 'modx-policy-template-header'
        },{
            xtype: 'modx-tabs'
            ,defaults: {
                autoHeight: true
                ,border: true
				,bodyCssClass: 'tab-panel-wrapper'
            }
            ,forceLayout: true
            ,deferredRender: false
            ,items: [{
                title: _('policy_template')
                ,layout: 'form'
                ,items: [{
                    html: '<p>'+_('policy_template.desc')+'</p>'
					,bodyCssClass: 'panel-desc'
                    ,border: false
                },{
					xtype: 'panel'
					,border: false
					,cls:'main-wrapper'
					,layout: 'form'
					,defaults:{ anchor: '100%' }
					,labelAlign: 'top'
					,labelSeparator: ''
					,items: [{
						xtype: 'hidden'
						,name: 'id'
					},{
						xtype: 'textfield'
						,fieldLabel: _('name')
						,description: MODx.expandHelp ? '' : _('policy_template_desc_name')
						,name: 'name'
						,id: 'modx-policy-template-name'
						,maxLength: 255
						,enableKeyEvents: true
						,allowBlank: false
						,listeners: {
							'keyup': {scope:this,fn:function(f,e) {
								Ext.getCmp('modx-policy-template-header').getEl().update('<h2>'+_('policy')+': '+f.getValue()+'</h2>');
							}}
						}
					},{
                        xtype: MODx.expandHelp ? 'label' : 'hidden'
                        ,forId: 'modx-policy-template-name'
                        ,html: _('policy_template_desc_name')
                        ,cls: 'desc-under'

                    },{
						xtype: 'textarea'
						,fieldLabel: _('description')
						,description: MODx.expandHelp ? '' : _('policy_template_desc_description')
						,name: 'description'
						,id: 'modx-policy-template-description'
						,grow: true
					},{
                        xtype: MODx.expandHelp ? 'label' : 'hidden'
                        ,forId: 'modx-policy-template-description'
                        ,html: _('policy_template_desc_description')
                        ,cls: 'desc-under'

                    },{
						xtype: 'textfield'
						,fieldLabel: _('lexicon')
						,description: MODx.expandHelp ? '' : _('policy_template_desc_lexicon')
						,name: 'lexicon'
						,id: 'modx-policy-template-lexicon'
						,allowBlank: true
						,value: 'permissions'
					},{
                        xtype: MODx.expandHelp ? 'label' : 'hidden'
                        ,forId: 'modx-policy-template-lexicon'
                        ,html: _('policy_template_desc_lexicon')
                        ,cls: 'desc-under'
                    }]
                },{
                    html: '<p>'+_('permissions_desc')+'</p>'
					,bodyCssClass: 'panel-desc'
                    ,border: false
                },{
                    xtype: 'modx-grid-template-permissions'
					,cls:'main-wrapper'
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
    MODx.panel.AccessPolicyTemplate.superclass.constructor.call(this,config);
};
Ext.extend(MODx.panel.AccessPolicyTemplate,MODx.FormPanel,{
    initialized: false
    ,setup: function() {
        if (this.initialized) return;
        if (this.config.template === '' || this.config.template === 0) {
            this.fireEvent('ready');
            return false;
        }
        var r = this.config.record;
        
        this.getForm().setValues(r);

        var g = Ext.getCmp('modx-grid-template-permissions');
        if (g && r.permissions) { g.getStore().loadData(r.permissions); }

        this.fireEvent('ready');
        MODx.fireEvent('ready');
        this.initialized = true;
    }
    ,beforeSubmit: function(o) {
        var g = Ext.getCmp('modx-grid-template-permissions');
        Ext.apply(o.form.baseParams,{
            permissions: g ? g.encode() : {}
        });
    }
    
    ,success: function(o) {
        Ext.getCmp('modx-grid-template-permissions').getStore().commitChanges();
    }
});
Ext.reg('modx-panel-access-policy-template',MODx.panel.AccessPolicyTemplate);



MODx.grid.TemplatePermissions = function(config) {
    config = config || {};
    Ext.applyIf(config,{
        id: 'modx-grid-template-permissions'
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
            text: _('permission_add_template')
            ,scope: this
            ,handler: this.createAttribute
        }]
    });
    MODx.grid.TemplatePermissions.superclass.constructor.call(this,config);
    this.propRecord = new Ext.data.Record.create(['name','description','value']);
};
Ext.extend(MODx.grid.TemplatePermissions,MODx.grid.LocalGrid,{
    createAttribute: function(btn,e) {        
        this.loadWindow(btn,e,{
            xtype: 'modx-window-template-permission-create'
            ,record: {}
            ,blankValues: true
            ,listeners: {
                'success': {fn:function(r) {
                    var s = this.getStore();
                    r.description_trans = r.description;
                    var rec = new this.propRecord(r);
                    s.add(rec);
                    
                    Ext.getCmp('modx-panel-access-policy-template').fireEvent('fieldChange');
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
Ext.reg('modx-grid-template-permissions',MODx.grid.TemplatePermissions);


MODx.window.NewTemplatePermission = function(config) {
    config = config || {};
    this.ident = config.ident || 'polpc'+Ext.id();
    Ext.applyIf(config,{
        title: _('permission_add_template')
        ,height: 150
        ,width: 475
        ,url: MODx.config.connectors_url+'security/access/policy/index.php'
        ,action: 'addProperty'
        ,saveBtnText: _('add')
        ,fields: [{
            xtype: 'modx-combo-permission'
            ,fieldLabel: _('name')
            ,name: 'name'
            ,hiddenName: 'name'
            ,id: 'modx-'+this.ident+'-name'
            ,anchor: '90%'
        },{
            xtype: 'textarea'
            ,fieldLabel: _('description')
            ,name: 'description'
            ,id: 'modx-'+this.ident+'-description'
            ,anchor: '90%'
            ,grow: true
        }]
        ,keys: []
    });
    MODx.window.NewTemplatePermission.superclass.constructor.call(this,config);
};
Ext.extend(MODx.window.NewTemplatePermission,MODx.Window,{
    submit: function() {
        var r = this.fp.getForm().getValues();
        
        var g = Ext.getCmp('modx-grid-template-permissions');
        var s = g.getStore();
        var v = s.findExact('name',r.name);
        if (v != -1) {
            MODx.msg.alert(_('error'),_('permission_err_ae'));
            return false;
        }

        var cb = Ext.getCmp('modx-'+this.ident+'-name');
        s = cb.getStore();
        var rec = s.getAt(s.find('name',r.name));
        if (rec) {
            r.description = rec.data.description;
            r.description_trans = rec.data.description;
        }
        r.value = 1;

        this.fireEvent('success',r);
        this.hide();
        return false;
    }
});
Ext.reg('modx-window-template-permission-create',MODx.window.NewTemplatePermission);


MODx.combo.Permission = function(config) {
    config = config || {};
    Ext.applyIf(config,{
        name: 'permission'
        ,hiddenName: 'permission'
        ,displayField: 'name'
        ,valueField: 'name'
        ,fields: ['name','description']
        ,editable: true
        ,typeAhead: false
        ,forceSelection: false
        ,enableKeyEvents: true
        ,autoSelect: false
        ,pageSize: 20
        ,tpl: new Ext.XTemplate('<tpl for="."><div class="x-combo-list-item"><span style="font-weight: bold">{name}</span>'
            ,'<p style="margin: 0; font-size: 11px; color: gray;">{description}</p></div></tpl>')
        ,url: MODx.config.connectors_url+'security/access/permission.php'
    });
    MODx.combo.Permission.superclass.constructor.call(this,config);
};
Ext.extend(MODx.combo.Permission,MODx.combo.ComboBox);
Ext.reg('modx-combo-permission',MODx.combo.Permission);
