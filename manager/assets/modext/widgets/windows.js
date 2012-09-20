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
                action: 'duplicate'
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
            url: this.config.url || MODx.config.connectors_url+'resource/index.php'
            ,baseParams: this.config.baseParams || {
                action: 'duplicate'
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
        ,url: MODx.config.connectors_url+'element/category.php'
        ,action: 'create'
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
        ,url: MODx.config.connectors_url+'workspace/namespace.php'
        ,action: 'create'
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
    this.ident = config.ident || 'qcc'+Ext.id();
    Ext.applyIf(config,{
        title: _('quick_create_chunk')
        ,id: this.ident
        ,width: 600
        ,height: 640
        ,autoHeight: false
        ,layout: 'anchor'
        ,url: MODx.config.connectors_url+'element/chunk.php'
        ,action: 'create'
        ,fields: [{
            xtype: 'textfield'
            ,name: 'name'
            ,id: 'modx-'+this.ident+'-name'
            ,fieldLabel: _('name')
            ,anchor: '100%'
        },{
            xtype: 'modx-combo-category'
            ,name: 'category'
            ,fieldLabel: _('category')
            ,id: 'modx-'+this.ident+'-category'
            ,anchor: '100%'
        },{
            xtype: 'textarea'
            ,name: 'description'
            ,id: 'modx-'+this.ident+'-description'
            ,fieldLabel: _('description')
            ,anchor: '100%'
            ,rows: 2
        },{
            xtype: 'textarea'
            ,name: 'snippet'
            ,id: 'modx-'+this.ident+'-snippet'
            ,fieldLabel: _('code')
            ,anchor: '100% -216'
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
        ,height: 640
        ,autoHeight: false
        ,layout: 'anchor'
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
            ,anchor: '100%'
        },{
            xtype: 'modx-combo-category'
            ,name: 'category'
            ,fieldLabel: _('category')
            ,id: 'modx-'+this.ident+'-category'
            ,anchor: '100%'
        },{
            xtype: 'textarea'
            ,name: 'description'
            ,id: 'modx-'+this.ident+'-description'
            ,fieldLabel: _('description')
            ,anchor: '100%'
            ,rows: 2
        },{
            xtype: 'textarea'
            ,name: 'snippet'
            ,id: 'modx-'+this.ident+'-snippet'
            ,fieldLabel: _('code')
            ,anchor: '100% -246'
        },{
            xtype: 'xcheckbox'
            ,name: 'clearCache'
            ,id: 'modx-'+this.ident+'-clearcache'
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
Ext.extend(MODx.window.QuickUpdateChunk,MODx.Window);
Ext.reg('modx-window-quick-update-chunk',MODx.window.QuickUpdateChunk);

MODx.window.QuickCreateTemplate = function(config) {
    config = config || {};
    this.ident = config.ident || 'qct'+Ext.id();
    Ext.applyIf(config,{
        title: _('quick_create_template')
        ,id: this.ident
        ,width: 600
        ,height: 640
        ,autoHeight: false
        ,layout: 'anchor'
        ,url: MODx.config.connectors_url+'element/template.php'
        ,action: 'create'
        ,fields: [{
            xtype: 'textfield'
            ,name: 'templatename'
            ,id: 'modx-'+this.ident+'-name'
            ,fieldLabel: _('name')
            ,anchor: '100%'
        },{
            xtype: 'modx-combo-category'
            ,name: 'category'
            ,fieldLabel: _('category')
            ,id: 'modx-'+this.ident+'-category'
            ,anchor: '100%'
        },{
            xtype: 'textarea'
            ,name: 'description'
            ,id: 'modx-'+this.ident+'-description'
            ,fieldLabel: _('description')
            ,anchor: '100%'
            ,rows: 2
        },{
            xtype: 'textarea'
            ,name: 'content'
            ,id: 'modx-'+this.ident+'-content'
            ,fieldLabel: _('code')
            ,anchor: '100% -216'
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
        ,height: 640
        ,autoHeight: false
        ,layout: 'anchor'
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
            ,anchor: '100%'
        },{
            xtype: 'modx-combo-category'
            ,name: 'category'
            ,fieldLabel: _('category')
            ,id: 'modx-'+this.ident+'-category'
            ,anchor: '100%'
        },{
            xtype: 'textarea'
            ,name: 'description'
            ,id: 'modx-'+this.ident+'-description'
            ,fieldLabel: _('description')
            ,anchor: '100%'
            ,rows: 2
        },{
            xtype: 'textarea'
            ,name: 'content'
            ,id: 'modx-'+this.ident+'-content'
            ,fieldLabel: _('code')
            ,anchor: '100% -246'
        },{
            xtype: 'xcheckbox'
            ,name: 'clearCache'
            ,id: 'modx-'+this.ident+'-clearcache'
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
Ext.extend(MODx.window.QuickUpdateTemplate,MODx.Window);
Ext.reg('modx-window-quick-update-template',MODx.window.QuickUpdateTemplate);


MODx.window.QuickCreateSnippet = function(config) {
    config = config || {};
    this.ident = config.ident || 'qcs'+Ext.id();
    Ext.applyIf(config,{
        title: _('quick_create_snippet')
        ,id: this.ident
        ,width: 600
        ,height: 640
        ,autoHeight: false
        ,layout: 'anchor'
        ,url: MODx.config.connectors_url+'element/snippet.php'
        ,action: 'create'
        ,fields: [{
            xtype: 'textfield'
            ,name: 'name'
            ,id: 'modx-'+this.ident+'-name'
            ,fieldLabel: _('name')
            ,anchor: '100%'
        },{
            xtype: 'modx-combo-category'
            ,name: 'category'
            ,fieldLabel: _('category')
            ,id: 'modx-'+this.ident+'-category'
            ,anchor: '100%'
        },{
            xtype: 'textarea'
            ,name: 'description'
            ,id: 'modx-'+this.ident+'-description'
            ,fieldLabel: _('description')
            ,anchor: '100%'
            ,rows: 2
        },{
            xtype: 'textarea'
            ,name: 'snippet'
            ,id: 'modx-'+this.ident+'-snippet'
            ,fieldLabel: _('code')
            ,anchor: '100% -216'
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
        ,height: 640
        ,autoHeight: false
        ,layout: 'anchor'
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
            ,anchor: '100%'
        },{
            xtype: 'modx-combo-category'
            ,name: 'category'
            ,fieldLabel: _('category')
            ,id: 'modx-'+this.ident+'-category'
            ,anchor: '100%'
        },{
            xtype: 'textarea'
            ,name: 'description'
            ,id: 'modx-'+this.ident+'-description'
            ,fieldLabel: _('description')
            ,anchor: '100%'
            ,rows: 2
        },{
            xtype: 'textarea'
            ,name: 'snippet'
            ,id: 'modx-'+this.ident+'-snippet'
            ,fieldLabel: _('code')
            ,anchor: '100% -246'
        },{
            xtype: 'xcheckbox'
            ,name: 'clearCache'
            ,id: 'modx-'+this.ident+'-clearcache'
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
Ext.extend(MODx.window.QuickUpdateSnippet,MODx.Window);
Ext.reg('modx-window-quick-update-snippet',MODx.window.QuickUpdateSnippet);



MODx.window.QuickCreatePlugin = function(config) {
    config = config || {};
    this.ident = config.ident || 'qcp'+Ext.id();
    Ext.applyIf(config,{
        title: _('quick_create_plugin')
        ,id: this.ident
        ,width: 600
        ,height: 640
        ,autoHeight: false
        ,layout: 'anchor'
        ,url: MODx.config.connectors_url+'element/plugin.php'
        ,action: 'create'
        ,fields: [{
            xtype: 'textfield'
            ,name: 'name'
            ,id: 'modx-'+this.ident+'-name'
            ,fieldLabel: _('name')
            ,anchor: '100%'
        },{
            xtype: 'modx-combo-category'
            ,name: 'category'
            ,fieldLabel: _('category')
            ,id: 'modx-'+this.ident+'-category'
            ,anchor: '100%'
        },{
            xtype: 'textarea'
            ,name: 'description'
            ,id: 'modx-'+this.ident+'-description'
            ,fieldLabel: _('description')
            ,anchor: '100%'
            ,rows: 2
        },{
            xtype: 'textarea'
            ,name: 'plugincode'
            ,id: 'modx-'+this.ident+'-plugincode'
            ,fieldLabel: _('code')
            ,anchor: '100% -246'
        },{
            xtype: 'xcheckbox'
            ,name: 'disabled'
            ,id: 'modx-'+this.ident+'-disabled'
            ,boxLabel: _('disabled')
            ,hideLabel: true
            ,inputValue: 1
            ,checked: false
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
        ,height: 640
        ,autoHeight: false
        ,layout: 'anchor'
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
            ,anchor: '100%'
        },{
            xtype: 'modx-combo-category'
            ,name: 'category'
            ,fieldLabel: _('category')
            ,id: 'modx-'+this.ident+'-category'
            ,anchor: '100%'
        },{
            xtype: 'textarea'
            ,name: 'description'
            ,id: 'modx-'+this.ident+'-description'
            ,fieldLabel: _('description')
            ,anchor: '100%'
            ,rows: 2
        },{
            xtype: 'textarea'
            ,name: 'plugincode'
            ,id: 'modx-'+this.ident+'-plugincode'
            ,fieldLabel: _('code')
            ,anchor: '100% -270'
        },{
            xtype: 'xcheckbox'
            ,name: 'disabled'
            ,id: 'modx-'+this.ident+'-disabled'
            ,boxLabel: _('disabled')
            ,hideLabel: true
            ,inputValue: 1
            ,checked: false
        },{
            xtype: 'xcheckbox'
            ,name: 'clearCache'
            ,id: 'modx-'+this.ident+'-clearcache'
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
Ext.extend(MODx.window.QuickUpdatePlugin,MODx.Window);
Ext.reg('modx-window-quick-update-plugin',MODx.window.QuickUpdatePlugin);


MODx.window.QuickCreateTV = function(config) {
    config = config || {};
    this.ident = config.ident || 'qctv'+Ext.id();
    Ext.applyIf(config,{
        title: _('quick_create_tv')
        ,id: this.ident
        ,width: 700
        ,url: MODx.config.connectors_url+'element/tv.php'
        ,action: 'create'
        ,fields: [{
            layout: 'column'
            ,border: false
            ,items: [{
                columnWidth: .6
                ,layout: 'form'
                ,items: [{
                    xtype: 'textfield'
                    ,name: 'name'
                    ,id: 'modx-'+this.ident+'-name'
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
                    ,id: 'modx-'+this.ident+'-category'
                    ,anchor: '100%'
                },{
                    xtype: 'textarea'
                    ,name: 'description'
                    ,id: 'modx-'+this.ident+'-description'
                    ,fieldLabel: _('description')
                    ,anchor: '100%'
                    ,rows: 2
                }]
            },{
                columnWidth: .4
                ,border: false
                ,layout: 'form'
                ,items: [{
                    xtype: 'modx-combo-tv-input-type'
                    ,fieldLabel: _('tv_type')
                    ,name: 'type'
                    ,id: 'modx-'+this.ident+'-type'
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
        ,width: 700
        ,url: MODx.config.connectors_url+'element/tv.php'
        ,action: 'update'
        ,fields: [{
            xtype: 'hidden'
            ,name: 'id'
            ,id: 'modx-'+this.ident+'-id'
        },{
            layout: 'column'
            ,border: false
            ,items: [{
                columnWidth: .6
                ,layout: 'form'
                ,items: [{
                    xtype: 'textfield'
                    ,name: 'name'
                    ,id: 'modx-'+this.ident+'-name'
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
                    ,id: 'modx-'+this.ident+'-category'
                    ,anchor: '100%'
                },{
                    xtype: 'textarea'
                    ,name: 'description'
                    ,id: 'modx-'+this.ident+'-description'
                    ,fieldLabel: _('description')
                    ,anchor: '100%'
                    ,rows: 2
                }]
            },{
                columnWidth: .4
                ,border: false
                ,layout: 'form'
                ,items: [{
                    xtype: 'modx-combo-tv-input-type'
                    ,fieldLabel: _('tv_type')
                    ,name: 'type'
                    ,id: 'modx-'+this.ident+'-type'
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
            ,id: 'modx-'+this.ident+'-clearcache'
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
            ,anchor: '100%'
            ,submitValue: true
        },{
            xtype: 'textfield'
            ,id: 'modx-'+this.ident+'-newkey'
            ,fieldLabel: _('new_key')
            ,name: 'newkey'
            ,anchor: '100%'
            ,value: ''
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
        ,url: MODx.config.connectors_url+'security/login.php'
        ,action: 'login'
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

