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
            html: '<h2>'+_('policies')+'</h2>'
            ,border: false
            ,id: 'modx-policy-templates-header'
            ,cls: 'modx-page-header'
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
            action: 'security/access/policy/template/getlist'
        }
        ,fields: ['id','name','description','template_group','template_group_name','total_permissions','cls']
        ,paging: true
        ,autosave: true
        ,save_action: 'security/access/policy/template/updatefromgrid'
        ,remoteSort: true
        ,sm: this.sm
        ,columns: [this.sm,{
            header: _('name')
            ,dataIndex: 'name'
            ,width: 200
            ,editor: { xtype: 'textfield' ,allowBlank: false }
            ,sortable: true
        },{
            header: _('description')
            ,dataIndex: 'description'
            ,width: 375
            ,editor: { xtype: 'textfield' }
            ,sortable: true
        },{
            header: _('template_group')
            ,dataIndex: 'template_group_name'
            ,width: 375
            ,sortable: true
        },{
            header: _('permissions')
            ,dataIndex: 'total_permissions'
            ,width: 100
            ,editable: false
            ,sortable: false
        }]
        ,tbar: [{
            text: _('policy_template_create')
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
                text: _('policy_remove_multiple')
                ,handler: this.removeSelected
                ,scope: this
            }]
        },'->',{
            xtype: 'textfield'
            ,name: 'search'
            ,id: 'modx-policy-template-search'
            ,cls: 'x-form-filter'
            ,emptyText: _('search_ellipsis')
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
                'click': {fn: this.clearFilter, scope: this}
            }
        }]
    });
    MODx.grid.AccessPolicyTemplate.superclass.constructor.call(this,config);
};
Ext.extend(MODx.grid.AccessPolicyTemplate,MODx.grid.Grid,{
    search: function(tf,newValue,oldValue) {
        var nv = newValue || tf;
        this.getStore().baseParams.query = Ext.isEmpty(nv) || Ext.isObject(nv) ? '' : nv;
        this.getBottomToolbar().changePage(1);
       // this.refresh();
        return true;
    }
    ,clearFilter: function() {
    	this.getStore().baseParams = {
            action: 'security/access/policy/template/getList'
    	};
        Ext.getCmp('modx-policy-template-search').reset();
    	this.getBottomToolbar().changePage(1);
       // this.refresh();
    }
    ,editPolicyTemplate: function(itm,e) {
        MODx.loadPage('security/access/policy/template/update', 'id='+this.menu.record.id);
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
    ,exportPolicyTemplate: function(btn,e) {
        var id = this.menu.record.id;
        MODx.Ajax.request({
            url: this.config.url
            ,params: {
                action: 'security/access/policy/template/export'
                ,id: id
            }
            ,listeners: {
                'success': {fn:function(r) {
                    location.href = this.config.url+'?action=security/access/policy/template/export&download=1&id='+id+'&HTTP_MODAUTH='+MODx.siteId;
                },scope:this}
            }
        });
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


    ,getMenu: function() {
        var r = this.getSelectionModel().getSelected();
        var p = r.data.cls;

        var m = [];

        if (this.getSelectionModel().getCount() > 1) {
            m.push({
                text: _('policy_template_remove_multiple')
                ,handler: this.removeSelected
            });
        } else {
            if (p.indexOf('pedit') != -1) {
                m.push({
                    text: _('policy_template_update')
                    ,handler: this.editPolicyTemplate
                });
                m.push({
                    text: _('policy_template_duplicate')
                    ,handler: this.confirm.createDelegate(this,["security/access/policy/template/duplicate","policy_template_duplicate_confirm"])
                });
            }
            if (m.length > 0) { m.push('-'); }
            m.push({
                text: _('policy_template_export')
                ,handler: this.exportPolicyTemplate
            });

            if (p.indexOf('premove') != -1) {
                if (m.length > 0) m.push('-');
                m.push({
                    text: _('policy_template_remove')
                    ,handler: this.confirm.createDelegate(this,["security/access/policy/template/remove","policy_template_remove_confirm"])
                });
            }
        }

        if (m.length > 0) {
            this.addContextMenuItem(m);
        }
    }
    ,removeSelected: function() {
        var cs = this.getSelectedAsList();
        if (cs === false) return false;

        MODx.msg.confirm({
            title: _('policy_template_remove_multiple')
            ,text: _('policy_template_remove_multiple_confirm')
            ,url: this.config.url
            ,params: {
                action: 'security/access/policy/template/removeMultiple'
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
        // width: 500
        title: _('policy_template_create')
        ,url: MODx.config.connector_url
        ,action: 'security/access/policy/template/create'
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


MODx.combo.AccessPolicyTemplateGroups = function(config) {
    config = config || {};
    Ext.applyIf(config,{
        name: 'template_group'
        ,hiddenName: 'template_group'
        ,fields: ['id','name','description']
        ,forceSelection: true
        ,typeAhead: false
        ,editable: false
        ,allowBlank: false
        // ,listWidth: 300
        ,url: MODx.config.connector_url
        ,baseParams: {
            action: 'security/access/policy/template/group/getlist'
        }
        ,tpl: new Ext.XTemplate('<tpl for="."><div class="x-combo-list-item"><span style="font-weight: bold">{name}</span>'
            ,'<p style="margin: 0; font-size: 11px; color: gray;">{description}</p></div></tpl>')
    });
    MODx.combo.AccessPolicyTemplateGroups.superclass.constructor.call(this,config);
};
Ext.extend(MODx.combo.AccessPolicyTemplateGroups,MODx.combo.ComboBox);
Ext.reg('modx-combo-access-policy-template-group',MODx.combo.AccessPolicyTemplateGroups);


MODx.window.ImportPolicyTemplate = function(config) {
    config = config || {};
    this.ident = config.ident || 'imppt-'+Ext.id();
    Ext.applyIf(config,{
        title: _('policy_template_import')
        ,id: 'modx-window-policy-template-import'
        ,url: MODx.config.connector_url
        ,action: 'security/access/policy/template/import'
        ,fileUpload: true
        ,saveBtnText: _('import')
        ,fields: [{
            html: _('policy_template_import_msg')
            ,id: this.ident+'-desc'
            ,border: false
            ,cls: 'panel-desc'
            ,style: 'margin-bottom: 10px;'
        },{
            xtype: 'fileuploadfield'
            ,fieldLabel: _('file')
            ,buttonText: _('upload.buttons.upload')
            ,name: 'file'
            ,id: this.ident+'-file'
            ,anchor: '100%'
            // ,inputType: 'file'
        }]
    });
    MODx.window.ImportPolicyTemplate.superclass.constructor.call(this,config);
};
Ext.extend(MODx.window.ImportPolicyTemplate,MODx.Window);
Ext.reg('modx-window-policy-template-import',MODx.window.ImportPolicyTemplate);
