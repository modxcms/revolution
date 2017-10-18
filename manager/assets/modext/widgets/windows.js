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
        title: config.pagetitle ? _('duplicate') + ' ' + config.pagetitle : _('duplication_options')
        ,id: this.ident
        // ,width: 500
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
                ,boxLabel: _('duplicate_children') + '  ('+this.config.childCount+')'
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
                ,hideLabel: true
                ,columns: 1
                ,value: pov
                ,items: [{
                    boxLabel: _('po_make_all_unpub')
                    ,hideLabel: true
                    ,name: 'published_mode'
                    ,inputValue: 'unpublish'
                },{
                    boxLabel: _('po_make_all_pub')
                    ,hideLabel: true
                    ,name: 'published_mode'
                    ,inputValue: 'publish'
                },{
                    boxLabel: _('po_preserve')
                    ,hideLabel: true
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
        xtype: 'hidden'
        ,name: 'source'
        ,id: 'modx-'+this.ident+'-source'
    }, {
        xtype: 'textfield'
        ,fieldLabel: _('element_name_new')
        ,name: config.record.type == 'template' ? 'templatename' : 'name'
        ,id: 'modx-'+this.ident+'-name'
        ,anchor: '100%'
        ,enableKeyEvents: true
        ,listeners: {
            'afterRender' : {scope:this,fn:function(f,e) {
                this.setStaticElementsPath(f);
            }},
            'keyup': {scope:this,fn:function(f,e) {
                this.setStaticElementsPath(f);
            }}
        }
    }];

    if (config.record.type == 'tv') {
        flds.push({
            xtype: 'textfield'
            ,fieldLabel: _('element_caption_new')
            ,name: 'caption'
            ,id: 'modx-'+this.ident+'-caption'
            ,anchor: '100%'
        });
        flds.push({
            xtype: 'xcheckbox'
            ,fieldLabel: _('element_duplicate_values')
            ,labelSeparator: ''
            ,name: 'duplicateValues'
            ,id: 'modx-'+this.ident+'-duplicate-values'
            ,anchor: '100%'
            ,inputValue: 1
            ,checked: false
        });
    }

    if (config.record.static === true) {
        flds.push({
                xtype: 'textfield'
                ,fieldLabel: _('static_file')
                ,name: 'static_file'
                ,id: 'modx-'+this.ident+'-static_file'
                ,anchor: '100%'
            }
        );
    }

    Ext.applyIf(config,{
        title: _('element_duplicate')
        ,url: MODx.config.connector_url
        ,action: 'element/'+config.record.type+'/duplicate'
        ,width: 600
        ,fields: flds
        ,labelWidth: 150
    });
    MODx.window.DuplicateElement.superclass.constructor.call(this,config);
};

Ext.extend(MODx.window.DuplicateElement,MODx.Window, {
    setStaticElementsPath: function(f) {
        if (this.config.record.static === true) {
            var category = this.config.record.category;

            if (typeof category !== 'number') {
                if (Ext.getCmp('modx-' + this.config.record.type + '-category').getValue() > 0) {
                    category = Ext.getCmp('modx-' + this.config.record.type + '-category').lastSelectionText;
                }

                var path = MODx.getStaticElementsPath(f.getValue(), category, this.config.record.type + 's');
                Ext.getCmp('modx-' + this.ident + '-static_file').setValue(path);
            } else {
                // If category is set but is a number, retrieve full category name.
                if (typeof category === "number" && category > 0) {
                    MODx.Ajax.request({
                        url: MODx.config.connector_url
                        ,params: {
                            action: 'element/category/getlist'
                            ,id: category
                        }
                        ,listeners: {
                            'success': {fn:function(response) {
                                for (var i = 0; i < response.results.length; i++) {
                                    if (response.results[i].id === category) {
                                        category = response.results[i].name;
                                    }
                                }

                                var path = MODx.getStaticElementsPath(f.getValue(), category, this.config.record.type + 's');
                                Ext.getCmp('modx-' + this.ident + '-static_file').setValue(path);
                            },scope:this}
                        }
                  });
                }
            }
        }
    }
});

