/**
 * Loads a grid of roles.
 * 
 * @class MODx.grid.Role
 * @extends MODx.grid.Grid
 * @constructor
 * @param {Object} config An object of options.
 * @xtype grid-role
 */
MODx.grid.Role = function(config) {
    config = config || {};
    Ext.applyIf(config,{
        title: _('roles')
        ,id: 'modx-grid-role'
        ,url: MODx.config.connectors_url+'security/role.php'
        ,fields: ['id','name','description','authority','menu']
        ,paging: true
        ,autosave: true
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
            ,editor: { xtype: 'textfield' }
        },{
            header: _('authority')
            ,dataIndex: 'authority'
            ,width: 60
            ,editor: { xtype: 'textfield' }
            ,sortable: true
        }]
        ,tbar: [{
            text: _('create_new')
            ,handler: this.createRole
            ,scope: this
        }]
    });
    MODx.grid.Role.superclass.constructor.call(this,config);
};
Ext.extend(MODx.grid.Role,MODx.grid.Grid,{
    createRole: function(btn,e) {
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


MODx.window.CreateRole = function(config) {
    config = config || {};
    Ext.applyIf(config,{
        title: _('role_create')
        ,height: 150
        ,width: 400
        ,url: MODx.config.connectors_url+'security/role.php'
        ,action: 'create'
        ,fields: [{
            name: 'name'
            ,fieldLabel: _('name')
            ,xtype: 'textfield'
            ,allowBlank: false
            ,anchor: '90%'
        },{
            name: 'authority'
            ,fieldLabel: _('authority')
            ,xtype: 'textfield'
            ,allowBlank: false
            ,value: 0
            ,width: 50
        },{
            name: 'description'
            ,fieldLabel: _('description')
            ,xtype: 'textarea'
            ,anchor: '90%'
            ,grow: true
        }]
    });
    MODx.window.CreateRole.superclass.constructor.call(this,config);
};
Ext.extend(MODx.window.CreateRole,MODx.Window);
Ext.reg('modx-window-role-create',MODx.window.CreateRole);