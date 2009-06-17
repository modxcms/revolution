/** 
 * Generates the Duplicate Resource window.
 *  
 * @class MODx.window.DuplicateResource
 * @extends MODx.Window
 * @param {Object} config An object of options.
 * @xtype modx-window-resource-duplicate
 */
MODx.window.DuplicateResource = function(config) {
	config = config || {};
	Ext.applyIf(config,{
		title: _('duplication_options')
		,width: 400
	});
	MODx.window.DuplicateResource.superclass.constructor.call(this,config);
};
Ext.extend(MODx.window.DuplicateResource,MODx.Window,{
	_loadForm: function() {
		if (this.checkIfLoaded(this.config.record)) {
			this.fp.getForm().baseParams = {
				action: 'duplicate'
				,id: this.config.resource
			};
			return false;
		}
		var items = [];
		
		if (this.config.is_folder) {
			items.push({
                xtype: 'checkbox'
                ,fieldLabel: _('duplicate_children')
                ,name: 'duplicate_children'
                ,checked: true
                ,listeners: {
                    'check': {fn: function(cb,checked) { 
                        if (checked) {
                            this.fp.getForm().findField('modx-dupres-name').disable();
                        } else {
                            this.fp.getForm().findField('modx-dupres-name').enable();
                        }
                    },scope:this}
                }
            });
		}
		items.push({
            xtype: 'textfield'
            ,id: 'modx-dupres-name'
            ,fieldLabel: _('resource_name_new')
            ,name: 'name'
            ,width: 150
            ,value: ''
            ,disabled: this.config.is_folder ? true : false
        });
		
		this.fp = this.createForm({
			url: this.config.url || MODx.config.connectors_url+'resource/index.php'
			,baseParams: this.config.baseParams || {
				action: 'duplicate'
				,id: this.config.resource
			}
			,labelWidth: 125
			,defaultType: 'textfield'
			,autoHeight: true
			,items: items
		});
		
		this.renderForm();
	}
});
Ext.reg('modx-window-resource-duplicate',MODx.window.DuplicateResource);

/** 
 * Generates the Create User Group window.
 *  
 * @class MODx.window.CreateUserGroup
 * @extends MODx.Window
 * @param {Object} config An object of options.
 * @xtype modx-window-usergroup-create
 */
MODx.window.CreateUserGroup = function(config) {
	config = config || {};
	Ext.applyIf(config,{
		title: _('create_user_group')
		,height: 150
		,width: 375
        ,url: MODx.config.connectors_url+'security/group.php'
        ,action: 'create'
        ,fields: [{
            fieldLabel: _('name')
            ,name: 'name'
            ,xtype: 'textfield'
            ,width: 150
        },{
            name: 'parent'
            ,xtype: 'hidden'
        }]
	});
	MODx.window.CreateUserGroup.superclass.constructor.call(this,config);
};
Ext.extend(MODx.window.CreateUserGroup,MODx.Window);
Ext.reg('modx-window-usergroup-create',MODx.window.CreateUserGroup);

/** 
 * Generates the Add User to User Group window.
 *  
 * @class MODx.window.AddUserToUserGroup
 * @extends MODx.Window
 * @param {Object} config An object of options.
 * @xtype modx-window-usergroup-adduser
 */
MODx.window.AddUserToUserGroup = function(config) {
	config = config || {};
	Ext.applyIf(config,{
		title: _('user_group_user_add')
		,height: 150
		,width: 375
        ,url: MODx.config.connectors_url+'security/group.php'
        ,action: 'addUser'
        ,fields: [{
            fieldLabel: _('name')
            ,name: 'member'
            ,hiddenName: 'member'
            ,xtype: 'modx-combo-user'
        },{
            name: 'user_group'
            ,xtype: 'hidden'
        }]
	});
	MODx.window.AddUserToUserGroup.superclass.constructor.call(this,config);
};
Ext.extend(MODx.window.AddUserToUserGroup,MODx.Window);
Ext.reg('modx-window-usergroup-adduser',MODx.window.AddUserToUserGroup);

/** 
 * Generates the Create Resource Group window.
 *  
 * @class MODx.window.CreateResourceGroup
 * @extends MODx.Window
 * @param {Object} config An object of options.
 * @xtype modx-window-resourcegroup-create
 */
