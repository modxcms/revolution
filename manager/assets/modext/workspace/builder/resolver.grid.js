/**
 * Loads a grid of Vehicles for a Package.
 * 
 * @class MODx.grid.Vehicle
 * @extends MODx.grid.Grid
 * @constructor
 * @param {Object} config An object of config options.
 * @xtype grid-vehicle
 */
MODx.grid.Resolver = function(config) {
    config = config || {};
    Ext.applyIf(config,{
        title: _('resolvers')
        ,id: 'grid-resolver'
        ,fields: ['type','name','source','target']
        ,columns: [{ 
            header: _('type')
            ,dataIndex: 'type'
            ,width: 120
        },{
            header: _('name')
            ,dataIndex: 'name'
            ,width: 150
        },{
            header: _('source')
            ,dataIndex: 'source'
            ,width: 250
        },{
            header: _('target')
            ,dataIndex: 'target'
            ,width: 250
        }]
        ,primaryKey: 'index'
        ,tbar: [{
            text: _('resolver_add')
            ,handler: { xtype: 'window-resolver-create' ,blankValues: true}
        }]
    });
    MODx.grid.Resolver.superclass.constructor.call(this,config);
};
Ext.extend(MODx.grid.Resolver,MODx.grid.LocalGrid,{
    getMenu: function() {
        return [{
            text: _('resolver_remove')
            ,handler: this.remove.createDelegate(this,[{
                title: _('resolver_remove')
                ,text: _('resolver_remove_confirm')
            }])
        }];
    }
});
Ext.reg('grid-resolver',MODx.grid.Resolver);

/**
 * Loads a window for creating a Resolver.
 * 
 * @class MODx.window.CreateResolver
 * @extends MODx.Window
 * @constructor
 * @param {Object} config An object of config options.
 * @xtype window-resolver-create
 */
MODx.window.CreateResolver = function(config) {
    config = config || {};
    Ext.applyIf(config,{
        title: _('resolver_create')
        ,width: 550
        ,url: MODx.config.connectors_url+'workspace/builder/resolver.php'
        ,action: 'create'
        ,fields: [{
            xtype: 'combo'
            ,fieldLabel: _('type')
            ,description: _('resolver_type_desc')
            ,name: 'type'
            ,hiddenName: 'type'
            ,store: new Ext.data.SimpleStore({
                fields: ['d','v']
                ,data: [[_('file'),'file'],[_('php_script'),'php']]
            })
            ,displayField: 'd'
            ,valueField: 'v'
            ,mode: 'local'
            ,editable: false
            ,typeAhead: false
            ,triggerAction: 'all'
            ,value: 'file'
        },{
            xtype: 'textfield'
            ,fieldLabel: _('name')
            ,description: _('resolver_name_desc')
            ,name: 'name'
            ,width: 300
        },{
            xtype: 'textfield'
            ,fieldLabel: _('source')
            ,description: _('resolver_source_desc')
            ,name: 'source'
            ,width: 400
            ,allowBlank: false
        },{
            xtype: 'textfield'
            ,fieldLabel: _('target')
            ,description: _('resolver_target_desc')
            ,name: 'target'
            ,width: 400
            ,allowBlank: false
            ,value: "return MODX_ASSETS_PATH . 'snippets/';"            
        }]
    });
    MODx.window.CreateResolver.superclass.constructor.call(this,config);
};
Ext.extend(MODx.window.CreateResolver,MODx.Window,{
    submit: function() {
        var f = this.fp.getForm();
        if (f.isValid()) {
            var r = new Ext.data.Record(f.getValues());
            Ext.getCmp('grid-resolver').getStore().add(r);
            this.hide();
        }
    }
});
Ext.reg('window-resolver-create',MODx.window.CreateResolver);
