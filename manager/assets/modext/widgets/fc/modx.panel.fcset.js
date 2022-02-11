/**
 * @class MODx.panel.FCSet
 * @extends MODx.FormPanel
 * @param {Object} config An object of configuration properties
 * @xtype modx-panel-fc-set
 */
MODx.panel.FCSet = function(config) {
    config = config || {};
    Ext.applyIf(config,{
        url: MODx.config.connector_url
        ,baseParams: {
            action: 'Security/Forms/Set/Update'
        }
        ,id: 'modx-panel-fc-set'
        ,class_key: 'MODX\\Revolution\\modFormCustomizationSet'
        ,cls: 'container'
        ,items: [this.getPageHeader(config), MODx.getPageStructure([{
            title: _('set_and_fields')
            ,xtype: 'panel'
            ,border: false
            ,defaults: { border: false }
            ,items: [{
                html: '<p>'+_('set_msg')+'</p>'
                ,id: 'modx-fcs-msg'
                ,xtype: 'modx-description'
            },{
                layout: 'form'
                ,id: 'modx-fcs-form'
                ,msgTarget: 'side'
                ,cls: 'main-wrapper'
                ,labelWidth: 150
                ,items: [{
                    xtype: 'hidden'
                    ,name: 'id'
                    ,id: 'modx-fcs-id'
                    ,value: config.record.id || MODx.request.id
                },{
                    xtype: 'modx-combo-fc-action'
                    ,fieldLabel: _('action')
                    ,name: 'action_id'
                    ,hiddenName: 'action_id'
                    ,id: 'modx-fcs-action'
                    ,anchor: '100%'
                    ,allowBlank: false
                    ,value: config.record.action
                    ,listeners: {
                        'select': {scope:this,fn:function(f,e) {
                            Ext.getCmp('modx-header-breadcrumbs').updateHeader(Ext.util.Format.htmlEncode(f.getRawValue()));
                        }}
                    }
                },{
                    xtype: 'modx-combo-template'
                    ,fieldLabel: _('template')
                    ,description: _('set_template_desc')
                    ,name: 'template'
                    ,hiddenName: 'template'
                    ,value: config.record.template || 0
                    ,anchor: '100%'
                    ,allowBlank: true
                    ,lazyInit: false
                    ,lazyRender: false
                    ,baseParams: {
                        action: 'Element/Template/GetList'
                        ,combo: true
                    }
                    ,listeners: {
                        'select': {fn:this.changeTemplate,scope:this}
                    }
                },{
                    xtype: 'textarea'
                    ,fieldLabel: _('description')
                    ,name: 'description'
                    ,id: 'modx-fcs-description'
                    ,anchor: '100%'
                    ,maxLength: 255
                    ,grow: false
                    ,value: config.record.description
                },{
                    xtype: 'hidden'
                    ,fieldLabel: _('constraint_class')
                    ,name: 'constraint_class'
                    ,value: 'MODX\\Revolution\\modResource'
                    ,anchor: '100%'
                    ,allowBlank: true
                },{
                    xtype: 'textfield'
                    ,fieldLabel: _('constraint_field')
                    ,description: _('set_constraint_field_desc')
                    ,name: 'constraint_field'
                    ,value: config.record.constraint_field
                    ,anchor: '100%'
                    ,allowBlank: true
                },{
                    xtype: 'textfield'
                    ,fieldLabel: _('constraint')
                    ,description: _('set_constraint_desc')
                    ,name: 'constraint'
                    ,value: config.record.constraint
                    ,anchor: '100%'
                    ,allowBlank: true
                },{
                    xtype: 'xcheckbox'
                    ,fieldLabel: _('active')
                    ,name: 'active'
                    ,inputValue: true
                    ,value: config.record.active ? true : false
                    ,anchor: '100%'
                    ,allowBlank: true
                }]
            },{
                html: '<p>'+_('set_fields_msg')+'</p>'
                ,xtype: 'modx-description'
            },{
                id: 'modx-fcs-fields-form'
                ,msgTarget: 'side'
                ,cls: 'main-wrapper'
                ,layout: 'anchor'
                ,items: [{
                    xtype: 'modx-grid-fc-set-fields'
                    ,data: config.record.fields || []
                    ,preventRender: true
                }]
            }]
        },{
            title: _('regions')
            ,border: false
            ,layout: 'anchor'
            ,items: [{
                html: '<p>'+_('set_tabs_msg')+'</p>'
                ,xtype: 'modx-description'
            },{
                xtype: 'modx-grid-fc-set-tabs'
                ,cls: 'main-wrapper'
                ,data: config.record.tabs || []
                ,preventRender: true
            }]
        },{
            title: _('tvs')
            ,border: false
            ,layout: 'anchor'
            ,items: [{
                html: '<p>'+_('set_tvs_msg')+'</p>'
                ,xtype: 'modx-description'
            },{
                xtype: 'modx-grid-fc-set-tvs'
                ,cls: 'main-wrapper'
                ,data: config.record.tvs || []
                ,preventRender: true
            }]
        }],{
            id: 'modx-fc-set-tabs'
        })]
        ,listeners: {
            'setup': {fn:this.setup,scope:this}
            ,'success': {fn:this.success,scope:this}
            ,'beforeSubmit': {fn:this.beforeSubmit,scope:this}
        }
    });
    MODx.panel.FCSet.superclass.constructor.call(this,config);
};
Ext.extend(MODx.panel.FCSet,MODx.FormPanel,{
    initialized: false

    ,setup: function() {
        if (!this.initialized) {this.getForm().setValues(this.config.record);}
        Ext.getCmp('modx-header-breadcrumbs').updateHeader(_('set'));

        this.fireEvent('ready',this.config.record);
        this.clearDirty();
        this.initialized = true;
        MODx.fireEvent('ready');
        return true;
    }

    ,beforeSubmit: function(o) {
        Ext.apply(o.form.baseParams,{
            fields: Ext.getCmp('modx-grid-fc-set-fields').encode()
            ,tabs: Ext.getCmp('modx-grid-fc-set-tabs').encode()
            ,tvs: Ext.getCmp('modx-grid-fc-set-tvs').encode()
        });
        return this.fireEvent('save',{
            values: this.getForm().getValues()
        });
    }

    ,success: function(r) {
        this.getForm().setValues(r.result.object);

        Ext.getCmp('modx-grid-fc-set-fields').getStore().commitChanges();
        Ext.getCmp('modx-grid-fc-set-tabs').getStore().commitChanges();
        Ext.getCmp('modx-grid-fc-set-tvs').getStore().commitChanges();
    }

    ,changeTemplate: function(cb) {
        if (cb.getValue() != this.config.record.template) {
            Ext.Msg.confirm(_('set_change_template'),_('set_change_template_confirm'),function(e) {
                if (e == 'yes') {
                    this.on('success',function() {
                        location.href = location.href;
                    },this);
                    this.submit();
                } else {
                    cb.setValue(this.config.record.template);
                }
            },this);
        }
        return false;
    }

    ,getPageHeader: function(config) {
        var profile = config.record.profile;
        return MODx.util.getHeaderBreadCrumbs('modx-fcs-header', [{
            text: _('form_customization'),
            href: MODx.getPage('security/forms')
        },{
            text: _('profile'),
            href: MODx.getPage('security/forms/profile/update&id='+profile)
        }]);
    }
});
Ext.reg('modx-panel-fc-set',MODx.panel.FCSet);