MODx.window.CreateResourceGroup = function(config) {
	config = config || {};
	Ext.applyIf(config,{
		title: _('resource_group_create')
        ,id: 'modx-window-resourcegroup-create'
		,height: 150
		,width: 350
        ,url: MODx.config.connectors_url+'security/documentgroup.php'
        ,action: 'create'
        ,fields: [{
            fieldLabel: _('name')
            ,name: 'name'
            ,id: 'modx-crgrp-name'
            ,xtype: 'textfield'
            ,width: 150
        }]
	});
	MODx.window.CreateResourceGroup.superclass.constructor.call(this,config);
};
Ext.extend(MODx.window.CreateResourceGroup,MODx.Window);
Ext.reg('modx-window-resourcegroup-create',MODx.window.CreateResourceGroup);

/** 
 * Generates the Create Category window.
 *  
 * @class MODx.window.CreateCategory
 * @extends MODx.Window
 * @param {Object} config An object of options.
 * @xtype modx-window-category-create
 */
MODx.window.CreateCategory = function(config) {
	config = config || {};
	Ext.applyIf(config,{
		title: _('new_category')
        ,id: 'modx-window-category-create'
		,height: 150
		,width: 350
        ,url: MODx.config.connectors_url+'element/category.php'
        ,action: 'create'
        ,fields: [{
            fieldLabel: _('name')
            ,name: 'category'
            ,id: 'modx-ccat-category'
            ,xtype: 'textfield'
            ,width: 150
        },{
        	fieldLabel: 'Parent'
        	,name: 'parent'
        	,hiddenName: 'parent'
        	,id: 'modx-ccat-parent'
    		,xtype: 'modx-combo-category'
        	,width: 200
        }]
	});
	MODx.window.CreateCategory.superclass.constructor.call(this,config);
};
Ext.extend(MODx.window.CreateCategory,MODx.Window);
Ext.reg('modx-window-category-create',MODx.window.CreateCategory);


/**
 * Generates the create namespace window.
 *  
 * @class MODx.window.CreateNamespace
 * @extends MODx.Window
 * @param {Object} config An object of options.
 * @xtype modx-window-namespace-create
 */
MODx.window.CreateNamespace = function(config) {
    config = config || {};
    var r = config.record;
    Ext.applyIf(config,{
        title: _('namespace_create')
        ,width: 600
        ,url: MODx.config.connectors_url+'workspace/namespace.php'
        ,action: 'create'
        ,fields: [{
            xtype: 'textfield'
            ,fieldLabel: _('name')
            ,name: 'name'
            ,id: 'modx-cns-name'
            ,width: 250
            ,maxLength: 100
        },{
            xtype: 'textfield'
            ,fieldLabel: _('path')
            ,description: _('namespace_path_desc')
            ,name: 'path'
            ,id: 'modx-cns-path'
            ,width: 400
        }]
    });
    MODx.window.CreateNamespace.superclass.constructor.call(this,config);
};
Ext.extend(MODx.window.CreateNamespace,MODx.Window);
Ext.reg('modx-window-namespace-create',MODx.window.CreateNamespace);


MODx.window.QuickCreateChunk = function(config) {
	config = config || {};
	Ext.applyIf(config,{
		title: _('quick_create_chunk')
		,width: 600
		,url: MODx.config.connectors_url+'element/chunk.php'
		,action: 'create'
		,fields: [{
			xtype: 'hidden'
			,name: 'category'
			,id: 'modx-qcc-category'
		},{
            xtype: 'textfield'
            ,name: 'name'
            ,id: 'modx-qcc-name'
            ,fieldLabel: _('name')
            ,width: 300
		},{
            xtype: 'textarea'
            ,name: 'description'
            ,id: 'modx-qcc-description'
            ,fieldLabel: _('description')
            ,width: 300
            ,rows: 2
        },{
            xtype: 'textarea'
            ,name: 'snippet'
            ,id: 'modx-qcc-snippet'
            ,fieldLabel: _('code')
            ,width: 400
            ,grow: true
        }]
        ,keys: []
	});
	MODx.window.QuickCreateChunk.superclass.constructor.call(this,config);
};
Ext.extend(MODx.window.QuickCreateChunk,MODx.Window);
Ext.reg('modx-window-quick-create-chunk',MODx.window.QuickCreateChunk);

