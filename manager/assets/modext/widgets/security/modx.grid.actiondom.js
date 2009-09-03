MODx.grid.ActionDom = function(config) {
    config = config || {};    
    Ext.applyIf(config,{
        id: 'modx-grid-actiondom'
        ,url: MODx.config.connectors_url+'security/forms/rule.php'
        ,fields: ['id'
            ,'action','controller'
            ,'principal','principal_class'
            ,'name','description','xtype','container','rule','value'
            ,'constraint','constraint_class','constraint_field','menu']
        ,paging: true
        ,autosave: false
        ,columns: [{
            header: _('id')
            ,dataIndex: 'id'
            ,width: 40
        },{
            header: _('action')
            ,dataIndex: 'controller'
            ,width: 250
        },{
            header: _('name')
            ,dataIndex: 'name'
            ,width: 200
        },{
            header: _('description')
            ,dataIndex: 'description'
            ,width: 200
        },{
            header: _('rule')
            ,dataIndex: 'rule'
            ,width: 150
        },{
            header: _('value')
            ,dataIndex: 'value'
            ,width: 300
        },{
            header: _('usergroup')
            ,dataIndex: 'principal'
            ,width: 200
        }]
        ,tbar: [{
            text: _('add')
            ,scope: this
            ,handler: { xtype: 'modx-window-actiondom-create' ,blankValues: true }
        }]
    });
    MODx.grid.ActionDom.superclass.constructor.call(this,config);
    this.on('render',function() { this.getStore().reload(); },this);
};
Ext.extend(MODx.grid.ActionDom,MODx.grid.Grid,{      
    updateRule: function(btn,e) {
        var r = this.menu.record;
        r.action_id = r.action;
        this.loadWindow(btn,e,{
            xtype: 'modx-window-actiondom-update'
            ,record: r
            ,listeners: {
                'success': {fn:function(r) {
                    this.getStore().reload();
                },scope:this}
            }
        });
    }
});
Ext.reg('modx-grid-actiondom',MODx.grid.ActionDom);

MODx.window.CreateActionDom = function(config) {
    config = config || {};
    this.ident = config.ident || 'cadom'+Ext.id();
    Ext.applyIf(config,{
        width: 400
        ,title: _('rule_create')
        ,url: MODx.config.connectors_url+'security/forms/rule.php'
        ,action: 'create'
        ,fields: [{
            fieldLabel: _('action')
            ,description: _('action_desc')
            ,name: 'action_id'
            ,hiddenName: 'action_id'
            ,xtype: 'modx-combo-action'
            ,id: 'modx-'+this.ident+'-action'
            ,width: 200
        },{
            fieldLabel: _('usergroup')
            ,description: _('usergroup_desc')
            ,name: 'principal'
            ,hiddenName: 'principal'
            ,xtype: 'modx-combo-usergroup'
            ,baseParams: { action: 'getList' ,addNone: true }
            ,id: 'modx-'+this.ident+'-usergroup'
            ,width: 200
            
        },{
            fieldLabel: _('description')
            ,description: _('rule_description_desc')
            ,name: 'description'
            ,id: 'modx-'+this.ident+'-description'
            ,xtype: 'textarea'
            ,width: 200
            ,height: 50
            
        },{ html: '<hr />' },{
            fieldLabel: _('field')
            ,description: _('field_desc')
            ,name: 'name'
            ,id: 'modx-'+this.ident+'-name'
            ,xtype: 'textfield'
            ,width: 200
            
        },{
            fieldLabel: _('containing_panel')
            ,description: _('containing_panel_desc')
            ,name: 'container'
            ,id: 'modx-'+this.ident+'-container'
            ,xtype: 'textfield'
            ,width: 200
            
        },{
            fieldLabel: _('rule')
            ,description: _('rule_desc')
            ,name: 'rule'
            ,id: 'modx-'+this.ident+'-rule'
            ,xtype: 'modx-combo-rule-type'
            ,width: 200
            
        },{
            fieldLabel: _('value')
            ,description: _('rule_value_desc')
            ,name: 'value'
            ,id: 'modx-'+this.ident+'-value'
            ,xtype: 'textarea'
            ,width: 200
            ,height: 50
            
        },{ html: '<hr />' },{
            fieldLabel: _('constraint_class')
            ,description: _('constraint_class_desc')
            ,name: 'constraint_class'
            ,id: 'modx-'+this.ident+'-constraint-class'
            ,xtype: 'textfield'
            ,width: 200
        },{
            fieldLabel: _('constraint_field')
            ,description: _('constraint_field_desc')
            ,name: 'constraint_field'
            ,id: 'modx-'+this.ident+'-constraint-field'
            ,xtype: 'textfield'
            ,width: 200
        },{
            fieldLabel: _('constraint')
            ,description: _('constraint_desc')
            ,name: 'constraint'
            ,id: 'modx-'+this.ident+'-constraint'
            ,xtype: 'textfield'
            ,width: 200
        }]
    });
    MODx.window.CreateActionDom.superclass.constructor.call(this,config);
};
Ext.extend(MODx.window.CreateActionDom,MODx.Window);
Ext.reg('modx-window-actiondom-create',MODx.window.CreateActionDom);


