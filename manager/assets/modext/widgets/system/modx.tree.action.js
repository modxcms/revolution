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
        id: 'modx-tree-action'
        ,root_id: 'n_root_0'
        ,root_name: _('actions')
        ,rootVisible: true
        ,expandFirst: true
        ,enableDrag: true
        ,enableDrop: true
		,ddAppendOnly: true
        ,url: MODx.config.connectors_url + 'system/action.php'
		,action: 'getNodes'
    });
    MODx.tree.Action.superclass.constructor.call(this,config);
};
Ext.extend(MODx.tree.Action,MODx.tree.Tree,{
	windows: {}
    
	,createAction: function(n,e) {
        var node = this.cm.activeNode.attributes;
        var r = node.data;
        if (node.type == 'namespace') {
            r = { 'namespace': r.name };
        }
        
        if (!this.windows.create_action) {
            this.windows.create_action = MODx.load({
                xtype: 'modx-window-action-create'
                ,scope: this
                ,success: this.refresh
                ,record: r
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
                ,scope: this
                ,success: this.refresh
                ,record: r
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
                'success':{fn:this.refresh,scope:this}
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
            ,id: 'modx-cact-controller'
            ,xtype: 'textfield'
            ,width: 200
        },{
            fieldLabel: _('namespace')
            ,name: 'namespace'
            ,id: 'modx-cact-namespace'
            ,xtype: 'modx-combo-namespace'
            ,width: 200
            ,allowBlank: false
            ,value: 'core'
        },{
            fieldLabel: _('controller_parent')
            ,name: 'parent'
            ,hiddenName: 'parent'
            ,id: 'modx-cact-parent'
            ,xtype: 'modx-combo-action'
            ,editable: false
            ,width: 200
        },{
            fieldLabel: _('load_headers')
            ,name: 'haslayout'
            ,id: 'modx-cact-haslayout'
            ,xtype: 'checkbox'
            ,checked: true
            ,inputValue: 1
        },{
            fieldLabel: _('lang_topics')
            ,description: _('lang_topics_desc')
            ,name: 'lang_topics'
            ,id: 'modx-cact-lang-topics'
            ,xtype: 'textfield'
            ,width: 200
        },{
            fieldLabel: _('assets')
            ,name: 'assets'
            ,id: 'modx-cact-assets'
            ,xtype: 'textarea'
            ,width: 200
            ,grow: false
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
            ,id: 'modx-uact-id'
            ,xtype: 'hidden'
        },{
            name: 'parent'
            ,id: 'modx-uact-parent'
            ,xtype: 'hidden'
        },{
            fieldLabel: _('controller')
            ,name: 'controller'
            ,id: 'modx-uact-controller' 
            ,xtype: 'textfield'
            ,width: 200
        },{
            fieldLabel: _('namespace')
            ,name: 'namespace'
            ,hiddenName: 'namespace'
            ,id: 'modx-uact-namespace'
            ,xtype: 'modx-combo-namespace'
            ,width: 200
            ,allowBlank: false
        },{
            fieldLabel: _('controller_parent')
            ,name: 'parent'
            ,hiddenName: 'parent'
            ,id: 'modx-uact-parent-controller'
            ,xtype: 'modx-combo-action'
            ,readOnly: true
            ,editable: false
            ,width: 200
        },{
            fieldLabel: _('load_headers')
            ,name: 'haslayout'
            ,id: 'modx-uact-haslayout'
            ,xtype: 'checkbox'
            ,checked: true
        },{
            fieldLabel: _('lang_topics')
            ,description: _('lang_topics_desc')
            ,name: 'lang_topics'
            ,id: 'modx-uact-lang-topics'
            ,xtype: 'textfield'
            ,width: 200
        },{
            fieldLabel: _('assets')
            ,name: 'assets'
            ,id: 'modx-uact-assets'
            ,xtype: 'textarea'
            ,width: 200
            ,grow: false
        }]
	});
	MODx.window.UpdateAction.superclass.constructor.call(this,config);
};
Ext.extend(MODx.window.UpdateAction,MODx.Window);
Ext.reg('modx-window-action-update',MODx.window.UpdateAction);


/**
 * Displays a dropdown list of modActions.
 * 
 * @class MODx.combo.Action
 * @extends MODx.combo.ComboBox
 * @xtype modx-combo-action
 */
MODx.combo.Action = function(config) {
    config = config || {};
    Ext.applyIf(config,{
        name: 'action'
        ,hiddenName: 'action'
        ,displayField: 'controller'
        ,valueField: 'id'
        ,fields: ['id','controller']
        ,url: MODx.config.connectors_url+'system/action/index.php'
    });
    MODx.combo.Action.superclass.constructor.call(this,config);
};
Ext.extend(MODx.combo.Action,MODx.combo.ComboBox);
Ext.reg('modx-combo-action',MODx.combo.Action);

