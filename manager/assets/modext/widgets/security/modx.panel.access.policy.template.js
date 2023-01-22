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
    Ext.applyIf(config,{
        url: MODx.config.connector_url
        ,baseParams: {
            action: 'Security/Access/Policy/Template/Update'
            ,id: MODx.request.id
        }
        ,id: 'modx-panel-access-policy-template'
        ,cls: 'container form-with-labels'
        ,class_key: 'modAccessPolicyTemplate'
        ,plugin: ''
        ,bodyStyle: ''
        ,defaults: { collapsible: false ,autoHeight: true }
        ,items: [this.getPageHeader(config),{
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
                    html: '<p>'+_('policy_template_desc')+'</p>'
                    ,xtype: 'modx-description'
                },{
					xtype: 'panel'
					,border: false
					,cls:'main-wrapper'
					,items: [{
						xtype: 'hidden'
						,name: 'id'
					},{
                        layout: 'column',
                        defaults: {msgTarget: 'under', border: true},
                        items: [{
                            columnWidth: .7
                            ,layout: 'form'
                            ,defaults:{ anchor: '100%' }
                            ,labelAlign: 'top'
                            ,labelSeparator: ''
                            ,items: [{
                                xtype: 'textfield'
                                ,fieldLabel: _('name')
                                ,description: MODx.expandHelp ? '' : _('policy_template_desc_name')
                                ,name: 'name'
                                ,id: 'modx-policy-template-name'
                                ,maxLength: 255
                                ,enableKeyEvents: true
                                ,allowBlank: false
                                ,listeners: {
                                    'keyup': {
                                        scope: this, fn: function (f, e) {
                                            Ext.getCmp('modx-header-breadcrumbs').updateHeader(Ext.util.Format.htmlEncode(f.getValue()));
                                        }
                                    }
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
                            }]
                        }, {
                            columnWidth: .3
                            ,layout: 'form'
                            ,defaults:{ anchor: '100%' }
                            ,labelAlign: 'top'
                            ,labelSeparator: ''
                            ,items: [{
                                xtype: 'modx-combo-access-policy-template-group'
                                ,fieldLabel: _('template_group')
                                ,description: MODx.expandHelp ? '' : _('policy_template_desc_template_group')
                                ,name: 'template_group'
                                ,id: 'modx-policy-template-template-group'
                                ,allowBlank: false
                            }, {
                                xtype: MODx.expandHelp ? 'label' : 'hidden'
                                ,forId: 'modx-policy-template-template-group'
                                ,html: _('policy_template_desc_template_group')
                                ,cls: 'desc-under'
                            },{
                                xtype: 'textfield'
                                ,fieldLabel: _('policy_template_lexicon')
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
                        }]
                    }]
                },{
                    html: '<p>'+_('permissions_desc')+'</p>'
                    ,xtype: 'modx-description'
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
        Ext.getCmp('modx-header-breadcrumbs').updateHeader(Ext.util.Format.htmlEncode(r.name));

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

    ,getPageHeader: function(config) {
        return MODx.util.getHeaderBreadCrumbs('modx-policy-template-header', [{
            text: _('user_group_management'),
            href: MODx.getPage('security/permission')
        }]);
    }
});
Ext.reg('modx-panel-access-policy-template',MODx.panel.AccessPolicyTemplate);

/**
 * @class MODx.grid.TemplatePermissions
 * @extends MODx.grid.LocalGrid
 * @constructor
 * @param {Object} config An object of options.
 * @xtype modx-grid-template-permissions
 */
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
            text: _('create')
            ,cls: 'primary-button'
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
            text: _('delete')
            ,scope: this
            ,handler: this.remove
        });
        m.show(e.target);
    }
});
Ext.reg('modx-grid-template-permissions',MODx.grid.TemplatePermissions);

/**
 * @class MODx.window.NewTemplatePermission
 * @extends MODx.Window
 * @param {Object} config An object of options.
 * @xtype modx-window-template-permission-create
 */
MODx.window.NewTemplatePermission = function(config) {
    config = config || {};
    this.ident = config.ident || 'polpc'+Ext.id();
    Ext.applyIf(config,{
        title: _('create')
        ,url: MODx.config.connector_url
        ,action: 'security/access/policy/addProperty'
        ,saveBtnText: _('add')
        ,fields: [{
            xtype: 'modx-combo-permission'
            ,fieldLabel: _('name')
            ,name: 'name'
            ,hiddenName: 'name'
            ,id: 'modx-'+this.ident+'-name'
            ,anchor: '100%'
            ,listeners: {
                change: {
                    fn: function(cmp, newValue, oldValue) {
                        if (Ext.isEmpty(cmp.getValue())) {
                            cmp.getStore().load();
                        }
                    },
                    scope: this
                }
            }
        },{
            xtype: 'textarea'
            ,fieldLabel: _('description')
            ,name: 'description'
            ,id: 'modx-'+this.ident+'-description'
            ,anchor: '100%'
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
