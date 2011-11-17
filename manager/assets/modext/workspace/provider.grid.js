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
        ,url: MODx.config.connectors_url+'workspace/providers.php'
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
            text: _('provider_add')
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
        title: _('provider_add')
        ,id: 'modx-window-provider-create'
        ,width: 400
        ,url: MODx.config.connectors_url+'workspace/providers.php'
        ,action: 'create'
        ,fields: [{
            fieldLabel: _('name')
            ,name: 'name'
            ,id: 'modx-cprov-name'
            ,xtype: 'textfield'
            ,anchor: '100%'
            ,allowBlank: false
        },{
            fieldLabel: _('service_url')
            ,name: 'service_url'
            ,id: 'modx-cprov-service-url'
            ,xtype: 'textfield'
            ,anchor: '100%'
            ,allowBlank: false
        },{
            fieldLabel: _('username')
            ,name: 'username'
            ,id: 'modx-cprov-username'
            ,xtype: 'textfield'
            ,anchor: '100%'
        },{
            fieldLabel: _('api_key')
            ,name: 'api_key'
            ,id: 'modx-cprov-api_key'
            ,xtype: 'textfield'
            ,anchor: '100%'
        },{
            fieldLabel: _('description')
            ,name: 'description'
            ,id: 'modx-cprov-description'
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
 * @extends MODx.Window
 * @constructor
 * @param {Object} config An object of options.
 * @xtype window-provider-update
 */
MODx.window.UpdateProvider = function(config) {
    config = config || {};
    Ext.applyIf(config,{
        title: _('provider_update')
        ,id: 'modx-window-provider-update'
        ,width: 400
        ,url: MODx.config.connectors_url+'workspace/providers.php'
        ,action: 'update'
        ,fields: [{
            name: 'id'
            ,xtype: 'hidden'
        },{
            fieldLabel: _('name')
            ,name: 'name'
            ,id: 'modx-uprov-name'
            ,xtype: 'textfield'
            ,anchor: '100%'
            ,allowBlank: false
        },{
            fieldLabel: _('service_url')
            ,name: 'service_url'
            ,id: 'modx-uprov-service-url'
            ,xtype: 'textfield'
            ,anchor: '100%'
            ,allowBlank: false
        },{
            fieldLabel: _('username')
            ,name: 'username'
            ,id: 'modx-uprov-username'
            ,xtype: 'textfield'
            ,anchor: '100%'
        },{
            fieldLabel: _('api_key')
            ,name: 'api_key'
            ,id: 'modx-uprov-api_key'
            ,xtype: 'textfield'
            ,anchor: '100%'
        },{
            fieldLabel: _('description')
            ,name: 'description'
            ,id: 'modx-uprov-description'
            ,xtype: 'textarea'
            ,anchor: '100%'
            ,grow: true
        }]
    });
    MODx.window.UpdateProvider.superclass.constructor.call(this,config);
};
Ext.extend(MODx.window.UpdateProvider,MODx.Window);
Ext.reg('modx-window-provider-update',MODx.window.UpdateProvider);
