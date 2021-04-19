/**
 * Loads the panel for managing access policy templates.
 *
 * @class MODx.panel.AccessPolicyTemplates
 * @extends MODx.FormPanel
 * @param {Object} config An object of configuration properties
 * @xtype modx-panel-access-policy-templates
 */
MODx.panel.AccessPolicyTemplates = function(config) {
    config = config || {};
    Ext.applyIf(config,{
        id: 'modx-panel-access-policy-templates'
        ,bodyStyle: ''
        ,defaults: { collapsible: false ,autoHeight: true }
        ,items: [{
            html: _('policies')
            ,id: 'modx-policy-templates-header'
            ,xtype: 'modx-header'
        },{
            layout: 'form'
            ,bodyStyle: 'padding: 15px'
            ,items: [{
                html: '<p>'+_('policy_templates.intro_msg')+'</p>'
                ,border: false
            },{
                xtype: 'modx-grid-access-policy-templates'
                ,preventRender: true
            }]
        }]
    });
    MODx.panel.AccessPolicyTemplates.superclass.constructor.call(this,config);
};
Ext.extend(MODx.panel.AccessPolicyTemplates,MODx.FormPanel);
Ext.reg('modx-panel-access-policy-templates',MODx.panel.AccessPolicyTemplates);

/**
 * Loads a grid of modAccessPolicyTemplates.
 *
 * @class MODx.grid.AccessPolicyTemplates
 * @extends MODx.grid.Grid
 * @param {Object} config An object of options.
 * @xtype modx-grid-access-policy
 */