/**
 * @class MODx.grid.FCSetFields
 * @extends MODx.grid.LocalGrid
 * @param {Object} config An object of configuration properties
 * @xtype modx-grid-fc-set-fields
 */
MODx.grid.FCSetFields = function(config) {
    config = config || {};
    this.vcb = new Ext.ux.grid.CheckColumn({
        header: _('visible')
        ,dataIndex: 'visible'
        ,width: 40
        ,sortable: false
    });
    Ext.applyIf(config,{
        id: 'modx-grid-fc-set-fields'
        ,showActionsColumn: false
        ,fields: ['id','action','name','tab','tab_rank','other','rank','visible','label','default_value']
        ,autoHeight: true
        ,grouping: true
        ,groupBy: 'tab_rank'
        ,plugins: [this.vcb]
        ,stateful: false
        ,remoteSort: false
        ,sortBy: 'rank'
        ,sortDir: 'ASC'
        ,hideGroupedColumn: true
        ,groupTextTpl: '{[values.rs[0].data.tab]} ({[values.rs.length]} {[values.rs.length > 1 ? "'+_('fields')+'" : "'+_('field')+'"]})'
        ,columns: [{
            header: _('name')
            ,dataIndex: 'name'
            ,width: 200
        },{
            header: _('region')+' ('+_('tab_id')+')'
            ,dataIndex: 'tab'
            ,width: 100
        },{
            header: _('tab_rank')
            ,dataIndex: 'tab_rank'
            ,width: 100
        },this.vcb,{
            header: _('label')
            ,dataIndex: 'label'
            ,editor: { xtype: 'textfield' }
            ,renderer: function(v,md) {
                return Ext.util.Format.htmlEncode(v);
            }
        },{
            header: _('default_value')
            ,dataIndex: 'default_value'
            ,editor: { xtype: 'textfield' }
            ,renderer: function(v,md) {
                return Ext.util.Format.htmlEncode(v);
            }
        }]
        ,viewConfig: {
            forceFit:true
            ,enableRowBody:true
            ,scrollOffset: 0
            ,autoFill: true
            ,showPreview: true
            ,getRowClass : function(rec, ri, p){
                return rec.data.visible ? 'grid-row-active' : 'grid-row-inactive';
            }
        }
    });
    MODx.grid.FCSetFields.superclass.constructor.call(this,config);
    this.propRecord = Ext.data.Record.create(config.fields);
};
Ext.extend(MODx.grid.FCSetFields,MODx.grid.LocalGrid);
Ext.reg('modx-grid-fc-set-fields',MODx.grid.FCSetFields);

