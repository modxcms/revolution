/**
 * Loads a grid of Plugin Events
 * 
 * @class MODx.grid.PluginEvent
 * @extends MODx.grid.Grid
 * @param {Object} config An object of options.
 * @xtype modx-grid-plugin-event
 */
MODx.grid.PluginEvent = function(config) {
    config = config || {};
    this.ident = config.ident || 'grid-pluge'+Ext.id();
    var ec = new Ext.ux.grid.CheckColumn({
        header: _('enabled')
        ,dataIndex: 'enabled'
        ,editable: true
        ,width: 80
        ,sortable: true
    });
    Ext.applyIf(config,{
        title: _('system_events')
        ,id: 'modx-grid-plugin-event'
        ,url: MODx.config.connectors_url+'element/plugin/event.php'
        ,baseParams: {
            action: 'getList'
            ,plugin: config.plugin
            ,limit: 0
        }
        ,saveParams: {
            plugin: config.plugin
        }
        ,enableColumnResize: true
        ,enableColumnMove: true
        ,primaryKey: 'name'
        ,fields: ['name','service','groupname','enabled','priority','propertyset','menu']
        ,paging: false
        ,pageSize: 0
        ,remoteSort: false
        ,singleText: _('event')
        ,pluralText: _('events')
        ,plugins: ec
        ,columns: [{
            header: _('name')
            ,dataIndex: 'name'
            ,id: 'modx-'+this.ident+'-col-name'
            ,width: 250
            ,sortable: true
        },ec
        ,{
            header: _('group')
            ,dataIndex: 'groupname'
            ,id: 'modx-'+this.ident+'-col-groupname'
            ,width: 200
            ,editor: { xtype: 'textfield' }
            ,sortable: true
        },{
            header: _('propertyset')
            ,dataIndex: 'propertyset'
            ,id: 'modx-'+this.ident+'-col-propertyset'
            ,width: 180
            ,editor: { 
                xtype: 'modx-combo-property-set'
                ,renderer: true
                ,baseParams: {
                    action: 'getList'
                    ,showAssociated: true
                    ,elementId: config.plugin
                    ,elementType: 'modPlugin'
                }
            }
            ,sortable: true
        },{
            header: _('priority')
            ,dataIndex: 'priority'
            ,id: 'modx-'+this.ident+'-priority'
            ,width: 100
            ,editor: { xtype: 'textfield' ,allowBlank: false }
            ,sortable: true
        }]
        /*,tbar: [{
            xtype: 'textfield'
            ,name: 'name_filter'
            ,id: 'modx-'+this.ident+'-filter-name'
            ,emptyText: _('filter_by_name')
            ,listeners: {
                'change': {fn:this.filterByName,scope:this}
                ,'render': {fn:function(tf) {
                    tf.getEl().addKeyListener(Ext.EventObject.ENTER,function() {
                        tf.fireEvent('change');
                    },this);
                }}
            }
        }] */
    });
    MODx.grid.PluginEvent.superclass.constructor.call(this,config);
    this.addEvents('updateEvent');
};
Ext.extend(MODx.grid.PluginEvent,MODx.grid.Grid,{
    /*filterByName: function(tf,newValue,oldValue) {
        this.getStore().baseParams = {
            action: 'getList'
            ,name: newValue
            ,id: this.config.plugin
        };
        this.getStore().load({
            params: {
                start: 0
                ,limit: 20
                ,plugin: this.config.plugin
            }
            ,scope: this
            ,callback: this.refresh
        });
    }
    ,*/updateEvent: function(btn,e) {
        var r = this.menu.record;
        if (!this.windows.peu) {
            this.windows.peu = MODx.load({
                xtype: 'modx-window-plugin-event-update'
                ,record: r
                ,plugin: this.config.plugin
                ,listeners: {
                    'success': {fn:function(r) {
                        this.refresh();
                        this.fireEvent('updateEvent',r);
                    },scope:this}
                }
            });
        }
        this.windows.peu.setValues(r);
        this.windows.peu.show(e.target);
    }
});
Ext.reg('modx-grid-plugin-event',MODx.grid.PluginEvent);


MODx.window.UpdatePluginEvent = function(config) {
    config = config || {};
    this.ident = config.ident || 'upluge'+Ext.id();
    Ext.applyIf(config,{
        title: _('plugin_event_update')
        ,id: 'modx-window-plugin-event-update'
        ,url: MODx.config.connectors_url+'element/plugin/event.php'
        ,action: 'associate'
        ,autoHeight: true
        ,width: 600
        ,fields: [{
            fieldLabel: _('name')
            ,name: 'name'
            ,id: 'modx-'+this.ident+'-name'
            ,xtype: 'statictextfield'
            ,anchor: '100%'
            ,submitValue: true
        },{
            xtype: 'modx-grid-plugin-event-assoc'
            ,id: 'modx-grid-'+this.ident+'-assoc'
            ,autoHeight: true
            ,plugin: config.plugin
        }]
    });
    MODx.window.UpdatePluginEvent.superclass.constructor.call(this,config);
    this.on('show',this.onShow,this);
    this.on('beforeSubmit',this.beforeSubmit,this);
};
Ext.extend(MODx.window.UpdatePluginEvent,MODx.Window,{
    onShow: function() {
        var evt = this.fp.getForm().findField('name').getValue();
        MODx.Ajax.request({
            url: MODx.config.connectors_url+'element/plugin/event.php'
            ,params: {
                action: 'getAssoc'
                ,'event': evt
            }
            ,listeners: {
                'success':{fn:function(r) {
                    var data = r.object;
                    var g = Ext.getCmp('modx-grid-'+this.ident+'-assoc');
                    var s = g.getStore();
                    s.removeAll();
                    if (data.length > 0) {
                        s.loadData(data);
                    }
                },scope:this}
            }
        });
    }
    ,beforeSubmit: function(vs) {
        this.fp.getForm().baseParams = {
            action: 'associate'
            ,plugins: Ext.getCmp('modx-grid-'+this.ident+'-assoc').encode()
        };
    }
});
Ext.reg('modx-window-plugin-event-update',MODx.window.UpdatePluginEvent);


