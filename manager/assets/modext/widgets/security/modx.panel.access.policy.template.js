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
        const record = this.config.record;

        this.getForm().setValues(record);
        Ext.getCmp('modx-header-breadcrumbs').updateHeader(Ext.util.Format.htmlEncode(record.name));

        var policyTemplateGrid = Ext.getCmp('modx-grid-template-permissions');
        if (policyTemplateGrid && record.permissions) {
            const store = policyTemplateGrid.getStore();
            store.loadData(record.permissions);
            store.sort('name', 'ASC');
        }

        this.fireEvent('ready');
        MODx.fireEvent('ready');
        this.initialized = true;
    }

    ,beforeSubmit: function(o) {
        const policyTemplateGrid = Ext.getCmp('modx-grid-template-permissions');
        policyTemplateGrid.clearFilter();
        Ext.apply(o.form.baseParams, {
            permissions: policyTemplateGrid ? policyTemplateGrid.encode() : {}
        });
    }

    ,success: function(o) {
        const store = Ext.getCmp('modx-grid-template-permissions').getStore();
        store.commitChanges();
        store.sort('name', 'ASC');
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
        ,fields: [
            'name',
            'description',
            'description_trans',
            'value',
            'menu'
        ]
        ,columns: [{
            header: _('name')
            ,dataIndex: 'name'
            ,width: 150
            ,editor: {
                xtype: 'textfield',
                renderer: true
            }
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
        ,tbar: [
            {
                text: _('create')
                ,cls: 'primary-button'
                ,scope: this
                ,handler: this.createAttribute
            },
            '->',
            {
                xtype: 'checkbox',
                id: 'filter-name-only',
                boxLabel: _('policy_query_name_only'),
                listeners: {
                    check: {
                        fn: function(cmp, isChecked) {
                            const queryValue = Ext.getCmp('filter-query').getValue();
                            if (!Ext.isEmpty(queryValue)) {
                                this.applyQueryFilter(cmp, queryValue);
                            }
                        },
                        scope: this
                    }
                }
            },
            {
                xtype: 'textfield',
                id: 'filter-query',
                cls: 'x-form-filter',
                emptyText: _('search'),
                listeners: {
                    change: {
                        fn: this.applyQueryFilter,
                        scope: this
                    },
                    render: {
                        fn: function(cmp) {
                            new Ext.KeyMap(cmp.getEl(), {
                                key: Ext.EventObject.ENTER,
                                fn: this.blur,
                                scope: cmp
                            });
                        },
                        scope: this
                    }
                }
            },
            {
                text: _('filter_clear'),
                cls: 'x-form-filter-clear',
                listeners: {
                    click: {
                        fn: this.clearFilter,
                        scope: this
                    },
                    mouseout: {
                        fn: function(evt){
                            this.removeClass('x-btn-focus');
                        }
                    }
                }
            }
        ]
    });
    MODx.grid.TemplatePermissions.superclass.constructor.call(this,config);
    this.propRecord = new Ext.data.Record.create(['name','description','value']);
    this.getView().on('rowsinserted', function(view, firstRowInserted, lastRowInserted){
        const store = this.getStore();
        view.getRow(firstRowInserted).classList.add('highlight-inserted');
        view.getCell(firstRowInserted, 0).classList.add('x-grid3-dirty-cell');
        view.focusRow(firstRowInserted);
    }, this);
};
Ext.extend(MODx.grid.TemplatePermissions, MODx.grid.LocalGrid, {
    createAttribute: function(btn, e) {
        this.loadWindow(btn, e, {
            xtype: 'modx-window-template-permission-create',
            record: {},
            blankValues: true,
            listeners: {
                success: {
                    fn: function(data) {
                        data.description_trans = data.description;
                        const store = this.getStore(),
                              newRecord = new this.propRecord(data)
                        ;
                        store.insert(0, newRecord);
                        Ext.getCmp('modx-panel-access-policy-template').fireEvent('fieldChange');
                    },
                    scope: this
                }
            }
        });
        return true;
    },

    remove: function() {
        var r = this.getSelectionModel().getSelected();
        if (this.fireEvent('beforeRemoveRow',r)) {
            this.getStore().remove(r);
            this.fireEvent('afterRemoveRow',r);
        }
    },

    _showMenu: function(g,ri,e) {
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
    },

    applyQueryFilter: function(cmp, newValue) {
        const store = this.getStore(),
              nameOnlyCb = Ext.getCmp('filter-name-only')
        ;
        if(newValue) {
            if (nameOnlyCb.checked) {
                store.filter('name', String.escape(newValue), true, false);
            } else {
                const query = new RegExp(Ext.escapeRe(newValue), 'i');
                store.filter({
                    fn: function(record) {
                        return query.test(record.get('name')) || query.test(record.get('description_trans'));
                    }
                });
            }
        } else {
            this.clearFilter();
        }
    },

    clearFilter: function() {
        Ext.getCmp('filter-query').setValue('');
        this.getStore().clearFilter();
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
        ,saveBtnText: _('add')
        ,fields: [{
            xtype: 'textfield'
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
        let formData = this.fp.getForm().getValues();
        const policyTemplateGrid = Ext.getCmp('modx-grid-template-permissions'),
              store = policyTemplateGrid.getStore(),
              policyIndex = store.findExact('name', formData.name)
        ;
        if (policyIndex != -1) {
            MODx.msg.alert(_('error'),_('permission_err_ae'));
            return false;
        }
        formData.value = 1;
        this.fireEvent('success', formData);
        this.hide();
        return false;
    }
});
Ext.reg('modx-window-template-permission-create',MODx.window.NewTemplatePermission);