MODx.grid.AccessPolicyTemplate = function(config) {
    config = config || {};
    this.sm = new Ext.grid.CheckboxSelectionModel();
    Ext.applyIf(config,{
        id: 'modx-grid-access-policy-template'
        ,url: MODx.config.connector_url
        ,baseParams: {
            action: 'Security/Access/Policy/Template/GetList'
        }
        ,fields: ['id','name','description','description_trans','template_group','template_group_name','total_permissions','policy_count','cls']
        ,paging: true
        ,autosave: true
        ,save_action: 'Security/Access/Policy/Template/UpdateFromGrid'
        ,remoteSort: true
        ,sm: this.sm
        ,columns: [this.sm,{
            header: _('name')
            ,dataIndex: 'name'
            ,width: 200
            ,editor: { xtype: 'textfield' ,allowBlank: false }
            ,sortable: true
            ,renderer: { fn: function(v,md,record) {
                return this.renderLink(v, {
                    href: '?a=security/access/policy/template/update&id=' + record.data.id
                });
            }, scope: this }
        },{
            header: _('description')
            ,dataIndex: 'description'
            ,width: 375
            ,editable: false
            ,renderer: function(value, metaData, record) {
                return Ext.util.Format.htmlEncode(record['data']['description_trans']);
            }
            ,sortable: true
        },{
            header: _('template_group')
            ,dataIndex: 'template_group_name'
            ,width: 375
            ,sortable: true
        },{
            header: _('policy_count')
            ,dataIndex: 'policy_count'
            ,width: 100
            ,editable: false
            ,sortable: true
        },{
            header: _('permissions')
            ,dataIndex: 'total_permissions'
            ,width: 100
            ,editable: false
            ,sortable: true
        }]
        ,tbar: [{
            text: _('create')
            ,cls:'primary-button'
            ,scope: this
            ,handler: this.createPolicyTemplate
        },{
            text: _('import')
            ,scope: this
            ,handler: this.importPolicyTemplate
        },{
            text: _('bulk_actions')
            ,menu: [{
                text: _('selected_remove')
                ,handler: this.removeSelected
                ,scope: this
            }]
        },'->',{
            xtype: 'textfield'
            ,name: 'search'
            ,id: 'modx-policy-template-search'
            ,cls: 'x-form-filter'
            ,emptyText: _('search')
            ,listeners: {
                'change': {fn: this.search, scope: this}
                ,'render': {fn: function(cmp) {
                    new Ext.KeyMap(cmp.getEl(), {
                        key: Ext.EventObject.ENTER
                        ,fn: function() {
                            this.fireEvent('change',this.getValue());
                            this.blur();
                            return true;}
                        ,scope: cmp
                    });
                },scope:this}
            }
        },{
            xtype: 'button'
            ,id: 'modx-sacpoltemp-filter-clear'
            ,cls: 'x-form-filter-clear'
            ,text: _('filter_clear')
            ,listeners: {
                'click': {fn: this.clearFilter, scope: this},
                'mouseout': { fn: function(evt){
                    this.removeClass('x-btn-focus');
                }
                }
            }
        }]
    });
    MODx.grid.AccessPolicyTemplate.superclass.constructor.call(this,config);
};
Ext.extend(MODx.grid.AccessPolicyTemplate,MODx.grid.Grid,{
    getMenu: function() {
        var r = this.getSelectionModel().getSelected();
        var p = r.data.cls;

        var m = [];

        if (this.getSelectionModel().getCount() > 1) {
            m.push({
                text: _('selected_remove')
                ,handler: this.removeSelected
            });
        } else {
            if (p.indexOf('pedit') !== -1) {
                m.push({
                    text: _('edit')
                    ,handler: this.editPolicyTemplate
                });
                m.push({
                    text: _('duplicate')
                    ,handler: this.confirm.createDelegate(this,["Security/Access/Policy/Template/Duplicate","policy_template_duplicate_confirm"])
                });
            }
            if (m.length > 0) { m.push('-'); }
            m.push({
                text: _('export')
                ,handler: this.exportPolicyTemplate
            });

            if (p.indexOf('premove') !== -1) {
                if (m.length > 0) m.push('-');
                m.push({
                    text: _('delete'),
                    handler: this.removePolicyTemplate
                });
            }
        }

        if (m.length > 0) {
            this.addContextMenuItem(m);
        }
    }

    ,createPolicyTemplate: function(btn,e) {
        var r = this.menu.record;
        if (!this.windows.aptc) {
            this.windows.aptc = MODx.load({
                xtype: 'modx-window-access-policy-template-create'
                ,record: r
                ,plugin: this.config.plugin
                ,listeners: {
                    'success': {fn:function(r) {
                        this.refresh();
                    },scope:this}
                }
            });
        }
        this.windows.aptc.reset();
        this.windows.aptc.show(e.target);
    }

    ,importPolicyTemplate: function(btn,e) {
        var r = {};
        if (!this.windows.importPolicyTemplate) {
            this.windows.importPolicyTemplate = MODx.load({
                xtype: 'modx-window-policy-template-import'
                ,record: r
                ,listeners: {
                    'success': {fn:function(o) {
                        this.refresh();
                    },scope:this}
                }
            });
        }
        this.windows.importPolicyTemplate.reset();
        this.windows.importPolicyTemplate.setValues(r);
        this.windows.importPolicyTemplate.show(e.target);
    }

    ,exportPolicyTemplate: function(btn,e) {
        var id = this.menu.record.id;
        MODx.Ajax.request({
            url: this.config.url
            ,params: {
                action: 'Security/Access/Policy/Template/Export'
                ,id: id
            }
            ,listeners: {
                'success': {fn:function(r) {
                    location.href = this.config.url+'?action=Security/Access/Policy/Template/Export&download=1&id='+id+'&HTTP_MODAUTH='+MODx.siteId;
                },scope:this}
            }
        });
    }

    ,editPolicyTemplate: function(itm,e) {
        MODx.loadPage('security/access/policy/template/update', 'id='+this.menu.record.id);
    }

    ,removeSelected: function() {
        var cs = this.getSelectedAsList();
        if (cs === false) return false;

        var store = this.getStore();
        var policiesCount = 0;
        cs.split(',').forEach(function(item){
            const record = store.getById(item);

            if (record) {
                policiesCount += parseInt(record.data.policy_count);
            }

        })

        MODx.msg.confirm({
            title: _('selected_remove')
            ,text: policiesCount ? _('policy_template_remove_multiple_confirm_in_use', {count: policiesCount}) : _('policy_template_remove_multiple_confirm')
            ,url: this.config.url
            ,params: {
                action: 'Security/Access/Policy/Template/RemoveMultiple'
                ,templates: cs
            }
            ,listeners: {
                'success': {fn:function(r) {
                    this.getSelectionModel().clearSelections(true);
                    this.refresh();
                },scope:this}
            }
        });
        return true;
    }

    ,removePolicyTemplate: function() {
        if (!this.menu.record) return;

        MODx.msg.confirm({
            title: _('warning'),
            text: parseInt(this.menu.record.policy_count) ? _('policy_template_remove_confirm_in_use', {count: this.menu.record.policy_count}) : _('policy_template_remove_confirm'),
            url: this.config.url,
            params: {
                action: 'Security/Access/Policy/Template/Remove',
                id: this.menu.record.id
            },
            listeners: {
                success: {
                    fn: this.refresh,
                    scope:this
                }
            }
        });
    }

    ,search: function(tf,newValue,oldValue) {
        var nv = newValue || tf;
        this.getStore().baseParams.query = Ext.isEmpty(nv) || Ext.isObject(nv) ? '' : nv;
        this.getBottomToolbar().changePage(1);
        return true;
    }

    ,clearFilter: function() {
        this.getStore().baseParams = {
            action: 'Security/Access/Policy/Template/GetList'
        };
        Ext.getCmp('modx-policy-template-search').reset();
        this.getBottomToolbar().changePage(1);
    }
});
Ext.reg('modx-grid-access-policy-templates',MODx.grid.AccessPolicyTemplate);