MODx.window.QuickUpdateChunk = function(config) {
    config = config || {};
    Ext.applyIf(config,{
        title: _('quick_update_chunk')
        ,width: 600
        ,url: MODx.config.connectors_url+'element/chunk.php'
        ,action: 'update'
        ,fields: [{
            xtype: 'hidden'
            ,name: 'id'
            ,id: 'modx-quc-id'
        },{
            xtype: 'hidden'
            ,name: 'category'
            ,id: 'modx-quc-category'
        },{
            xtype: 'textfield'
            ,name: 'name'
            ,id: 'modx-quc-name'
            ,fieldLabel: _('name')
            ,width: 300
        },{
            xtype: 'textarea'
            ,name: 'description'
            ,id: 'modx-quc-description'
            ,fieldLabel: _('description')
            ,width: 300
            ,rows: 2
        },{
            xtype: 'checkbox'
            ,name: 'clearCache'
            ,id: 'modx-quc-clearcache'
            ,fieldLabel: _('clear_cache_on_save')
            ,description: _('clear_cache_on_save_msg')
            ,inputValue: 1
            ,checked: true
        },{
            xtype: 'textarea'
            ,name: 'snippet'
            ,id: 'modx-quc-snippet'
            ,fieldLabel: _('code')
            ,width: 400
            ,height: 380 
        }]
        ,keys: []
    });
    MODx.window.QuickUpdateChunk.superclass.constructor.call(this,config);
};
Ext.extend(MODx.window.QuickUpdateChunk,MODx.Window);
Ext.reg('modx-window-quick-update-chunk',MODx.window.QuickUpdateChunk);



MODx.window.QuickCreateTemplate = function(config) {
    config = config || {};
    Ext.applyIf(config,{
        title: _('quick_create_template')
        ,width: 600
        ,url: MODx.config.connectors_url+'element/template.php'
        ,action: 'create'
        ,fields: [{
            xtype: 'hidden'
            ,name: 'category'
            ,id: 'modx-qct-category'
        },{
            xtype: 'textfield'
            ,name: 'templatename'
            ,id: 'modx-qct-name'
            ,fieldLabel: _('name')
            ,width: 300
        },{
            xtype: 'textarea'
            ,name: 'description'
            ,id: 'modx-qct-description'
            ,fieldLabel: _('description')
            ,width: 300
            ,rows: 2
        },{
            xtype: 'textarea'
            ,name: 'content'
            ,id: 'modx-qct-content'
            ,fieldLabel: _('code')
            ,width: 400
            ,grow: true
        }]
        ,keys: []
    });
    MODx.window.QuickCreateTemplate.superclass.constructor.call(this,config);
};
Ext.extend(MODx.window.QuickCreateTemplate,MODx.Window);
Ext.reg('modx-window-quick-create-template',MODx.window.QuickCreateTemplate);

MODx.window.QuickUpdateTemplate = function(config) {
    config = config || {};
    Ext.applyIf(config,{
        title: _('quick_update_template')
        ,width: 600
        ,url: MODx.config.connectors_url+'element/template.php'
        ,action: 'update'
        ,fields: [{
            xtype: 'hidden'
            ,name: 'id'
            ,id: 'modx-qut-id'
        },{
            xtype: 'textfield'
            ,name: 'templatename'
            ,id: 'modx-qut-name'
            ,fieldLabel: _('name')
            ,width: 300
        },{
            xtype: 'textarea'
            ,name: 'description'
            ,id: 'modx-qut-description'
            ,fieldLabel: _('description')
            ,width: 300
            ,rows: 2
        },{
            xtype: 'checkbox'
            ,name: 'clearCache'
            ,id: 'modx-qut-clearcache'
            ,fieldLabel: _('clear_cache_on_save')
            ,description: _('clear_cache_on_save_msg')
            ,inputValue: 1
            ,checked: true
        },{
            xtype: 'textarea'
            ,name: 'content'
            ,id: 'modx-qut-content'
            ,fieldLabel: _('code')
            ,width: 400
            ,height: 380 
        }]
        ,keys: []
    });
    MODx.window.QuickUpdateTemplate.superclass.constructor.call(this,config);
};
Ext.extend(MODx.window.QuickUpdateTemplate,MODx.Window);
Ext.reg('modx-window-quick-update-template',MODx.window.QuickUpdateTemplate);


