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
    this.ident = config.ident || 'dupres'+Ext.id();
    Ext.applyIf(config,{
        title: _('duplication_options')
        ,id: this.ident
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
                ,id: 'modx-'+this.ident+'-duplicate-children'
                ,checked: true
                ,listeners: {
                    'check': {fn: function(cb,checked) {
                        if (checked) {
                            this.fp.getForm().findField('modx-'+this.ident+'-name').disable();
                        } else {
                            this.fp.getForm().findField('modx-'+this.ident+'-name').enable();
                        }
                    },scope:this}
                }
            });
        }
        items.push({
            xtype: 'textfield'
            ,id: 'modx-'+this.ident+'-name'
            ,fieldLabel: _('resource_name_new')
            ,name: 'name'
            ,width: 150
            ,value: ''
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

MODx.window.CreateUserGroup = function(config) {
    config = config || {};
    this.ident = config.ident || 'cugrp'+Ext.id();
    Ext.applyIf(config,{
        title: _('create_user_group')
        ,id: this.ident
        ,height: 150
        ,width: 375
        ,url: MODx.config.connectors_url+'security/group.php'
        ,action: 'create'
        ,fields: [{
            fieldLabel: _('name')
            ,name: 'name'
            ,id: 'modx-'+this.ident+'-name'
            ,xtype: 'textfield'
            ,width: 150
        },{
            name: 'parent'
            ,id: 'modx-'+this.ident+'-parent'
            ,xtype: 'hidden'
        }]
    });
    MODx.window.CreateUserGroup.superclass.constructor.call(this,config);
};
Ext.extend(MODx.window.CreateUserGroup,MODx.Window);
Ext.reg('modx-window-usergroup-create',MODx.window.CreateUserGroup);

MODx.window.AddUserToUserGroup = function(config) {
    config = config || {};
    this.ident = config.ident || 'adtug'+Ext.id();
    Ext.applyIf(config,{
        title: _('user_group_user_add')
        ,id: this.ident
        ,height: 150
        ,width: 375
        ,url: MODx.config.connectors_url+'security/usergroup/user.php'
        ,action: 'create'
        ,fields: [{
            fieldLabel: _('name')
            ,name: 'user'
            ,hiddenName: 'user'
            ,xtype: 'modx-combo-user'
            ,id: 'modx-'+this.ident+'-user'
        },{
            fieldLabel: _('role')
            ,name: 'role'
            ,hiddenName: 'role'
            ,xtype: 'modx-combo-role'
            ,id: 'modx-'+this.ident+'-role'
            ,allowBlank: false
        },{
            name: 'usergroup'
            ,xtype: 'hidden'
            ,id: 'modx-'+this.ident+'-user-group'
        }]
    });
    MODx.window.AddUserToUserGroup.superclass.constructor.call(this,config);
};
Ext.extend(MODx.window.AddUserToUserGroup,MODx.Window);
Ext.reg('modx-window-usergroup-adduser',MODx.window.AddUserToUserGroup);

MODx.window.CreateResourceGroup = function(config) {
    config = config || {};
    this.ident = config.ident || 'crgrp'+Ext.id();
    Ext.applyIf(config,{
        title: _('resource_group_create')
        ,id: this.ident
        ,height: 150
        ,width: 350
        ,url: MODx.config.connectors_url+'security/documentgroup.php'
        ,action: 'create'
        ,fields: [{
            fieldLabel: _('name')
            ,name: 'name'
            ,id: 'modx-'+this.ident+'-name'
            ,xtype: 'textfield'
            ,width: 150
        }]
    });
    MODx.window.CreateResourceGroup.superclass.constructor.call(this,config);
};
Ext.extend(MODx.window.CreateResourceGroup,MODx.Window);
Ext.reg('modx-window-resourcegroup-create',MODx.window.CreateResourceGroup);

MODx.window.CreateCategory = function(config) {
    config = config || {};
    this.ident = config.ident || 'ccat'+Ext.id();
    Ext.applyIf(config,{
        title: _('new_category')
        ,id: this.ident
        ,height: 150
        ,width: 350
        ,url: MODx.config.connectors_url+'element/category.php'
        ,action: 'create'
        ,fields: [{
            fieldLabel: _('name')
            ,name: 'category'
            ,id: 'modx-'+this.ident+'-category'
            ,xtype: 'textfield'
            ,anchor: '85%'
        },{
            fieldLabel: 'Parent'
            ,name: 'parent'
            ,hiddenName: 'parent'
            ,id: 'modx-'+this.ident+'-parent'
            ,xtype: 'modx-combo-category'
            ,anchor: '85%'
        }]
    });
    MODx.window.CreateCategory.superclass.constructor.call(this,config);
};
Ext.extend(MODx.window.CreateCategory,MODx.Window);
Ext.reg('modx-window-category-create',MODx.window.CreateCategory);


MODx.window.CreateNamespace = function(config) {
    config = config || {};
    var r = config.record;
    this.ident = config.ident || 'cns'+Ext.id();
    Ext.applyIf(config,{
        title: _('namespace_create')
        ,id: this.ident
        ,width: 600
        ,url: MODx.config.connectors_url+'workspace/namespace.php'
        ,action: 'create'
        ,fields: [{
            xtype: 'textfield'
            ,fieldLabel: _('name')
            ,name: 'name'
            ,id: 'modx-'+this.ident+'-name'
            ,anchor: '70%'
            ,maxLength: 100
        },{
            xtype: 'textfield'
            ,fieldLabel: _('path')
            ,description: _('namespace_path_desc')
            ,name: 'path'
            ,id: 'modx-'+this.ident+'-path'
            ,anchor: '97%'
        }]
    });
    MODx.window.CreateNamespace.superclass.constructor.call(this,config);
};
Ext.extend(MODx.window.CreateNamespace,MODx.Window);
Ext.reg('modx-window-namespace-create',MODx.window.CreateNamespace);


MODx.window.QuickCreateChunk = function(config) {
    config = config || {};
    this.ident = config.ident || 'qcc'+Ext.id();
    Ext.applyIf(config,{
        title: _('quick_create_chunk')
        ,id: this.ident
        ,width: 600
        ,url: MODx.config.connectors_url+'element/chunk.php'
        ,action: 'create'
        ,fields: [{
            xtype: 'textfield'
            ,name: 'name'
            ,id: 'modx-'+this.ident+'-name'
            ,fieldLabel: _('name')
            ,anchor: '80%'
        },{
            xtype: 'modx-combo-category'
            ,name: 'category'
            ,fieldLabel: _('category')
            ,id: 'modx-'+this.ident+'-category'
            ,anchor: '70%'
        },{
            xtype: 'textarea'
            ,name: 'description'
            ,id: 'modx-'+this.ident+'-description'
            ,fieldLabel: _('description')
            ,anchor: '97%'
            ,rows: 2
        },{
            xtype: 'textarea'
            ,name: 'snippet'
            ,id: 'modx-'+this.ident+'-snippet'
            ,fieldLabel: _('code')
            ,anchor: '97%'
            ,grow: true
        }]
       ,keys: [{
            key: Ext.EventObject.ENTER
            ,shift: true
            ,fn: this.submit
            ,scope: this
        }]
    });
    MODx.window.QuickCreateChunk.superclass.constructor.call(this,config);
};
Ext.extend(MODx.window.QuickCreateChunk,MODx.Window);
Ext.reg('modx-window-quick-create-chunk',MODx.window.QuickCreateChunk);

MODx.window.QuickUpdateChunk = function(config) {
    config = config || {};
    this.ident = config.ident || 'quc'+Ext.id();
    Ext.applyIf(config,{
        title: _('quick_update_chunk')
        ,id: this.ident
        ,width: 600
        ,url: MODx.config.connectors_url+'element/chunk.php'
        ,action: 'update'
        ,fields: [{
            xtype: 'hidden'
            ,name: 'id'
            ,id: 'modx-'+this.ident+'-id'
        },{
            xtype: 'textfield'
            ,name: 'name'
            ,id: 'modx-'+this.ident+'-name'
            ,fieldLabel: _('name')
            ,anchor: '80%'
        },{
            xtype: 'modx-combo-category'
            ,name: 'category'
            ,fieldLabel: _('category')
            ,id: 'modx-'+this.ident+'-category'
            ,anchor: '70%'
        },{
            xtype: 'textarea'
            ,name: 'description'
            ,id: 'modx-'+this.ident+'-description'
            ,fieldLabel: _('description')
            ,anchor: '97%'
            ,rows: 2
        },{
            xtype: 'checkbox'
            ,name: 'clearCache'
            ,id: 'modx-'+this.ident+'-clearcache'
            ,fieldLabel: _('clear_cache_on_save')
            ,description: _('clear_cache_on_save_msg')
            ,inputValue: 1
            ,checked: true
        },{
            xtype: 'textarea'
            ,name: 'snippet'
            ,id: 'modx-'+this.ident+'-snippet'
            ,fieldLabel: _('code')
            ,anchor: '97%'
            ,grow: true ,growMax: 380
        }]
       ,keys: [{
            key: Ext.EventObject.ENTER
            ,shift: true
            ,fn: this.submit
            ,scope: this
        }]
    });
    MODx.window.QuickUpdateChunk.superclass.constructor.call(this,config);
};
Ext.extend(MODx.window.QuickUpdateChunk,MODx.Window);
Ext.reg('modx-window-quick-update-chunk',MODx.window.QuickUpdateChunk);

MODx.window.QuickCreateTemplate = function(config) {
    config = config || {};
    this.ident = config.ident || 'qct'+Ext.id();
    Ext.applyIf(config,{
        title: _('quick_create_template')
        ,id: this.ident
        ,width: 600
        ,url: MODx.config.connectors_url+'element/template.php'
        ,action: 'create'
        ,fields: [{
            xtype: 'textfield'
            ,name: 'templatename'
            ,id: 'modx-'+this.ident+'-name'
            ,fieldLabel: _('name')
            ,anchor: '80%'
        },{
            xtype: 'modx-combo-category'
            ,name: 'category'
            ,fieldLabel: _('category')
            ,id: 'modx-'+this.ident+'-category'
            ,anchor: '70%'
        },{
            xtype: 'textarea'
            ,name: 'description'
            ,id: 'modx-'+this.ident+'-description'
            ,fieldLabel: _('description')
            ,anchor: '97%'
            ,rows: 2
        },{
            xtype: 'textarea'
            ,name: 'content'
            ,id: 'modx-'+this.ident+'-content'
            ,fieldLabel: _('code')
            ,anchor: '97%'
            ,grow: true ,growMax: 380
        }]
       ,keys: [{
            key: Ext.EventObject.ENTER
            ,shift: true
            ,fn: this.submit
            ,scope: this
        }]
    });
    MODx.window.QuickCreateTemplate.superclass.constructor.call(this,config);
};
Ext.extend(MODx.window.QuickCreateTemplate,MODx.Window);
Ext.reg('modx-window-quick-create-template',MODx.window.QuickCreateTemplate);

MODx.window.QuickUpdateTemplate = function(config) {
    config = config || {};
    this.ident = config.ident || 'qut'+Ext.id();
    Ext.applyIf(config,{
        title: _('quick_update_template')
        ,id: this.ident
        ,width: 600
        ,url: MODx.config.connectors_url+'element/template.php'
        ,action: 'update'
        ,fields: [{
            xtype: 'hidden'
            ,name: 'id'
            ,id: 'modx-'+this.ident+'-id'
        },{
            xtype: 'textfield'
            ,name: 'templatename'
            ,id: 'modx-'+this.ident+'-name'
            ,fieldLabel: _('name')
            ,anchor: '80%'
        },{
            xtype: 'modx-combo-category'
            ,name: 'category'
            ,fieldLabel: _('category')
            ,id: 'modx-'+this.ident+'-category'
            ,anchor: '70%'
        },{
            xtype: 'textarea'
            ,name: 'description'
            ,id: 'modx-'+this.ident+'-description'
            ,fieldLabel: _('description')
            ,anchor: '97%'
            ,rows: 2
        },{
            xtype: 'checkbox'
            ,name: 'clearCache'
            ,id: 'modx-'+this.ident+'-clearcache'
            ,fieldLabel: _('clear_cache_on_save')
            ,description: _('clear_cache_on_save_msg')
            ,inputValue: 1
            ,checked: true
        },{
            xtype: 'textarea'
            ,name: 'content'
            ,id: 'modx-'+this.ident+'-content'
            ,fieldLabel: _('code')
            ,anchor: '97%'
            ,grow: true ,growMax: 380
        }]
       ,keys: [{
            key: Ext.EventObject.ENTER
            ,shift: true
            ,fn: this.submit
            ,scope: this
        }]
    });
    MODx.window.QuickUpdateTemplate.superclass.constructor.call(this,config);
};
Ext.extend(MODx.window.QuickUpdateTemplate,MODx.Window);
Ext.reg('modx-window-quick-update-template',MODx.window.QuickUpdateTemplate);


MODx.window.QuickCreateSnippet = function(config) {
    config = config || {};
    this.ident = config.ident || 'qcs'+Ext.id();
    Ext.applyIf(config,{
        title: _('quick_create_snippet')
        ,id: this.ident
        ,width: 600
        ,url: MODx.config.connectors_url+'element/snippet.php'
        ,action: 'create'
        ,fields: [{
            xtype: 'textfield'
            ,name: 'name'
            ,id: 'modx-'+this.ident+'-name'
            ,fieldLabel: _('name')
            ,anchor: '80%'
        },{
            xtype: 'modx-combo-category'
            ,name: 'category'
            ,fieldLabel: _('category')
            ,id: 'modx-'+this.ident+'-category'
            ,anchor: '70%'
        },{
            xtype: 'textarea'
            ,name: 'description'
            ,id: 'modx-'+this.ident+'-description'
            ,fieldLabel: _('description')
            ,anchor: '97%'
            ,rows: 2
        },{
            xtype: 'textarea'
            ,name: 'snippet'
            ,id: 'modx-'+this.ident+'-snippet'
            ,fieldLabel: _('code')
            ,anchor: '97%'
            ,grow: true ,growMax: 380
        }]
       ,keys: [{
            key: Ext.EventObject.ENTER
            ,shift: true
            ,fn: this.submit
            ,scope: this
        }]
    });
    MODx.window.QuickCreateSnippet.superclass.constructor.call(this,config);
};
Ext.extend(MODx.window.QuickCreateSnippet,MODx.Window);
Ext.reg('modx-window-quick-create-snippet',MODx.window.QuickCreateSnippet);

MODx.window.QuickUpdateSnippet = function(config) {
    config = config || {};
    this.ident = config.ident || 'qus'+Ext.id();
    Ext.applyIf(config,{
        title: _('quick_update_snippet')
        ,id: this.ident
        ,width: 600
        ,url: MODx.config.connectors_url+'element/snippet.php'
        ,action: 'update'
        ,fields: [{
            xtype: 'hidden'
            ,name: 'id'
            ,id: 'modx-'+this.ident+'-id'
        },{
            xtype: 'textfield'
            ,name: 'name'
            ,id: 'modx-'+this.ident+'-name'
            ,fieldLabel: _('name')
            ,anchor: '80%'
        },{
            xtype: 'modx-combo-category'
            ,name: 'category'
            ,fieldLabel: _('category')
            ,id: 'modx-'+this.ident+'-category'
            ,anchor: '70%'
        },{
            xtype: 'textarea'
            ,name: 'description'
            ,id: 'modx-'+this.ident+'-description'
            ,fieldLabel: _('description')
            ,anchor: '97%'
            ,rows: 2
        },{
            xtype: 'checkbox'
            ,name: 'clearCache'
            ,id: 'modx-'+this.ident+'-clearcache'
            ,fieldLabel: _('clear_cache_on_save')
            ,description: _('clear_cache_on_save_msg')
            ,inputValue: 1
            ,checked: true
        },{
            xtype: 'textarea'
            ,name: 'snippet'
            ,id: 'modx-'+this.ident+'-snippet'
            ,fieldLabel: _('code')
            ,anchor: '97%'
            ,grow: true ,growMax: 380
        }]
       ,keys: [{
            key: Ext.EventObject.ENTER
            ,shift: true
            ,fn: this.submit
            ,scope: this
        }]
    });
    MODx.window.QuickUpdateSnippet.superclass.constructor.call(this,config);
};
Ext.extend(MODx.window.QuickUpdateSnippet,MODx.Window);
Ext.reg('modx-window-quick-update-snippet',MODx.window.QuickUpdateSnippet);



MODx.window.QuickCreatePlugin = function(config) {
    config = config || {};
    this.ident = config.ident || 'qcp'+Ext.id();
    Ext.applyIf(config,{
        title: _('quick_create_plugin')
        ,id: this.ident
        ,width: 600
        ,url: MODx.config.connectors_url+'element/plugin.php'
        ,action: 'create'
        ,fields: [{
            xtype: 'textfield'
            ,name: 'name'
            ,id: 'modx-'+this.ident+'-name'
            ,fieldLabel: _('name')
            ,anchor: '80%'
        },{
            xtype: 'modx-combo-category'
            ,name: 'category'
            ,fieldLabel: _('category')
            ,id: 'modx-'+this.ident+'-category'
            ,anchor: '70%'
        },{
            xtype: 'textarea'
            ,name: 'description'
            ,id: 'modx-'+this.ident+'-description'
            ,fieldLabel: _('description')
            ,anchor: '97%'
            ,rows: 2
        },{
            xtype: 'checkbox'
            ,name: 'disabled'
            ,id: 'modx-'+this.ident+'-disabled'
            ,fieldLabel: _('disabled')
            ,inputValue: 1
            ,checked: false
        },{
            xtype: 'textarea'
            ,name: 'plugincode'
            ,id: 'modx-'+this.ident+'-plugincode'
            ,fieldLabel: _('code')
            ,anchor: '97%'
            ,grow: true ,growMax: 380
        }]
       ,keys: [{
            key: Ext.EventObject.ENTER
            ,shift: true
            ,fn: this.submit
            ,scope: this
        }]
    });
    MODx.window.QuickCreatePlugin.superclass.constructor.call(this,config);
};
Ext.extend(MODx.window.QuickCreatePlugin,MODx.Window);
Ext.reg('modx-window-quick-create-plugin',MODx.window.QuickCreatePlugin);

MODx.window.QuickUpdatePlugin = function(config) {
    config = config || {};
    this.ident = config.ident || 'qup'+Ext.id();
    Ext.applyIf(config,{
        title: _('quick_update_plugin')
        ,id: this.ident
        ,width: 600
        ,url: MODx.config.connectors_url+'element/plugin.php'
        ,action: 'update'
        ,fields: [{
            xtype: 'hidden'
            ,name: 'id'
            ,id: 'modx-'+this.ident+'-id'
        },{
            xtype: 'textfield'
            ,name: 'name'
            ,id: 'modx-'+this.ident+'-name'
            ,fieldLabel: _('name')
            ,anchor: '80%'
        },{
            xtype: 'modx-combo-category'
            ,name: 'category'
            ,fieldLabel: _('category')
            ,id: 'modx-'+this.ident+'-category'
            ,anchor: '70%'
        },{
            xtype: 'textarea'
            ,name: 'description'
            ,id: 'modx-'+this.ident+'-description'
            ,fieldLabel: _('description')
            ,anchor: '97%'
            ,rows: 2
        },{
            xtype: 'checkbox'
            ,name: 'disabled'
            ,id: 'modx-'+this.ident+'-disabled'
            ,fieldLabel: _('disabled')
            ,inputValue: 1
            ,checked: false
        },{
            xtype: 'checkbox'
            ,name: 'clearCache'
            ,id: 'modx-'+this.ident+'-clearcache'
            ,fieldLabel: _('clear_cache_on_save')
            ,description: _('clear_cache_on_save_msg')
            ,inputValue: 1
            ,checked: true
        },{
            xtype: 'textarea'
            ,name: 'plugincode'
            ,id: 'modx-'+this.ident+'-plugincode'
            ,fieldLabel: _('code')
            ,anchor: '97%'
            ,grow: true ,growMax: 380
        }]
       ,keys: [{
            key: Ext.EventObject.ENTER
            ,shift: true
            ,fn: this.submit
            ,scope: this
        }]
    });
    MODx.window.QuickUpdatePlugin.superclass.constructor.call(this,config);
};
Ext.extend(MODx.window.QuickUpdatePlugin,MODx.Window);
Ext.reg('modx-window-quick-update-plugin',MODx.window.QuickUpdatePlugin);


MODx.window.QuickCreateTV = function(config) {
    config = config || {};
    this.ident = config.ident || 'qctv'+Ext.id();
    Ext.applyIf(config,{
        title: _('quick_create_tv')
        ,id: this.ident
        ,width: 600
        ,url: MODx.config.connectors_url+'element/tv.php'
        ,action: 'create'
        ,fields: [{
            xtype: 'textfield'
            ,name: 'name'
            ,id: 'modx-'+this.ident+'-name'
            ,fieldLabel: _('name')
            ,anchor: '80%'
        },{
            xtype: 'modx-combo-category'
            ,name: 'category'
            ,fieldLabel: _('category')
            ,id: 'modx-'+this.ident+'-category'
            ,anchor: '70%'
        },{
            xtype: 'textarea'
            ,name: 'description'
            ,id: 'modx-'+this.ident+'-description'
            ,fieldLabel: _('description')
            ,anchor: '97%'
            ,rows: 2
        },{
            xtype: 'modx-combo-tv-input-type'
            ,fieldLabel: _('tv_type')
            ,name: 'type'
            ,id: 'modx-'+this.ident+'-type'
            ,anchor: '70%'
        },{
            xtype: 'textfield'
            ,fieldLabel: _('tv_elements')
            ,name: 'els'
            ,id: 'modx-'+this.ident+'-elements'
            ,anchor: '80%'
        },{
            xtype: 'textarea'
            ,fieldLabel: _('tv_default')
            ,name: 'default_text'
            ,id: 'modx-'+this.ident+'-default-text'
            ,anchor: '97%'
            ,grow: true ,growMax: 380
        }]
       ,keys: [{
            key: Ext.EventObject.ENTER
            ,shift: true
            ,fn: this.submit
            ,scope: this
        }]
    });
    MODx.window.QuickCreateTV.superclass.constructor.call(this,config);
};
Ext.extend(MODx.window.QuickCreateTV,MODx.Window);
Ext.reg('modx-window-quick-create-tv',MODx.window.QuickCreateTV);

MODx.window.QuickUpdateTV = function(config) {
    config = config || {};
    this.ident = config.ident || 'qutv'+Ext.id();
    Ext.applyIf(config,{
        title: _('quick_update_tv')
        ,id: this.ident
        ,width: 600
        ,url: MODx.config.connectors_url+'element/tv.php'
        ,action: 'update'
        ,fields: [{
            xtype: 'hidden'
            ,name: 'id'
            ,id: 'modx-'+this.ident+'-id'
        },{
            xtype: 'textfield'
            ,name: 'name'
            ,id: 'modx-'+this.ident+'-name'
            ,fieldLabel: _('name')
            ,anchor: '80%'
        },{
            xtype: 'modx-combo-category'
            ,name: 'category'
            ,fieldLabel: _('category')
            ,id: 'modx-'+this.ident+'-category'
            ,anchor: '70%'
        },{
            xtype: 'textarea'
            ,name: 'description'
            ,id: 'modx-'+this.ident+'-description'
            ,fieldLabel: _('description')
            ,anchor: '97%'
            ,rows: 2
        },{
            xtype: 'checkbox'
            ,name: 'clearCache'
            ,id: 'modx-'+this.ident+'-clearcache'
            ,fieldLabel: _('clear_cache_on_save')
            ,description: _('clear_cache_on_save_msg')
            ,inputValue: 1
            ,checked: true
        },{
            xtype: 'modx-combo-tv-input-type'
            ,fieldLabel: _('tv_type')
            ,name: 'type'
            ,id: 'modx-'+this.ident+'-type'
            ,anchor: '70%'
        },{
            xtype: 'textfield'
            ,fieldLabel: _('tv_elements')
            ,name: 'els'
            ,id: 'modx-'+this.ident+'-elements'
            ,anchor: '80%'
        },{
            xtype: 'textarea'
            ,fieldLabel: _('tv_default')
            ,name: 'default_text'
            ,id: 'modx-'+this.ident+'-default-text'
            ,anchor: '97%'
            ,grow: true ,growMax: 380
        }]
       ,keys: [{
            key: Ext.EventObject.ENTER
            ,shift: true
            ,fn: this.submit
            ,scope: this
        }]
    });
    MODx.window.QuickUpdateTV.superclass.constructor.call(this,config);
};
Ext.extend(MODx.window.QuickUpdateTV,MODx.Window);
Ext.reg('modx-window-quick-update-tv',MODx.window.QuickUpdateTV);


MODx.window.DuplicateContext = function(config) {
    config = config || {};
    this.ident = config.ident || 'dupctx'+Ext.id();
    Ext.Ajax.timeout = 0;
    Ext.applyIf(config,{
        title: _('context_duplicate')
        ,id: this.ident
        ,url: MODx.config.connectors_url+'context/index.php'
        ,action: 'duplicate'        
        ,width: 400
        ,fields: [{
            xtype: 'statictextfield'
            ,id: 'modx-'+this.ident+'-key'
            ,fieldLabel: _('old_key')
            ,name: 'key'
            ,width: 200
            ,submitValue: true
        },{
            xtype: 'textfield'
            ,id: 'modx-'+this.ident+'-newkey'
            ,fieldLabel: _('new_key')
            ,name: 'newkey'
            ,width: 200
            ,value: ''
        }]
    });
    MODx.window.DuplicateContext.superclass.constructor.call(this,config);
};
Ext.extend(MODx.window.DuplicateContext,MODx.Window);
Ext.reg('modx-window-context-duplicate',MODx.window.DuplicateContext);
