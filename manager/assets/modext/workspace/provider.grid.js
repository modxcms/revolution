/**
 * Loads a grid of Provisioners.
 *
 * @class MODx.grid.Provisioner
 * @extends MODx.grid.Grid
 * @constructor
 * @param {Object} config An object of options.
 * @xtype grid-provisioner
 */
MODx.grid.Provider = function(config) {
    config = config || {};
    Ext.applyIf(config,{
        title: _('providers')
        ,url: MODx.config.connector_url
        ,save_action: 'Workspace/Providers/UpdateFromGrid'
        ,baseParams: {
            action: 'Workspace/Providers/GetList'
        }
        ,fields: ['id','name','description','service_url','username','api_key','menu']
        ,paging: true
        ,autosave: true
        ,columns: [{
            header: _('name')
            ,dataIndex: 'name'
            ,editor: { xtype: 'textfield' ,allowBlank: false }
        },{
            header: _('service_url')
            ,dataIndex: 'service_url'
            ,width: 200
            ,editor: { xtype: 'textfield' ,allowBlank: false }
        },{
            header: _('description')
            ,dataIndex: 'description'
            ,width: 300
            ,editor: { xtype: 'textarea' }
        }]
        ,tbar: [{
            text: _('create')
            ,cls: 'primary-button'
            ,handler: { xtype: 'modx-window-provider-create' ,blankValues: true }
        }]
    });
    MODx.grid.Provider.superclass.constructor.call(this,config);
};
Ext.extend(MODx.grid.Provider,MODx.grid.Grid);
Ext.reg('modx-grid-provider',MODx.grid.Provider);

/**
 * Generates the Create Provider window.
 *
 * @class MODx.window.CreateProvider
 * @extends MODx.Window
 * @constructor
 * @param {Object} config An object of options.
 * @xtype window-provider-create
 */
MODx.window.CreateProvider = function(config) {
    config = config || {};
    Ext.applyIf(config,{
        title: _('create')
        ,url: MODx.config.connector_url
        ,action: 'Workspace/Providers/Create'
        ,fields: [{
            name: 'id'
            ,xtype: 'hidden'
        },{
            fieldLabel: _('name')
            ,name: 'name'
            ,xtype: 'textfield'
            ,anchor: '100%'
            ,allowBlank: false
        },{
            fieldLabel: _('service_url')
            ,name: 'service_url'
            ,xtype: 'textfield'
            ,anchor: '100%'
            ,allowBlank: false
        },{
            fieldLabel: _('username')
            ,name: 'username'
            ,xtype: 'textfield'
            ,anchor: '100%'
        },{
            fieldLabel: _('api_key')
            ,name: 'api_key'
            ,xtype: 'textfield'
            ,anchor: '100%'
        },{
            fieldLabel: _('description')
            ,name: 'description'
            ,xtype: 'textarea'
            ,anchor: '100%'
            ,grow: true
        }]
    });
    MODx.window.CreateProvider.superclass.constructor.call(this,config);
};
Ext.extend(MODx.window.CreateProvider,MODx.Window);
Ext.reg('modx-window-provider-create',MODx.window.CreateProvider);

/**
 * Generates the Update Provider window.
 *
 * @class MODx.window.UpdateProvider
 * @extends MODx.window.CreateProvider
 * @constructor
 * @param {Object} config An object of options.
 * @xtype window-provider-update
 */
MODx.window.UpdateProvider = function(config) {
    config = config || {};
    Ext.applyIf(config,{
        title: _('edit')
        ,action: 'Workspace/Providers/Update'
    });
    MODx.window.UpdateProvider.superclass.constructor.call(this,config);
};
Ext.extend(MODx.window.UpdateProvider, MODx.window.CreateProvider);
Ext.reg('modx-window-provider-update',MODx.window.UpdateProvider);