Ext.reg('modx-window-element-duplicate',MODx.window.DuplicateElement);

MODx.window.CreateCategory = function(config) {
    config = config || {};
    this.ident = config.ident || 'ccat'+Ext.id();
    Ext.applyIf(config,{
        title: _('new_category')
        ,id: this.ident
        // ,height: 150
        // ,width: 350
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
        },{
            fieldLabel: _('rank')
            ,name: 'rank'
            ,id: 'modx-'+this.ident+'-rank'
            ,xtype: 'numberfield'
            ,anchor: '100%'
        }]
    });
    MODx.window.CreateCategory.superclass.constructor.call(this,config);
};
Ext.extend(MODx.window.CreateCategory,MODx.Window);
Ext.reg('modx-window-category-create',MODx.window.CreateCategory);

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
        // ,height: 150
        // ,width: 350
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
            ,anchor: '100%'
        },{
            fieldLabel: _('rank')
            ,name: 'rank'
            ,id: 'modx-'+this.ident+'-rank'
            ,xtype: 'numberfield'
            ,anchor: '100%'
        }]
    });
    MODx.window.RenameCategory.superclass.constructor.call(this,config);
};
Ext.extend(MODx.window.RenameCategory,MODx.Window);
Ext.reg('modx-window-category-rename',MODx.window.RenameCategory);


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
            ,readOnly: config.isUpdate || false
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

MODx.window.UpdateNamespace = function(config) {
    config = config || {};

    Ext.applyIf(config, {
        title: _('namespace_update')
        ,action: 'workspace/namespace/update'
        ,isUpdate: true
    });
    MODx.window.UpdateNamespace.superclass.constructor.call(this, config);
};
Ext.extend(MODx.window.UpdateNamespace, MODx.window.CreateNamespace, {});
Ext.reg('modx-window-namespace-update',MODx.window.UpdateNamespace);


MODx.window.QuickCreateChunk = function(config) {
    config = config || {};

    Ext.applyIf(config,{
        title: _('quick_create_chunk')
        ,width: 600
        //,height: 640
        // ,autoHeight: true
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
            ,cls: 'primary-button'
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
        // ,autoHeight: true
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
            ,cls: 'primary-button'
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
        // ,autoHeight: true
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
            ,cls: 'primary-button'
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
        // ,autoHeight: true
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
            ,cls: 'primary-button'
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
            ,cls: 'primary-button'
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
        // ,width: 400
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
            xtype: 'checkbox'
            ,id: 'modx-'+this.ident+'-preserveresources'
            ,hideLabel: true
            ,boxLabel: _('preserve_resources')
            ,name: 'preserve_resources'
            ,anchor: '100%'
            ,checked: true
            ,listeners: {
                'check': {fn: function(cb,checked) {
                    if (checked) {
                        this.fp.getForm().findField('modx-'+this.ident+'-preservealias').setValue(true).enable();
                        this.fp.getForm().findField('modx-'+this.ident+'-preservemenuindex').setValue(true).enable();
                    } else {
                        this.fp.getForm().findField('modx-'+this.ident+'-preservealias').setValue(false).disable();
                        this.fp.getForm().findField('modx-'+this.ident+'-preservemenuindex').setValue(false).disable();
                    }
                },scope:this}
            }

        },{
            xtype: 'checkbox'
            ,id: 'modx-'+this.ident+'-preservealias'
            ,hideLabel: true
            ,boxLabel: _('preserve_alias') // Todo: add translation
            ,name: 'preserve_alias'
            ,anchor: '100%'
            ,checked: true
        },{
            xtype: 'checkbox'
            ,id: 'modx-'+this.ident+'-preservemenuindex'
            ,hideLabel: true
            ,boxLabel: _('preserve_menuindex') // Todo: add translation
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
        ,url: MODx.config.connectors_url
        ,action: 'security/login'
        // ,width: 400
        ,fields: [{
            html: '<p>'+_('session_logging_out')+'</p>'
            ,xtype: 'modx-description'
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
            ,cls: 'primary-button'
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
