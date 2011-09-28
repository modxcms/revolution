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
        ,url: MODx.config.connectors_url+'security/access/policy/template.php'
        ,fields: ['id','name','description','template_group','template_group_name','total_permissions','cls']
        ,paging: true
        ,autosave: true
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
            ,scope: this
            ,handler: this.createPolicyTemplate
        },'-',{
            text: _('bulk_actions')
            ,menu: [{
                text: _('policy_remove_multiple')
                ,handler: this.removeSelected
                ,scope: this
            }]
        }]
    });
    MODx.grid.AccessPolicyTemplate.superclass.constructor.call(this,config);
};
Ext.extend(MODx.grid.AccessPolicyTemplate,MODx.grid.Grid,{
    editPolicyTemplate: function(itm,e) {
        location.href = '?a='+MODx.action['security/access/policy/template/update']+'&id='+this.menu.record.id;
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
                    ,handler: this.confirm.createDelegate(this,["duplicate","policy_template_duplicate_confirm"])
                });
            }
            if (p.indexOf('premove') != -1) {
                if (m.length > 0) m.push('-');
                m.push({
                    text: _('policy_template_remove')
                    ,handler: this.confirm.createDelegate(this,["remove","policy_template_remove_confirm"])
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
                action: 'removeMultiple'
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
        width: 400
        ,title: _('policy_template_create')
        ,url: MODx.config.connectors_url+'security/access/policy/template.php'
        ,action: 'create'
        ,fields: [{
            fieldLabel: _('name')
            ,name: 'name'
            ,id: this.ident+'-name'
            ,xtype: 'textfield'
            ,anchor: '90%'
        },{
            fieldLabel: _('template_group')
            ,name: 'template_group'
            ,id: this.ident+'-template-group'
            ,xtype: 'modx-combo-access-policy-template-group'
            ,anchor: '90%'
            ,value: 1
        },{
            fieldLabel: _('description')
            ,name: 'description'
            ,id: this.ident+'-description'
            ,xtype: 'textarea'
            ,anchor: '90%'
            ,height: 50
        }]
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
        ,listWidth: 300
        ,url: MODx.config.connectors_url+'security/access/policy/template.group.php'
        ,tpl: new Ext.XTemplate('<tpl for="."><div class="x-combo-list-item"><span style="font-weight: bold">{name}</span>'
            ,'<p style="margin: 0; font-size: 11px; color: gray;">{description}</p></div></tpl>')
    });
    MODx.combo.AccessPolicyTemplateGroups.superclass.constructor.call(this,config);
};
Ext.extend(MODx.combo.AccessPolicyTemplateGroups,MODx.combo.ComboBox);
Ext.reg('modx-combo-access-policy-template-group',MODx.combo.AccessPolicyTemplateGroups);