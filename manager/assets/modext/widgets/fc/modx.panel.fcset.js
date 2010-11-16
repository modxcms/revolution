/**
 * @class MODx.panel.FCSet
 * @extends MODx.FormPanel
 * @param {Object} config An object of configuration properties
 * @xtype modx-panel-fc-set
 */
MODx.panel.FCSet = function(config) {
    config = config || {};
    Ext.applyIf(config,{
        url: MODx.config.connectors_url+'security/forms/set.php'
        ,baseParams: {}
        ,id: 'modx-panel-fc-set'
        ,class_key: 'modFormCustomizationSet'
        ,bodyStyle: ''
        ,items: [{
            html: '<h2>'+_('set_new')+'</h2>'
            ,border: false
            ,cls: 'modx-page-header'
            ,id: 'modx-fcs-header'
        },MODx.getPageStructure([{
            title: _('set_and_fields')
            ,bodyStyle: 'padding: 15px;'
            ,defaults: {border: false ,msgTarget: 'side'}
            ,layout: 'form'
            ,id: 'modx-fcs-form'
            ,labelWidth: 150
            ,items: [{
                html: '<p>'+_('set_msg')+'</p>'
                ,id: 'modx-fcs-msg'
                ,border: false
            },{
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
                ,anchor: '90%'
                ,allowBlank: false
                ,value: config.record.action
                ,listeners: {
                    'select': {scope:this,fn:function(f,e) {
                        Ext.getCmp('modx-fcs-header').getEl().update('<h2>'+_('set')+': '+f.getRawValue()+'</h2>');
                    }}
                }
            },{
                xtype: 'statictextfield'
                ,fieldLabel: _('template')
                ,description: _('set_template_desc')
                ,name: 'templatename'
                ,hiddenName: 'templatename'
                ,value: config.record.templatename
                ,hidden: config.record.template == 0 ? true : false
                ,anchor: '90%'
                ,allowBlank: true
                ,baseParams: {
                    action: 'getList'
                    ,combo: true
                }
            },{
                xtype: 'textarea'
                ,fieldLabel: _('description')
                ,name: 'description'
                ,id: 'modx-fcs-description'
                ,anchor: '90%'
                ,maxLength: 255
                ,grow: false
                ,value: config.record.description
            },{
                xtype: 'hidden'
                ,fieldLabel: _('constraint_class')
                ,name: 'constraint_class'
                ,value: 'modResource'
                ,anchor: '90%'
                ,allowBlank: true
            },{
                xtype: 'textfield'
                ,fieldLabel: _('constraint_field')
                ,description: _('set_constraint_field_desc')
                ,name: 'constraint_field'
                ,value: config.record.constraint_field
                ,anchor: '90%'
                ,allowBlank: true
            },{
                xtype: 'textfield'
                ,fieldLabel: _('constraint')
                ,description: _('set_constraint_desc')
                ,name: 'constraint'
                ,value: config.record.constraint
                ,anchor: '90%'
                ,allowBlank: true
            },{
                xtype: 'checkbox'
                ,fieldLabel: _('active')
                ,name: 'active'
                ,inputValue: true
                ,value: config.record.active ? true : false
                ,anchor: '90%'
                ,allowBlank: true
            },{
                html: '<hr /><p>'+_('set_fields_msg')+'</p>'
                ,border: false
            },{
                xtype: 'modx-grid-fc-set-fields'
                ,data: config.record.fields || []
                ,preventRender: true
            }]
        },{
            title: _('tabs')
            ,bodyStyle: { padding: '15px' }
            ,items: [{
                html: '<p>'+_('set_tabs_msg')+'</p>'
                ,border: false
            },{
                xtype: 'modx-grid-fc-set-tabs'
                ,data: config.record.tabs || []
                ,preventRender: true
            }]
        },{
            title: _('tvs')
            ,bodyStyle: { padding: '15px' }
            ,items: [{
                html: '<p>'+_('set_tvs_msg')+'</p>'
                ,border: false
            },{
                xtype: 'modx-grid-fc-set-tvs'
                ,data: config.record.tvs || []
                ,preventRender: true
            }]
        }],{
            id: 'modx-fc-set-tabs'
        })]
        ,useLoadingMask: true
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
        if (!Ext.isEmpty(this.config.record.controller)) {
            Ext.getCmp('modx-fcs-header').getEl().update('<h2>'+_('set')+': '+this.config.record.controller+'</h2>');
        }
        
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
});
Ext.reg('modx-panel-fc-set',MODx.panel.FCSet);


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
        ,fields: ['id','action','name','tab','tab_rank','other','rank','visible','label','default_value']
        ,autoHeight: true
        ,grouping: true
        ,groupBy: 'tab'
        ,plugins: [this.vcb]
        ,stateful: false
        ,remoteSort: false
        ,sortBy: 'rank'
        ,sortDir: 'ASC'
        ,hideGroupedColumn: true
        ,groupTextTpl: '{group} ({[values.rs.length]} {[values.rs.length > 1 ? "'+_('fields')+'" : "'+_('field')+'"]})'
        ,columns: [{
            header: _('name')
            ,dataIndex: 'name'
            ,width: 200
        },{
            header: _('tab')
            ,dataIndex: 'tab'
            ,width: 100
        },this.vcb,{
            header: _('label')
            ,dataIndex: 'label'
            ,editor: { xtype: 'textfield' }
        },{
            header: _('default_value')
            ,dataIndex: 'default_value'
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
    MODx.grid.FCSetFields.superclass.constructor.call(this,config);
    this.propRecord = Ext.data.Record.create(config.fields);
};
Ext.extend(MODx.grid.FCSetFields,MODx.grid.LocalGrid);
Ext.reg('modx-grid-fc-set-fields',MODx.grid.FCSetFields);


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
        ,fields: ['id','action','name','form','other','rank','visible','label']
        ,autoHeight: true
        ,plugins: [this.vcb]
        ,stateful: false
        ,columns: [{
            header: _('tab_name')
            ,dataIndex: 'name'
            ,width: 200
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
            text: _('tab_create')
            ,handler: this.createTab
            ,scope: this
        }]
    });
    MODx.grid.FCSetTabs.superclass.constructor.call(this,config);
    this.propRecord = Ext.data.Record.create(config.fields);
};
Ext.extend(MODx.grid.FCSetTabs,MODx.grid.LocalGrid,{
    createTab: function() {
        /* TODO: Code for adding a new tab */
    }
});
Ext.reg('modx-grid-fc-set-tabs',MODx.grid.FCSetTabs);



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
            header: _('tab')
            ,dataIndex: 'tab'
            ,width: 100
            ,editor: { xtype: 'textfield' }
        },{
            header: _('tab_rank')
            ,dataIndex: 'rank'
            ,width: 60
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