/**
 * @class MODx.grid.FCSetTabs
 * @extends MODx.grid.LocalGrid
 * @param {Object} config An object of configuration properties
 * @xtype modx-grid-fc-set-tabs
 */
MODx.grid.FCSetTabs = function(config) {
    config = config || {};
    this.vcb = new Ext.ux.grid.CheckColumn({
        header: _('visible')
        ,dataIndex: 'visible'
        ,width: 40
        ,sortable: false
    });
    Ext.applyIf(config,{
        id: 'modx-grid-fc-set-tabs'
        ,showActionsColumn: false
        ,fields: ['id','action','name','form','other','rank','visible','label','type']
        ,autoHeight: true
        ,plugins: [this.vcb]
        ,stateful: false
        ,columns: [{
            header: _('region')+' ('+_('tab_id')+')'
            ,dataIndex: 'name'
            ,width: 100
        },this.vcb,{
            header: _('tab_title')
            ,dataIndex: 'label'
            ,editor: { xtype: 'textfield' }
        }]
        ,viewConfig: {
            forceFit:true
            ,enableRowBody:true
            ,scrollOffset: 0
            ,autoFill: true
            ,showPreview: true
            ,getRowClass : function(rec, ri, p){
                return rec.data.visible ? 'grid-row-active' : 'grid-row-inactive';
            }
        }
        ,tbar: [{
            text: _('create')
            ,cls: 'primary-button'
            ,handler: this.createTab
            ,scope: this
        }]
    });
    MODx.grid.FCSetTabs.superclass.constructor.call(this,config);
    this.propRecord = Ext.data.Record.create(config.fields);
};
Ext.extend(MODx.grid.FCSetTabs,MODx.grid.LocalGrid,{
    getMenu: function(g,ri) {
        var rec = this.getStore().getAt(ri);
        if (rec.data.type == 'new') {
            return [{
                text: _('delete')
                ,handler: this.removeTab
                ,scope: this
            }];
        }
        return [];
    }

    ,createTab: function(btn,e) {
        if (!this.windows.ctab) {
            this.windows.ctab = MODx.load({
                xtype: 'modx-window-fc-set-add-tab'
                ,listeners: {
                    'success': {fn:function(r) {
                        var s = this.getStore();
                        var rec = new this.propRecord(r);
                        s.add(rec);
                    },scope:this}
                }
            });
        }
        this.windows.ctab.reset();
        this.windows.ctab.show(e.target);
    }

    ,removeTab: function(btn,e) {
        var rec = this.getSelectionModel().getSelected();
        Ext.Msg.confirm(_('delete'),_('tab_remove_confirm'),function(e) {
            if (e == 'yes') {
                this.getStore().remove(rec);
            }
        },this);
    }
});
Ext.reg('modx-grid-fc-set-tabs',MODx.grid.FCSetTabs);

/**
 * @class MODx.grid.FCSetTVs
 * @extends MODx.grid.LocalGrid
 * @param {Object} config An object of configuration properties
 * @xtype modx-grid-fc-set-tvs
 */
