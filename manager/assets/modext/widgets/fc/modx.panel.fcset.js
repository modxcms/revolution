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
            title: _('set')
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
                xtype: 'modx-combo-action'
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
                xtype: 'modx-combo-template'
                ,fieldLabel: _('template')
                ,name: 'template'
                ,hiddenName: 'template'
                ,value: config.record.template
                ,anchor: '90%'
                ,allowBlank: true
                ,baseParams: {
                    action: 'getList'
                    ,combo: '1'
                }
            },{
                html: '<hr /><p>Adjust fields here.</p>'
                ,border: false
            },{
                xtype: 'modx-grid-fc-set-fields'
                ,data: config.record.fields || []
                ,preventRender: true
            }/*{
                layout:'column'
                ,border: false
                ,anchor: '97%'
                ,items:[{
                    columnWidth: .50
                    ,layout: 'form'
                    ,border: false
                    ,items: [{
                        xtype: 'treepanel'
                        ,useArrows: true
                        ,rootVisible: false
                        ,autoScroll: true
                        ,animate: true
                        ,enableDD: true
                        ,containerScroll: true
                        ,border: false
                        ,root: {
                            expanded: true
                            ,children: config.record.tree
                        }
                    }]
                },{
                    columnWidth: .50
                    ,layout: 'form'
                    ,border: false
                    ,items: [{
                        layout: 'form'
                        ,cls: 'modx-form'
                        ,border: false
                        ,defaults: {collapsible: false ,autoHeight: true, border: false}
                        ,items: [{
                            xtype: 'checkbox'
                            ,fieldLabel: 'Visible'
                            ,name: 'visible'
                        },{
                            xtype: 'textfield'
                            ,fieldLabel: 'Label'
                            ,name: 'label'
                            ,anchor: '95%'
                        },{
                            xtype: 'textarea'
                            ,fieldLabel: 'Default Value'
                            ,name: 'default_value'
                            ,anchor: '95%'
                        }]
                    }]
                }]
            }*/]
        },{
            title: 'Tabs'
            ,bodyStyle: { padding: '15px' }
            ,items: [{
                html: '<p>Adjust tabs here.</p>'
                ,border: false
            },{
                xtype: 'modx-grid-fc-set-tabs'
                ,data: config.record.tabs || []
                ,preventRender: true
            }]
        },{
            title: 'TVs'
            ,bodyStyle: { padding: '15px' }
            ,items: [{
                html: '<p>Adjust TVs here.</p>'
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
        /*
        if (!Ext.isEmpty(this.config.record.properties)) {
            var d = this.config.record.properties;
            var g = Ext.getCmp('modx-grid-element-properties');
            if (g) {
                g.defaultProperties = d;
                g.getStore().loadData(d);
            }
        }*/
        this.fireEvent('ready',this.config.record);
        this.clearDirty();
        this.initialized = true;
        MODx.fireEvent('ready');
        return true;
    }
    ,beforeSubmit: function(o) {
        Ext.apply(o.form.baseParams,{
            //propdata: Ext.getCmp('modx-grid-element-properties').encode()
        });
        return this.fireEvent('save',{
            values: this.getForm().getValues()
        });
    }
    ,success: function(r) {
        //if (MODx.request.id) Ext.getCmp('modx-grid-').save();
        this.getForm().setValues(r.result.object);
    }
});
Ext.reg('modx-panel-fc-set',MODx.panel.FCSet);


MODx.grid.FCSetFields = function(config) {
    config = config || {};
    this.vcb = new Ext.ux.grid.CheckColumn({
        header: 'Visible'
        ,dataIndex: 'visible'
        ,width: 40
        ,sortable: false
    });
    Ext.applyIf(config,{
        fields: ['id','action','name','tab','other','rank','visible','label','default_value']
        ,autoHeight: true
        ,grouping: true
        ,groupBy: 'tab'
        ,plugins: [this.vcb]
        ,columns: [{
            header: 'Name'
            ,dataIndex: 'name'
            ,width: 200
        },{
            header: 'Tab'
            ,dataIndex: 'tab'
            ,width: 100
        },this.vcb,{
            header: 'Label'
            ,dataIndex: 'label'
            ,editor: { xtype: 'textfield' }
        },{
            header: 'Default Value'
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
        header: 'Visible'
        ,dataIndex: 'visible'
        ,width: 40
        ,sortable: false
    });
    Ext.applyIf(config,{
        fields: ['id','action','name','form','other','rank','visible','label','default_value']
        ,autoHeight: true
        ,plugins: [this.vcb]
        ,columns: [{
            header: 'Name'
            ,dataIndex: 'name'
            ,width: 200
        },this.vcb,{
            header: 'Tab Title'
            ,dataIndex: 'label'
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
    MODx.grid.FCSetTabs.superclass.constructor.call(this,config);
    this.propRecord = Ext.data.Record.create(config.fields);
};
Ext.extend(MODx.grid.FCSetTabs,MODx.grid.LocalGrid,{

});
Ext.reg('modx-grid-fc-set-tabs',MODx.grid.FCSetTabs);



MODx.grid.FCSetTVs = function(config) {
    config = config || {};
    this.vcb = new Ext.ux.grid.CheckColumn({
        header: 'Visible'
        ,dataIndex: 'visible'
        ,width: 40
        ,sortable: false
    });
    Ext.applyIf(config,{
        fields: ['id','name','tab','rank','visible','label','default_value']
        ,autoHeight: true
        ,grouping: true
        ,groupBy: 'tab'
        ,plugins: [this.vcb]
        ,columns: [{
            header: 'Name'
            ,dataIndex: 'name'
            ,width: 200
        },{
            header: 'Tab'
            ,dataIndex: 'tab'
            ,width: 100
            ,editor: { xtype: 'textfield' }
        },this.vcb,{
            header: 'Label'
            ,dataIndex: 'label'
            ,editor: { xtype: 'textfield' }
        },{
            header: 'Default Value'
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
    MODx.grid.FCSetTVs.superclass.constructor.call(this,config);
    this.propRecord = Ext.data.Record.create(config.fields);
};
Ext.extend(MODx.grid.FCSetTVs,MODx.grid.LocalGrid,{
});
Ext.reg('modx-grid-fc-set-tvs',MODx.grid.FCSetTVs);