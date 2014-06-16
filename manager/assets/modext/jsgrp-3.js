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
        ,width: 500
    });
    MODx.window.DuplicateResource.superclass.constructor.call(this,config);
};
Ext.extend(MODx.window.DuplicateResource,MODx.Window,{
    _loadForm: function() {
        if (this.checkIfLoaded(this.config.record)) {
            this.fp.getForm().baseParams = {
                action: 'resource/updateduplicate'
                ,prefixDuplicate: true
                ,id: this.config.resource
            };
            return false;
        }
        var items = [];
        items.push({
            xtype: 'textfield'
            ,id: 'modx-'+this.ident+'-name'
            ,fieldLabel: _('resource_name_new')
            ,name: 'name'
            ,anchor: '100%'
            ,value: ''
        });

        if (this.config.hasChildren) {
            items.push({
                xtype: 'xcheckbox'
                ,boxLabel: _('duplicate_children')
                ,hideLabel: true
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

        var pov = MODx.config.default_duplicate_publish_option || 'preserve';
        items.push({
            xtype: 'fieldset'
            ,title: _('publishing_options')
            ,items: [{
                xtype: 'radiogroup'
                ,columns: 1
                ,value: pov
                ,items: [{
                    boxLabel: _('po_make_all_unpub')
                    ,name: 'published_mode'
                    ,inputValue: 'unpublish'
                },{
                    boxLabel: _('po_make_all_pub')
                    ,name: 'published_mode'
                    ,inputValue: 'publish'
                },{
                    boxLabel: _('po_preserve')
                    ,name: 'published_mode'
                    ,inputValue: 'preserve'
                }]
            }]
        });

        this.fp = this.createForm({
            url: this.config.url || MODx.config.connector_url
            ,baseParams: this.config.baseParams || {
                action: 'resource/duplicate'
                ,id: this.config.resource
                ,prefixDuplicate: true
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

MODx.window.CreateCategory = function(config) {
    config = config || {};
    this.ident = config.ident || 'ccat'+Ext.id();
    Ext.applyIf(config,{
        title: _('new_category')
        ,id: this.ident
        ,height: 150
        ,width: 350
        ,url: MODx.config.connector_url
        ,action: 'element/category/create'
        ,fields: [{
            fieldLabel: _('name')
            ,name: 'category'
            ,id: 'modx-'+this.ident+'-category'
            ,xtype: 'textfield'
            ,anchor: '100%'
        },{
            fieldLabel: _('parent')
            ,name: 'parent'
            ,hiddenName: 'parent'
            ,id: 'modx-'+this.ident+'-parent'
            ,xtype: 'modx-combo-category'
            ,anchor: '100%'
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
        ,url: MODx.config.connector_url
        ,action: 'workspace/namespace/create'
        ,fields: [{
            xtype: 'textfield'
            ,fieldLabel: _('name')
            ,description: MODx.expandHelp ? '' : _('namespace_name_desc')
            ,name: 'name'
            ,id: 'modx-'+this.ident+'-name'
            ,anchor: '100%'
            ,maxLength: 100

        },{
            xtype: MODx.expandHelp ? 'label' : 'hidden'
            ,forId: 'modx-'+this.ident+'-name'
            ,html: _('namespace_name_desc')
            ,cls: 'desc-under'

        },{
            xtype: 'textfield'
            ,fieldLabel: _('namespace_path')
            ,description: MODx.expandHelp ? '' : _('namespace_path_desc')
            ,name: 'path'
            ,id: 'modx-'+this.ident+'-path'
            ,anchor: '100%'
        },{
            xtype: MODx.expandHelp ? 'label' : 'hidden'
            ,forId: 'modx-'+this.ident+'-path'
            ,html: _('namespace_path_desc')
            ,cls: 'desc-under'

        },{
            xtype: 'textfield'
            ,fieldLabel: _('namespace_assets_path')
            ,description: MODx.expandHelp ? '' : _('namespace_assets_path_desc')
            ,name: 'assets_path'
            ,id: 'modx-'+this.ident+'-assets-path'
            ,anchor: '100%'
        },{
            xtype: MODx.expandHelp ? 'label' : 'hidden'
            ,forId: 'modx-'+this.ident+'-assets-path'
            ,html: _('namespace_assets_path_desc')
            ,cls: 'desc-under'
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
        //,height: 640
        ,autoHeight: true
        ,layout: 'anchor'
        ,url: MODx.config.connector_url
        ,action: 'element/chunk/create'
        ,fields: [{
            xtype: 'hidden'
            ,name: 'id'
        },{
            xtype: 'textfield'
            ,name: 'name'
            ,fieldLabel: _('name')
            ,anchor: '100%'
        },{
            xtype: 'modx-combo-category'
            ,name: 'category'
            ,fieldLabel: _('category')
            ,anchor: '100%'
        },{
            xtype: 'textarea'
            ,name: 'description'
            ,fieldLabel: _('description')
            ,anchor: '100%'
            //,rows: 2
        },{
            xtype: 'textarea'
            ,name: 'snippet'
            ,fieldLabel: _('code')
            ,anchor: '100%'
            ,grow: true
            ,growMax: 216
        },{
            xtype: 'xcheckbox'
            ,name: 'clearCache'
            ,hideLabel: true
            ,boxLabel: _('clear_cache_on_save')
            ,description: _('clear_cache_on_save_msg')
            ,inputValue: 1
            ,checked: true
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

    Ext.applyIf(config,{
        title: _('quick_update_chunk')
        ,action: 'element/chunk/update'
        ,buttons: [{
            text: config.cancelBtnText || _('cancel')
            ,scope: this
            ,handler: function() { this.hide(); }
        },{
            text: config.saveBtnText || _('save')
            ,scope: this
            ,handler: function() { this.submit(false); }
        },{
            text: config.saveBtnText || _('save_and_close')
            ,scope: this
            ,handler: this.submit
        }]
    });
    MODx.window.QuickUpdateChunk.superclass.constructor.call(this,config);
};
Ext.extend(MODx.window.QuickUpdateChunk, MODx.window.QuickCreateChunk);
Ext.reg('modx-window-quick-update-chunk',MODx.window.QuickUpdateChunk);

MODx.window.QuickCreateTemplate = function(config) {
    config = config || {};

    Ext.applyIf(config,{
        title: _('quick_create_template')
        ,width: 600
        ,autoHeight: true
        ,layout: 'anchor'
        ,url: MODx.config.connector_url
        ,action: 'element/template/create'
        ,fields: [{
            xtype: 'hidden'
            ,name: 'id'
        },{
            xtype: 'textfield'
            ,name: 'templatename'
            ,fieldLabel: _('name')
            ,anchor: '100%'
        },{
            xtype: 'modx-combo-category'
            ,name: 'category'
            ,fieldLabel: _('category')
            ,anchor: '100%'
        },{
            xtype: 'textarea'
            ,name: 'description'
            ,fieldLabel: _('description')
            ,anchor: '100%'
        },{
            xtype: 'textarea'
            ,name: 'content'
            ,fieldLabel: _('code')
            ,anchor: '100%'
            ,grow: true
            ,growMax: 216
        },{
            xtype: 'xcheckbox'
            ,name: 'clearCache'
            ,hideLabel: true
            ,boxLabel: _('clear_cache_on_save')
            ,description: _('clear_cache_on_save_msg')
            ,inputValue: 1
            ,checked: true
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

    Ext.applyIf(config,{
        title: _('quick_update_template')
        ,action: 'element/template/update'
        ,buttons: [{
            text: config.cancelBtnText || _('cancel')
            ,scope: this
            ,handler: function() { this.hide(); }
        },{
            text: config.saveBtnText || _('save')
            ,scope: this
            ,handler: function() { this.submit(false); }
        },{
            text: config.saveBtnText || _('save_and_close')
            ,scope: this
            ,handler: this.submit
        }]
    });
    MODx.window.QuickUpdateTemplate.superclass.constructor.call(this,config);
};
Ext.extend(MODx.window.QuickUpdateTemplate,MODx.window.QuickCreateTemplate);
Ext.reg('modx-window-quick-update-template',MODx.window.QuickUpdateTemplate);


MODx.window.QuickCreateSnippet = function(config) {
    config = config || {};

    Ext.applyIf(config,{
        title: _('quick_create_snippet')
        ,width: 600
        ,autoHeight: true
        ,layout: 'anchor'
        ,url: MODx.config.connector_url
        ,action: 'element/snippet/create'
        ,fields: [{
            xtype: 'hidden'
            ,name: 'id'
        },{
            xtype: 'textfield'
            ,name: 'name'
            ,fieldLabel: _('name')
            ,anchor: '100%'
        },{
            xtype: 'modx-combo-category'
            ,name: 'category'
            ,fieldLabel: _('category')
            ,anchor: '100%'
        },{
            xtype: 'textarea'
            ,name: 'description'
            ,fieldLabel: _('description')
            ,anchor: '100%'
        },{
            xtype: 'textarea'
            ,name: 'snippet'
            ,fieldLabel: _('code')
            ,anchor: '100%'
            ,grow: true
            ,growMax: 216
        },{
            xtype: 'xcheckbox'
            ,name: 'clearCache'
            ,hideLabel: true
            ,boxLabel: _('clear_cache_on_save')
            ,description: _('clear_cache_on_save_msg')
            ,inputValue: 1
            ,checked: true
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

    Ext.applyIf(config,{
        title: _('quick_update_snippet')
        ,action: 'element/snippet/update'
        ,buttons: [{
            text: config.cancelBtnText || _('cancel')
            ,scope: this
            ,handler: function() { this.hide(); }
        },{
            text: config.saveBtnText || _('save')
            ,scope: this
            ,handler: function() { this.submit(false); }
        },{
            text: config.saveBtnText || _('save_and_close')
            ,scope: this
            ,handler: this.submit
        }]
    });
    MODx.window.QuickUpdateSnippet.superclass.constructor.call(this,config);
};
Ext.extend(MODx.window.QuickUpdateSnippet,MODx.window.QuickCreateSnippet);
Ext.reg('modx-window-quick-update-snippet',MODx.window.QuickUpdateSnippet);



MODx.window.QuickCreatePlugin = function(config) {
    config = config || {};

    Ext.applyIf(config,{
        title: _('quick_create_plugin')
        ,width: 600
        ,autoHeight: true
        ,layout: 'anchor'
        ,url: MODx.config.connector_url
        ,action: 'element/plugin/create'
        ,fields: [{
            xtype: 'hidden'
            ,name: 'id'
        },{
            xtype: 'textfield'
            ,name: 'name'
            ,fieldLabel: _('name')
            ,anchor: '100%'
        },{
            xtype: 'modx-combo-category'
            ,name: 'category'
            ,fieldLabel: _('category')
            ,anchor: '100%'
        },{
            xtype: 'textarea'
            ,name: 'description'
            ,fieldLabel: _('description')
            ,anchor: '100%'
            ,rows: 2
        },{
            xtype: 'textarea'
            ,name: 'plugincode'
            ,fieldLabel: _('code')
            ,anchor: '100%'
            ,grow: true
            ,growMax: 216
        },{
            xtype: 'xcheckbox'
            ,name: 'disabled'
            ,boxLabel: _('disabled')
            ,hideLabel: true
            ,inputValue: 1
            ,checked: false
        },{
            xtype: 'xcheckbox'
            ,name: 'clearCache'
            ,boxLabel: _('clear_cache_on_save')
            ,hideLabel: true
            ,description: _('clear_cache_on_save_msg')
            ,inputValue: 1
            ,checked: true
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

    Ext.applyIf(config,{
        title: _('quick_update_plugin')
        ,action: 'element/plugin/update'
        ,buttons: [{
            text: config.cancelBtnText || _('cancel')
            ,scope: this
            ,handler: function() { this.hide(); }
        },{
            text: config.saveBtnText || _('save')
            ,scope: this
            ,handler: function() { this.submit(false); }
        },{
            text: config.saveBtnText || _('save_and_close')
            ,scope: this
            ,handler: this.submit
        }]
    });
    MODx.window.QuickUpdatePlugin.superclass.constructor.call(this,config);
};
Ext.extend(MODx.window.QuickUpdatePlugin,MODx.window.QuickCreatePlugin);
Ext.reg('modx-window-quick-update-plugin',MODx.window.QuickUpdatePlugin);


MODx.window.QuickCreateTV = function(config) {
    config = config || {};
    this.ident = config.ident || 'qtv'+Ext.id();

    Ext.applyIf(config,{
        title: _('quick_create_tv')
        ,width: 700
        ,url: MODx.config.connector_url
        ,action: 'element/tv/create'
        ,fields: [{
            xtype: 'hidden'
            ,name: 'id'
        },{
            layout: 'column'
            ,border: false
            ,items: [{
                columnWidth: .6
                ,layout: 'form'
                ,items: [{
                    xtype: 'textfield'
                    ,name: 'name'
                    ,fieldLabel: _('name')
                    ,anchor: '100%'
                },{
                    xtype: 'textfield'
                    ,name: 'caption'
                    ,id: 'modx-'+this.ident+'-caption'
                    ,fieldLabel: _('caption')
                    ,anchor: '100%'
                },{
                    xtype: 'label'
                    ,forId: 'modx-'+this.ident+'-caption'
                    ,html: _('caption_desc')
                    ,cls: 'desc-under'
                },{
                    xtype: 'modx-combo-category'
                    ,name: 'category'
                    ,fieldLabel: _('category')
                    ,anchor: '100%'
                },{
                    xtype: 'textarea'
                    ,name: 'description'
                    ,fieldLabel: _('description')
                    ,anchor: '100%'
                }]
            },{
                columnWidth: .4
                ,border: false
                ,layout: 'form'
                ,items: [{
                    xtype: 'modx-combo-tv-input-type'
                    ,fieldLabel: _('tv_type')
                    ,name: 'type'
                    ,anchor: '100%'
                },{
                    xtype: 'textfield'
                    ,fieldLabel: _('tv_elements')
                    ,name: 'els'
                    ,id: 'modx-'+this.ident+'-elements'
                    ,anchor: '100%'
                },{
                    xtype: 'label'
                    ,forId: 'modx-'+this.ident+'-elements'
                    ,html: _('tv_elements_desc')
                    ,cls: 'desc-under'
                },{
                    xtype: 'textarea'
                    ,fieldLabel: _('tv_default')
                    ,name: 'default_text'
                    ,id: 'modx-'+this.ident+'-default-text'
                    ,anchor: '100%'
                    ,grow: true
                    ,growMax: Ext.getBody().getViewSize().height <= 768 ? 300 : 380
                },{
                    xtype: 'label'
                    ,forId: 'modx-'+this.ident+'-default-text'
                    ,html: _('tv_default_desc')
                    ,cls: 'desc-under'
                }]
            }]
        },{
            xtype: 'xcheckbox'
            ,name: 'clearCache'
            ,hideLabel: true
            ,boxLabel: _('clear_cache_on_save')
            ,description: _('clear_cache_on_save_msg')
            ,inputValue: 1
            ,checked: true
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

    Ext.applyIf(config,{
        title: _('quick_update_tv')
        ,action: 'element/tv/update'
        ,buttons: [{
            text: config.cancelBtnText || _('cancel')
            ,scope: this
            ,handler: function() { this.hide(); }
        },{
            text: config.saveBtnText || _('save')
            ,scope: this
            ,handler: function() { this.submit(false); }
        },{
            text: config.saveBtnText || _('save_and_close')
            ,scope: this
            ,handler: this.submit
        }]
    });
    MODx.window.QuickUpdateTV.superclass.constructor.call(this,config);
};
Ext.extend(MODx.window.QuickUpdateTV,MODx.window.QuickCreateTV);
Ext.reg('modx-window-quick-update-tv',MODx.window.QuickUpdateTV);


MODx.window.DuplicateContext = function(config) {
    config = config || {};
    this.ident = config.ident || 'dupctx'+Ext.id();
    Ext.Ajax.timeout = 0;
    Ext.applyIf(config,{
        title: _('context_duplicate')
        ,id: this.ident
        ,url: MODx.config.connector_url
        ,action: 'context/duplicate'
        ,width: 400
        ,fields: [{
            xtype: 'statictextfield'
            ,id: 'modx-'+this.ident+'-key'
            ,fieldLabel: _('old_key')
            ,name: 'key'
            ,anchor: '100%'
            ,submitValue: true
        },{
            xtype: 'textfield'
            ,id: 'modx-'+this.ident+'-newkey'
            ,fieldLabel: _('new_key')
            ,name: 'newkey'
            ,anchor: '100%'
            ,value: ''
        },{
            xtype: 'checkbox',
            id: 'modx-'+this.ident+'-preservealias'
            ,fieldLabel: _('preserve_alias') // Todo: add translation
            ,name: 'preserve_alias'
            ,anchor: '100%'
            ,checked: true
        },{
            xtype: 'checkbox',
            id: 'modx-'+this.ident+'-preservemenuindex'
            ,fieldLabel: _('preserve_menuindex') // Todo: add translation
            ,name: 'preserve_menuindex'
            ,anchor: '100%'
            ,checked: true
        }]
    });
    MODx.window.DuplicateContext.superclass.constructor.call(this,config);
};
Ext.extend(MODx.window.DuplicateContext,MODx.Window);
Ext.reg('modx-window-context-duplicate',MODx.window.DuplicateContext);

MODx.window.Login = function(config) {
    config = config || {};
    this.ident = config.ident || 'dupctx'+Ext.id();
    Ext.Ajax.timeout = 0;
    Ext.applyIf(config,{
        title: _('login')
        ,id: this.ident
        ,url: MODx.config.connector_url
        ,action: 'security/login'
        ,width: 400
        ,fields: [{
            html: '<p>'+_('session_logging_out')+'</p>'
            ,bodyCssClass: 'panel-desc'
        },{
            xtype: 'textfield'
            ,id: 'modx-'+this.ident+'-username'
            ,fieldLabel: _('username')
            ,name: 'username'
            ,anchor: '100%'
        },{
            xtype: 'textfield'
            ,inputType: 'password'
            ,id: 'modx-'+this.ident+'-password'
            ,fieldLabel: _('password')
            ,name: 'password'
            ,anchor: '100%'
        },{
            xtype: 'hidden'
            ,name: 'rememberme'
            ,value: 1
        }]
        ,buttons: [{
            text: _('logout')
            ,scope: this
            ,handler: function() { location.href = '?logout=1' }
        },{
            text: _('login')
            ,scope: this
            ,handler: this.submit
        }]
    });
    MODx.window.Login.superclass.constructor.call(this,config);
    this.on('success',this.onLogin,this);
};
Ext.extend(MODx.window.Login,MODx.Window,{
    onLogin: function(o) {
        var r = o.a.result;
        if (r.object && r.object.token) {
            Ext.Ajax.defaultHeaders = {
                'modAuth': r.object.token
            };
            Ext.Ajax.extraParams = {
                'HTTP_MODAUTH': r.object.token
            };
            MODx.siteId = r.object.token;
            MODx.msg.status({
                message: _('session_extended')
            });
        }
    }
});
Ext.reg('modx-window-login',MODx.window.Login);


MODx.tree.AsyncTreeNode = function(config) {
    config = config || {};
    config.id = config.id || Ext.id();
    Ext.applyIf(config,{


    });
    MODx.tree.AsyncTreeNode.superclass.constructor.call(this,config);

};
Ext.extend(MODx.tree.AsyncTreeNode,Ext.tree.AsyncTreeNode,{

});
//Ext.tree.AsyncTreeNode = MODx.tree.AsyncTreeNode;
Ext.reg('modx-tree-asynctreenode',MODx.tree.AsyncTreeNode);


/**
 * Generates the Resource Tree in Ext
 *
 * @class MODx.tree.Resource
 * @extends MODx.tree.Tree
 * @param {Object} config An object of options.
 * @xtype modx-tree-resource
 */
MODx.tree.Resource = function(config) {
    config = config || {};
    Ext.applyIf(config,{
        url: MODx.config.connector_url
        ,action: 'resource/getNodes'
        ,title: ''
        ,rootVisible: false
        ,expandFirst: true
        ,enableDD: (MODx.config.enable_dragdrop != '0') ? true : false
        ,ddGroup: 'modx-treedrop-dd'
        ,remoteToolbar: true
        ,remoteToolbarAction: 'resource/gettoolbar'
        ,sortAction: 'resource/sort'
        ,sortBy: this.getDefaultSortBy(config)
        ,tbarCfg: {
        //    hidden: true
            id: config.id ? config.id+'-tbar' : 'modx-tree-resource-tbar'
        }
        ,baseParams: {
            sortBy: this.getDefaultSortBy(config)
            ,currentResource: MODx.request.id || 0
            ,currentAction: MODx.request.a || 0
        }
    });
    MODx.tree.Resource.superclass.constructor.call(this,config);
    this.on('render',function() {
        var el = Ext.get('modx-resource-tree');
        el.createChild({tag: 'div', id: 'modx-resource-tree_tb'});
        el.createChild({tag: 'div', id: 'modx-resource-tree_filter'});
        //this.addSearchToolbar();
    },this);
    this.addEvents('loadCreateMenus');
    this.on('afterSort',this._handleAfterDrop,this);
};
Ext.extend(MODx.tree.Resource,MODx.tree.Tree,{
    forms: {}
    ,windows: {}
    ,stores: {}

    ,_initExpand: function() {
        var treeState = Ext.state.Manager.get(this.treestate_id);
        if ((Ext.isString(treeState) || Ext.isEmpty(treeState)) && this.root) {
            if (this.root) {this.root.expand();}
            var wn = this.getNodeById('web_0');
            if (wn && this.config.expandFirst) {
                wn.select();
                wn.expand();
            }
        } else {
            for (var i=0;i<treeState.length;i++) {
                this.expandPath(treeState[i]);
            }
        }
    }

    /*,addSearchToolbar: function() {
        this.searchField = new Ext.form.TextField({
            emptyText: _('search_ellipsis')
            ,listeners: {
                'change': {fn: this.search,scope:this}
                ,'render': {fn: function(cmp) {
                    new Ext.KeyMap(cmp.getEl(), {
                        key: Ext.EventObject.ENTER
                        ,fn: function() {
                            this.fireEvent('change',this.getValue());
                            this.blur();
                            return true;}
                        ,scope: cmp
                    });
                },scope:this}
            }
        });
        this.searchBar = new Ext.Toolbar({
            renderTo: this.tbar
            ,id: 'modx-resource-searchbar'
            ,items: [this.searchField]
        });
        this.on('resize', function(){
            this.searchField.setWidth(this.getWidth() - 12);
        }, this);
    }

    ,search: function(nv) {
        Ext.state.Manager.set(this.treestate_id+'-search',nv);
        this.config.search = nv;
        this.getLoader().baseParams = {
            action: this.config.action
            ,search: this.config.search
        };
        this.refresh();
    }*/

    /**
     * Shows the current context menu.
     * @param {Ext.tree.TreeNode} n The current node
     * @param {Ext.EventObject} e The event object run.
     */
    ,_showContextMenu: function(n,e) {
        this.cm.activeNode = n;
        this.cm.removeAll();
        if (n.attributes.menu && n.attributes.menu.items) {
            this.addContextMenuItem(n.attributes.menu.items);
        } else {
            var m = [];
            switch (n.attributes.type) {
                case 'modResource':
                case 'modDocument':
                    m = this._getModResourceMenu(n);
                    break;
                case 'modContext':
                    m = this._getModContextMenu(n);
                    break;
            }

            this.addContextMenuItem(m);
        }
        this.cm.showAt(e.xy);
        e.stopEvent();
    }

    ,duplicateResource: function(item,e) {
        var node = this.cm.activeNode;
        var id = node.id.split('_');id = id[1];

        var r = {
            resource: id
            ,is_folder: node.getUI().hasClass('folder')
        };
        var w = MODx.load({
            xtype: 'modx-window-resource-duplicate'
            ,resource: id
            ,hasChildren: node.attributes.hasChildren
            ,listeners: {
                'success': {fn:function() {this.refreshNode(node.id);},scope:this}
            }
        });
        w.config.hasChildren = node.attributes.hasChildren;
        w.setValues(r);
        w.show(e.target);
    }

    ,duplicateContext: function(itm,e) {
        var node = this.cm.activeNode;
        var key = node.attributes.pk;

        var r = {
            key: key
            ,newkey: ''
        };
        var w = MODx.load({
            xtype: 'modx-window-context-duplicate'
            ,record: r
            ,listeners: {
                'success': {fn:function() {this.refresh();},scope:this}
            }
        });
        w.show(e.target);
    }

    ,removeContext: function(itm,e) {
        var node = this.cm.activeNode;
        var key = node.attributes.pk;
        MODx.msg.confirm({
            title: _('context_remove')
            ,text: _('context_remove_confirm')
            ,url: MODx.config.connector_url
            ,params: {
                action: 'context/remove'
                ,key: key
            }
            ,listeners: {
                'success': {fn:function() {this.refresh();},scope:this}
            }
        });
    }

    ,preview: function() {
        window.open(this.cm.activeNode.attributes.preview_url);
    }

    ,deleteDocument: function(itm,e) {
        var node = this.cm.activeNode;
        var id = node.id.split('_');id = id[1];
        MODx.msg.confirm({
            title: _('resource_delete')
            ,text: _('resource_delete_confirm')
            ,url: MODx.config.connector_url
            ,params: {
                action: 'resource/delete'
                ,id: id
            }
            ,listeners: {
                'success': {fn:function(data) {
                    var trashButton = this.getTopToolbar().findById('emptifier');
                    if (trashButton) {
                        if (data.object.deletedCount == 0) {
                            trashButton.disable();
                        } else {
                            trashButton.enable();
                        }

                        trashButton.setTooltip(_('empty_recycle_bin') + ' (' + data.object.deletedCount + ')');
                    }

                    var n = this.cm.activeNode;
                    var ui = n.getUI();

                    ui.addClass('deleted');
                    n.cascade(function(nd) {
                        nd.getUI().addClass('deleted');
                    },this);
                    Ext.get(ui.getEl()).frame();
                },scope:this}
            }
        });
    }

    ,undeleteDocument: function(itm,e) {
        var node = this.cm.activeNode;
        var id = node.id.split('_');id = id[1];
        MODx.Ajax.request({
            url: MODx.config.connector_url
            ,params: {
                action: 'resource/undelete'
                ,id: id
            }
            ,listeners: {
                'success': {fn:function(data) {
                    var trashButton = this.getTopToolbar().findById('emptifier');
                    if (trashButton) {
                        if (data.object.deletedCount == 0) {
                            trashButton.disable();
                        } else {
                            trashButton.enable();
                        }

                        trashButton.setTooltip(_('empty_recycle_bin') + ' (' + data.object.deletedCount + ')');
                    }

                    var n = this.cm.activeNode;
                    var ui = n.getUI();

                    ui.removeClass('deleted');
                    n.cascade(function(nd) {
                        nd.getUI().removeClass('deleted');
                    },this);
                    Ext.get(ui.getEl()).frame();
                },scope:this}
            }
        });
    }

    ,publishDocument: function(itm,e) {
        var node = this.cm.activeNode;
        var id = node.id.split('_');id = id[1];
        MODx.msg.confirm({
            title: _('resource_publish')
            ,text: _('resource_publish_confirm')
            ,url: MODx.config.connector_url
            ,params: {
                action: 'resource/publish'
                ,id: id
            }
            ,listeners: {
                'success': {fn:function() {
                    var ui = this.cm.activeNode.getUI();
                    ui.removeClass('unpublished');
                    Ext.get(ui.getEl()).frame();
                },scope:this}
            }
        });
    }

    ,unpublishDocument: function(itm,e) {
        var node = this.cm.activeNode;
        var id = node.id.split('_');id = id[1];
        MODx.msg.confirm({
            title: _('resource_unpublish')
            ,text: _('resource_unpublish_confirm')
            ,url: MODx.config.connector_url
            ,params: {
                action: 'resource/unpublish'
                ,id: id
            }
            ,listeners: {
                'success': {fn:function() {
                    var ui = this.cm.activeNode.getUI();
                    ui.addClass('unpublished');
                    Ext.get(ui.getEl()).frame();
                },scope:this}
            }
        });
    }

    ,emptyRecycleBin: function() {
        MODx.msg.confirm({
            title: _('empty_recycle_bin')
            ,text: _('empty_recycle_bin_confirm')
            ,url: MODx.config.connector_url
            ,params: {
                action: 'resource/emptyRecycleBin'
            }
            ,listeners: {
                'success':{fn:function() {
                    Ext.select('div.deleted',this.getRootNode()).remove();
                    MODx.msg.status({
                        title: _('success')
                        ,message: _('empty_recycle_bin_emptied')
                    })
                },scope:this}
            }
        });
    }

    ,getDefaultSortBy: function(config) {
        var v = 'menuindex';
        if (!Ext.isEmpty(config) && !Ext.isEmpty(config.sortBy)) {
            v = config.sortBy;
        } else {
            var d = Ext.state.Manager.get(this.treestate_id+'-sort-default');
            if (d != MODx.config.tree_default_sort) {
                v = MODx.config.tree_default_sort;
                Ext.state.Manager.set(this.treestate_id+'-sort-default',v);
                Ext.state.Manager.set(this.treestate_id+'-sort',v);
            } else {
                v = Ext.state.Manager.get(this.treestate_id+'-sort') || MODx.config.tree_default_sort;
            }
        }
        return v;
    }

    ,filterSort: function(itm, e) {
        this.getLoader().baseParams = {
            action: this.config.action
            ,sortBy: itm.sortBy
            ,sortDir: itm.sortDir
            ,node: this.cm.activeNode.ide
        };
        this.refreshActiveNode()
    }


    ,hideFilter: function(itm,e) {
        this.filterBar.destroy();
        this._filterVisible = false;
    }

    ,_handleAfterDrop: function(o,r) {
        var targetNode = o.event.target;
        var dropNode = o.event.dropNode;
        if (o.event.point == 'append' && targetNode) {
            var ui = targetNode.getUI();
            ui.addClass('haschildren');
            ui.removeClass('icon-resource');
        }
        if((MODx.request.a == MODx.action['resource/update']) && dropNode.attributes.pk == MODx.request.id){
            var parentFieldCmb = Ext.getCmp('modx-resource-parent');
            var parentFieldHidden = Ext.getCmp('modx-resource-parent-hidden');
            if(parentFieldCmb && parentFieldHidden){
                parentFieldHidden.setValue(dropNode.parentNode.attributes.pk);
                parentFieldCmb.setValue(dropNode.parentNode.attributes.text.replace(/(<([^>]+)>)/ig,""));
            }
        }
    }

    ,_handleDrop:  function(e){
        var dropNode = e.dropNode;
        var targetParent = e.target;

        if (targetParent.findChild('id',dropNode.attributes.id) !== null) {return false;}

        if (dropNode.attributes.type == 'modContext' && (targetParent.getDepth() > 1 || (targetParent.attributes.id == targetParent.attributes.pk + '_0' && e.point == 'append'))) {
        	return false;
        }

        if (dropNode.attributes.type !== 'modContext' && targetParent.getDepth() <= 1 && e.point !== 'append') {
        	return false;
        }

        if (MODx.config.resource_classes_drop[targetParent.attributes.classKey] == undefined) {
            if (targetParent.attributes.hide_children_in_tree) { return false; }
        } else if (MODx.config.resource_classes_drop[targetParent.attributes.classKey] == 0) {
            return false;
        }

        return dropNode.attributes.text != 'root' && dropNode.attributes.text !== ''
            && targetParent.attributes.text != 'root' && targetParent.attributes.text !== '';
    }

    ,getContextSettingForNode: function(node,ctx,setting,dv) {
        var val = dv || null;
        if (node.attributes.type != 'modContext') {
            var t = node.getOwnerTree();
            var rn = t.getRootNode();
            var cn = rn.findChild('ctx',ctx,false);
            if (cn) {
                val = cn.attributes.settings[setting];
            }
        } else {
            val = node.attributes.settings[setting];
        }
        return val;
    }

    ,quickCreate: function(itm,e,cls,ctx,p) {
        cls = cls || 'modDocument';
        var r = {
            class_key: cls
            ,context_key: ctx || 'web'
            ,'parent': p || 0
            ,'template': parseInt(this.getContextSettingForNode(this.cm.activeNode,ctx,'default_template',MODx.config.default_template))
            ,'richtext': parseInt(this.getContextSettingForNode(this.cm.activeNode,ctx,'richtext_default',MODx.config.richtext_default))
            ,'hidemenu': parseInt(this.getContextSettingForNode(this.cm.activeNode,ctx,'hidemenu_default',MODx.config.hidemenu_default))
            ,'searchable': parseInt(this.getContextSettingForNode(this.cm.activeNode,ctx,'search_default',MODx.config.search_default))
            ,'cacheable': parseInt(this.getContextSettingForNode(this.cm.activeNode,ctx,'cache_default',MODx.config.cache_default))
            ,'published': parseInt(this.getContextSettingForNode(this.cm.activeNode,ctx,'publish_default',MODx.config.publish_default))
            ,'content_type': parseInt(this.getContextSettingForNode(this.cm.activeNode,ctx,'default_content_type',MODx.config.default_content_type))
        };
        if (this.cm.activeNode.attributes.type != 'modContext') {
            var t = this.cm.activeNode.getOwnerTree();
            var rn = t.getRootNode();
            var cn = rn.findChild('ctx',ctx,false);
            if (cn) {
                r['template'] = cn.attributes.settings.default_template;
            }
        } else {
            r['template'] = this.cm.activeNode.attributes.settings.default_template;
        }

        var w = MODx.load({
            xtype: 'modx-window-quick-create-modResource'
            ,record: r
            ,listeners: {
                'success':{fn:function() {
                    var node = this.getNodeById(this.cm.activeNode.id);
                    if (node) {
                        var n = node.parentNode ? node.parentNode : node;
                        this.getLoader().load(n,function() {
                            n.expand();
                        },this);
                    }
                },scope:this}
                ,'hide':{fn:function() {this.destroy();}}
                ,'show':{fn:function() {this.center();}}
            }
        });
        w.setValues(r);
        w.show(e.target,function() {
            Ext.isSafari ? w.setPosition(null,30) : w.center();
        },this);
    }

    ,quickUpdate: function(itm,e,cls) {
        MODx.Ajax.request({
            url: MODx.config.connector_url
            ,params: {
                action: 'resource/get'
                ,id: this.cm.activeNode.attributes.pk
            }
            ,listeners: {
                'success': {fn:function(r) {
                    var pr = r.object;
                    pr.class_key = cls;

                    var w = MODx.load({
                        xtype: 'modx-window-quick-update-modResource'
                        ,record: pr
                        ,listeners: {
                            'success':{fn:function() {
                                this.refreshNode(this.cm.activeNode.id);
                            },scope:this}
                            ,'hide':{fn:function() {this.destroy();}}
                        }
                    });
                    w.setValues(r.object);
                    w.show(e.target,function() {
                        Ext.isSafari ? w.setPosition(null,30) : w.center();
                    },this);
                },scope:this}
            }
        });
    }

    ,_getModContextMenu: function(n) {
        var a = n.attributes;
        var ui = n.getUI();
        var m = [];

        m.push({
            text: '<b>'+a.text+'</b>'
            ,handler: function() {return false;}
            ,header: true
        });
        m.push('-');
        if (ui.hasClass('pedit')) {
            m.push({
                text: _('edit_context')
                ,handler: function() {
                    var at = this.cm.activeNode.attributes;
                    this.loadAction('a=context/update&key='+at.pk);
                }
            });
        }
        m.push({
            text: _('context_refresh')
            ,handler: function() {
                this.refreshNode(this.cm.activeNode.id,true);
            }
        });
        if (ui.hasClass('pnewdoc')) {
            m.push('-');
            this._getCreateMenus(m,'0',ui);
        }
        if (ui.hasClass('pnew')) {
            m.push({
                text: _('context_duplicate')
                ,handler: this.duplicateContext
            });
        }
        if (ui.hasClass('pdelete')) {
            m.push('-');
            m.push({
                text: _('context_remove')
                ,handler: this.removeContext
            });
        }

        if(!ui.hasClass('x-tree-node-leaf')) {
            m.push('-');
            m.push(this._getSortMenu());
        }

        return m;
    }

    ,overviewResource: function() {this.loadAction('a=resource/data')}

    ,quickUpdateResource: function(itm,e) {
        Ext.getCmp("modx-resource-tree").quickUpdate(itm,e,itm.classKey);
    }

    ,editResource: function() {this.loadAction('a=resource/update');}

    ,_getModResourceMenu: function(n) {
        var a = n.attributes;
        var ui = n.getUI();
        var m = [];
        m.push({
            text: '<b>'+a.text+'</b>'
            ,handler: function() {return false;}
            ,header: true
        });
        m.push('-');
        if (ui.hasClass('pview')) {
            m.push({
                text: _('resource_overview')
                ,handler: this.overviewResource
            });
        }
        if (ui.hasClass('pedit')) {
            m.push({
                text: _('resource_edit')
                ,handler: this.editResource
            });
        }
        if (ui.hasClass('pqupdate')) {
            m.push({
                text: _('quick_update_resource')
                ,classKey: a.classKey
                ,handler: this.quickUpdateResource
            });
        }
        if (ui.hasClass('pduplicate')) {
            m.push({
                text: _('resource_duplicate')
                ,handler: this.duplicateResource
            });
        }
        m.push({
            text: _('resource_refresh')
            ,handler: this.refreshResource
            ,scope: this
        });

        if (ui.hasClass('pnew')) {
            m.push('-');
            this._getCreateMenus(m,null,ui);
        }

        if (ui.hasClass('psave')) {
            m.push('-');
            if (ui.hasClass('ppublish') && ui.hasClass('unpublished')) {
                m.push({
                    text: _('resource_publish')
                    ,handler: this.publishDocument
                });
            } else if (ui.hasClass('punpublish')) {
                m.push({
                    text: _('resource_unpublish')
                    ,handler: this.unpublishDocument
                });
            }
            if (ui.hasClass('pundelete') && ui.hasClass('deleted')) {
                m.push({
                    text: _('resource_undelete')
                    ,handler: this.undeleteDocument
                });
            } else if (ui.hasClass('pdelete') && !ui.hasClass('deleted')) {
                m.push({
                    text: _('resource_delete')
                    ,handler: this.deleteDocument
                });
            }
        }

        if(!ui.hasClass('x-tree-node-leaf')) {
            m.push('-');
            m.push(this._getSortMenu());
        }

        if (ui.hasClass('pview') && a.preview_url != '') {
            m.push('-');
            m.push({
                text: _('resource_view')
                ,handler: this.preview
            });
        }
        return m;
    }

    ,refreshResource: function() {
        this.refreshNode(this.cm.activeNode.id);
    }

    ,createResourceHere: function(itm) {
        var at = this.cm.activeNode.attributes;
        var p = itm.usePk ? itm.usePk : at.pk;
        Ext.getCmp('modx-resource-tree').loadAction(
            'a=resource/create&class_key=' + itm.classKey + '&parent=' + p + (at.ctx ? '&context_key='+at.ctx : '')
        );
    }

    ,createResource: function(itm,e) {
        var at = this.cm.activeNode.attributes;
        var p = itm.usePk ? itm.usePk : at.pk;
        Ext.getCmp('modx-resource-tree').quickCreate(itm,e,itm.classKey,at.ctx,p);
    }

    ,_getCreateMenus: function(m,pk,ui) {
        var types = MODx.config.resource_classes;
        var o = this.fireEvent('loadCreateMenus',types);
        if (Ext.isObject(o)) {
            Ext.apply(types,o);
        }
        var coreTypes = ['modDocument','modWebLink','modSymLink','modStaticResource'];
        var ct = [];
        var qct = [];
        for (var k in types) {
            if (coreTypes.indexOf(k) != -1) {
                if (!ui.hasClass('pnew_'+k)) {
                    continue;
                }
            }
            ct.push({
                text: types[k]['text_create_here']
                ,classKey: k
                ,usePk: pk ? pk : false
                ,handler: this.createResourceHere
                ,scope: this
            });
            if (ui && ui.hasClass('pqcreate')) {
                qct.push({
                    text: types[k]['text_create']
                    ,classKey: k
                    ,handler: this.createResource
                    ,scope: this
                });
            }
        }
        m.push({
            text: _('create')
            ,handler: function() {return false;}
            ,menu: {items: ct}
        });
        if (ui && ui.hasClass('pqcreate')) {
            m.push({
               text: _('quick_create')
                ,handler: function() {return false;}
               ,menu: {items: qct}
            });
        }

        return m;
    }

    ,_getSortMenu: function(){
        return [{
            text: _('sort_by')
            ,handler: function() {return false;}
            ,menu: {
                items:[{
                    text: _('tree_order')
                    ,sortBy: 'menuindex'
                    ,sortDir: 'ASC'
                    ,handler: this.filterSort
                    ,scope: this
                },{
                    text: _('recently_updated')
                    ,sortBy: 'editedon'
                    ,sortDir: 'ASC'
                    ,handler: this.filterSort
                    ,scope: this
                },{
                    text: _('newest')
                    ,sortBy: 'createdon'
                    ,sortDir: 'DESC'
                    ,handler: this.filterSort
                    ,scope: this
                },{
                    text: _('oldest')
                    ,sortBy: 'createdon'
                    ,sortDir: 'ASC'
                    ,handler: this.filterSort
                    ,scope: this
                },{
                    text: _('publish_date')
                    ,sortBy: 'pub_date'
                    ,sortDir: 'ASC'
                    ,handler: this.filterSort
                    ,scope: this
                },{
                    text: _('unpublish_date')
                    ,sortBy: 'unpub_date'
                    ,sortDir: 'ASC'
                    ,handler: this.filterSort
                    ,scope: this
                },{
                    text: _('publishedon')
                    ,sortBy: 'publishedon'
                    ,sortDir: 'ASC'
                    ,handler: this.filterSort
                    ,scope: this
                },{
                    text: _('title')
                    ,sortBy: 'pagetitle'
                    ,sortDir: 'ASC'
                    ,handler: this.filterSort
                    ,scope: this
                },{
                    text: _('alias')
                    ,sortBy: 'alias'
                    ,sortDir: 'ASC'
                    ,handler: this.filterSort
                    ,scope: this
                }]
            }
        }];
    }

    ,handleCreateClick: function(node){
        this.cm.activeNode = node;
        var itm = {
            usePk: '0'
            ,classKey: 'modDocument'
        };

        this.createResourceHere(itm);
    }
});
Ext.reg('modx-tree-resource',MODx.tree.Resource);



MODx.window.QuickCreateResource = function(config) {
    config = config || {};
    this.ident = config.ident || 'qcr'+Ext.id();
    Ext.applyIf(config,{
        title: _('quick_create_resource')
        ,id: this.ident
        ,width: 700
        ,height: ['modSymLink', 'modWebLink', 'modStaticResource'].indexOf(config.record.class_key) == -1 ? 640 : 498
        ,autoHeight: false
        ,layout: 'anchor'
        ,url: MODx.config.connector_url
        ,action: 'resource/create'
        ,shadow: false
        ,fields: [{
            xtype: 'modx-tabs'
            ,bodyStyle: { background: 'transparent' }
            ,border: true
            ,deferredRender: false
            ,autoHeight: false
            ,autoScroll: false
            ,anchor: '100% 100%'
            ,items: [{
                title: _('resource')
                ,layout: 'form'
                ,cls: 'modx-panel'
                ,bodyStyle: { background: 'transparent', padding: '10px' }
                ,autoHeight: false
                ,anchor: '100% 100%'
                ,labelWidth: 100
                ,items: [{
                    xtype: 'hidden'
                    ,name: 'id'
                },{
                    layout: 'column'
                    ,border: false
                    ,items: [{
                        columnWidth: .6
                        ,border: false
                        ,layout: 'form'
                        ,items: [{
                            xtype: 'textfield'
                            ,name: 'pagetitle'
                            ,id: 'modx-'+this.ident+'-pagetitle'
                            ,fieldLabel: _('pagetitle')
                            ,anchor: '100%'
                        },{
                            xtype: 'textfield'
                            ,name: 'longtitle'
                            ,id: 'modx-'+this.ident+'-longtitle'
                            ,fieldLabel: _('long_title')
                            ,anchor: '100%'
                        },{
                            xtype: 'textarea'
                            ,name: 'description'
                            ,id: 'modx-'+this.ident+'-description'
                            ,fieldLabel: _('description')
                            ,anchor: '100%'
                            ,grow: false
                            ,height: 50
                        },{
                            xtype: 'textarea'
                            ,name: 'introtext'
                            ,id: 'modx-'+this.ident+'-introtext'
                            ,fieldLabel: _('introtext')
                            ,anchor: '100%'
                            ,height: 50
                        }]
                    },{
                        columnWidth: .4
                        ,border: false
                        ,layout: 'form'
                        ,items: [{
                            xtype: 'modx-combo-template'
                            ,name: 'template'
                            ,id: 'modx-'+this.ident+'-template'
                            ,fieldLabel: _('template')
                            ,editable: false
                            ,anchor: '100%'
                            ,baseParams: {
                                action: 'element/template/getList'
                                ,combo: '1'
                                ,limit: 0
                            }
                            ,value: MODx.config.default_template
                        },{
                            xtype: 'textfield'
                            ,name: 'alias'
                            ,id: 'modx-'+this.ident+'-alias'
                            ,fieldLabel: _('alias')
                            ,anchor: '100%'
                        },{
                            xtype: 'textfield'
                            ,name: 'menutitle'
                            ,id: 'modx-'+this.ident+'-menutitle'
                            ,fieldLabel: _('resource_menutitle')
                            ,anchor: '100%'
                        },{
                            xtype: 'xcheckbox'
                            ,boxLabel: _('resource_hide_from_menus')
                            ,description: _('resource_hide_from_menus_help')
                            ,name: 'hidemenu'
                            ,id: 'modx-'+this.ident+'-hidemenu'
                            ,inputValue: 1
                            ,checked: MODx.config.hidemenu_default == '1' ? 1 : 0
                        },{
                            xtype: 'xcheckbox'
                            ,name: 'published'
                            ,id: 'modx-'+this.ident+'-published'
                            ,boxLabel: _('resource_published')
                            ,description: _('resource_published_help')
                            ,inputValue: 1
                            ,checked: MODx.config.publish_default == '1' ? 1 : 0
                        }]
                    }]
                },MODx.getQRContentField(this.ident,config.record.class_key)]
            },{
                id: 'modx-'+this.ident+'-settings'
                ,title: _('settings')
                ,layout: 'form'
                ,cls: 'modx-panel'
                ,autoHeight: true
                ,forceLayout: true
                ,labelWidth: 100
                ,defaults: {autoHeight: true ,border: false}
                ,style: 'background: transparent;'
                ,bodyStyle: { background: 'transparent', padding: '10px' }
                ,items: MODx.getQRSettings(this.ident,config.record)
            }]
        }]
       ,keys: [{
            key: Ext.EventObject.ENTER
            ,shift: true
            ,fn: this.submit
            ,scope: this
        }]
    });
    MODx.window.QuickCreateResource.superclass.constructor.call(this,config);
};
Ext.extend(MODx.window.QuickCreateResource,MODx.Window);
Ext.reg('modx-window-quick-create-modResource',MODx.window.QuickCreateResource);

MODx.window.QuickUpdateResource = function(config) {
    config = config || {};
    this.ident = config.ident || 'qur'+Ext.id();
    Ext.applyIf(config,{
        title: _('quick_update_resource')
        ,id: this.ident
        ,action: 'resource/update'
        ,buttons: [{
            text: config.cancelBtnText || _('cancel')
            ,scope: this
            ,handler: function() { this.hide(); }
        },{
            text: config.saveBtnText || _('save')
            ,scope: this
            ,handler: function() { this.submit(false); }
        },{
            text: config.saveBtnText || _('save_and_close')
            ,scope: this
            ,handler: this.submit
        }]
    });
    MODx.window.QuickUpdateResource.superclass.constructor.call(this,config);
};
Ext.extend(MODx.window.QuickUpdateResource,MODx.window.QuickCreateResource);
Ext.reg('modx-window-quick-update-modResource',MODx.window.QuickUpdateResource);


MODx.getQRContentField = function(id,cls) {
    id = id || 'qur';
    cls = cls || 'modDocument';
    var dm = Ext.getBody().getViewSize();
    var o = {};
    switch (cls) {
        case 'modSymLink':
            o = {
                xtype: 'textfield'
                ,fieldLabel: _('symlink')
                ,name: 'content'
                ,id: 'modx-'+id+'-content'
                ,anchor: '100%'
                ,maxLength: 255
                ,allowBlank: false
            };
            break;
        case 'modWebLink':
            o = {
                xtype: 'textfield'
                ,fieldLabel: _('weblink')
                ,name: 'content'
                ,id: 'modx-'+id+'-content'
                ,anchor: '100%'
                ,maxLength: 255
                ,value: 'http://'
                ,allowBlank: false
            };
            break;
        case 'modStaticResource':
            o = {
                xtype: 'modx-combo-browser'
                ,browserEl: 'modx-browser'
                ,prependPath: false
                ,prependUrl: false
                ,hideFiles: true
                ,fieldLabel: _('static_resource')
                ,name: 'content'
                ,id: 'modx-'+id+'-content'
                ,anchor: '100%'
                ,maxLength: 255
                ,value: ''
                ,listeners: {
                    'select':{fn:function(data) {
                        if (data.url.substring(0,1) == '/') {
                            Ext.getCmp('modx-'+id+'-content').setValue(data.url.substring(1));
                        }
                    },scope:this}
                }
            };
            break;
        case 'modResource':
        case 'modDocument':
        default:
            o = {
                xtype: 'textarea'
                ,name: 'content'
                ,id: 'modx-'+id+'-content'
                ,hideLabel: true
                ,labelSeparator: ''
                ,anchor: '100% -274'
            };
            break;
    }
    return o;
};

MODx.getQRSettings = function(id,va) {
    id = id || 'qur';
    return [{
        layout: 'column'
        ,border: false
        ,anchor: '100%'
        ,defaults: {
            labelSeparator: ''
            ,labelAlign: 'top'
            ,border: false
            ,layout: 'form'
        }
        ,items: [{
            columnWidth: .5
            ,items: [{
                xtype: 'hidden'
                ,name: 'parent'
                ,id: 'modx-'+id+'-parent'
                ,value: va['parent']
            },{
                xtype: 'hidden'
                ,name: 'context_key'
                ,id: 'modx-'+id+'-context_key'
                ,value: va['context_key']
            },{
                xtype: 'hidden'
                ,name: 'class_key'
                ,id: 'modx-'+id+'-class_key'
                ,value: va['class_key']
            },{
                xtype: 'hidden'
                ,name: 'publishedon'
                ,id: 'modx-'+id+'-publishedon'
                ,value: va['publishedon']
            },{
                xtype: 'modx-combo-content-type'
                ,fieldLabel: _('resource_content_type')
                ,name: 'content_type'
                ,hiddenName: 'content_type'
                ,id: 'modx-'+id+'-type'
                ,anchor: '100%'
                ,value: va['content_type'] != undefined ? va['content_type'] : (MODx.config.default_content_type || 1)

            },{
                xtype: 'modx-combo-content-disposition'
                ,fieldLabel: _('resource_contentdispo')
                ,name: 'content_dispo'
                ,hiddenName: 'content_dispo'
                ,id: 'modx-'+id+'-dispo'
                ,anchor: '100%'
                ,value: va['content_dispo'] != undefined ? va['content_dispo'] : 0
            },{
                xtype: 'modx-combo-class-derivatives'
                ,fieldLabel: _('class_key')
                ,description: '<b>[[*class_key]]</b><br />'
                ,name: 'class_key'
                ,hiddenName: 'class_key'
                ,id: 'modx-'+id+'-class-key'
                ,anchor: '100%'
                ,value: va['class_key'] != undefined ? va['class_key'] : 'modDocument'
            }]
        },{
            columnWidth: .5
            ,items: [{
                xtype: 'xcheckbox'
                ,boxLabel: _('resource_folder')
                ,description: _('resource_folder_help')
                ,name: 'isfolder'
                ,id: 'modx-'+id+'-isfolder'
                ,inputValue: 1
                ,checked: va['isfolder'] != undefined ? va['isfolder'] : false
            },{
                xtype: 'xcheckbox'
                ,boxLabel: _('resource_richtext')
                ,description: _('resource_richtext_help')
                ,name: 'richtext'
                ,id: 'modx-'+id+'-richtext'
                ,inputValue: 1
                ,checked: va['richtext'] !== undefined ? (va['richtext'] ? 1 : 0) : (MODx.config.richtext_default == '1' ? 1 : 0)
            },{
                xtype: 'xcheckbox'
                ,boxLabel: _('resource_searchable')
                ,description: _('resource_searchable_help')
                ,name: 'searchable'
                ,id: 'modx-'+id+'-searchable'
                ,inputValue: 1
                ,checked: va['searchable'] != undefined ? va['searchable'] : (MODx.config.search_default == '1' ? 1 : 0)
                ,listeners: {'check': {fn:MODx.handleQUCB}}
            },{
                xtype: 'xcheckbox'
                ,boxLabel: _('resource_cacheable')
                ,description: _('resource_cacheable_help')
                ,name: 'cacheable'
                ,id: 'modx-'+id+'-cacheable'
                ,inputValue: 1
                ,checked: va['cacheable'] != undefined ? va['cacheable'] : (MODx.config.cache_default == '1' ? 1 : 0)
            },{
                xtype: 'xcheckbox'
                ,name: 'clearCache'
                ,id: 'modx-'+id+'-clearcache'
                ,boxLabel: _('clear_cache_on_save')
                ,description: _('clear_cache_on_save_msg')
                ,inputValue: 1
                ,checked: true
            }]
        }]
    }];
};
MODx.handleQUCB = function(cb) {
    var h = Ext.getCmp(cb.id+'-hd');
    if (cb.checked && h) {
        cb.setValue(1);
        h.setValue(1);
    } else if (h) {
        cb.setValue(0);
        h.setValue(0);
    }
}



Ext.override(Ext.tree.AsyncTreeNode,{

    listeners: {
        click: {fn: function(){
            console.log('Clicked me!',arguments);
            return false;
        },scope: this}
    }
});

/**
 * Generates the Element Tree
 *
 * @class MODx.tree.Element
 * @extends MODx.tree.Tree
 * @param {Object} config An object of options.
 * @xtype modx-tree-element
 */
MODx.tree.Element = function(config) {
    config = config || {};
    Ext.applyIf(config,{
        rootVisible: false
        ,enableDD: !Ext.isEmpty(MODx.config.enable_dragdrop) ? true : false
        ,ddGroup: 'modx-treedrop-elements-dd'
        ,title: ''
        ,url: MODx.config.connector_url
        ,action: 'element/getnodes'
        ,sortAction: 'element/sort'
        ,useDefaultToolbar: false
        ,baseParams: {
            currentElement: MODx.request.id || 0
            ,currentAction: MODx.request.a || 0
        }
        ,tbar: [{
            cls: 'tree-new-template'
            ,tooltip: {text: _('new')+' '+_('template')}
            ,handler: function() {
                this.redirect('?a=element/template/create');
            }
            ,scope: this
            ,hidden: MODx.perm.new_template ? false : true
        },{
            cls: 'tree-new-tv'
            ,tooltip: {text: _('new')+' '+_('tv')}
            ,handler: function() {
                this.redirect('?a=element/tv/create');
            }
            ,scope: this
            ,hidden: MODx.perm.new_tv ? false : true
        },{
            cls: 'tree-new-chunk'
            ,tooltip: {text: _('new')+' '+_('chunk')}
            ,handler: function() {
                this.redirect('?a=element/chunk/create');
            }
            ,scope: this
            ,hidden: MODx.perm.new_chunk ? false : true
        },{
            cls: 'tree-new-snippet'
            ,tooltip: {text: _('new')+' '+_('snippet')}
            ,handler: function() {
                this.redirect('?a=element/snippet/create');
            }
            ,scope: this
            ,hidden: MODx.perm.new_snippet ? false : true
        },{
            cls: 'tree-new-plugin'
            ,tooltip: {text: _('new')+' '+_('plugin')}
            ,handler: function() {
                this.redirect('?a=element/plugin/create');
            }
            ,scope: this
            ,hidden: MODx.perm.new_plugin ? false : true
        },{
            cls: 'tree-new-category'
            ,tooltip: {text: _('new_category')}
            ,handler: function() {
                this.createCategory(null,{target: this.getEl()});
            }
            ,scope: this
            ,hidden: MODx.perm.new_category ? false : true
        }]
    });
    MODx.tree.Element.superclass.constructor.call(this,config);
    this.on('afterSort',this.afterSort);
};
Ext.extend(MODx.tree.Element,MODx.tree.Tree,{
    forms: {}
    ,windows: {}
    ,stores: {}

    ,createCategory: function(n,e) {
        var r = {};
        if (this.cm.activeNode && this.cm.activeNode.attributes.data) {
            r['parent'] = this.cm.activeNode.attributes.data.id;
        }

        var w = MODx.load({
            xtype: 'modx-window-category-create'
            ,record: r
            ,listeners: {
                'success': {fn:function() {
                    var node = (this.cm.activeNode) ? this.cm.activeNode.id : 'n_category';
                    this.refreshNode(node,true);
                },scope:this}
                ,'hide':{fn:function() {this.destroy();}}
            }
        });
        w.show(e.target);
    }

    ,renameCategory: function(itm,e) {
        var r = this.cm.activeNode.attributes.data;
        var w = MODx.load({
            xtype: 'modx-window-category-rename'
            ,record: r
            ,listeners: {
                'success':{fn:function(r) {
                    var c = r.a.result.object;
                    var n = this.cm.activeNode;
                    n.setText(c.category+' ('+c.id+')');
                    Ext.get(n.getUI().getEl()).frame();
                    n.attributes.data.id = c.id;
                    n.attributes.data.category = c.category;
                },scope:this}
                ,'hide':{fn:function() {this.destroy();}}
            }
        });
        w.show(e.target);
    }

    ,removeCategory: function(itm,e) {
        var id = this.cm.activeNode.attributes.data.id;
        MODx.msg.confirm({
            title: _('warning')
            ,text: _('category_confirm_delete')
            ,url: MODx.config.connector_url
            ,params: {
                action: 'element/category/remove'
                ,id: id
            }
            ,listeners: {
                'success': {fn:function() {
                    this.cm.activeNode.remove();
                },scope:this}
            }
        });
    }

    ,duplicateElement: function(itm,e,id,type) {
        var r = {
            id: id
            ,type: type
            ,name: _('duplicate_of',{name: this.cm.activeNode.attributes.name})
        };
        var w = MODx.load({
            xtype: 'modx-window-element-duplicate'
            ,record: r
            ,listeners: {
                'success': {fn:function() {this.refreshNode(this.cm.activeNode.id);},scope:this}
                ,'hide':{fn:function() {this.destroy();}}
            }
        });
        w.show(e.target);
    }

    ,removeElement: function(itm,e) {
        var id = this.cm.activeNode.id.substr(2);
        var oar = id.split('_');
        MODx.msg.confirm({
            title: _('warning')
            ,text: _('remove_this_confirm',{
                type: oar[0]
                ,name: this.cm.activeNode.attributes.name
            })
            ,url: MODx.config.connector_url
            ,params: {
                action: 'element/'+oar[0]+'/remove'
                ,id: oar[2]
            }
            ,listeners: {
                'success': {fn:function() {
                    this.cm.activeNode.remove();
                    /* if editing the element being removed */
                    if (MODx.request.a == 'element/'+oar[0]+'/update' && MODx.request.id == oar[2]) {
                        MODx.loadPage('welcome');
                    }
                },scope:this}
            }
        });
    }

    ,activatePlugin: function(itm,e) {
        var id = this.cm.activeNode.id.substr(2);
        var oar = id.split('_');
        MODx.Ajax.request({
            url: MODx.config.connector_url
            ,params: {
                action: 'element/plugin/activate'
                ,id: oar[2]
            }
            ,listeners: {
                'success': {fn:function() {
                    this.refreshParentNode();
                },scope:this}
            }
        });
    }

    ,deactivatePlugin: function(itm,e) {
        var id = this.cm.activeNode.id.substr(2);
        var oar = id.split('_');
        MODx.Ajax.request({
            url: MODx.config.connector_url
            ,params: {
                action: 'element/plugin/deactivate'
                ,id: oar[2]
            }
            ,listeners: {
                'success': {fn:function() {
                    this.refreshParentNode();
                },scope:this}
            }
        });
    }

    ,quickCreate: function(itm,e,type) {
        var r = {
            category: this.cm.activeNode.attributes.pk || ''
        };
        var w = MODx.load({
            xtype: 'modx-window-quick-create-'+type
            ,record: r
            ,listeners: {
                success: {
                    fn: function() {
                        this.refreshNode(this.cm.activeNode.id, true);
                    }
                    ,scope: this
                }
                ,hide: {
                    fn: function() {
                        this.destroy();
                    }
                }
            }
        });
        w.setValues(r);
        w.show(e.target);
    }

    ,quickUpdate: function(itm,e,type) {
        MODx.Ajax.request({
            url: MODx.config.connector_url
            ,params: {
                action: 'element/'+type+'/get'
                ,id: this.cm.activeNode.attributes.pk
            }
            ,listeners: {
                'success': {fn:function(r) {
                    var w = MODx.load({
                        xtype: 'modx-window-quick-update-'+type
                        ,record: r.object
                        ,listeners: {
                            'success':{fn:function(r) {
                                this.refreshNode(this.cm.activeNode.id);
                            },scope:this}
                            ,'hide':{fn:function() {this.destroy();}}
                        }
                    });
                    w.setValues(r.object);
                    w.show(e.target);
                },scope:this}
            }
        });
    }

    ,_createElement: function(itm,e,t) {
        var id = this.cm.activeNode.id.substr(2);
        var oar = id.split('_');
        var type = oar[0] == 'type' ? oar[1] : oar[0];
        var cat_id = oar[0] == 'type' ? 0 : (oar[1] == 'category' ? oar[2] : oar[3]);
        var a = 'element/'+type+'/create';
        this.redirect('?a='+a+'&category='+cat_id);
        this.cm.hide();
        return false;
    }

    ,afterSort: function(o) {
        var tn = o.event.target.attributes;
        if (tn.type == 'category') {
            var dn = o.event.dropNode.attributes;
            if (tn.id != 'n_category' && dn.type == 'category') {
                o.event.target.expand();
            } else {
                this.refreshNode(o.event.target.attributes.id,true);
                this.refreshNode('n_type_'+o.event.dropNode.attributes.type,true);
            }
        }
    }

    ,_handleDrop: function(e) {
        var target = e.target;
        if (e.point == 'above' || e.point == 'below') {return false;}
        if (target.attributes.classKey != 'modCategory' && target.attributes.classKey != 'root') { return false; }

        if (!this.isCorrectType(e.dropNode,target)) {return false;}
        if (target.attributes.type == 'category' && e.point == 'append') {return true;}

        return target.getDepth() > 0;
    }

    ,isCorrectType: function(dropNode,targetNode) {
        var r = false;
        /* types must be the same */
        if(targetNode.attributes.type == dropNode.attributes.type) {
            /* do not allow anything to be dropped on an element */
            if(!(targetNode.parentNode &&
                ((dropNode.attributes.cls == 'folder'
                    && targetNode.attributes.cls == 'folder'
                    && dropNode.parentNode.id == targetNode.parentNode.id
                ) || targetNode.attributes.cls == 'file'))) {
                r = true;
            }
        }
        return r;
    }


    /**
     * Shows the current context menu.
     * @param {Ext.tree.TreeNode} n The current node
     * @param {Ext.EventObject} e The event object run.
     */
    ,_showContextMenu: function(n,e) {
        this.cm.activeNode = n;
        this.cm.removeAll();
        if (n.attributes.menu && n.attributes.menu.items) {
            this.addContextMenuItem(n.attributes.menu.items);
            this.cm.show(n.getUI().getEl(),'t?');
        } else {
            var m = [];
            switch (n.attributes.classKey) {
                case 'root':
                    m = this._getRootMenu(n);
                    break;
                case 'modCategory':
                    m = this._getCategoryMenu(n);
                    break;
                default:
                    m = this._getElementMenu(n);
                    break;
            }

            this.addContextMenuItem(m);
            this.cm.showAt(e.xy);
        }
        e.stopEvent();
    }

    ,_getQuickCreateMenu: function(n,m) {
        var ui = n.getUI();
        var mn = [];
        var types = ['template','tv','chunk','snippet','plugin'];
        var t;
        for (var i=0;i<types.length;i++) {
            t = types[i];
            if (ui.hasClass('pnew_'+t)) {
                mn.push({
                    text: _(t)
                    ,scope: this
                    ,type: t
                    ,handler: function(itm,e) {
                        this.quickCreate(itm,e,itm.type);
                    }
                });
            }
        }
        m.push({
            text: _('quick_create')
            ,handler: function() {return false;}
            ,menu: {
                items: mn
            }
        });
        return m;
    }

    ,_getElementMenu: function(n) {
        var a = n.attributes;
        var ui = n.getUI();
        var m = [];

        m.push({
            text: '<b>'+a.text+'</b>'
            ,handler: function() { return false; }
            ,header: true
        });
        m.push('-');

        if (ui.hasClass('pedit')) {
            m.push({
                text: _('edit_'+a.type)
                ,type: a.type
                ,pk: a.pk
                ,handler: function(itm,e) {
                    MODx.loadPage('element/'+itm.type+'/update',
                        'id='+itm.pk);
                }
            });
            m.push({
                text: _('quick_update_'+a.type)
                ,type: a.type
                ,handler: function(itm,e) {
                    this.quickUpdate(itm,e,itm.type);
                }
            });
            if (a.classKey == 'modPlugin') {
                if (a.active) {
                    m.push({
                        text: _('plugin_deactivate')
                        ,type: a.type
                        ,handler: this.deactivatePlugin
                    });
                } else {
                    m.push({
                        text: _('plugin_activate')
                        ,type: a.type
                        ,handler: this.activatePlugin
                    });
                }
            }
        }
        if (ui.hasClass('pnew')) {
            m.push({
                text: _('duplicate_'+a.type)
                ,pk: a.pk
                ,type: a.type
                ,handler: function(itm,e) {
                    this.duplicateElement(itm,e,itm.pk,itm.type);
                }
            });
        }
        if (ui.hasClass('pdelete')) {
            m.push({
                text: _('remove_'+a.type)
                ,handler: this.removeElement
            });
        }
        m.push('-');
        if (ui.hasClass('pnew')) {
            m.push({
                text: _('add_to_category_'+a.type)
                ,handler: this._createElement
            });
        }
        if (ui.hasClass('pnewcat')) {
            m.push({
                text: _('new_category')
                ,handler: this.createCategory
            });
        }
        return m;
    }

    ,_getCategoryMenu: function(n) {
        var a = n.attributes;
        var ui = n.getUI();
        var m = [];

        m.push({
            text: '<b>'+a.text+'</b>'
            ,handler: function() { return false; }
            ,header: true
        });
        m.push('-');
        if (ui.hasClass('pnewcat')) {
            m.push({
                text: _('category_create')
                ,handler: this.createCategory
            });
        }
        if (ui.hasClass('peditcat')) {
            m.push({
                text: _('category_rename')
                ,handler: this.renameCategory
            });
        }
        if (m.length > 2) {m.push('-');}

        if (a.elementType) {
            m.push({
                text: _('add_to_category_'+a.type)
                ,handler: this._createElement
            });
        }
        this._getQuickCreateMenu(n,m);

        if (ui.hasClass('pdelcat')) {
            m.push('-');
            m.push({
                text: _('category_remove')
                ,handler: this.removeCategory
            });
        }
        return m;
    }

    ,_getRootMenu: function(n) {
        var a = n.attributes;
        var ui = n.getUI();
        var m = [];

        if (ui.hasClass('pnew')) {
            m.push({
                text: _('new_'+a.type)
                ,handler: this._createElement
            });
            m.push({
                text: _('quick_create_'+a.type)
                ,type: a.type
                ,handler: function(itm,e) {
                    this.quickCreate(itm,e,itm.type);
                }
            });
        }

        if (ui.hasClass('pnewcat')) {
            if (ui.hasClass('pnew')) {m.push('-');}
            m.push({
                text: _('new_category')
                ,handler: this.createCategory
            });
        }

        return m;
    }

    ,handleCreateClick: function(node){
        this.cm.activeNode = node;
        var type = this.cm.activeNode.id.substr(2).split('_');
        if (type[0] != 'category') {
            this._createElement(null, null, null);
        } else {
            this.createCategory(null, {target: this});
        }
    }
});
Ext.reg('modx-tree-element',MODx.tree.Element);


/**
 * Generates the Duplicate Element window
 *
 * @class MODx.window.DuplicateElement
 * @extends MODx.Window
 * @param {Object} config An object of options.
 * @xtype modx-window-element-duplicate
 */
MODx.window.DuplicateElement = function(config) {
    config = config || {};
    this.ident = config.ident || 'dupeel-'+Ext.id();
    var flds = [{
        xtype: 'hidden'
        ,name: 'id'
        ,id: 'modx-'+this.ident+'-id'
    },{
        xtype: 'textfield'
        ,fieldLabel: _('element_name_new')
        ,name: config.record.type == 'template' ? 'templatename' : 'name'
        ,id: 'modx-'+this.ident+'-name'
        ,anchor: '90%'
    }];
    if (config.record.type == 'tv') {
        flds.push({
            xtype: 'xcheckbox'
            ,fieldLabel: _('element_duplicate_values')
            ,labelSeparator: ''
            ,name: 'duplicateValues'
            ,id: 'modx-'+this.ident+'-duplicate-values'
            ,anchor: '95%'
            ,inputValue: 1
            ,checked: false
        });
    }
    Ext.applyIf(config,{
        title: _('element_duplicate')
        ,url: MODx.config.connector_url
        ,action: 'element/'+config.record.type+'/duplicate'
        ,fields: flds
        ,labelWidth: 150
    });
    MODx.window.DuplicateElement.superclass.constructor.call(this,config);
};
Ext.extend(MODx.window.DuplicateElement,MODx.Window);
Ext.reg('modx-window-element-duplicate',MODx.window.DuplicateElement);



/**
 * Generates the Rename Category window.
 *
 * @class MODx.window.RenameCategory
 * @extends MODx.Window
 * @param {Object} config An object of options.
 * @xtype modx-window-category-rename
 */
MODx.window.RenameCategory = function(config) {
    config = config || {};
    this.ident = config.ident || 'rencat-'+Ext.id();
    Ext.applyIf(config,{
        title: _('category_rename')
        ,height: 150
        ,width: 350
        ,url: MODx.config.connector_url
        ,action: 'element/category/update'
        ,fields: [{
            xtype: 'hidden'
            ,name: 'id'
            ,id: 'modx-'+this.ident+'-id'
            ,value: config.record.id
        },{
            xtype: 'textfield'
            ,fieldLabel: _('name')
            ,name: 'category'
            ,id: 'modx-'+this.ident+'-category'
            ,width: 150
            ,value: config.record.category
            ,anchor: '90%'
        }]
    });
    MODx.window.RenameCategory.superclass.constructor.call(this,config);
};
Ext.extend(MODx.window.RenameCategory,MODx.Window);
Ext.reg('modx-window-category-rename',MODx.window.RenameCategory);

/**
 * Generates the Directory Tree
 *
 * @class MODx.tree.Directory
 * @extends MODx.tree.Tree
 * @param {Object} config An object of options.
 * @xtype modx-tree-directory
 */
MODx.tree.Directory = function(config) {
    config = config || {};
    config.id = config.id || Ext.id();
    Ext.applyIf(config,{
        rootVisible: true
        ,rootName: 'Filesystem'
        ,rootId: '/'
        ,title: _('files')
        ,ddAppendOnly: false
        ,enableDrag: true
        ,enableDrop: true
        ,ddGroup: 'modx-treedrop-sources-dd'
        ,url: MODx.config.connector_url
        ,hideSourceCombo: false
        ,baseParams: {
            hideFiles: config.hideFiles || false
            ,wctx: MODx.ctx || 'web'
            ,currentAction: MODx.request.a || 0
            ,currentFile: MODx.request.file || ''
            ,source: config.source || 0
        }
        ,action: 'browser/directory/getList'
        ,primaryKey: 'dir'
        ,useDefaultToolbar: true
        ,autoExpandRoot: false
        ,tbar: [{
            icon: MODx.config.manager_url+'templates/default/images/restyle/icons/folder.png'
            ,cls: 'x-btn-icon'
            ,tooltip: {text: _('file_folder_create')}
            ,handler: this.createDirectory
            ,scope: this
            ,hidden: MODx.perm.directory_create ? false : true
        },{
            icon: MODx.config.manager_url+'templates/default/images/restyle/icons/page_white.png'
            ,cls: 'x-btn-icon'
            ,tooltip: {text: _('file_create')}
            ,handler: this.createFile
            ,scope: this
            ,hidden: MODx.perm.file_create ? false : true
        },{
            icon: MODx.config.manager_url+'templates/default/images/restyle/icons/file_upload.png'
            ,cls: 'x-btn-icon'
            ,tooltip: {text: _('upload_files')}
            ,handler: this.uploadFiles
            ,scope: this
            ,hidden: MODx.perm.file_upload ? false : true
        },'->',{
            icon: MODx.config.manager_url+'templates/default/images/restyle/icons/file_manager.png'
            ,cls: 'x-btn-icon'
            ,tooltip: {text: _('modx_browser')}
            ,handler: this.loadFileManager
            ,scope: this
            ,hidden: MODx.perm.file_manager && !MODx.browserOpen ? false : true
        }]
        ,tbarCfg: {
            id: config.id+'-tbar'
        }
    });
    MODx.tree.Directory.superclass.constructor.call(this,config);
    this.addEvents({
        'beforeUpload': true
        ,'afterUpload': true
        ,'fileBrowserSelect': true
        ,'changeSource': true
    });
    this.on('click',function(n,e) {
        n.select();
        this.cm.activeNode = n;
    },this);
    this.on('render',function() {
        var el = Ext.get(this.config.id);
        el.createChild({tag: 'div', id: this.config.id+'_tb'});
        el.createChild({tag: 'div', id: this.config.id+'_filter'});
        this.addSourceToolbar();

//        this.getRootNode().pseudoroot = true
//        console.log(this.getRootNode())

    },this);
    //this.addSourceToolbar();
    this.on('show',function() {
        if (!this.config.hideSourceCombo) {
            try { this.sourceCombo.show(); } catch (e) {}
        }
    },this);
    this._init();
    this.on('afterrender', this.showRefresh, this);
};
Ext.extend(MODx.tree.Directory,MODx.tree.Tree,{

    windows: {}

    /**
     * Create a refresh button on the root node
     *
     * @see MODx.Tree.Tree#_onAppend
     */
    ,showRefresh: function() {
        var node = this.getRootNode()
            ,inlineButtonsLang = this.getInlineButtonsLang(node)
            ,elId = node.ui.elNode.id+ '_tools'
            ,el = document.createElement('div');

        el.id = elId;
        el.className = 'modx-tree-node-tool-ct';
        node.ui.elNode.appendChild(el);

        MODx.load({
            xtype: 'modx-button'
            ,text: ''
            ,scope: this
            ,tooltip: new Ext.ToolTip({
                title: inlineButtonsLang.refresh
                ,target: this
            })
            ,node: node
            ,handler: function(btn,evt){
                evt.stopPropagation(evt);
                node.reload();
            }
            ,iconCls: 'icon-refresh'
            ,renderTo: elId
            ,listeners: {
                mouseover: function(button, e){
                    button.tooltip.onTargetOver(e);
                }
                ,mouseout: function(button, e){
                    button.tooltip.onTargetOut(e);
                }
            }
        });
    }

    ,addSourceToolbar: function() {
        this.sourceCombo = new MODx.combo.MediaSource({
            value: this.config.source || MODx.config.default_media_source
            ,listeners: {
                select:{
                    fn: this.changeSource
                    ,scope: this
                }
            }
        });
        this.searchBar = new Ext.Toolbar({
            renderTo: this.tbar
            ,id: this.config.id+'-sourcebar'
            ,items: [this.sourceCombo]
        });
        this.on('resize', function(){
            this.sourceCombo.setWidth(this.getWidth() - 12);
        }, this);
        if (this.config.hideSourceCombo) {
            try { this.sourceCombo.hide(); } catch (e) {}
        }
    }

    ,changeSource: function(sel) {
        var s = sel.getValue();
        var rn = this.getRootNode();
        if (rn) { rn.setText(sel.getRawValue()); }
        this.config.baseParams.source = s;
        this.fireEvent('changeSource',s);
        this.refresh();
    }

    /**
     * Expand the root node if appropriate
     */
    ,_init: function() {
        var treeState = Ext.state.Manager.get(this.treestate_id)
            ,rootPath = this.root.getPath('text');

        if (rootPath === treeState) {
            // Nothing to do
            return;
        }

        this.root.expand();
    }

    ,_initExpand: function() {
        var treeState = Ext.state.Manager.get(this.treestate_id);
        if (!Ext.isEmpty(this.config.openTo)) {
            this.selectPath('/'+_('files')+'/'+this.config.openTo,'text');
        } else {
            this.expandPath(treeState, 'text');
        }
    }

    ,_saveState: function(n) {
        if (!n.expanded && !n.isRoot) {
            // Node has been collapsed, grab its parent
            n = n.parentNode;
        }
        var p = n.getPath('text');
        Ext.state.Manager.set(this.treestate_id, p);
    }

    ,_handleDrag: function(dropEvent) {
        var from = dropEvent.dropNode.attributes.id;
        var to = dropEvent.target.attributes.id;
        MODx.Ajax.request({
            url: this.config.url
            ,params: {
                source: this.config.baseParams.source
                ,from: from
                ,to: to
                ,action: this.config.sortAction || 'browser/directory/sort'
                ,point: dropEvent.point
            }
            ,listeners: {
                'success': {fn:function(r) {
                    var el = dropEvent.dropNode.getUI().getTextEl();
                    if (el) {Ext.get(el).frame();}
                    this.fireEvent('afterSort',{event:dropEvent,result:r});
                },scope:this}
                ,'failure': {fn:function(r) {
                    MODx.form.Handler.errorJSON(r);
                    this.refresh();
                    return false;
                },scope:this}
            }
        });
    }

    ,getPath:function(node) {
        var path, p, a;

        // get path for non-root node
        if(node !== this.root) {
            p = node.parentNode;
            a = [node.text];
            while(p && p !== this.root) {
                a.unshift(p.text);
                p = p.parentNode;
            }
            a.unshift(this.root.attributes.path || '');
            path = a.join(this.pathSeparator);
        }

        // path for root node is it's path attribute
        else {
            path = node.attributes.path || '';
        }

        // a little bit of security: strip leading / or .
        // full path security checking has to be implemented on server
        path = path.replace(/^[\/\.]*/, '');
        return path+'/';
    }

    ,editFile: function(itm,e) {
        MODx.loadPage('system/file/edit', 'file='+this.cm.activeNode.attributes.id+'&source='+this.config.source);
    }

    ,quickUpdateFile: function(itm,e) {
        var node = this.cm.activeNode;
        MODx.Ajax.request({
            url: MODx.config.connector_url
            ,params: {
                action: 'browser/file/get'
                ,file:  node.attributes.id
                ,wctx: MODx.ctx || ''
                ,source: this.getSource()
            }
            ,listeners: {
                'success': {fn:function(response) {
                    var r = {
                        file: node.attributes.id
                        ,name: node.text
                        ,path: node.attributes.pathRelative
                        ,source: this.getSource()
                        ,content: response.object.content
                    };
                    var w = MODx.load({
                        xtype: 'modx-window-file-quick-update'
                        ,record: r
                        ,listeners: {
                            'hide':{fn:function() {this.destroy();}}
                        }
                    });
                    w.show(e.target);
                },scope:this}
            }
        });
    }

    ,createFile: function(itm,e) {
        var active = this.cm.activeNode
            ,dir = active && active.attributes && (active.isRoot || active.attributes.type == 'dir')
                ? active.attributes.id
                : '';

        MODx.loadPage('system/file/create', 'directory='+dir+'&source='+this.getSource());
    }

    ,quickCreateFile: function(itm,e) {
        var node = this.cm.activeNode;
        var r = {
            directory: node.attributes.id
            ,source: this.getSource()
        };
        var w = MODx.load({
            xtype: 'modx-window-file-quick-create'
            ,record: r
            ,listeners: {
                'success':{fn:this.refreshActiveNode,scope:this}
                ,'hide':{fn:function() {this.destroy();}}
            }
        });
        w.show(e.target);
    }

    ,browser: null

    ,loadFileManager: function(btn,e) {
        var refresh = false;
        if (this.browser === null) {
            this.browser = MODx.load({
                xtype: 'modx-browser'
                ,hideFiles: true
                ,rootVisible: false
                ,wctx: MODx.ctx
                ,source: this.config.baseParams.source
                ,listeners: {
                    'select': {fn: function(data) {
                        this.fireEvent('fileBrowserSelect',data);
                    },scope:this}
                }
            });
        } else {
            refresh = true;
        }
        if (this.browser) {
            this.browser.setSource(this.config.baseParams.source);
            if (refresh) {
                this.browser.win.tree.refresh();
            }
            this.browser.show();
        }
    }

    ,renameNode: function(field,nv,ov) {
        MODx.Ajax.request({
            url: MODx.config.connector_url
            ,params: {
                action: 'browser/file/rename'
                ,new_name: nv
                ,old_name: ov
                ,file: this.treeEditor.editNode.id
                ,wctx: MODx.ctx || ''
                ,source: this.getSource()
            }
            ,listeners: {
               'success': {fn:this.refreshActiveNode,scope:this}
            }
        });
    }

    ,renameDirectory: function(item,e) {
        var node = this.cm.activeNode;
        var r = {
            old_name: node.text
            ,name: node.text
            ,path: node.attributes.pathRelative
            ,source: this.getSource()
        };
        var w = MODx.load({
            xtype: 'modx-window-directory-rename'
            ,record: r
            ,listeners: {
                'success':{fn:this.refreshParentNode,scope:this}
                ,'hide':{fn:function() {this.destroy();}}
            }
        });
        w.show(e.target);
    }

    ,renameFile: function(item,e) {
        var node = this.cm.activeNode;
        var r = {
            old_name: node.text
            ,name: node.text
            ,path: node.attributes.pathRelative
            ,source: this.getSource()
        };
        var w = MODx.load({
            xtype: 'modx-window-file-rename'
            ,record: r
            ,listeners: {
                'success':{fn:this.refreshParentNode,scope:this}
                ,'hide':{fn:function() {this.destroy();}}
            }
        });
        w.show(e.target);
    }

    ,createDirectory: function(item,e) {
        var node = this.cm && this.cm.activeNode ? this.cm.activeNode : false;
        var r = {
            'parent': node && node.attributes.type == 'dir' ? node.attributes.pathRelative : '/'
            ,source: this.getSource()
        };
        var w = MODx.load({
            xtype: 'modx-window-directory-create'
            ,record: r
            ,listeners: {
                'success':{fn:this.refreshActiveNode,scope:this}
                ,'hide':{fn:function() {this.destroy();}}
            }
        });
        w.show(e ? e.target : Ext.getBody());
    }

    ,chmodDirectory: function(item,e) {
        var node = this.cm.activeNode;
        var r = {
            dir: node.attributes.path
            ,mode: node.attributes.perms
            ,source: this.getSource()
        };
        var w = MODx.load({
            xtype: 'modx-window-directory-chmod'
            ,record: r
            ,listeners: {
                'success':{fn:this.refreshActiveNode,scope:this}
                ,'hide':{fn:function() {this.destroy();}}
            }
        });
        w.show(e.target);
    }

    ,removeDirectory: function(item,e) {
        var node = this.cm.activeNode;
        MODx.msg.confirm({
            text: _('file_folder_remove_confirm')
            ,url: MODx.config.connector_url
            ,params: {
                action: 'browser/directory/remove'
                ,dir: node.attributes.path
                ,wctx: MODx.ctx || ''
                ,source: this.getSource()
            }
            ,listeners: {
                success: {
                    fn: this._afterRemove
                    ,scope: this
                }
            }
        });
    }

    ,removeFile: function(item,e) {
        var node = this.cm.activeNode;
        MODx.msg.confirm({
            text: _('file_confirm_remove')
            ,url: MODx.config.connector_url
            ,params: {
                action: 'browser/file/remove'
                ,file: node.attributes.id
                ,wctx: MODx.ctx || ''
                ,source: this.getSource()
            }
            ,listeners: {
                success: {
                    fn: this._afterRemove
                    ,scope: this
                }
            }
        });
    }

    /**
     * Operation executed after a node has been removed
     */
    ,_afterRemove: function() {
        this.refreshParentNode();
        this.cm.activeNode = null;
    }

    ,downloadFile: function(item,e) {
        var node = this.cm.activeNode;
        MODx.Ajax.request({
            url: MODx.config.connector_url
            ,params: {
                action: 'browser/file/download'
                ,file: node.attributes.id
                ,wctx: MODx.ctx || ''
                ,source: this.getSource()
            }
            ,listeners: {
                'success':{fn:function(r) {
                    if (!Ext.isEmpty(r.object.url)) {
                        location.href = MODx.config.connector_url+'?action=browser/file/download&download=1&file='+node.attributes.id+'&HTTP_MODAUTH='+MODx.siteId+'&source='+this.getSource()+'&wctx='+MODx.ctx;
                    }
                },scope:this}
            }
        });
    }

    ,getSource: function() {
        return this.config.baseParams.source;
    }

    ,uploadFiles: function(btn,e) {
        if (!this.uploader) {
            this.uploader = new MODx.util.MultiUploadDialog.Dialog({
                url: MODx.config.connector_url
                ,base_params: {
                    action: 'browser/file/upload'
                    ,wctx: MODx.ctx || ''
                    ,source: this.getSource()
                }
                ,cls: 'ext-ux-uploaddialog-dialog modx-upload-window'
            });
            this.uploader.on('show',this.beforeUpload,this);
            this.uploader.on('uploadsuccess',this.uploadSuccess,this);
            this.uploader.on('uploaderror',this.uploadError,this);
            this.uploader.on('uploadfailed',this.uploadFailed,this);
        }
        this.uploader.base_params.source = this.getSource();
        this.uploader.show(btn);
    }

    ,uploadError: function(dlg,file,data,rec) {}

    ,uploadFailed: function(dlg,file,rec) {}

    ,uploadSuccess:function() {
        if (this.cm.activeNode) {
            var node = this.cm.activeNode;
            if (node.isLeaf) {
                var pn = (node.isLeaf() ? node.parentNode : node);
                if (pn) {
                    pn.reload();
                } else {
                    this.refreshActiveNode();
                }
                this.fireEvent('afterUpload',node);
            } else {
                this.refreshActiveNode();
            }
        } else {
            this.refresh();
            this.fireEvent('afterUpload');
        }
    }

    ,beforeUpload: function() {
        var path = this.config.rootId || '/';
        if (this.cm.activeNode) {
            path = this.getPath(this.cm.activeNode);
            if(this.cm.activeNode.isLeaf()) {
                path = this.getPath(this.cm.activeNode.parentNode);
            }
        }

        this.uploader.setBaseParams({
            action: 'browser/file/upload'
            ,path: path
            ,wctx: MODx.ctx || ''
            ,source: this.getSource()
        });
        this.fireEvent('beforeUpload',this.cm.activeNode);
    }

});
Ext.reg('modx-tree-directory',MODx.tree.Directory);

/**
 * Generates the Create Directory window
 *
 * @class MODx.window.CreateDirectory
 * @extends MODx.Window
 * @param {Object} config An object of configuration options.
 * @xtype modx-window-directory-create
 */
MODx.window.CreateDirectory = function(config) {
    config = config || {};
    Ext.applyIf(config,{
        width: 430
        ,height: 200
        ,title: _('file_folder_create')
        ,url: MODx.config.connector_url
        ,action: 'browser/directory/create'
        ,fields: [{
            xtype: 'hidden'
            ,name: 'wctx'
            ,value: MODx.ctx || ''
        },{
            xtype: 'hidden'
            ,name: 'source'
        },{
            fieldLabel: _('name')
            ,name: 'name'
            ,xtype: 'textfield'
            ,anchor: '100%'
            ,allowBlank: false
        },{
            fieldLabel: _('file_folder_parent')
            ,name: 'parent'
            ,xtype: 'textfield'
            ,anchor: '100%'
        }]
    });
    MODx.window.CreateDirectory.superclass.constructor.call(this,config);
};
Ext.extend(MODx.window.CreateDirectory,MODx.Window);
Ext.reg('modx-window-directory-create',MODx.window.CreateDirectory);

/**
 * Generates the Chmod Directory window
 *
 * @class MODx.window.ChmodDirectory
 * @extends MODx.Window
 * @param {Object} config An object of configuration options.
 * @xtype modx-window-directory-chmod
 */
MODx.window.ChmodDirectory = function(config) {
    config = config || {};
    Ext.applyIf(config,{
        title: _('file_folder_chmod')
        ,width: 430
        ,height: 200
        ,url: MODx.config.connector_url
        ,action: 'browser/directory/chmod'
        ,fields: [{
            xtype: 'hidden'
            ,name: 'wctx'
            ,value: MODx.ctx || ''
        },{
            xtype: 'hidden'
            ,name: 'source'
        },{
            name: 'dir'
            ,fieldLabel: _('name')
            ,xtype: 'statictextfield'
            ,anchor: '100%'
            ,submitValue: true
        },{
            fieldLabel: _('mode')
            ,name: 'mode'
            ,xtype: 'textfield'
            ,anchor: '100%'
            ,allowBlank: false
        }]
    });
    MODx.window.ChmodDirectory.superclass.constructor.call(this,config);
};
Ext.extend(MODx.window.ChmodDirectory,MODx.Window);
Ext.reg('modx-window-directory-chmod',MODx.window.ChmodDirectory);

/**
 * Generates the Rename Directory window
 *
 * @class MODx.window.RenameDirectory
 * @extends MODx.Window
 * @param {Object} config An object of configuration options.
 * @xtype modx-window-directory-rename
 */
MODx.window.RenameDirectory = function(config) {
    config = config || {};
    Ext.applyIf(config,{
        title: _('rename')
        ,width: 430
        ,height: 200
        ,url: MODx.config.connector_url
        ,action: 'browser/directory/rename'
        ,fields: [{
            xtype: 'hidden'
            ,name: 'wctx'
            ,value: MODx.ctx || ''
        },{
            xtype: 'hidden'
            ,name: 'source'
        },{
            fieldLabel: _('path')
            ,name: 'path'
            ,xtype: 'statictextfield'
            ,submitValue: true
            ,anchor: '100%'
        },{
            fieldLabel: _('old_name')
            ,name: 'old_name'
            ,xtype: 'statictextfield'
            ,anchor: '100%'
        },{
            fieldLabel: _('new_name')
            ,name: 'name'
            ,xtype: 'textfield'
            ,anchor: '100%'
            ,allowBlank: false
        }]
    });
    MODx.window.RenameDirectory.superclass.constructor.call(this,config);
};
Ext.extend(MODx.window.RenameDirectory,MODx.Window);
Ext.reg('modx-window-directory-rename',MODx.window.RenameDirectory);

/**
 * Generates the Rename File window
 *
 * @class MODx.window.RenameFile
 * @extends MODx.Window
 * @param {Object} config An object of configuration options.
 * @xtype modx-window-file-rename
 */
MODx.window.RenameFile = function(config) {
    config = config || {};
    Ext.applyIf(config,{
        title: _('rename')
        ,width: 430
        ,height: 200
        ,url: MODx.config.connector_url
        ,action: 'browser/file/rename'
        ,fields: [{
            xtype: 'hidden'
            ,name: 'wctx'
            ,value: MODx.ctx || ''
        },{
            xtype: 'hidden'
            ,name: 'source'
        },{
            fieldLabel: _('path')
            ,name: 'path'
            ,xtype: 'statictextfield'
            ,submitValue: true
            ,anchor: '100%'
        },{
            fieldLabel: _('old_name')
            ,name: 'old_name'
            ,xtype: 'statictextfield'
            ,anchor: '100%'
        },{
            fieldLabel: _('new_name')
            ,name: 'name'
            ,xtype: 'textfield'
            ,anchor: '100%'
            ,allowBlank: false
        },{
            name: 'dir'
            ,xtype: 'hidden'
        }]
    });
    MODx.window.RenameFile.superclass.constructor.call(this,config);
};
Ext.extend(MODx.window.RenameFile,MODx.Window);
Ext.reg('modx-window-file-rename',MODx.window.RenameFile);

/**
 * Generates the Quick Update File window
 *
 * @class MODx.window.QuickUpdateFile
 * @extends MODx.Window
 * @param {Object} config An object of configuration options.
 * @xtype modx-window-file-quick-update
 */
MODx.window.QuickUpdateFile = function(config) {
    config = config || {};
    Ext.applyIf(config,{
        title: _('file_quick_update')
        ,width: 600
        ,height: 640
        ,autoHeight: false
        ,layout: 'anchor'
        ,url: MODx.config.connector_url
        ,action: 'browser/file/update'
        ,fields: [{
            xtype: 'hidden'
            ,name: 'wctx'
            ,value: MODx.ctx || ''
        },{
            xtype: 'hidden'
            ,name: 'source'
        },{
            xtype: 'hidden'
            ,name: 'file'
        },{
            fieldLabel: _('name')
            ,name: 'name'
            ,xtype: 'statictextfield'
            ,anchor: '100%'
        },{
            fieldLabel: _('path')
            ,name: 'path'
            ,xtype: 'statictextfield'
            ,anchor: '100%'
        },{
            fieldLabel: _('content')
            ,xtype: 'textarea'
            ,name: 'content'
            ,anchor: '100% -118'
        }]
       ,keys: [{
            key: Ext.EventObject.ENTER
            ,shift: true
            ,fn: this.submit
            ,scope: this
        }]
        ,buttons: [{
            text: config.cancelBtnText || _('cancel')
            ,scope: this
            ,handler: function() { this.hide(); }
        },{
            text: config.saveBtnText || _('save')
            ,scope: this
            ,handler: function() { this.submit(false); }
        },{
            text: config.saveBtnText || _('save_and_close')
            ,scope: this
            ,handler: this.submit
        }]
    });
    MODx.window.QuickUpdateFile.superclass.constructor.call(this,config);
};
Ext.extend(MODx.window.QuickUpdateFile,MODx.Window);
Ext.reg('modx-window-file-quick-update',MODx.window.QuickUpdateFile);

/**
 * Generates the Quick Create File window
 *
 * @class MODx.window.QuickCreateFile
 * @extends MODx.Window
 * @param {Object} config An object of configuration options.
 * @xtype modx-window-file-quick-create
 */
MODx.window.QuickCreateFile = function(config) {
    config = config || {};
    Ext.applyIf(config,{
        title: _('file_quick_create')
        ,width: 600
        ,height: 640
        ,autoHeight: false
        ,layout: 'anchor'
        ,url: MODx.config.connector_url
        ,action: 'browser/file/create'
        ,fields: [{
            xtype: 'hidden'
            ,name: 'wctx'
            ,value: MODx.ctx || ''
        },{
            xtype: 'hidden'
            ,name: 'source'
        },{
            fieldLabel: _('directory')
            ,name: 'directory'
            ,submitValue: true
            ,xtype: 'statictextfield'
            ,anchor: '100%'
        },{
            fieldLabel: _('name')
            ,name: 'name'
            ,xtype: 'textfield'
            ,anchor: '100%'
            ,allowBlank: false
        },{
            fieldLabel: _('content')
            ,xtype: 'textarea'
            ,name: 'content'
            ,anchor: '100% -120'
        }]
       ,keys: [{
            key: Ext.EventObject.ENTER
            ,shift: true
            ,fn: this.submit
            ,scope: this
        }]
        ,buttons: [{
            text: config.cancelBtnText || _('cancel')
            ,scope: this
            ,handler: function() { this.hide(); }
        },{
            text: config.saveBtnText || _('save')
            ,scope: this
            ,handler: this.submit
        }]
    });
    MODx.window.QuickCreateFile.superclass.constructor.call(this,config);
};
Ext.extend(MODx.window.QuickCreateFile,MODx.Window);
Ext.reg('modx-window-file-quick-create',MODx.window.QuickCreateFile);



/**
 * Light container with all available media sources trees
 *
 * @class MODx.panel.FileTree
 * @extends Ext.Container
 * @param {Object} config
 * @xtype modx-panel-filetree
 */
MODx.panel.FileTree = function(config) {
    config = config || {};
    Ext.applyIf(config, {
        _treePrefix: 'source-tree-'
        ,id: 'modx-leftbar-filetree'
        ,autoHeight: true
        ,defaults: {
            autoHeight: true
            ,border: false
        }
    });
    MODx.panel.FileTree.superclass.constructor.call(this, config);
    this.on('render', this.getSourceList, this);
};
Ext.extend(MODx.panel.FileTree, Ext.Container, {
    /**
     * Query the media sources list
     */
    getSourceList: function() {
        MODx.Ajax.request({
            url: MODx.config.connectors_url
            ,params: {
                action: 'source/getList'
            }
            ,listeners: {
                success: {
                    fn: function(data) {
                        this.onSourceListReceived(data.results);
                    }
                    ,scope:this
                }
                ,failure: {
                    fn: function(data) {
                        // Check if this really is an error
                        if (data.total > 0 && data.results != undefined) {
                            this.onSourceListReceived(data.results);
                        }
                        return false;
                    }
                    ,scope: this
                }
            }
        })
    }

    /**
     * Iterate over the given media sources list to add their trees
     *
     * @param {Array} sources
     */
    ,onSourceListReceived: function(sources) {
        for (var k = 0; k < sources.length; k++) {
            var source = sources[k]
                ,exists = this.getComponent(this._treePrefix + source.id);

            if (!exists) {
                var tree = this.loadTree(source);
            }

            this.add(tree);
            tree = exists = void 0;
        }
        this.doLayout();
    }

    /**
     * Load the tree configuration for the given media source
     *
     * @param {Object} source
     * @returns {Object}
     */
    ,loadTree: function(source) {
        return MODx.load({
            xtype: 'modx-tree-directory'
            ,itemId: this._treePrefix + source.id
            ,stateId: this._treePrefix + source.id
            ,id: this._treePrefix + source.id
            ,rootName: source.name
            ,hideSourceCombo: true
            ,source: source.id
            ,tbar: false
            ,tbarCfg: {
                hidden: true
            }
        });
    }
});
Ext.reg('modx-panel-filetree', MODx.panel.FileTree);


/**
 * Abstract class for Ext.DataView creation in MODx
 *
 * @class MODx.DataView
 * @extends Ext.DataView
 * @constructor
 * @param {Object} config An object of options.
 * @xtype modx-dataview
 */
MODx.DataView = function(config) {
    config = config || {};
    this._loadStore(config);

    Ext.applyIf(config.listeners || {},{
        'loadexception': {fn:this.onLoadException, scope: this}
        ,'beforeselect': {fn:function(view){ return view.store.getRange().length > 0;}}
        ,'contextmenu': {fn:this._showContextMenu, scope: this}
    });
    Ext.applyIf(config,{
        store: this.store
        ,singleSelect: true
        ,overClass: 'x-view-over'
        ,itemSelector: 'div.modx-pb-thumb-wrap'
        ,emptyText: '<div style="padding:10px;">'+_('file_err_filter')+'</div>'
        ,closeAction: 'hide'
    });
    MODx.DataView.superclass.constructor.call(this,config);
    this.config = config;
    this.cm = new Ext.menu.Menu();
};
Ext.extend(MODx.DataView,Ext.DataView,{
    lookup: {}

    ,onLoadException: function(){
        this.getEl().update('<div style="padding:10px;">'+_('data_err_load')+'</div>');
    }

    /**
     * Add context menu items to the dataview.
     * @param {Object, Array} items Either an Object config or array of Object configs.
     */
    ,_addContextMenuItem: function(items) {
        var a = items, l = a.length;
        for(var i=0;i<l;i=i+1) {
            var options = a[i];

            if (options === '-') {
                this.cm.add('-');
                continue;
            }
            var h = Ext.emptyFn;
            if (options.handler) {
                h = eval(options.handler);
            } else {
                h = function(itm,e) {
                    var o = itm.options;
                    var id = this.cm.activeNode.id.split('_'); id = id[1];
                    var w = Ext.get('modx_content');
                    if (o.confirm) {
                        Ext.Msg.confirm('',o.confirm,function(e) {
                            if (e === 'yes') {
                                var a = Ext.urlEncode(o.params || {action: o.action});
                                var s = '?id='+id+'&'+a;
                                if (w === null) {
                                    location.href = s;
                                } else { w.dom.src = s; }
                            }
                        },this);
                    } else {
                        var a = Ext.urlEncode(o.params);
                        var s = '?id='+id+'&'+a;
                        if (w === null) {
                            location.href = s;
                        } else { w.dom.src = s; }
                    }
                };
            }
            this.cm.add({
                id: options.id
                ,text: options.text
                ,scope: this
                ,options: options
                ,handler: h
            });
        }
    }


    ,_loadStore: function(config) {
        this.store = new Ext.data.JsonStore({
            url: config.url
            ,baseParams: config.baseParams || {
                action: 'browser/directory/getList'
                ,wctx: config.wctx || MODx.ctx
                ,dir: config.openTo || ''
                ,source: config.source || 0
            }
            ,root: config.root || 'results'
            ,fields: config.fields
            ,totalProperty: 'total'
            ,listeners: {
                'load': {fn:function(){ this.select(0); }, scope:this, single:true}
            }
        });
        this.store.load();
    }

    ,_showContextMenu: function(v,i,n,e) {
        e.preventDefault();
        var data = this.lookup[n.id];
        var m = this.cm;
        m.removeAll();
        if (data.menu) {
            this._addContextMenuItem(data.menu);
            m.show(n,'tl-c?');
        }
        m.activeNode = n;
    }
});
Ext.reg('modx-dataview',MODx.DataView);


Ext.namespace('MODx.browser');

MODx.Browser = function(config) {
    if (MODx.browserOpen && !config.multiple) return false;
    if (!config.multiple) MODx.browserOpen = true;

    config = config || {};
    Ext.applyIf(config,{
        onSelect: function(data) {}
        ,scope: this
        ,source: config.source || 1
        ,cls: 'modx-browser'
        ,closeAction: 'hide'
    });
    MODx.Browser.superclass.constructor.call(this,config);
    this.config = config;

    this.win = new MODx.browser.Window(config);
    this.win.reset();
};
Ext.extend(MODx.Browser,Ext.Component,{
    show: function(el) { if (this.win) { this.win.show(el); } }
    ,hide: function() { if (this.win) { this.win.hide(); } }

    ,setSource: function(source) {
        this.config.source = source;
        this.win.tree.config.baseParams.source = source;
        this.win.view.config.baseParams.source = source;
    }

});
Ext.reg('modx-browser',MODx.Browser);

MODx.browser.Window = function(config) {
    config = config || {};
    this.ident = Ext.id();
    this.view = MODx.load({
        xtype: 'modx-browser-view'
        ,onSelect: {fn: this.onSelect, scope: this}
        ,source: config.source || MODx.config.default_media_source
        ,allowedFileTypes: config.allowedFileTypes || ''
        ,wctx: config.wctx || 'web'
        ,openTo: config.openTo || ''
        ,ident: this.ident
        ,id: this.ident+'-view'
    });
    this.tree = MODx.load({
        xtype: 'modx-tree-directory'
        ,onUpload: function() { this.view.run(); }
        ,scope: this
        ,source: config.source || MODx.config.default_media_source
        ,hideFiles: config.hideFiles || false
        ,openTo: config.openTo || ''
        ,ident: this.ident
        ,rootId: config.rootId || '/'
        ,rootName: _('files')
        ,rootVisible: config.rootVisible == undefined || !Ext.isEmpty(config.rootId)
        ,id: this.ident+'-tree'
        ,hideSourceCombo: config.hideSourceCombo || false
        ,listeners: {
            'afterUpload': {fn:function() { this.view.run(); },scope:this}
            ,'changeSource': {fn:function(s) {
                this.config.source = s;
                this.view.config.source = s;
                this.view.baseParams.source = s;
                this.view.dir = '/';
                this.view.run();
            },scope:this}
            ,'nodeclick': {fn:function(n,e) {
                n.select();
                e.preventDefault();
                e.stopPropagation();
                return false;
            },scope:this}
            ,afterrender: {
                fn: function(tree) {
                    tree.root.expand();
                }
                ,scope: this
            }
        }
    });
    this.tree.on('click',function(node,e) {
        this.load(node.id);
    },this);

    Ext.applyIf(config,{
        title: _('modx_browser')+' ('+(MODx.ctx ? MODx.ctx : 'web')+')'
        ,cls: 'modx-pb-win'
        ,layout: 'border'
        ,minWidth: 500
        ,minHeight: 300
        ,width: '90%'
        ,height: 500
        ,modal: false
        ,closeAction: 'hide'
        ,border: false
        ,items: [{
            id: this.ident+'-browser-tree'
            ,cls: 'modx-pb-browser-tree'
            ,region: 'west'
            ,width: 250
            ,height: '100%'
            ,items: this.tree
            ,autoScroll: true
            ,split: true
            ,border: false
        },{
            id: this.ident+'-browser-view'
            ,cls: 'modx-pb-view-ct'
            ,region: 'center'
            ,autoScroll: true
            //,width: 635
            ,border: false
            ,items: this.view
            ,tbar: this.getToolbar()
        },{
            id: this.ident+'-img-detail-panel'
            ,cls: 'modx-pb-details-ct'
            ,region: 'east'
            ,split: true
            ,border: false
            ,width: 250
        }]
        ,buttons: [{
            id: this.ident+'-ok-btn'
            ,text: _('ok')
            ,handler: this.onSelect
            ,scope: this
        },{
            id: this.ident+'-cancel-btn'
            ,text: _('cancel')
            ,handler: this.close
            ,scope: this
        }]
        ,keys: {
            key: 27
            ,handler: this.hide
            ,scope: this
        }
    });
    MODx.browser.Window.superclass.constructor.call(this,config);
    this.config = config;
    this.addEvents({
        'select': true
    });
};
Ext.extend(MODx.browser.Window,Ext.Window,{
    returnEl: null

    ,filter : function(){
        var filter = Ext.getCmp(this.ident+'filter');
        this.view.store.filter('name', filter.getValue(),true);
        this.view.select(0);
    }

    ,setReturn: function(el) {
        this.returnEl = el;
    }

    ,load: function(dir) {
        dir = dir || (Ext.isEmpty(this.config.openTo) ? '' : this.config.openTo);
        this.view.run({
            dir: dir
            ,source: this.config.source
            ,allowedFileTypes: this.config.allowedFileTypes || ''
            ,wctx: this.config.wctx || 'web'
        });
        this.sortImages();
    }

    ,sortImages : function(){
        var v = Ext.getCmp(this.ident+'sortSelect').getValue();
        this.view.store.sort(v, v == 'name' ? 'asc' : 'desc');
        this.view.select(0);
    }

    ,reset: function(){
        if(this.rendered){
            Ext.getCmp(this.ident+'filter').reset();
            this.view.getEl().dom.scrollTop = 0;
        }
        this.view.store.clearFilter();
        this.view.select(0);
    }

    ,getToolbar: function() {
        return ['-', {
            text: _('filter')+':'
            ,xtype: 'label'
        }, '-', {
            xtype: 'textfield'
            ,id: this.ident+'filter'
            ,selectOnFocus: true
            ,width: 100
            ,listeners: {
                'render': {fn:function(){
                    Ext.getCmp(this.ident+'filter').getEl().on('keyup', function(){
                        this.filter();
                    }, this, {buffer:500});
                }, scope:this}
            }
        }, '-', {
            text: _('sort_by')+':'
            ,xtype: 'label'
        }, '-', {
            id: this.ident+'sortSelect'
            ,xtype: 'combo'
            ,typeAhead: true
            ,triggerAction: 'all'
            ,width: 100
            ,editable: false
            ,mode: 'local'
            ,displayField: 'desc'
            ,valueField: 'name'
            ,lazyInit: false
            ,value: MODx.config.modx_browser_default_sort || 'name'
            ,store: new Ext.data.SimpleStore({
                fields: ['name', 'desc'],
                data : [['name',_('name')],['size',_('file_size')],['lastmod',_('last_modified')]]
            })
            ,listeners: {
                'select': {fn:this.sortImages, scope:this}
            }
        }];
    }

    ,onSelect: function(data) {
        var selNode = this.view.getSelectedNodes()[0];
        var callback = this.config.onSelect || this.onSelectHandler;
        var lookup = this.view.lookup;
        var scope = this.config.scope;
        this.hide(this.config.animEl || null,function(){
            if(selNode && callback){
                var data = lookup[selNode.id];
                Ext.callback(callback,scope || this,[data]);
                this.fireEvent('select',data);
            }
        },scope);
    }

    ,onSelectHandler: function(data) {
        Ext.get(this.returnEl).dom.value = unescape(data.url);
    }
});
Ext.reg('modx-browser-window',MODx.browser.Window);

MODx.browser.View = function(config) {
    config = config || {};
    this.ident = config.ident+'-view' || 'modx-browser-'+Ext.id()+'-view';

    this._initTemplates();
    Ext.applyIf(config,{
        url: MODx.config.connector_url
        ,id: this.ident
        ,fields: [
            'name','cls','url','relativeUrl','fullRelativeUrl','image','image_width','image_height','thumb','thumb_width','thumb_height','pathname','ext','disabled'
            ,{name:'size', type: 'float'}
            ,{name:'lastmod', type:'date', dateFormat:'timestamp'}
            ,'menu'
        ]
        ,baseParams: {
            action: 'browser/directory/getfiles'
            ,prependPath: config.prependPath || null
            ,prependUrl: config.prependUrl || null
            ,source: config.source || 1
            // @todo: this overrides the media source configuration
            ,allowedFileTypes: config.allowedFileTypes || ''
            ,wctx: config.wctx || 'web'
            ,dir: config.openTo || ''
        }
        ,sortInfo: {
            field: MODx.config.modx_browser_default_sort || 'name'
            ,direction: 'ASC'
        }
        ,tpl: this.templates.thumb
        ,listeners: {
            'selectionchange': {fn:this.showDetails, scope:this, buffer:100}
            ,'dblclick': config.onSelect || {fn:Ext.emptyFn,scope:this}
        }
        ,prepareData: this.formatData.createDelegate(this)
    });
    MODx.browser.View.superclass.constructor.call(this,config);
};
Ext.extend(MODx.browser.View,MODx.DataView,{
    templates: {}

    ,removeFile: function(item,e) {
        var node = this.cm.activeNode;
        var data = this.lookup[node.id];
        var d = '';
        if (typeof(this.dir) != 'object' && typeof(this.dir) != 'undefined') { d = this.dir; }
        MODx.msg.confirm({
            text: _('file_remove_confirm')
            ,url: MODx.config.connector_url
            ,params: {
                action: 'browser/file/remove'
                ,file: d+'/'+node.id
                ,source: this.config.source
                ,wctx: this.config.wctx || 'web'
            }
            ,listeners: {
                'success': {fn:function(r) {
                    this.run();
                },scope:this}
            }
        });
    }

    ,run: function(p) {
        p = p || {};
        if (p.dir) { this.dir = p.dir; }
        Ext.applyIf(p,{
            action: 'browser/directory/getFiles'
            ,dir: this.dir
            ,source: this.config.source || MODx.config.default_media_source
        });
        this.store.load({
            params: p
            ,callback: function() { this.refresh(); }
            ,scope: this
        });
    }

    ,showDetails : function(){
        var selNode = this.getSelectedNodes();
        var detailEl = Ext.getCmp(this.config.ident+'-img-detail-panel').body;
        var okBtn = Ext.getCmp(this.ident+'-ok-btn');
        if (selNode && selNode.length > 0) {
            selNode = selNode[0];
            if (okBtn) {
                okBtn.enable();
            }
            var data = this.lookup[selNode.id];
            detailEl.hide();
            this.templates.details.overwrite(detailEl, data);
            detailEl.slideIn('l', {stopFx:true,duration:'.2'});
        } else {
            if (okBtn) {
                okBtn.disable();
            }
            detailEl.update('');
        }
    }
    ,formatData: function(data) {
        var formatSize = function(size){
            if(size < 1024) {
                return size + " bytes";
            } else {
                return (Math.round(((size*10) / 1024))/10) + " KB";
            }
        };
        data.shortName = Ext.util.Format.ellipsis(data.name,18);
        data.sizeString = data.size != 0 ? formatSize(data.size) : 0;
        data.dateString = !Ext.isEmpty(data.lastmod) ? new Date(data.lastmod).format("m/d/Y g:i a") : 0;
        this.lookup[data.name] = data;
        return data;
    }
    ,_initTemplates: function() {
        this.templates.thumb = new Ext.XTemplate(
            '<tpl for=".">'
                ,'<div class="modx-pb-thumb-wrap" id="{name}" title="{name}">'
                ,'<div class="modx-pb-thumb"><img src="{thumb}" title="{name}" /></div>'
                ,'<span>{shortName}</span></div>'
            ,'</tpl>'
        );
        this.templates.thumb.compile();

        this.templates.details = new Ext.XTemplate(
            '<div class="details">'
            ,'<tpl for=".">'
                ,'<div class="modx-pb-detail-thumb">'
                    ,'<img src="{thumb}" alt="" onclick="Ext.getCmp(\''+this.ident+'\').showFullView(\'{name}\',\''+this.ident+'\'); return false;" />'
                ,'</div>'
                ,'<div class="modx-pb-details-info">'
                ,'<b>'+_('file_name')+':</b>'
                ,'<span>{name}</span>'
                ,'<tpl if="this.isEmpty(sizeString) == false">'
                    ,'<b>'+_('file_size')+':</b>'
                    ,'<span>{sizeString}</span>'
                ,'</tpl>'
                ,'<tpl if="this.isEmpty(dateString) == false">'
                    ,'<b>'+_('last_modified')+':</b>'
                    ,'<span>{dateString}</span></div>'
                ,'</tpl>'
            ,'</tpl>'
            ,'</div>'
        ,{
            isEmpty: function (v) {
                return (v == '' || v == null || v == undefined || v === 0);
            }
        });
        this.templates.details.compile();
    }
    ,showFullView: function(name,ident) {
        var data = this.lookup[name];
        if (!data) return;

        if (!this.fvWin) {
            this.fvWin = new Ext.Window({
                layout:'fit'
                ,width: 600
                ,height: 450
                ,closeAction:'hide'
                ,plain: true
                ,items: [{
                    id: this.ident+'modx-view-item-full'
                    ,cls: 'modx-pb-fullview'
                    ,html: ''
                }]
                ,buttons: [{
                    text: _('close')
                    ,handler: function() { this.fvWin.hide(); }
                    ,scope: this
                }]
            });
        }
        this.fvWin.show();
        var w = data.image_width < 250 ? 250 : data.image_width;
        var h = data.image_height < 200 ? 200 : data.image_height;
        this.fvWin.setSize(w,h);
        this.fvWin.center();
        this.fvWin.setTitle(data.name);
        Ext.get(this.ident+'modx-view-item-full').update('<img src="'+data.image+'" alt="" class="modx-pb-fullview-img" onclick="Ext.getCmp(\''+ident+'\').fvWin.hide();" />');
    }
});
Ext.reg('modx-browser-view',MODx.browser.View);

/**
 * Loads the MODx Ext-driven Layout
 *
 * @class MODx.Layout
 * @extends Ext.Viewport
 * @param {Object} config An object of config options.
 * @xtype modx-layout
 */
Ext.apply(Ext, {
    isFirebug: (window.console && window.console.firebug)
});

MODx.Layout = function(config){
    config = config || {};
    Ext.BLANK_IMAGE_URL = MODx.config.manager_url+'assets/ext3/resources/images/default/s.gif';
    Ext.Ajax.defaultHeaders = {
        'modAuth': config.auth
    };
    Ext.Ajax.extraParams = {
        'HTTP_MODAUTH': config.auth
    };
    MODx.siteId = config.auth;
    MODx.expandHelp = !Ext.isEmpty(MODx.config.inline_help);

    var sp = new MODx.HttpProvider();
    Ext.state.Manager.setProvider(sp);
    sp.initState(MODx.defaultState);

    config.showTree = false;

    Ext.applyIf(config, {
         layout: 'border'
        ,id: 'modx-layout'
        ,stateSave: true
        ,items: this.buildLayout(config)
    });
    MODx.Layout.superclass.constructor.call(this,config);
    this.config = config;

    this.addEvents({
        'afterLayout': true
        ,'loadKeyMap': true
        ,'loadTabs': true
    });
    this.loadKeys();
    if (!config.showTree) {
        Ext.getCmp('modx-leftbar-tabs').collapse(false);
        Ext.get('modx-leftbar').hide();
        Ext.get('modx-leftbar-tabs-xcollapsed').setStyle('display','none');
    }
    this.fireEvent('afterLayout');
};
Ext.extend(MODx.Layout, Ext.Viewport, {
    /**
     * Wrapper method to build the layout regions
     *
     * @param {Object} config
     *
     * @returns {Array}
     */
    buildLayout: function(config) {
        var items = []
            ,north = this.getNorth(config)
            ,west = this.getWest(config)
            ,center = this.getCenter(config)
            ,south = this.getSouth(config)
            ,east = this.getEast(config);

        if (north && Ext.isObject(north)) {
            items.push(north);
        }
        if (west && Ext.isObject(west)) {
            items.push(west);
        }
        if (center && Ext.isObject(center)) {
            items.push(center);
        }
        if (south && Ext.isObject(south)) {
            items.push(south);
        }
        if (east && Ext.isObject(east)) {
            items.push(east);
        }

        return items;
    }
    /**
     * Build the north region (header)
     *
     * @param {Object} config
     *
     * @returns {Object|void}
     */
    ,getNorth: function(config) {
        return {
            xtype: 'box'
            ,region: 'north'
            ,applyTo: 'modx-header'
            //,height: 55
        };
    }
    /**
     * Build the west region (trees)
     *
     * @param {Object} config
     *
     * @returns {Object|void}
     */
    ,getWest: function(config) {
        var tabs = [];
        if (MODx.perm.resource_tree) {
            tabs.push({
                title: _('resources')
                ,xtype: 'modx-tree-resource'
                ,id: 'modx-resource-tree'
            });
            config.showTree = true;
        }
        if (MODx.perm.element_tree) {
            tabs.push({
                title: _('elements')
                ,xtype: 'modx-tree-element'
                ,id: 'modx-tree-element'
            });
            config.showTree = true;
        }
        if (MODx.perm.file_tree) {
            tabs.push({
                title: _('files')
                ,xtype: 'modx-panel-filetree'
                ,id: 'modx-file-tree'
            });
            config.showTree = true;
        }
        var activeTab = 0;

        return {
            region: 'west'
            ,applyTo: 'modx-leftbar'
            ,id: 'modx-leftbar-tabs'
            ,split: true
            ,width: 310
            ,minSize: 288
            ,maxSize: 800
            ,autoScroll: true
            ,unstyled: true
            ,collapseMode: 'mini'
            ,useSplitTips: true
            ,monitorResize: true
            ,layout: 'anchor'
            ,items: [{
                xtype: 'modx-tabs'
                ,plain: true
                ,defaults: {
                    autoScroll: true
                    ,fitToFrame: true
                }
                ,id: 'modx-leftbar-tabpanel'
                ,border: false
                ,anchor: '100%'
                ,activeTab: activeTab
                ,stateful: true
                //,stateId: 'modx-leftbar-tabs'
                ,stateEvents: ['tabchange']
                ,getState:function() {
                    return {
                        activeTab: this.items.indexOf(this.getActiveTab())
                    };
                }
                ,items: tabs
            }]
            ,getState: function() {
                // The region's attributes we want to save/restore
                return {
                    collapsed: this.collapsed
                    ,width: this.width
                };
            }
            ,listeners:{
                beforestatesave: this.onBeforeSaveState
                ,scope: this
            }
        };
    }
    /**
     * Build the center region (main content)
     *
     * @param {Object} config
     *
     * @returns {Object|void}
     */
    ,getCenter: function(config) {
        return {
            region: 'center'
            ,applyTo: 'modx-content'
            ,padding: '0 1px 0 0'
            ,bodyStyle: 'background-color:transparent;'
            ,id: 'modx-content'
            ,border: false
            ,autoScroll: true
        };
    }
    /**
     * Build the south region (footer)
     *
     * @param {Object} config
     *
     * @returns {Object|void}
     */
    ,getSouth: function(config) {

    }
    /**
     * Build the east region
     *
     * @param {Object} config
     *
     * @returns {Object|void}
     */
    ,getEast: function(config) {

    }

    /**
     * Convenient method to target the west region
     *
     * @returns {Ext.Component|void}
     */
    ,getLeftBar: function() {
        var nav = Ext.getCmp('modx-leftbar-tabpanel');
        if (nav) {
            return nav;
        }

        return null;
    }

    /**
     * Add the given item(s) to the west container
     *
     * @param {Object|Array} items
     */
    ,addToLeftBar: function(items) {
        var nav = this.getLeftBar();
        if (nav && items) {
            nav.add(items);
            this.onAfterLeftBarAdded(nav, items);
        }
    }
    /**
     * Method executed after some item(s) has been added to the west container
     *
     * @param {Ext.Component} nav The container
     * @param {Object|Array} items Added item(s)
     */
    ,onAfterLeftBarAdded: function(nav, items) {

    }


    /**
     * Set keyboard shortcuts
     */
    ,loadKeys: function() {
        Ext.KeyMap.prototype.stopEvent = true;
        var k = new Ext.KeyMap(Ext.get(document));
        // ctrl + shift + h : toggle left bar
        k.addBinding({
            key: Ext.EventObject.H
            ,ctrl: true
            ,shift: true
            ,fn: this.toggleLeftbar
            ,scope: this
            ,stopEvent: true
        });
        // ctrl + shift + n : new document
        k.addBinding({
            key: Ext.EventObject.N
            ,ctrl: true
            ,shift: true
            ,fn: function() {
                var t = Ext.getCmp('modx-resource-tree');
                if (t) { t.quickCreate(document,{},'modDocument','web',0); }
            }
            ,stopEvent: true
        });
        // ctrl + shift + u : clear cache
        k.addBinding({
            key: Ext.EventObject.U
            ,ctrl: true
            ,shift: true
            ,alt: false
            ,fn: MODx.clearCache
            ,scope: this
            ,stopEvent: true
        });

        this.fireEvent('loadKeyMap',{
            keymap: k
        });
    }
    /**
     * Wrapper method to refresh all available trees
     */
    ,refreshTrees: function() {
        var t;
        t = Ext.getCmp('modx-resource-tree');
        if (t && t.rendered) {
            t.refresh();
        }
        t = Ext.getCmp('modx-tree-element');
        if (t && t.rendered) {
            t.refresh();
        }
        t = Ext.getCmp('modx-file-tree');
        if (t && t.rendered) {
            // Iterate over panel's items (trees) to refresh them
            t.items.each(function(tree, idx) {
                tree.refresh();
            });
        }
    }
    // Why here & why assuming visible ??
    ,leftbarVisible: true
    /**
     * Toggle left bar
     */
    ,toggleLeftbar: function() {
        this.leftbarVisible ? this.hideLeftbar(true) : this.showLeftbar(true);
        // Toggle the left bar visibility
        this.leftbarVisible = !this.leftbarVisible;
    }
    /**
     * Hide the left bar
     *
     * @param {Boolean} [anim] Whether or not to animate the transition
     * @param {Boolean} [state] Whether or not to save the component's state
     */
    ,hideLeftbar: function(anim, state) {
        Ext.getCmp('modx-leftbar-tabs').collapse(anim);
        if (Ext.isBoolean(state)) {
            this.stateSave = state;
        }
    }
    /**
     * Show the left bar
     *
     * @param {Boolean} [anim] Whether or not to animate the transition
     */
    ,showLeftbar: function(anim) {
        Ext.getCmp('modx-leftbar-tabs').expand(anim);
    }
    /**
     * Actions performed before we save the component state
     *
     * @param {Ext.Component} component
     * @param {Object} state
     */
    ,onBeforeSaveState: function(component, state) {
        var collapsed = state.collapsed;
        if (collapsed && !this.stateSave) {
            // Stateful status changed to prevent saving the state
            this.stateSave = true;
            return false;
        }
        if (!collapsed) {
            var wrap = Ext.get('modx-leftbar').down('div');
            if (!wrap.isVisible()) {
                // Set the "masking div" to visible
                wrap.setVisible(true);
                Ext.getCmp('modx-leftbar-tabpanel').expand(true);
            }
        }
    }
});

/**
 * Handles layout functions. In module format for easier privitization.
 * @class MODx.LayoutMgr
 */
MODx.LayoutMgr = function() {
    var _activeMenu = 'menu0';
    return {
        loadPage: function(action, parameters) {
            // Handles url, passed as first argument
            var parts = [];
            if (action) {
                if (action.substr(0,1) == '?' || (action.substr(0, "index.php?".length) == 'index.php?')) {
                    parts.push(action);
                } else {
                    parts.push('?a=' + action);
                }
            }
            if (parameters) {
                parts.push(parameters);
            }
            var url = parts.join('&');
            if (MODx.fireEvent('beforeLoadPage', url)) {
                location.href = url;
            }
            return false;
        }
        ,changeMenu: function(a,sm) {
            if (sm === _activeMenu) return false;

            Ext.get(sm).addClass('active');
            var om = Ext.get(_activeMenu);
            if (om) om.removeClass('active');
            _activeMenu = sm;
            return false;
        }
    }
}();

/* aliases for quicker reference */
MODx.loadPage = MODx.LayoutMgr.loadPage;
MODx.showDashboard = MODx.LayoutMgr.showDashboard;
MODx.hideDashboard = MODx.LayoutMgr.hideDashboard;
MODx.changeMenu = MODx.LayoutMgr.changeMenu;

MODx.Layout.Default = function(config,getStore) {
    config = config || {};
    Ext.applyIf(config,{
    });

    MODx.Layout.Default.superclass.constructor.call(this,config);
    return this;
};
Ext.extend(MODx.Layout.Default,MODx.Layout);
Ext.reg('modx-layout',MODx.Layout.Default);