MODx.grid.PluginEventAssoc = function(config) {
    config = config || {};
    this.ident = config.ident || 'grid-pluge-assoc'+Ext.id();
    Ext.applyIf(config,{
        title: _('plugins')
        ,id: this.ident
        ,url: MODx.config.connectors_url+'element/plugin/event.php'
        ,baseParams: {
            action: 'getPlugins'
            ,plugin: config.plugin
        }
        ,saveParams: {
            plugin: config.plugin
        }
        ,fields: ['id','name','priority','propertyset']
        ,pluginRecord: [{name:'id'},{name:'name'},{name:'priority'},{name:'propertyset'}]
        ,paging: true
        ,remoteSort: true
        ,columns: [{
            header: _('id')
            ,dataIndex: 'id'
            ,width: 80
            ,sortable: true
        },{
            header: _('plugin')
            ,dataIndex: 'name'
            ,width: 150
            ,sortable: true
        },{
            header: _('propertyset')
            ,dataIndex: 'propertyset'
            ,width: 150
            ,editor: MODx.load({ 
                xtype: 'modx-combo-property-set'
                ,baseParams: {
                    action: 'getList'
                    ,showAssociated: true
                    ,elementId: config.plugin
                    ,elementType: 'modPlugin'
                }
            })
        },{
            header: _('priority')
            ,dataIndex: 'priority'
            ,width: 100
            ,editor: { xtype: 'textfield' ,allowBlank: false }
        }]
        ,tbar: [{
            text: _('plugin_add')
            ,handler: this.addPlugin
            ,scope: this
        }]
    });
    MODx.grid.PluginEventAssoc.superclass.constructor.call(this,config);
    this.pluginRecord = Ext.data.Record.create(config.pluginRecord);
};
Ext.extend(MODx.grid.PluginEventAssoc,MODx.grid.LocalGrid,{
    addPlugin: function(btn,e) {
        this.loadWindow(btn,e,{
            xtype: 'modx-window-plugin-event-add-plugin'
            ,record: this.menu.record
            ,plugin: this.config.plugin
            ,listeners: {
                'success': {fn:function(r) {
                    var rec = new this.pluginRecord({
                        id: r.id
                        ,name: r.name
                        ,priority: r.priority
                        ,propertyset: r.propertyset
                    });
                    this.getStore().add(rec);                  
                },scope:this}
            }
        });
    }    
    
    ,_showMenu: function(g,ri,e) {
        var sm = this.getSelectionModel();
        e.stopEvent();
        e.preventDefault();
        this.menu.removeAll();
        this.addContextMenuItem([{
            text: _('remove')
            ,handler: this.remove.createDelegate(this,[{
                title: _('warning')
                ,text: _('plugin_event_plugin_remove_confirm')
            }])
            ,scope: this
        }]);
        this.menu.show(e.target);
    }
});
Ext.reg('modx-grid-plugin-event-assoc',MODx.grid.PluginEventAssoc);


MODx.window.AddPluginToEvent = function(config) {
    config = config || {};
    this.ident = config.ident || 'apluge'+Ext.id();
    Ext.applyIf(config,{
        title: _('plugin_add_to_event')
        ,id: this.ident
        ,url: MODx.config.connectors_url+'element/plugin/event.php'
        ,action: 'addplugin'
        ,height: 250
        ,width: 600
        ,fields: [{
            xtype: 'modx-combo-plugin'
            ,fieldLabel: _('plugin')
            ,name: 'plugin'
            ,id: 'modx-'+this.ident+'-plugin'
            ,anchor: '100%'
        },{
            xtype: 'numberfield'
            ,name: 'priority'
            ,fieldLabel: _('priority')
            ,id: 'modx-'+this.ident+'-priority'
            ,value: 0
            ,allowBlank: false
        }]
    });
    MODx.window.AddPluginToEvent.superclass.constructor.call(this,config);
};
Ext.extend(MODx.window.AddPluginToEvent,MODx.Window,{
    submit: function() {
        var f = this.fp.getForm();
        var vs = f.getValues();
        var cb = f.findField('plugin');
        vs.id = cb.getValue();
        vs.name = cb.getRawValue();
        
        if (this.fp.getForm().isValid()) {
            if (this.fireEvent('success',vs)) {
                this.fp.getForm().reset();
                this.hide();
                return true;
            }
        }
        return false;
    }
});
Ext.reg('modx-window-plugin-event-add-plugin',MODx.window.AddPluginToEvent);


MODx.combo.Plugin = function(config) {
    config = config || {};
    Ext.applyIf(config,{
        url: MODx.config.connectors_url+'element/plugin.php'
        ,fields: ['id','name','description']
        ,name: 'plugin'
        ,hiddenName: 'plugin'
        ,displayField: 'name'
        ,valueField: 'id'
        ,editable: false
        ,tpl: new Ext.XTemplate('<tpl for="."><div class="x-combo-list-item"><span style="font-weight: bold">{name}</span>'
                               ,'<br />{description}</div></tpl>')
    });
    MODx.combo.Plugin.superclass.constructor.call(this,config);
};
Ext.extend(MODx.combo.Plugin,MODx.combo.ComboBox);
Ext.reg('modx-combo-plugin',MODx.combo.Plugin);
