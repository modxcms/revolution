/**
 * Loads the panel for managing access policies.
 *
 * @class MODx.panel.AccessPolicies
 * @extends MODx.FormPanel
 * @param {Object} config An object of configuration properties
 * @xtype modx-panel-access-policies
 */
MODx.panel.AccessPolicies = function(config) {
    config = config || {};
    Ext.applyIf(config,{
        id: 'modx-panel-access-policies'
        ,bodyStyle: ''
        ,defaults: { collapsible: false ,autoHeight: true }
        ,items: [{
            html: '<h2>'+_('policies')+'</h2>'
            ,border: false
            ,id: 'modx-policies-header'
            ,cls: 'modx-page-header'
        },{
            layout: 'form'
            ,cls: 'main-wrapper'
            ,items: [{
                html: '<p>'+_('policy_management_msg')+'</p>'
                ,border: false
            },{
                xtype: 'modx-grid-access-policy'
                ,preventRender: true
            }]
        }]
    });
    MODx.panel.AccessPolicies.superclass.constructor.call(this,config);
};
Ext.extend(MODx.panel.AccessPolicies,MODx.FormPanel);
Ext.reg('modx-panel-access-policies',MODx.panel.AccessPolicies);

/**
 * Loads a grid of modAccessPolicies.
 *
 * @class MODx.grid.AccessPolicy
 * @extends MODx.grid.Grid
 * @param {Object} config An object of options.
 * @xtype modx-grid-access-policy
 */
