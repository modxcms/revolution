/**
 * Loads a grid of roles.
 *
 * @class MODx.grid.Role
 * @extends MODx.grid.Grid
 * @constructor
 * @param {Object} config An object of options.
 * @xtype modx-grid-role
 */
MODx.grid.Role = function(config) {
    config = config || {};
    Ext.applyIf(config,{
        title: _('roles')
        ,id: 'modx-grid-role'
        ,url: MODx.config.connector_url
        ,baseParams: {
            action: 'Security/Role/GetList'
        }
        ,fields: ['id','name','description','authority','perm']
        ,paging: true
        ,autosave: true
        ,save_action: 'Security/Role/UpdateFromGrid'
        ,columns: [{
            header: _('id')
            ,dataIndex: 'id'
            ,width: 50
            ,sortable: true
        },{
            header: _('name')
            ,dataIndex: 'name'
            ,width: 150
            ,sortable: true
            ,editor: { xtype: 'textfield' }
        },{
            header: _('description')
            ,dataIndex: 'description'
            ,width: 350
            ,editor: { xtype: 'textarea' }
        },{
            header: _('authority')
            ,dataIndex: 'authority'
            ,width: 60
            ,editor: { xtype: 'textfield' }
            ,sortable: true
        }]
        ,tbar: [{
            text: _('create')
            ,cls:'primary-button'
            ,handler: this.createRole
            ,scope: this
        }]
    });
    MODx.grid.Role.superclass.constructor.call(this,config);
    this.on('beforeedit',this.checkEditable,this);
};
Ext.extend(MODx.grid.Role,MODx.grid.Grid,{
    checkEditable: function(o) {
        var p = o.record.data.perm || '';
        return p.indexOf('edit') != -1;
    }

    ,getMenu: function() {
        var r = this.getSelectionModel().getSelected();
        var p = r.data.perm || '';
        var m = [];
        if (p.indexOf('remove') != -1) {
            m.push({
                text: _('delete')
                ,handler: this.remove.createDelegate(this,['role_remove_confirm', 'Security/Role/Remove'])
            });
        }
        return m;
    }

    ,createRole: function(btn,e) {
        this.loadWindow(btn,e,{
            xtype: 'modx-window-role-create'
            ,listeners: {
                'success': {fn: function() {
                    this.refresh();
                },scope:this}
            }
        });
    }
});
Ext.reg('modx-grid-role',MODx.grid.Role);

/**
 * @class MODx.window.CreateRole
 * @extends MODx.Window
 * @param {Object} config An object of options.
 * @xtype modx-window-role-create
 */
MODx.window.CreateRole = function(config) {
    config = config || {};
    this.ident = config.ident || 'crole'+Ext.id();
    Ext.applyIf(config,{
        title: _('create')
        ,url: MODx.config.connector_url
        ,action: 'Security/Role/Create'
        ,fields: [{
            name: 'name'
            ,fieldLabel: _('name')
            ,id: 'modx-'+this.ident+'-name'
            ,xtype: 'textfield'
            ,allowBlank: false
            ,anchor: '100%'
        },{
            xtype: MODx.expandHelp ? 'label' : 'hidden'
            ,forId: 'modx-'+this.ident+'-name'
            ,html: _('role_desc_name')
            ,cls: 'desc-under'
        },{
            name: 'authority'
            ,fieldLabel: _('authority')
            ,xtype: 'textfield'
            ,id: 'modx-'+this.ident+'-authority'
            ,allowBlank: false
            ,allowNegative: false
            ,value: 0
            ,anchor: '100%'
        },{
            xtype: MODx.expandHelp ? 'label' : 'hidden'
            ,forId: 'modx-'+this.ident+'-authority'
            ,html: _('role_desc_authority')
            ,cls: 'desc-under'
        },{
            name: 'description'
            ,fieldLabel: _('description')
            ,id: 'modx-'+this.ident+'-description'
            ,xtype: 'textarea'
            ,anchor: '100%'
            ,grow: true
        },{
            xtype: MODx.expandHelp ? 'label' : 'hidden'
            ,forId: 'modx-'+this.ident+'-description'
            ,html: _('role_desc_description')
            ,cls: 'desc-under'
        }]
        ,keys: []
    });
    MODx.window.CreateRole.superclass.constructor.call(this,config);
};
Ext.extend(MODx.window.CreateRole,MODx.Window);
Ext.reg('modx-window-role-create',MODx.window.CreateRole);