MODx.window.UpdateActionDom = function(config) {
    config = config || {};
    this.ident = config.ident || 'uadom'+Ext.id();
    Ext.applyIf(config,{
        width: 400
        ,title: _('rule_update')
        ,url: MODx.config.connectors_url+'security/forms/rule.php'
        ,action: 'update'
        ,fields: [{
            name: 'id'
            ,xtype: 'hidden'
            ,id: 'modx-'+this.ident+'-id'
        },{
            fieldLabel: _('action')
            ,description: _('action_desc')
            ,name: 'action_id'
            ,hiddenName: 'action_id'
            ,xtype: 'modx-combo-action'
            ,id: 'modx-'+this.ident+'-action'
            ,width: 200
        },{
            fieldLabel: _('usergroup')
            ,description: _('usergroup_desc')
            ,name: 'principal'
            ,hiddenName: 'principal'
            ,xtype: 'modx-combo-usergroup'
            ,baseParams: { action: 'getList' ,addNone: true }
            ,id: 'modx-'+this.ident+'-usergroup'
            ,width: 200
            
        },{
            fieldLabel: _('description')
            ,description: _('rule_description_desc')
            ,name: 'description'
            ,id: 'modx-'+this.ident+'-description'
            ,xtype: 'textarea'
            ,width: 200
            ,height: 50
            
        },{ html: '<hr />' },{
            fieldLabel: _('field')
            ,description: _('field_desc')
            ,name: 'name'
            ,id: 'modx-'+this.ident+'-name'
            ,xtype: 'textfield'
            ,width: 200
            
        },{
            fieldLabel: _('containing_panel')
            ,description: _('containing_panel_desc')
            ,name: 'container'
            ,id: 'modx-'+this.ident+'-container'
            ,xtype: 'textfield'
            ,width: 200
            
        },{
            fieldLabel: _('rule')
            ,description: _('rule_desc')
            ,name: 'rule'
            ,id: 'modx-'+this.ident+'-rule'
            ,xtype: 'modx-combo-rule-type'
            ,width: 200
            
        },{
            fieldLabel: _('value')
            ,description: _('rule_value_desc')
            ,name: 'value'
            ,id: 'modx-'+this.ident+'-value'
            ,xtype: 'textarea'
            ,width: 200
            ,height: 50
            
        },{ html: '<hr />' },{
            fieldLabel: _('constraint_class')
            ,description: _('constraint_class_desc')
            ,name: 'constraint_class'
            ,id: 'modx-'+this.ident+'-constraint-class'
            ,xtype: 'textfield'
            ,width: 200
        },{
            fieldLabel: _('constraint_field')
            ,description: _('constraint_field_desc')
            ,name: 'constraint_field'
            ,id: 'modx-'+this.ident+'-constraint-field'
            ,xtype: 'textfield'
            ,width: 200
        },{
            fieldLabel: _('constraint')
            ,description: _('constraint_desc')
            ,name: 'constraint'
            ,id: 'modx-'+this.ident+'-constraint'
            ,xtype: 'textfield'
            ,width: 200
        }]
    });
    MODx.window.UpdateActionDom.superclass.constructor.call(this,config);
};
Ext.extend(MODx.window.UpdateActionDom,MODx.Window);
Ext.reg('modx-window-actiondom-update',MODx.window.UpdateActionDom);

MODx.combo.RuleTypes = function(config) {
    config = config || {};
    Ext.applyIf(config,{
        name: 'rule'
        ,hiddenName: 'rule'
        ,forceSelection: true
        ,typeAhead: false
        ,editable: false
        ,allowBlank: false
        ,listWidth: 300
        ,url: MODx.config.connectors_url+'security/forms/rule.php'
        ,baseParams: { action: 'getTypeList' }
    });
    MODx.combo.RuleTypes.superclass.constructor.call(this,config);
};
Ext.extend(MODx.combo.RuleTypes,MODx.combo.ComboBox);
Ext.reg('modx-combo-rule-type',MODx.combo.RuleTypes);