MODx.grid.AccessPolicy = function(config) {
    config = config || {};
    this.sm = new Ext.grid.CheckboxSelectionModel();
    Ext.applyIf(config,{
        id: 'modx-grid-access-policy'
        ,url: MODx.config.connector_url
        ,baseParams: {
            action: 'security/access/policy/getlist'
        }
        ,fields: ['id','name','description','class','data','parent','template','template_name','active_permissions','total_permissions','active_of','cls']
        ,paging: true
        ,autosave: true
        ,save_action: 'security/access/policy/updatefromgrid'
        ,remoteSort: true
        ,sm: this.sm
        ,columns: [this.sm,{
            header: _('policy_name')
            ,dataIndex: 'name'
            ,width: 200
            ,editor: { xtype: 'textfield' ,allowBlank: false }
            ,sortable: true
        },{
            header: _('description')
            ,dataIndex: 'description'
            ,width: 375
            ,editor: { xtype: 'textfield' }
        },{
            header: _('policy_template')
            ,dataIndex: 'template_name'
            ,width: 375
        },{
            header: _('active_permissions')
            ,dataIndex: 'active_of'
            ,width: 100
            ,editable: false
        }]
        ,tbar: [{
            text: _('policy_create')
            ,cls:'primary-button'
            ,scope: this
            ,handler: this.createPolicy
        },{
            text: _('import')
            ,scope: this
            ,handler: this.importPolicy
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
            ,id: 'modx-policy-search'
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
            ,id: 'modx-sacpol-filter-clear'
            ,cls: 'x-form-filter-clear'
            ,text: _('filter_clear')
            ,listeners: {
                'click': {fn: this.clearFilter, scope: this}
            }
        }]
    });
    MODx.grid.AccessPolicy.superclass.constructor.call(this,config);
};
Ext.extend(MODx.grid.AccessPolicy,MODx.grid.Grid,{
    search: function(tf,newValue,oldValue) {
        var nv = newValue || tf;
        this.getStore().baseParams.query = Ext.isEmpty(nv) || Ext.isObject(nv) ? '' : nv;
        this.getBottomToolbar().changePage(1);
        //this.refresh();
        return true;
    }
    ,clearFilter: function() {
    	this.getStore().baseParams = {
            action: 'security/access/policy/getList'
    	};
        Ext.getCmp('modx-policy-search').reset();
    	this.getBottomToolbar().changePage(1);
        //this.refresh();
    }

    ,editPolicy: function(itm,e) {
        MODx.loadPage('security/access/policy/update', 'id='+this.menu.record.id);
    }

    ,createPolicy: function(btn,e) {
        var r = this.menu.record;
        if (!this.windows.apc) {
            this.windows.apc = MODx.load({
                xtype: 'modx-window-access-policy-create'
                ,record: r
                ,plugin: this.config.plugin
                ,listeners: {
                    'success': {fn:function(r) {
                        this.refresh();
                    },scope:this}
                }
            });
        }
        this.windows.apc.reset();
        this.windows.apc.show(e.target);
    }
    ,exportPolicy: function(btn,e) {
        var id = this.menu.record.id;
        MODx.Ajax.request({
            url: this.config.url
            ,params: {
                action: 'security/access/policy/export'
                ,id: id
            }
            ,listeners: {
                'success': {fn:function(r) {
                    location.href = this.config.url+'?action=security/access/policy/export&download=1&id='+id+'&HTTP_MODAUTH='+MODx.siteId;
                },scope:this}
            }
        });
    }

    ,importPolicy: function(btn,e) {
        var r = {};
        if (!this.windows.importPolicy) {
            this.windows.importPolicy = MODx.load({
                xtype: 'modx-window-policy-import'
                ,record: r
                ,listeners: {
                    'success': {fn:function(o) {
                        this.refresh();
                    },scope:this}
                }
            });
        }
        this.windows.importPolicy.reset();
        this.windows.importPolicy.setValues(r);
        this.windows.importPolicy.show(e.target);
    }

    ,getMenu: function() {
        var r = this.getSelectionModel().getSelected();
        var p = r.data.cls;

        var m = [];
        if (this.getSelectionModel().getCount() > 1) {
            m.push({
                text: _('policy_remove_multiple')
                ,handler: this.removeSelected
            });
        } else {
            if (p.indexOf('pedit') != -1) {
                m.push({
                    text: _('policy_update')
                    ,handler: this.editPolicy
                });
                m.push({
                    text: _('policy_duplicate')
                    ,handler: this.confirm.createDelegate(this,["security/access/policy/duplicate","policy_duplicate_confirm"])
                });
            }
            if (m.length > 0) { m.push('-'); }
            m.push({
                text: _('policy_export')
                ,handler: this.exportPolicy
            });
            if (p.indexOf('premove') != -1) {
                if (m.length > 0) m.push('-');
                m.push({
                    text: _('policy_remove')
                    ,handler: this.confirm.createDelegate(this,["security/access/policy/remove","policy_remove_confirm"])
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
            title: _('policy_remove_multiple')
            ,text: _('policy_remove_multiple_confirm')
            ,url: this.config.url
            ,params: {
                action: 'security/access/policy/removeMultiple'
                ,policies: cs
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
Ext.reg('modx-grid-access-policy',MODx.grid.AccessPolicy);

/**
 * Generates a window for creating Access Policies.
 *
 * @class MODx.window.CreateAccessPolicy
 * @extends MODx.Window
 * @param {Object} config An object of options.
 * @xtype modx-window-access-policy-create
 */
MODx.window.CreateAccessPolicy = function(config) {
    config = config || {};
    this.ident = config.ident || 'cacp'+Ext.id();
    Ext.applyIf(config,{
        // width: 500
        title: _('policy_create')
        ,url: MODx.config.connector_url
        ,action: 'security/access/policy/create'
        ,fields: [{
            fieldLabel: _('name')
            ,description: MODx.expandHelp ? '' : _('policy_desc_name')
            ,name: 'name'
            ,id: 'modx-'+this.ident+'-name'
            ,xtype: 'textfield'
            ,anchor: '100%'
        },{
            xtype: MODx.expandHelp ? 'label' : 'hidden'
            ,forId: 'modx-'+this.ident+'-name'
            ,html: _('policy_desc_name')
            ,cls: 'desc-under'
        },{
            fieldLabel: _('policy_template')
            ,description: MODx.expandHelp ? '' : _('policy_desc_template')
            ,name: 'template'
            ,hiddenName: 'template'
            ,id: 'modx-'+this.ident+'-template'
            ,xtype: 'modx-combo-access-policy-template'
            ,anchor: '100%'
        },{
            xtype: MODx.expandHelp ? 'label' : 'hidden'
            ,forId: 'modx-'+this.ident+'-template'
            ,html: _('policy_desc_template')
            ,cls: 'desc-under'
        },{
            fieldLabel: _('description')
            ,description: MODx.expandHelp ? '' : _('policy_desc_description')
            ,name: 'description'
            ,id: 'modx-'+this.ident+'-description'
            ,xtype: 'textarea'
            ,anchor: '100%'
            ,height: 50
        },{
            xtype: MODx.expandHelp ? 'label' : 'hidden'
            ,forId: 'modx-'+this.ident+'-description'
            ,html: _('policy_desc_description')
            ,cls: 'desc-under'
        },{
            name: 'class'
            ,id: 'modx-'+this.ident+'-class'
            ,xtype: 'hidden'
        },{
            name: 'id'
            ,id: 'modx-'+this.ident+'-id'
            ,xtype: 'hidden'
        }]
        ,keys: []
    });
    MODx.window.CreateAccessPolicy.superclass.constructor.call(this,config);
};
Ext.extend(MODx.window.CreateAccessPolicy,MODx.Window);
Ext.reg('modx-window-access-policy-create',MODx.window.CreateAccessPolicy);


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
        // ,listWidth: 300
        ,pageSize: 20
        ,url: MODx.config.connector_url
        ,baseParams: {
            action: 'security/access/policy/template/getlist'
        }
        ,tpl: new Ext.XTemplate('<tpl for="."><div class="x-combo-list-item"><span style="font-weight: bold">{name}</span>'
            ,'<p style="margin: 0; font-size: 11px; color: gray;">{description}</p></div></tpl>')
    });
    MODx.combo.AccessPolicyTemplate.superclass.constructor.call(this,config);
};
Ext.extend(MODx.combo.AccessPolicyTemplate,MODx.combo.ComboBox);
Ext.reg('modx-combo-access-policy-template',MODx.combo.AccessPolicyTemplate);

MODx.window.ImportPolicy = function(config) {
    config = config || {};
    this.ident = config.ident || 'imppol-'+Ext.id();
    Ext.applyIf(config,{
        title: _('policy_import')
        ,id: 'modx-window-policy-import'
        ,url: MODx.config.connector_url
        ,action: 'security/access/policy/import'
        ,fileUpload: true
        ,saveBtnText: _('import')
        ,fields: [{
            html: _('policy_import_msg')
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
    MODx.window.ImportPolicy.superclass.constructor.call(this,config);
};
Ext.extend(MODx.window.ImportPolicy,MODx.Window);
Ext.reg('modx-window-policy-import',MODx.window.ImportPolicy);