MODx.grid.FCSetTVs = function(config) {
    config = config || {};
    this.vcb = new Ext.ux.grid.CheckColumn({
        header: _('visible')
        ,dataIndex: 'visible'
        ,width: 40
        ,sortable: false
    });
    Ext.applyIf(config,{
        id: 'modx-grid-fc-set-tvs'
        ,showActionsColumn: false
        ,fields: ['id','name','tab','rank','visible','label','default_value','category','default_text']
        ,autoHeight: true
        ,grouping: true
        ,groupBy: 'category'
        ,sortBy: 'rank'
        ,sortDir: 'ASC'
        ,stateful: false
        ,groupTextTpl: '{group} ({[values.rs.length]} {[values.rs.length > 1 ? "'+_('tvs')+'" : "'+_('tv')+'"]})'
        ,plugins: [this.vcb]
        ,hideGroupedColumn: true
        ,columns: [{
            header: _('category')
            ,dataIndex: 'category'
        },{
            header: _('tv_name')
            ,dataIndex: 'name'
            ,width: 200
            ,renderer: { fn: function(v,md,record) {
                return this.renderLink(v, {
                    href: '?a=element/tv/update&id=' + record.data.id
                    ,target: '_blank'
                });
            }, scope: this }
        },this.vcb,{
            header: _('label')
            ,dataIndex: 'label'
            ,editor: { xtype: 'textfield' }
        },{
            header: _('default_value')
            ,dataIndex: 'default_value'
            ,editor: { xtype: 'textfield' }
            ,renderer: function(v) { return Ext.util.Format.htmlEncode(v); }
        },{
            header: _('original_value')
            ,dataIndex: 'default_text'
            ,editable: false
        },{
            header: _('region')+' ('+_('tab_id')+')'
            ,dataIndex: 'tab'
            ,width: 100
            ,editor: { xtype: 'textfield' }
        },{
            header: _('tab_rank')
            ,dataIndex: 'rank'
            ,width: 70
            ,editor: { xtype: 'textfield' }
        }]
        ,viewConfig: {
            forceFit:true
            ,enableRowBody:true
            ,scrollOffset: 0
            ,autoFill: true
            ,showPreview: true
            ,getRowClass : function(rec, ri, p){
                return rec.data.visible ? 'grid-row-active' : 'grid-row-inactive';
            }
        }
    });
    MODx.grid.FCSetTVs.superclass.constructor.call(this,config);
    this.propRecord = Ext.data.Record.create(config.fields);
};
Ext.extend(MODx.grid.FCSetTVs,MODx.grid.LocalGrid,{
});
Ext.reg('modx-grid-fc-set-tvs',MODx.grid.FCSetTVs);

/**
 * @class MODx.window.AddTabToSet
 * @extends MODx.Window
 * @param {Object} config An object of options.
 * @xtype modx-window-fc-set-add-tab
 */
MODx.window.AddTabToSet = function(config) {
    config = config || {};
    Ext.applyIf(config,{
        title: _('create')
        ,fields: [{
            xtype: 'hidden'
            ,name: 'container'
            ,value: 'modx-resource-tabs'
        },{
            xtype: 'hidden'
            ,name: 'visible'
            ,value: true
        },{
            xtype: 'hidden'
            ,name: 'type'
            ,value: 'new'
        },{
            xtype: 'textfield'
            ,name: 'name'
            ,fieldLabel: _('region')+' ('+_('tab_id')+')'
            ,id: 'modx-fcatab-id'
            ,allowBlank: false
            ,anchor: '100%'
        },{
            xtype: 'textfield'
            ,fieldLabel: _('tab_title')
            ,name: 'value'
            ,id: 'modx-fcatab-name'
            ,allowBlank: false
            ,anchor: '100%'
        }]
    });
    MODx.window.AddTabToSet.superclass.constructor.call(this,config);
};
Ext.extend(MODx.window.AddTabToSet,MODx.Window,{
    submit: function() {
        var rec = this.fp.getForm().getValues();

        var g = Ext.getCmp('modx-grid-fc-set-tabs');
        var s = g.getStore();
        var v = s.query('name',rec.name).items;
        if (v.length > 0) {
            MODx.msg.alert(_('error'),_('set_tab_err_ae'));
            return false;
        }
        rec.label = rec.value;
        rec.visible = true;
        rec.type = 'new';

        this.fireEvent('success',rec);
        this.hide();
        return false;
    }
});
Ext.reg('modx-window-fc-set-add-tab',MODx.window.AddTabToSet);
