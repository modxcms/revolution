MODx.grid.ActionDom = function(config) {
    config = config || {};
    this.sm = new Ext.grid.CheckboxSelectionModel();
    Ext.applyIf(config,{
        id: 'modx-grid-actiondom'
        ,url: MODx.config.connectors_url+'security/forms/rule.php'
        ,fields: ['id'
            ,'action','controller'
            ,'principal','principal_class'
            ,'name','description','xtype','container','rule','value'
            ,'constraint','constraint_class','constraint_field','active','perm']
        ,paging: true
        ,autosave: false
        ,sm: this.sm
        ,remoteSort: true
        ,columns: [this.sm,{
            header: _('id')
            ,dataIndex: 'id'
            ,width: 40
            ,sortable: true
        },{
            header: _('action')
            ,dataIndex: 'controller'
            ,width: 250
            ,sortable: true
        },{
            header: _('name')
            ,dataIndex: 'name'
            ,width: 200
            ,sortable: true
        },{
            header: _('description')
            ,dataIndex: 'description'
            ,width: 200
            ,sortable: true
        },{
            header: _('rule')
            ,dataIndex: 'rule'
            ,width: 150
            ,sortable: true
        },{
            header: _('value')
            ,dataIndex: 'value'
            ,width: 300
            ,sortable: true
        },{
            header: _('usergroup')
            ,dataIndex: 'principal'
            ,width: 200
            ,editor: { xtype: 'modx-combo-usergroup' ,renderer: true }
            ,editable: false
            ,sortable: true
        }]
        ,viewConfig: {
            forceFit:true
            ,enableRowBody:true
            ,scrollOffset: 0
            ,autoFill: true
            ,showPreview: true
            ,getRowClass : function(rec, ri, p){
                return rec.data.active ? 'grid-row-active' : 'grid-row-inactive';
            }
        }
        ,tbar: [{
            text: _('add')
            ,scope: this
            ,handler: { xtype: 'modx-window-actiondom-create' ,blankValues: true }
        },'-',{
            text: _('bulk_actions')
            ,menu: [{
                text: _('selected_activate')
                ,handler: this.activateSelected
                ,scope: this
            },{
                text: _('selected_deactivate')
                ,handler: this.deactivateSelected
                ,scope: this
            },'-',{
                text: _('selected_remove')
                ,handler: this.removeSelected
                ,scope: this
            }]
        }]
    });
    MODx.grid.ActionDom.superclass.constructor.call(this,config);
    this.on('render',function() { this.getStore().reload(); },this);
};
Ext.extend(MODx.grid.ActionDom,MODx.grid.Grid,{
    getMenu: function() {
        var r = this.getSelectionModel().getSelected();
        var p = r.data.perm;

        var m = [];
        if (p.indexOf('pedit') != -1) {
            m.push({
                text: _('edit')
                ,handler: this.updateRule
            },{
                text: _('duplicate')
                ,handler: this.duplicateRule
            },'-');
            if (r.data.active) {
                m.push({
                    text: _('deactivate')
                    ,handler: this.deactivateRule
                });
            } else {
                m.push({
                    text: _('activate')
                    ,handler: this.activateRule
                });
            }
        }
        if (p.indexOf('premove') != -1) {
            m.push('-',{
                text: _('remove')
                ,handler: this.confirm.createDelegate(this,['remove','rule_remove_confirm'])
            });
        }
        
        if (m.length > 0) {
            this.addContextMenuItem(m);
        }
    }
    ,updateRule: function(btn,e) {
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
    ,duplicateRule: function(btn,e) {
        MODx.Ajax.request({
            url: this.config.url
            ,params: {
                action: 'duplicate'
                ,id: this.menu.record.id
            }
            ,listeners: {
                'success': {fn:this.refresh,scope:this}
            }
        });
    }

    ,activateRule: function(btn,e) {
        MODx.Ajax.request({
            url: this.config.url
            ,params: {
                action: 'activate'
                ,id: this.menu.record.id
            }
            ,listeners: {
                'success': {fn:this.refresh,scope:this}
            }
        });
    }

    ,activateSelected: function() {
        var cs = this.getSelectedAsList();
        if (cs === false) return false;

        MODx.Ajax.request({
            url: this.config.url
            ,params: {
                action: 'activateMultiple'
                ,rules: cs
            }
            ,listeners: {
                'success': {fn:function(r) {
                    this.getSelectionModel().clearSelections(true);
                    this.refresh();
                },scope:this}
            }
        });
        return true;
    }
    ,deactivateRule: function(btn,e) {
        MODx.Ajax.request({
            url: this.config.url
            ,params: {
                action: 'deactivate'
                ,id: this.menu.record.id
            }
            ,listeners: {
                'success': {fn:this.refresh,scope:this}
            }
        });
    }
    ,deactivateSelected: function() {
        var cs = this.getSelectedAsList();
        if (cs === false) return false;

        MODx.Ajax.request({
            url: this.config.url
            ,params: {
                action: 'deactivateMultiple'
                ,rules: cs
            }
            ,listeners: {
                'success': {fn:function(r) {
                    this.getSelectionModel().clearSelections(true);
                    this.refresh();
                },scope:this}
            }
        });
        return true;
    }
    ,removeSelected: function() {
        var cs = this.getSelectedAsList();
        if (cs === false) return false;

        MODx.msg.confirm({
            title: _('rule_remove_multiple')
            ,text: _('rule_remove_multiple_confirm')
            ,url: this.config.url
            ,params: {
                action: 'removeMultiple'
                ,rules: cs
            }
            ,listeners: {
                'success': {fn:function(r) {
                    this.getSelectionModel().clearSelections(true);
                    this.refresh();
                },scope:this}
            }
        });
        return true;
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
            fieldLabel: _('name')
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
        },{
            fieldLabel: _('active')
            ,description: _('active_desc')
            ,name: 'active'
            ,id: 'modx-'+this.ident+'-active'
            ,xtype: 'checkbox'
            ,value: 1
            ,checked: true
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
            fieldLabel: _('name')
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
        },{
            fieldLabel: _('active')
            ,description: _('active_desc')
            ,name: 'active'
            ,id: 'modx-'+this.ident+'-active'
            ,xtype: 'checkbox'
            ,value: 1
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