/**
 * Generates a window for creating Access Policies.
 *
 * @class MODx.window.CreateAccessPolicy
 * @extends MODx.Window
 * @param {Object} config An object of options.
 * @xtype modx-window-access-policy-create
 */
MODx.window.CreateAccessPolicyTemplate = function(config) {
    config = config || {};
    this.ident = config.ident || 'cacpt'+Ext.id();
    Ext.applyIf(config,{
        title: _('create')
        ,url: MODx.config.connector_url
        ,action: 'Security/Access/Policy/Template/Create'
        ,fields: [{
            fieldLabel: _('name')
            ,name: 'name'
            ,id: 'modx-'+this.ident+'-name'
            ,xtype: 'textfield'
            ,anchor: '100%'
        },{
            xtype: MODx.expandHelp ? 'label' : 'hidden'
            ,forId: 'modx-'+this.ident+'-name'
            ,html: _('policy_template_desc_name')
            ,cls: 'desc-under'
        },{
            fieldLabel: _('template_group')
            ,name: 'template_group'
            ,id: 'modx-'+this.ident+'-template-group'
            ,xtype: 'modx-combo-access-policy-template-group'
            ,anchor: '100%'
            ,value: 1
        },{
            xtype: MODx.expandHelp ? 'label' : 'hidden'
            ,forId: 'modx-'+this.ident+'-template-group'
            ,html: _('policy_template_desc_template_group')
            ,cls: 'desc-under'
        },{
            fieldLabel: _('description')
            ,name: 'description'
            ,id: 'modx-'+this.ident+'-description'
            ,xtype: 'textarea'
            ,anchor: '100%'
            ,height: 50
        },{
            xtype: MODx.expandHelp ? 'label' : 'hidden'
            ,forId: 'modx-'+this.ident+'-description'
            ,html: _('policy_template_desc_description')
            ,cls: 'desc-under'
        }]
        ,keys: []
    });
    MODx.window.CreateAccessPolicyTemplate.superclass.constructor.call(this,config);
};
Ext.extend(MODx.window.CreateAccessPolicyTemplate,MODx.Window);
Ext.reg('modx-window-access-policy-template-create',MODx.window.CreateAccessPolicyTemplate);

/**
 * @class MODx.window.ImportPolicyTemplate
 * @extends MODx.Window
 * @param {Object} config An object of options.
 * @xtype modx-window-policy-template-import
 */
MODx.window.ImportPolicyTemplate = function(config) {
    config = config || {};
    this.ident = config.ident || 'imppt-'+Ext.id();
    Ext.applyIf(config,{
        title: _('import')
        ,id: 'modx-window-policy-template-import'
        ,url: MODx.config.connector_url
        ,action: 'Security/Access/Policy/Template/Import'
        ,fileUpload: true
        ,saveBtnText: _('import')
        ,fields: [{
            html: _('policy_template_import_msg')
            ,id: this.ident+'-desc'
            ,xtype: 'modx-description'
            ,style: 'margin-bottom: 10px;'
        },{
            xtype: 'fileuploadfield'
            ,fieldLabel: _('file')
            ,buttonText: _('upload.buttons.upload')
            ,name: 'file'
            ,id: this.ident+'-file'
            ,anchor: '100%'
        }]
    });
    MODx.window.ImportPolicyTemplate.superclass.constructor.call(this,config);
};
Ext.extend(MODx.window.ImportPolicyTemplate,MODx.Window);
Ext.reg('modx-window-policy-template-import',MODx.window.ImportPolicyTemplate);