MODx.window.QuickCreateSnippet = function(config) {
    config = config || {};
    Ext.applyIf(config,{
        title: _('quick_create_snippet')
        ,width: 600
        ,url: MODx.config.connectors_url+'element/snippet.php'
        ,action: 'create'
        ,fields: [{
            xtype: 'hidden'
            ,name: 'category'
            ,id: 'modx-qcs-category'
        },{
            xtype: 'textfield'
            ,name: 'name'
            ,id: 'modx-qcs-name'
            ,fieldLabel: _('name')
            ,width: 300
        },{
            xtype: 'textarea'
            ,name: 'description'
            ,id: 'modx-qcs-description'
            ,fieldLabel: _('description')
            ,width: 300
            ,rows: 2
        },{
            xtype: 'textarea'
            ,name: 'snippet'
            ,id: 'modx-qcs-snippet'
            ,fieldLabel: _('code')
            ,width: 400
            ,grow: true
        }]
        ,keys: []
    });
    MODx.window.QuickCreateSnippet.superclass.constructor.call(this,config);
};
Ext.extend(MODx.window.QuickCreateSnippet,MODx.Window);
Ext.reg('modx-window-quick-create-snippet',MODx.window.QuickCreateSnippet);

MODx.window.QuickUpdateSnippet = function(config) {
    config = config || {};
    Ext.applyIf(config,{
        title: _('quick_update_snippet')
        ,width: 600
        ,url: MODx.config.connectors_url+'element/snippet.php'
        ,action: 'update'
        ,fields: [{
            xtype: 'hidden'
            ,name: 'id'
            ,id: 'modx-qus-id'
        },{
            xtype: 'textfield'
            ,name: 'name'
            ,id: 'modx-qus-name'
            ,fieldLabel: _('name')
            ,width: 300
        },{
            xtype: 'textarea'
            ,name: 'description'
            ,id: 'modx-qus-description'
            ,fieldLabel: _('description')
            ,width: 300
            ,rows: 2
        },{
            xtype: 'checkbox'
            ,name: 'clearCache'
            ,id: 'modx-qus-clearcache'
            ,fieldLabel: _('clear_cache_on_save')
            ,description: _('clear_cache_on_save_msg')
            ,inputValue: 1
            ,checked: true
        },{
            xtype: 'textarea'
            ,name: 'snippet'
            ,id: 'modx-qus-snippet'
            ,fieldLabel: _('code')
            ,width: 400
            ,height: 380 
        }]
        ,keys: []
    });
    MODx.window.QuickUpdateSnippet.superclass.constructor.call(this,config);
};
Ext.extend(MODx.window.QuickUpdateSnippet,MODx.Window);
Ext.reg('modx-window-quick-update-snippet',MODx.window.QuickUpdateSnippet);



MODx.window.QuickCreatePlugin = function(config) {
    config = config || {};
    Ext.applyIf(config,{
        title: _('quick_create_plugin')
        ,width: 600
        ,url: MODx.config.connectors_url+'element/plugin.php'
        ,action: 'create'
        ,fields: [{
            xtype: 'hidden'
            ,name: 'category'
            ,id: 'modx-qcp-category'
        },{
            xtype: 'textfield'
            ,name: 'name'
            ,id: 'modx-qcp-name'
            ,fieldLabel: _('name')
            ,width: 300
        },{
            xtype: 'textarea'
            ,name: 'description'
            ,id: 'modx-qcp-description'
            ,fieldLabel: _('description')
            ,width: 300
            ,rows: 2
        },{
            xtype: 'textarea'
            ,name: 'plugincode'
            ,id: 'modx-qcp-plugincode'
            ,fieldLabel: _('code')
            ,width: 400
            ,grow: true
        }]
        ,keys: []
    });
    MODx.window.QuickCreatePlugin.superclass.constructor.call(this,config);
};
Ext.extend(MODx.window.QuickCreatePlugin,MODx.Window);
Ext.reg('modx-window-quick-create-plugin',MODx.window.QuickCreatePlugin);

