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
        ,url: MODx.config.connector_url
        ,baseParams: {
            action: 'Element/Plugin/Event/GetList'
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
                    action: 'Element/PropertySet/GetList'
                    ,showAssociated: true
                    ,elementId: config.plugin
                    ,elementType: 'MODX\\Revolution\\modPlugin'
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
        ,tbar: ['->',{
            xtype: 'modx-combo-eventgroup'
            ,name: 'group'
            ,id: 'modx-plugin-event-filter-group'
            ,itemId: 'group'
            ,emptyText: _('group')+'...'
            ,width: 200
            ,listeners: {
                'select': {fn:this.filterGroup,scope:this}
            }
        },{
            xtype: 'textfield'
            ,name: 'search'
            ,id: 'modx-plugin-event-search'
            ,cls: 'x-form-filter'
            ,emptyText: _('search')
            ,listeners: {
                'change': {fn: this.search, scope: this}
                ,'render': {fn: function(cmp) {
                    new Ext.KeyMap(cmp.getEl(), {
                        key: Ext.EventObject.ENTER
                        ,fn: this.blur
                        ,scope: cmp
                    });
                },scope:this}
            }
        },{
            xtype: 'button'
            ,id: 'modx-filter-clear'
            ,cls: 'x-form-filter-clear'
            ,text: _('filter_clear')
            ,listeners: {
                'click': {fn: this.clearFilter, scope: this},
                'mouseout': {fn: function () {this.removeClass('x-btn-focus')}}
            }
        }]
    });
    MODx.grid.PluginEvent.superclass.constructor.call(this,config);

    this.store.sortInfo = {
        field: 'enabled',
        direction: 'DESC'
    };
    this.addEvents('updateEvent');
};
Ext.extend(MODx.grid.PluginEvent,MODx.grid.Grid,{
    search: function(tf,newValue) {
        var nv = newValue || tf;
        this.getStore().baseParams.query = Ext.isEmpty(nv) || Ext.isObject(nv) ? '' : nv;
        this.getStore().load();
        return true;
    }
    ,filterGroup: function (cb,nv,ov) {
        this.getStore().baseParams.group = Ext.isEmpty(nv) || Ext.isObject(nv) ? cb.getValue() : nv;
        this.getStore().load();
        return true;
    }
    ,clearFilter: function() {
        delete this.getStore().baseParams.query;
        delete this.getStore().baseParams.group;
        Ext.getCmp('modx-plugin-event-search').reset();
        Ext.getCmp('modx-plugin-event-filter-group').reset();
        this.getStore().load();
    }
    ,updateEvent: function(btn,e) {
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
        title: _('edit')
        ,id: 'modx-window-plugin-event-update'
        ,url: MODx.config.connector_url
        ,action: 'Element/Plugin/Event/Associate'
        ,autoHeight: true // needed here or the window will always show a scrollbar
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
    this.on('beforeSubmit',this.beforeSubmit,this);
};
Ext.extend(MODx.window.UpdatePluginEvent,MODx.Window,{
    onShow: function() {
        var evt = this.fp.getForm().findField('name').getValue();
        MODx.Ajax.request({
            url: MODx.config.connector_url
            ,params: {
                action: 'Element/Plugin/Event/GetAssoc'
                ,'event': evt
            }
            ,listeners: {
                'success':{fn:function(r) {
                    var data = r.results;
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
            action: 'Element/Plugin/Event/Associate'
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
        ,url: MODx.config.connector_url
        ,baseParams: {
            action: 'Element/Plugin/Event/GetPlugins'
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
            ,editor: {
                xtype: 'modx-combo-property-set'
                ,renderer: true
                ,baseParams: {
                    action: 'Element/PropertySet/GetList'
                    ,showAssociated: true
                    ,elementId: config.plugin
                    ,elementType: 'MODX\\Revolution\\modPlugin'
                }
            }
        },{
            header: _('priority')
            ,dataIndex: 'priority'
            ,width: 100
            ,editor: { xtype: 'textfield' ,allowBlank: false }
        }]
        ,tbar: [{
            text: _('plugin_add')
            ,cls: 'primary-button'
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
        if (!sm.isSelected(ri)) {
            sm.selectRow(ri);
        }
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
        title: _('plugin_add')
        ,id: this.ident
        ,url: MODx.config.connector_url
        ,action: 'element/plugin/event/addplugin'
        ,autoHeight: true
        ,fields: [{
            xtype: 'modx-combo-plugin'
            ,fieldLabel: _('plugin')
            ,name: 'plugin'
            ,id: 'modx-'+this.ident+'-plugin'
            ,anchor: '100%'
            ,allowBlank: false
        },{
            xtype: 'numberfield'
            ,name: 'priority'
            ,fieldLabel: _('priority')
            ,id: 'modx-'+this.ident+'-priority'
            ,value: 0
            ,allowBlank: false
            ,anchor: '100%'
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
        url: MODx.config.connector_url
        ,baseParams: {
            action: 'Element/Plugin/GetList'
        }
        ,fields: ['id','name','description']
        ,name: 'plugin'
        ,hiddenName: 'plugin'
        ,displayField: 'name'
        ,valueField: 'id'
        ,editable: false
        ,tpl: new Ext.XTemplate('<tpl for="."><div class="x-combo-list-item"><span style="font-weight: bold">{name:htmlEncode}</span>'
                               ,'<br />{description:htmlEncode}</div></tpl>')
    });
    MODx.combo.Plugin.superclass.constructor.call(this,config);
};
Ext.extend(MODx.combo.Plugin,MODx.combo.ComboBox);
Ext.reg('modx-combo-plugin',MODx.combo.Plugin);
