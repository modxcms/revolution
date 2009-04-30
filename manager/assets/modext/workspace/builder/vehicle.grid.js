/**
 * Loads a grid of Vehicles for a Package.
 * 
 * @class MODx.grid.Vehicle
 * @extends MODx.grid.Grid
 * @param {Object} config An object of config options.
 * @xtype grid-vehicle
 */
MODx.grid.Vehicle = function(config) {
    config = config || {};
    Ext.applyIf(config,{
        title: _('vehicles')
        ,url: MODx.config.connectors_url+'workspace/builder/vehicle.php'
        ,fields: ['index','class_key','name','pk','menu']
        ,columns: [{ 
           header: _('index')
           ,dataIndex: 'index'
        },{ 
            header: _('class_key')
            ,dataIndex: 'class_key'
        },{
            header: _('name')
            ,dataIndex: 'name'
        },{
            header: _('object_id')
            ,dataIndex: 'pk'
        }]
        ,paging: true
        ,primaryKey: 'index'
        ,tbar: [{
            text: _('vehicle_add')
            ,handler: { xtype: 'window-vehicle-create' }
        }]
    });
    MODx.grid.Vehicle.superclass.constructor.call(this,config);
};
Ext.extend(MODx.grid.Vehicle,MODx.grid.Grid);
Ext.reg('grid-vehicle',MODx.grid.Vehicle);

/**
 * @class MODx.window.CreateVehicle
 * @extends MODx.Window
 * @param {Object} config An object of config properties
 * @xtype window-vehicle-create
 */
MODx.window.CreateVehicle = function(config) {
    config = config || {};
    Ext.applyIf(config,{
        title: _('vehicle_create')
        ,width: 600
        ,url: MODx.config.connectors_url+'workspace/builder/vehicle.php'
        ,action: 'create'
        ,fields: [{
            xtype: 'combo-class-key'
            ,fieldLabel: _('class_key')
            ,description: _('class_key_desc')     
            ,allowBlank: true
            ,listeners: {
                'change': {fn:this.updateObjField,scope:this}
            }
        },{
            xtype: 'textfield'
            ,fieldLabel: _('class_key_custom')
            ,description: _('class_key_custom_desc')
            ,name: 'classKeyOther'
            ,allowBlank: true
            ,listeners: {
                'change': {fn:this.updateObjField,scope:this}
            }
        },{
            xtype: 'combo-object'
            ,fieldLabel: _('object_id')
            ,description: _('object_id_desc')
            ,id: 'combo-object'
        },{
            xtype: 'textfield'
            ,fieldLabel: _('unique_key')
            ,description: _('unique_key_desc')
            ,name: 'unique_key'
            ,value: 'name'
        },{
            xtype: 'checkbox'
            ,boxLabel: _('update_object')
            ,description: _('update_object_desc')
            ,name: 'update_object'
            ,labelSeparator: ''
            ,inputValue: 1
            ,checked: true
        },{
            xtype: 'checkbox'
            ,boxLabel: _('resolve_files')
            ,description: _('resolve_files_desc')
            ,name: 'resolve_files'
            ,labelSeparator: ''
            ,inputValue: 1
            ,checked: true
        },{
            xtype: 'checkbox'
            ,boxLabel: _('resolve_php')
            ,description: _('resolve_php_desc')
            ,name: 'resolve_php'
            ,labelSeparator: ''
            ,inputValue: 1
            ,checked: true
        },{
            xtype: 'checkbox'
            ,boxLabel: _('preserve_keys')
            ,description: _('preserve_keys_desc')
            ,name: 'preserve_keys'
            ,labelSeparator: ''
            ,inputValue: 1
            ,checked: false
        },{
            xtype: 'grid-resolver'
            ,id: 'grid-resolver'
            ,preventRender: true
        }]
    });
    MODx.window.CreateVehicle.superclass.constructor.call(this,config);
    this.on('success',this.resetForm,this);
};
Ext.extend(MODx.window.CreateVehicle,MODx.Window,{
    submit: function() {
        var f = this.fp.getForm();
        var rs = Ext.getCmp('grid-resolver').getStore().getRange();
        var r = [];
        for (var i=0;i<rs.length;i++) {
            r.push(rs[i].data);
        }
        f.baseParams.resolvers = Ext.encode(r);
        MODx.window.CreateVehicle.superclass.submit.call(this);
    }
    ,updateObjField: function(f,nv,ov) {
        var cbo = Ext.getCmp('combo-object');
        cbo.store.baseParams.class_key = f.getValue();
        cbo.store.reload({
            params: { start: 0, limit: 10 }
        });
        cbo.clearValue();
        
        cbo.reset();
    }
    ,resetForm: function() {
        Ext.getCmp('grid-resolver').getStore().removeAll();
        this.fp.getForm().reset();
    }
});
Ext.reg('window-vehicle-create',MODx.window.CreateVehicle);