MODx.window.QuickUpdatePlugin = function(config) {
    config = config || {};
    Ext.applyIf(config,{
        title: _('quick_update_plugin')
        ,width: 600
        ,url: MODx.config.connectors_url+'element/plugin.php'
        ,action: 'update'
        ,fields: [{
            xtype: 'hidden'
            ,name: 'id'
            ,id: 'modx-qup-id'
        },{
            xtype: 'textfield'
            ,name: 'name'
            ,id: 'modx-qup-name'
            ,fieldLabel: _('name')
            ,width: 300
        },{
            xtype: 'textarea'
            ,name: 'description'
            ,id: 'modx-qup-description'
            ,fieldLabel: _('description')
            ,width: 300
            ,rows: 2
        },{
            xtype: 'checkbox'
            ,name: 'clearCache'
            ,id: 'modx-qup-clearcache'
            ,fieldLabel: _('clear_cache_on_save')
            ,description: _('clear_cache_on_save_msg')
            ,inputValue: 1
            ,checked: true
        },{
            xtype: 'textarea'
            ,name: 'plugincode'
            ,id: 'modx-qup-plugincode'
            ,fieldLabel: _('code')
            ,width: 400
            ,height: 380 
        }]
        ,keys: []
    });
    MODx.window.QuickUpdatePlugin.superclass.constructor.call(this,config);
};
Ext.extend(MODx.window.QuickUpdatePlugin,MODx.Window);
Ext.reg('modx-window-quick-update-plugin',MODx.window.QuickUpdatePlugin);


MODx.window.QuickCreateTV = function(config) {
    config = config || {};
    Ext.applyIf(config,{
        title: _('quick_create_tv')
        ,width: 600
        ,url: MODx.config.connectors_url+'element/tv.php'
        ,action: 'create'
        ,fields: [{
            xtype: 'hidden'
            ,name: 'category'
            ,id: 'modx-qctv-category'
        },{
            xtype: 'textfield'
            ,name: 'name'
            ,id: 'modx-qctv-name'
            ,fieldLabel: _('name')
            ,width: 300
        },{
            xtype: 'textarea'
            ,name: 'description'
            ,id: 'modx-qctv-description'
            ,fieldLabel: _('description')
            ,width: 300
            ,rows: 2
        },{
            xtype: 'modx-combo-tv-input-type'
            ,fieldLabel: _('tv_type')
            ,name: 'type'
            ,id: 'modx-qctv-type'
        },{
            xtype: 'textfield'
            ,fieldLabel: _('tv_elements')
            ,name: 'els'
            ,id: 'modx-qctv-elements'
            ,width: 250
        },{
            xtype: 'textarea'
            ,fieldLabel: _('tv_default')
            ,name: 'default_text'
            ,id: 'modx-qctv-default-text'
            ,width: 300
            ,grow: true
        }]
        ,keys: []
    });
    MODx.window.QuickCreateTV.superclass.constructor.call(this,config);
};
Ext.extend(MODx.window.QuickCreateTV,MODx.Window);
Ext.reg('modx-window-quick-create-tv',MODx.window.QuickCreateTV);

MODx.window.QuickUpdateTV = function(config) {
    config = config || {};
    Ext.applyIf(config,{
        title: _('quick_update_tv')
        ,width: 600
        ,url: MODx.config.connectors_url+'element/tv.php'
        ,action: 'update'
        ,fields: [{
            xtype: 'hidden'
            ,name: 'id'
            ,id: 'modx-qutv-id'
        },{
            xtype: 'textfield'
            ,name: 'name'
            ,id: 'modx-qutv-name'
            ,fieldLabel: _('name')
            ,width: 300
        },{
            xtype: 'textarea'
            ,name: 'description'
            ,id: 'modx-qutv-description'
            ,fieldLabel: _('description')
            ,width: 300
            ,rows: 2
        },{
            xtype: 'checkbox'
            ,name: 'clearCache'
            ,id: 'modx-qutv-clearcache'
            ,fieldLabel: _('clear_cache_on_save')
            ,description: _('clear_cache_on_save_msg')
            ,inputValue: 1
            ,checked: true
        },{
            xtype: 'modx-combo-tv-input-type'
            ,fieldLabel: _('tv_type')
            ,name: 'type'
            ,id: 'modx-qutv-type'
        },{
            xtype: 'textfield'
            ,fieldLabel: _('tv_elements')
            ,name: 'els'
            ,id: 'modx-qutv-elements'
            ,width: 250
        },{
            xtype: 'textarea'
            ,fieldLabel: _('tv_default')
            ,name: 'default_text'
            ,id: 'modx-qutv-default-text'
            ,width: 300
            ,grow: true
        }]
        ,keys: []
    });
    MODx.window.QuickUpdateTV.superclass.constructor.call(this,config);
};
Ext.extend(MODx.window.QuickUpdateTV,MODx.Window);
Ext.reg('modx-window-quick-update-tv',MODx.window.QuickUpdateTV);