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
        url: MODx.config.connector_url
        ,baseParams: {
            action: 'Security/Access/Policy/Update'
            ,id: MODx.request.id
        }
        ,id: 'modx-panel-access-policy'
        ,cls: 'container form-with-labels'
        ,class_key: 'modAccessPolicy'
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
                title: _('policy')
                ,layout: 'form'
                ,items: [{
                    html: '<p>'+_('policy_desc')+'</p>'
                    ,xtype: 'modx-description'
                },{
                    xtype: 'panel'
                    ,border: false
                    ,cls:'main-wrapper'
                    ,layout: 'form'
                    ,labelAlign: 'top'
                    ,labelSeparator: ''
                    ,defaults: {
                        msgTarget: 'under'
                    }
                    ,items: [{
                        xtype: 'hidden'
                        ,name: 'id'
                        ,value: config.plugin
                    },{
                        xtype: 'textfield'
                        ,fieldLabel: _('name')
                        ,description: MODx.expandHelp ? '' : _('policy_desc_name')
                        ,name: 'name'
                        ,maxLength: 255
                        ,enableKeyEvents: true
                        ,allowBlank: false
                        ,anchor: '100%'
                        ,listeners: {
                            'keyup': {scope:this,fn:function(f,e) {
                                Ext.getCmp('modx-header-breadcrumbs').updateHeader(Ext.util.Format.htmlEncode(f.getValue()));
                            }}
                        }
                    },{
                        xtype: MODx.expandHelp ? 'label' : 'hidden'
                        ,forId: 'modx-policy-name'
                        ,html: _('policy_desc_name')
                        ,cls: 'desc-under'
                    },{
                        xtype: 'textarea'
                        ,fieldLabel: _('description')
                        ,description: MODx.expandHelp ? '' : _('policy_desc_description')
                        ,name: 'description'
                        ,anchor: '100%'
                        ,grow: true
                    },{
                        xtype: MODx.expandHelp ? 'label' : 'hidden'
                        ,forId: 'modx-policy-description'
                        ,html: _('policy_desc_description')
                        ,cls: 'desc-under'
                    },{
                        xtype: 'textfield'
                        ,fieldLabel: _('lexicon')
                        ,description: MODx.expandHelp ? '' : _('policy_desc_lexicon')
                        ,name: 'lexicon'
                        ,allowBlank: true
                        ,anchor: '100%'
                        ,value: 'permissions'
                    },{
                        xtype: MODx.expandHelp ? 'label' : 'hidden'
                        ,forId: 'modx-policy-lexicon'
                        ,html: _('policy_desc_lexicon')
                        ,cls: 'desc-under'
                    }]
                },{
                    html: '<p>'+_('permissions_desc')+'</p>'
                    ,xtype: 'modx-description'
                },{
                    xtype: 'modx-grid-policy-permissions'
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
            Ext.getCmp('modx-header-breadcrumbs').updateHeader(Ext.util.Format.htmlEncode(r.name));

            var g = Ext.getCmp('modx-grid-policy-permissions');
            if (g) { g.getStore().loadData(r.permissions); }

            this.fireEvent('ready');
            MODx.fireEvent('ready');
            this.initialized = true;
        }
    }

    ,beforeSubmit: function(o) {
        const policyGrid = Ext.getCmp('modx-grid-policy-permissions');
        policyGrid.clearFilter();
        Ext.apply(o.form.baseParams,{
            permissions: policyGrid ? policyGrid.encode() : {}
        });
    }

    ,success: function(o) {
        Ext.getCmp('modx-grid-policy-permissions').getStore().commitChanges();
    }

    ,getPageHeader: function(config) {
        return MODx.util.getHeaderBreadCrumbs('modx-policy-header', [{
            text: _('user_group_management'),
            href: MODx.getPage('security/permission')
        }]);
    }
});
Ext.reg('modx-panel-access-policy',MODx.panel.AccessPolicy);

/**
 * @class MODx.grid.PolicyPermissions
 * @extends MODx.grid.LocalGrid
 * @constructor
 * @param {Object} config An object of options.
 * @xtype modx-grid-policy-permissions
 */
MODx.grid.PolicyPermissions = function(config) {
    config = config || {};
    const enabledCheckCol = new Ext.ux.grid.CheckColumn({
        header: _('enabled')
        ,dataIndex: 'enabled'
        ,width: 40
        ,sortable: true
    });
    Ext.applyIf(config,{
        id: 'modx-grid-policy-permissions'
        ,showActionsColumn: false
        ,cls: 'modx-grid modx-policy-permissions-grid'
        ,fields: [
            'name',
            'description',
            'description_trans',
            'value',
            'enabled'
        ]
        ,plugins: enabledCheckCol
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
        },
            enabledCheckCol
        ]
        ,tbar: [
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
        if (!s || typeof ri == 'undefined') { return; }

        var r = s.getAt(ri);
        r.set('enabled',r.get('enabled') ? false : true);
        r.commit();
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
Ext.reg('modx-grid-policy-permissions',MODx.grid.PolicyPermissions);
