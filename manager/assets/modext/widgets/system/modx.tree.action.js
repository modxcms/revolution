/**
 * Generates a modAction Tree
 * 
 * @class MODx.tree.Action
 * @extends MODx.tree.Tree
 * @param {Object} config An object of options.
 * @xtype modx-tree-action
 */
MODx.tree.Action = function(config) {
    config = config || {};
    Ext.applyIf(config,{
        root_id: 'n_root_0'
        ,root_name: _('actions')
        ,rootVisible: true
        ,expandFirst: true
        ,enableDrag: true
        ,enableDrop: true
        ,ddAppendOnly: true
        ,ddGroup: 'modx-action'
        ,url: MODx.config.connectors_url + 'system/action.php'
        ,action: 'getNodes'
    });
    MODx.tree.Action.superclass.constructor.call(this,config);
};
Ext.extend(MODx.tree.Action,MODx.tree.Tree,{
    windows: {}

    ,getMenu: function(n,e) {
        var a = n.attributes;
        var ui = n.getUI();
        var m = [];
        switch (a.type) {
            case 'namespace':
                m.push({
                    text: _('action_create_here')
                    ,handler: this.createAction
                });
                break;
            case 'action':
                m.push({
                    text: _('action_update')
                    ,handler: this.updateAction
                });
                m.push({
                    text: _('action_create_here')
                    ,handler: this.createAction
                });
                m.push({
                    text: _('action_remove')
                    ,handler: this.removeAction
                });
                break;
        }
        return m;
    }

    ,refreshActionCombos: function() {
        var cb;
        var ls = ['modx-cact-parent','modx-cmen-action','modx-umen-action'];
        for (var i=0;i<ls.length;i++) {
            cb = Ext.getCmp(ls[i]);
            if (cb) { cb.store.reload(); }
        }
    }
    
    ,createAction: function(n,e) {
        var node = this.cm.activeNode.attributes;
        var r = node.data;
        if (node.type == 'namespace') {
            r = { 'namespace': r.name };
        } else {
            r = { 'parent': r.id ,'namespace': r.namespace };
        }
        
        if (!this.windows.create_action) {
            this.windows.create_action = MODx.load({
                xtype: 'modx-window-action-create'
                ,record: r
                ,listeners: {
                    'success': {fn:function(r) { 
                        this.refresh();
                        this.refreshActionCombos();
                    },scope:this}
                }
            });
        }
        this.windows.create_action.setValues(r);
        this.windows.create_action.show(e.target);
        return false;
    }
	
    ,updateAction: function(n,e) {
        var r = this.cm.activeNode.attributes.data;
        
        if (!this.windows.update_action) {
            this.windows.update_action = MODx.load({
                xtype: 'modx-window-action-update'
                ,record: r
                ,listeners: {
                    'success': {fn:function(r) {
                        this.refresh();
                        this.refreshActionCombos();
                    },scope:this}
                }
            });
        }
        this.windows.update_action.setValues(r);
        this.windows.update_action.show(e.target);        
    }

    ,removeAction: function(n,e) {
        MODx.msg.confirm({
            title: _('warning')
            ,text: _('action_confirm_remove')
            ,url: this.config.url
            ,params: {
                action: 'remove'
                ,id: this.cm.activeNode.attributes.pk
            }
            ,listeners: {
                'success':{fn:function() {
                    this.refresh();
                    this.refreshActionCombos();
                },scope:this}
            }
        });
    }
});
Ext.reg('modx-tree-action',MODx.tree.Action);

/** 
 * Generates the Create Action window
 * 
 * @class MODx.window.CreateAction
 * @extends MODx.Window
 * @param {Object} config An object of options.
 * @xtype modx-window-action-create
 */
MODx.window.CreateAction = function(config) {
    config = config || {};
    Ext.applyIf(config,{
        title: _('action_create')
        ,width: 430
        ,url: MODx.config.connectors_url+'system/action.php'
        ,action: 'create'
        ,fields: [{
            fieldLabel: _('controller')
            ,name: 'controller'
            ,xtype: 'textfield'
            ,anchor: '100%'
        },{
            fieldLabel: _('namespace')
            ,name: 'namespace'
            ,xtype: 'modx-combo-namespace'
            ,anchor: '100%'
            ,allowBlank: false
            ,value: 'core'
        },{
            fieldLabel: _('controller_parent')
            ,name: 'parent'
            ,hiddenName: 'parent'
            ,xtype: 'modx-combo-action'
            ,editable: false
            ,anchor: '100%'
            ,id: 'modx-cact-parent'
        }]
    });
    MODx.window.CreateAction.superclass.constructor.call(this,config);
};
Ext.extend(MODx.window.CreateAction,MODx.Window);
Ext.reg('modx-window-action-create',MODx.window.CreateAction);


/** 
 * Generates the Update Action window
 * 
 * @class MODx.window.UpdateAction
 * @extends MODx.Window
 * @param {Object} config An object of options.
 * @xtype modx-window-action-update
 */
MODx.window.UpdateAction = function(config) {
    config = config || {};
    Ext.applyIf(config,{
        title: _('action_update')
        ,width: 430
        ,url: MODx.config.connectors_url+'system/action.php'
        ,action: 'update'
        ,fields: [{
            name: 'id'
            ,xtype: 'hidden'
        },{
            name: 'parent'
            ,xtype: 'hidden'
        },{
            fieldLabel: _('controller')
            ,name: 'controller'
            ,xtype: 'textfield'
            ,anchor: '100%'
        },{
            fieldLabel: _('namespace')
            ,name: 'namespace'
            ,hiddenName: 'namespace'
            ,xtype: 'modx-combo-namespace'
            ,anchor: '100%'
            ,allowBlank: false
        },{
            fieldLabel: _('controller_parent')
            ,name: 'parent'
            ,hiddenName: 'parent'
            ,xtype: 'modx-combo-action'
            ,editable: false
            ,anchor: '100%'
        }]
    });
    MODx.window.UpdateAction.superclass.constructor.call(this,config);
};
Ext.extend(MODx.window.UpdateAction,MODx.Window);
Ext.reg('modx-window-action-update',MODx.window.UpdateAction);
