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
    const windowId = `window-dup-resource-${Ext.id()}`;

    Ext.applyIf(config,{
        id: windowId
        ,title: config.pagetitle ? `${_('duplicate')} ${config.pagetitle}` : _('duplication_options')
        ,modxPseudoModal: true
    });
    MODx.window.DuplicateResource.superclass.constructor.call(this,config);
};
Ext.extend(MODx.window.DuplicateResource,MODx.Window,{
    _loadForm: function() {
        if (this.checkIfLoaded(this.config.record)) {
            this.fp.getForm().baseParams = {
                action: 'Resource/Updateduplicate'
                ,prefixDuplicate: true
                ,id: this.config.resource
            };
            return false;
        }
        var items = [];
        items.push({
            xtype: 'textfield'
            ,name: 'name'
            ,fieldLabel: _('resource_name_new')
            ,value: ''
        });

        if (this.config.hasChildren) {
            items.push({
                xtype: 'xcheckbox'
                ,name: 'duplicate_children'
                ,boxLabel: _('duplicate_children') + '  ('+this.config.childCount+')'
                ,hideLabel: true
                ,checked: true
            });
        }

        items.push({
            xtype: 'xcheckbox'
            ,name: 'redirect'
            ,boxLabel: _('duplicate_redirect')
            ,hideLabel: true
            ,checked: this.config.redirect
        });

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
                    name: 'published_mode'
                    ,boxLabel: _('po_make_all_unpub')
                    ,hideLabel: true
                    ,inputValue: 'unpublish'
                },{
                    name: 'published_mode'
                    ,boxLabel: _('po_make_all_pub')
                    ,hideLabel: true
                    ,inputValue: 'publish'
                },{
                    name: 'published_mode'
                    ,boxLabel: _('po_preserve')
                    ,hideLabel: true
                    ,inputValue: 'preserve'
                }]
            }]
        });

        this.fp = this.createForm({
            url: this.config.url || MODx.config.connector_url
            ,baseParams: this.config.baseParams || {
                action: 'Resource/Duplicate'
                ,id: this.config.resource
                ,prefixDuplicate: true
            }
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

    const   windowId = `window-dup-element-${Ext.id()}`,
            staticFileCmpId = `${windowId}-modx-static_file`,
            nameFieldName = config.record.type == 'template' ? 'templatename' : 'name' ,
            createExampleTag = ['tv', 'chunk', 'snippet'].includes(config.record.type),
            defaultExampleTag = createExampleTag ? _(`example_tag_${config.record.type}_name`) : '' ,
            elementNameCmpId = `${windowId}-modx-name`,
            nameFieldListeners = {
                change: function(cmp) {
                    cmp.setValue(cmp.getValue().trim());
                }
            },
            nameHelpListeners = {}
    ;
    if (createExampleTag) {
        Object.assign(nameHelpListeners, {
            afterrender: function(cmp) {
                MODx.util.insertTagCopyUtility(cmp, config.record.type);
            }
        });
    }
    // console.log('record:', config.record);
    // console.log('name field listeners: ',nameFieldListeners);
    const flds = [{
        xtype: 'hidden'
        ,name: 'id'
    },{
        xtype: 'hidden'
        ,name: 'source'
    },{
        xtype: 'textfield'
        ,name: nameFieldName
        ,id: elementNameCmpId
        ,fieldLabel: _(`${config.record.type}_new_name`) || _('element_name_new')
        ,enableKeyEvents: true
        ,allowBlank: false
        ,listeners: nameFieldListeners
        ,value: config.record.name
    },{
        xtype: 'box'
        ,hidden: MODx.expandHelp ? false : true
        ,html: createExampleTag
            ? _(`${config.record.type}_name_desc`, {
                tag: `<span class="copy-this">[[*<span class="example-replace-name">${defaultExampleTag}</span>]]</span>`
            })
            :  _(`${config.record.type}_name_desc`) || ''
        ,cls: 'desc-under'
        ,listeners: nameHelpListeners
    }];

    if (config.record.type == 'tv') {
        flds.push({
            xtype: 'textfield'
            ,name: 'caption'
            ,fieldLabel: _(`tv_new_caption`) || _('element_caption_new')
            ,value: config.record.caption
        },{
            xtype: 'box'
            ,hidden: MODx.expandHelp ? false : true
            ,html: _('tv_caption_desc')
            ,cls: 'desc-under'
        });
    }

    if (config.record.static === true) {
        flds.push({
            xtype: 'textfield'
            ,name: 'static_file'
            ,id: staticFileCmpId
            ,fieldLabel: _('static_file')
            ,listeners: {
                change: {
                    fn: function(cmp) {
                        const file = cmp.getValue().trim();
                        if (!Ext.isEmpty(file)) {
                            const fileName =
                            cmp.setValue(MODx.util.Format.fileFullPath(file));
                        }
                    },
                    scope: this
                }
            }
        },{
            xtype: 'box'
            ,hidden: MODx.expandHelp ? false : true
            ,html: _('static_file_desc')
            ,cls: 'desc-under'
        });
    }

    Ext.applyIf(config,{
        id: windowId
        ,title: _('duplicate_'+config.record.type)
        ,url: MODx.config.connector_url
        ,action: `element/${config.record.type}/duplicate`
        ,width: 600
        ,fields: flds
        ,labelWidth: 150
        ,modxPseudoModal: true
        ,modxFbarSaveSwitches: config.record.type == 'tv' ? ['duplicateValues', 'redirect'] : ['redirect']
    });
    MODx.window.DuplicateElement.superclass.constructor.call(this,config);

    if (this.config.record.static) {

        const   elementAutomationType = `${this.config.record.type}s`,
                staticsAutomationConfigKey = `static_elements_automate_${elementAutomationType}`
        ;
        this.staticsAutomated = MODx.config[staticsAutomationConfigKey] ? true : false ;

        if (this.staticsAutomated) {
            const elementCategory = this.config.record.category || 0;
            this.staticElementType = elementAutomationType;
            this.getElementCategoryName(elementCategory);
        } else {
            const   currentPath = this.config.record.static_file,
                    fileName = currentPath.indexOf('/') !== -1 ? currentPath.split('/').pop() : currentPath,
                    fileExt = fileName.indexOf('.') !== -1 ? fileName.slice(fileName.lastIndexOf('.')) : ''
            ;
            this.staticElementBasePath = currentPath.replace(fileName, '');
            this.staticElementFileExt = fileExt;
        }
        Ext.getCmp(elementNameCmpId).on({
            afterrender: {
                fn: function(cmp) {
                    const elementName = cmp.getValue().trim() || this.config.record.name;
                    let path;
                    if (this.staticsAutomated) {
                        path = MODx.getStaticElementsPath(elementName, this.staticElementCategoryName, this.staticElementType);
                    } else {
                        path = MODx.util.Format.staticElementPathFragment(elementName);
                        path = `${this.staticElementBasePath}${path}${this.staticElementFileExt}`;
                    }
                    Ext.getCmp(staticFileCmpId).setValue(path);
                },
                scope: this,
                delay: 250
            },
            keyup: {
                fn: function(cmp, e) {
                    const elementName = cmp.getValue().trim();
                    let path;
                    if (this.staticsAutomated) {
                        path = MODx.getStaticElementsPath(elementName, this.staticElementCategoryName, this.staticElementType);
                    } else {
                        path = MODx.util.Format.staticElementPathFragment(elementName);
                        path = `${this.staticElementBasePath}${path}${this.staticElementFileExt}`;
                    }
                    Ext.getCmp(staticFileCmpId).setValue(path);
                },
                scope: this
            }
        });
    }

};
Ext.extend(MODx.window.DuplicateElement,MODx.Window, {

    getElementCategoryName: function(categoryId) {

        if (typeof categoryId === 'number' && categoryId > 0) {
            MODx.Ajax.request({
                url: MODx.config.connector_url
                ,params: {
                    action: 'Element/Category/GetList'
                    ,id: categoryId
                }
                ,listeners: {
                    success: {
                        fn: function(response) {
                            response.results.forEach(result => {
                                if (result.id === categoryId) {
                                    this.staticElementCategoryName = result.name;
                                }
                            });
                        },
                        scope: this
                    }
                }
          });
        } else {
            this.staticElementCategoryName = '';
        }
    }
});
Ext.reg('modx-window-element-duplicate',MODx.window.DuplicateElement);

MODx.window.CreateCategory = function(config) {
    config = config || {};
    // this.ident = config.ident || 'ccat'+Ext.id();
    Ext.applyIf(config,{
        title: _('category_create')
        ,id: this.ident
        ,url: MODx.config.connector_url
        ,action: 'Element/Category/Create'
        ,modxPseudoModal: true
        ,fields: [{
            xtype: 'modx-description'
            ,html: _('category_create_desc')
        },{
            fieldLabel: _('name')
            ,name: 'category'
            // ,id: 'modx-'+this.ident+'-category'
            ,xtype: 'textfield'
        },{
            fieldLabel: _('parent')
            ,name: 'parent'
            ,hiddenName: 'parent'
            // ,id: 'modx-'+this.ident+'-parent'
            ,xtype: 'modx-combo-category'
        },{
            fieldLabel: _('rank')
            ,name: 'rank'
            // ,id: 'modx-'+this.ident+'-rank'
            ,xtype: 'numberfield'
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
        ,url: MODx.config.connector_url
        ,action: 'Element/Category/Update'
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
        },{
            fieldLabel: _('rank')
            ,name: 'rank'
            ,id: 'modx-'+this.ident+'-rank'
            ,xtype: 'numberfield'
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
        title: _('create')
        ,id: this.ident
        ,width: 600
        ,url: MODx.config.connector_url
        ,action: 'Workspace/PackageNamespace/Create'
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
        title: _('edit')
        ,action: 'Workspace/PackageNamespace/Update'
        ,isUpdate: true
    });
    MODx.window.UpdateNamespace.superclass.constructor.call(this, config);
};
Ext.extend(MODx.window.UpdateNamespace, MODx.window.CreateNamespace, {});
Ext.reg('modx-window-namespace-update',MODx.window.UpdateNamespace);


MODx.window.QuickCreateChunk = function(config) {

    config = config || {};
    const windowId = `window-qce-chunk-${Ext.id()}`;

    Ext.applyIf(config,{
        id: windowId
        ,title: _('quick_create_chunk')
        ,width: 700
        ,layout: 'form'
        ,url: MODx.config.connector_url
        ,action: 'Element/Chunk/Create'
        ,cls: 'qce-window qce-create'
        ,modxPseudoModal: true
        ,modxFbarSaveSwitches: ['clearCache']
        ,fields: [{
            xtype: 'hidden'
            ,name: 'id'
            ,value: config.record.id || 0
        },{
            // row 1
            cls:'form-row-wrapper',
            defaults: {
                layout: 'column'
            }
            ,items: [{
                defaults: {
                    layout: 'form'
                    ,labelSeparator: ''
                    ,labelAlign: 'top'
                }
                ,items: [{
                    columnWidth: 0.5
                    ,defaults: {
                        anchor: '100%'
                        ,msgTarget: 'under'
                        ,validationEvent: 'change'
                        ,validateOnBlur: false
                    }
                    ,items: [{
                        xtype: 'textfield'
                        ,name: 'name'
                        ,fieldLabel: _('name')
                        ,allowBlank: false
                        ,maxLength: 50
                        ,value: config.record.name || ''
                    }]
                },{
                    columnWidth: 0.5
                    ,defaults: {
                        anchor: '100%'
                        ,msgTarget: 'under'
                    }
                    ,items: [{
                        xtype: 'modx-combo-category'
                        ,name: 'category'
                        ,fieldLabel: _('category')
                        ,description: MODx.expandHelp ? '' : _('chunk_category_desc')
                        ,value: config.record.category || 0
                    },{
                        xtype: MODx.expandHelp ? 'label' : 'hidden'
                        ,html: _('chunk_category_desc')
                        ,cls: 'desc-under'
                    }]
                }]
            }]
        },{
            // row 2
            cls:'form-row-wrapper',
            defaults: {
                layout: 'column'
            }
            ,items: [{
                defaults: {
                    layout: 'form'
                    ,labelSeparator: ''
                    ,labelAlign: 'top'
                }
                ,items: [{
                    columnWidth: 0.5
                    ,defaults: {
                        anchor: '100%'
                        ,msgTarget: 'under'
                        ,validationEvent: 'change'
                        ,validateOnBlur: false
                    }
                    ,items: [{
                        xtype: 'textarea'
                        ,name: 'description'
                        ,description: MODx.expandHelp ? '' : _('chunk_description_desc')
                        ,fieldLabel: _('description')
                        ,grow: true
                        ,growMin: 50
                        ,growMax: this.isSmallScreen ? 90 : 120
                        ,value: config.record.description || ''
                    },{
                        xtype: MODx.expandHelp ? 'label' : 'hidden'
                        ,html: _('chunk_description_desc')
                        ,cls: 'desc-under'
                    }]
                }]
            }]
        },{
            // row 3
            cls:'form-row-wrapper',
            layout: 'form'
            ,labelSeparator: ''
            ,labelAlign: 'top'
            ,defaults: {
                anchor: '100%'
                ,msgTarget: 'under'
                ,validationEvent: 'change'
                ,validateOnBlur: false
            }
            ,items: [{
                xtype: 'textarea'
                ,fieldLabel: _('chunk_code')
                ,name: 'snippet'
                ,grow: true
                ,growMin: 90
                ,growMax: this.isSmallScreen ? 160 : 300
                ,value: config.record.snippet || ''
            }]
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
        ,action: 'Element/Chunk/Update'
        ,cls: 'qce-window qce-update'
        ,modxFbarButtons: 'c-s-sc'
    });
    MODx.window.QuickUpdateChunk.superclass.constructor.call(this,config);
};
Ext.extend(MODx.window.QuickUpdateChunk, MODx.window.QuickCreateChunk);
Ext.reg('modx-window-quick-update-chunk',MODx.window.QuickUpdateChunk);


MODx.window.QuickCreateTemplate = function(config) {

    config = config || {};
    const windowId = `window-qce-template-${Ext.id()}`;

    Ext.applyIf(config,{
        id: windowId
        ,title: _('quick_create_template')
        ,width: 700
        ,url: MODx.config.connector_url
        ,action: 'Element/Template/Create'
        ,cls: 'qce-window qce-create'
        ,modxPseudoModal: true
        ,modxFbarSaveSwitches: ['clearCache']
        ,fields: [{
            xtype: 'hidden'
            ,name: 'id'
            ,value: config.record.id || 0
        },{
            // row 1
            cls:'form-row-wrapper',
            defaults: {
                layout: 'column'
            }
            ,items: [{
                defaults: {
                    layout: 'form'
                    ,labelSeparator: ''
                    ,labelAlign: 'top'
                }
                ,items: [{
                    columnWidth: 0.5
                    ,defaults: {
                        anchor: '100%'
                        ,msgTarget: 'under'
                        ,validationEvent: 'change'
                        ,validateOnBlur: false
                    }
                    ,items: [{
                        xtype: 'textfield'
                        ,name: 'templatename'
                        ,fieldLabel: _('name')
                        ,allowBlank: false
                        ,maxLength: 50
                        ,value: config.record.templatename || ''
                    }]
                },{
                    columnWidth: 0.5
                    ,defaults: {
                        anchor: '100%'
                        ,msgTarget: 'under'
                    }
                    ,items: [{
                        xtype: 'modx-combo-category'
                        ,name: 'category'
                        ,fieldLabel: _('category')
                        ,description: MODx.expandHelp ? '' : _('template_category_desc')
                        ,value: config.record.category || 0
                    },{
                        xtype: MODx.expandHelp ? 'label' : 'hidden'
                        ,html: _('template_category_desc')
                        ,cls: 'desc-under'
                    }]
                }]
            }]
        },{
            // row 2
            cls:'form-row-wrapper',
            defaults: {
                layout: 'column'
            }
            ,items: [{
                defaults: {
                    layout: 'form'
                    ,labelSeparator: ''
                    ,labelAlign: 'top'
                }
                ,items: [{
                    columnWidth: 1
                    ,defaults: {
                        anchor: '100%'
                        ,msgTarget: 'under'
                        ,validationEvent: 'change'
                        ,validateOnBlur: false
                    }
                    ,items: [{
                        xtype: 'textarea'
                        ,name: 'description'
                        ,description: MODx.expandHelp ? '' : _('template_description_desc')
                        ,fieldLabel: _('description')
                        ,grow: true
                        ,growMin: 50
                        ,growMax: this.isSmallScreen ? 90 : 120
                        ,value: config.record.description || ''
                    },{
                        xtype: MODx.expandHelp ? 'label' : 'hidden'
                        ,html: _('template_description_desc')
                        ,cls: 'desc-under'
                    }]
                }]
            }]
        },{
            // row 3
            cls:'form-row-wrapper',
            layout: 'form'
            ,labelSeparator: ''
            ,labelAlign: 'top'
            ,defaults: {
                anchor: '100%'
                ,msgTarget: 'under'
                ,validationEvent: 'change'
                ,validateOnBlur: false
            }
            ,items: [{
                xtype: 'textarea'
                ,fieldLabel: _('template_code')
                ,name: 'content'
                ,grow: true
                ,growMin: 120
                ,growMax: this.isSmallScreen ? 160 : 300
                ,value: config.record.content || ''
            }]
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
        ,action: 'Element/Template/Update'
        ,cls: 'qce-window qce-update'
        ,modxFbarButtons: 'c-s-sc'
    });
    MODx.window.QuickUpdateTemplate.superclass.constructor.call(this,config);
};
Ext.extend(MODx.window.QuickUpdateTemplate,MODx.window.QuickCreateTemplate);
Ext.reg('modx-window-quick-update-template',MODx.window.QuickUpdateTemplate);


MODx.window.QuickCreateSnippet = function(config) {

    config = config || {};
    const windowId = `window-qce-snippet-${Ext.id()}`;

    Ext.applyIf(config,{
        id: windowId
        ,title: _('quick_create_snippet')
        ,width: 700
        ,url: MODx.config.connector_url
        ,action: 'Element/Snippet/Create'
        ,cls: 'qce-window qce-create'
        ,modxPseudoModal: true
        ,modxFbarSaveSwitches: ['clearCache']
        ,fields: [{
            xtype: 'hidden'
            ,name: 'id'
            ,value: config.record.id || 0
        },{
            // row 1
            cls:'form-row-wrapper',
            defaults: {
                layout: 'column'
            }
            ,items: [{
                defaults: {
                    layout: 'form'
                    ,labelSeparator: ''
                    ,labelAlign: 'top'
                }
                ,items: [{
                    columnWidth: 0.5
                    ,defaults: {
                        anchor: '100%'
                        ,msgTarget: 'under'
                        ,validationEvent: 'change'
                        ,validateOnBlur: false
                    }
                    ,items: [{
                        xtype: 'textfield'
                        ,name: 'name'
                        ,fieldLabel: _('name')
                        ,allowBlank: false
                        ,maxLength: 50
                        ,value: config.record.name || ''
                    }]
                },{
                    columnWidth: 0.5
                    ,defaults: {
                        anchor: '100%'
                        ,msgTarget: 'under'
                    }
                    ,items: [{
                        xtype: 'modx-combo-category'
                        ,name: 'category'
                        ,fieldLabel: _('category')
                        ,description: MODx.expandHelp ? '' : _('snippet_category_desc')
                        ,value: config.record.category || 0
                    },{
                        xtype: MODx.expandHelp ? 'label' : 'hidden'
                        ,html: _('snippet_category_desc')
                        ,cls: 'desc-under'
                    }]
                }]
            }]
        },{
            // row 2
            cls:'form-row-wrapper',
            defaults: {
                layout: 'column'
            }
            ,items: [{
                defaults: {
                    layout: 'form'
                    ,labelSeparator: ''
                    ,labelAlign: 'top'
                }
                ,items: [{
                    columnWidth: 1
                    ,defaults: {
                        anchor: '100%'
                        ,msgTarget: 'under'
                        ,validationEvent: 'change'
                        ,validateOnBlur: false
                    }
                    ,items: [{
                        xtype: 'textarea'
                        ,name: 'description'
                        ,description: MODx.expandHelp ? '' : _('snippet_description_desc')
                        ,fieldLabel: _('description')
                        ,grow: true
                        ,growMin: 50
                        ,growMax: this.isSmallScreen ? 90 : 120
                        ,value: config.record.description || ''
                    },{
                        xtype: MODx.expandHelp ? 'label' : 'hidden'
                        ,html: _('snippet_description_desc')
                        ,cls: 'desc-under'
                    }]
                }]
            }]
        },{
            // row 3
            cls:'form-row-wrapper',
            layout: 'form'
            ,labelSeparator: ''
            ,labelAlign: 'top'
            ,defaults: {
                anchor: '100%'
                ,msgTarget: 'under'
                ,validationEvent: 'change'
                ,validateOnBlur: false
            }
            ,items: [{
                xtype: 'textarea'
                ,fieldLabel: _('snippet_code')
                ,name: 'snippet'
                ,id: `modx-${this.ident}-code`
                ,grow: true
                ,growMin: 90
                ,growMax: this.isSmallScreen ? 160 : 300
                ,value: config.record.snippet || ''
            }]
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
        ,action: 'Element/Snippet/Update'
        ,cls: 'qce-window qce-update'
        ,modxFbarButtons: 'c-s-sc'
    });
    MODx.window.QuickUpdateSnippet.superclass.constructor.call(this,config);
};
Ext.extend(MODx.window.QuickUpdateSnippet,MODx.window.QuickCreateSnippet);
Ext.reg('modx-window-quick-update-snippet',MODx.window.QuickUpdateSnippet);


MODx.window.QuickCreatePlugin = function(config) {

    config = config || {};
    const windowId = `window-qce-plugin-${Ext.id()}`;

    Ext.applyIf(config,{
        id: windowId
        ,title: _('quick_create_plugin')
        ,width: 700
        ,layout: 'anchor'
        ,url: MODx.config.connector_url
        ,action: 'Element/Plugin/Create'
        ,modxPseudoModal: true
        ,modxFbarSaveSwitches: ['clearCache']
        ,fields: [{
            xtype: 'hidden'
            ,name: 'id'
            ,value: config.record.id || 0
        },{
            // row 1
            cls:'form-row-wrapper',
            defaults: {
                layout: 'column'
            }
            ,items: [{
                defaults: {
                    layout: 'form'
                    ,labelSeparator: ''
                    ,labelAlign: 'top'
                }
                ,items: [{
                    columnWidth: 0.5
                    ,defaults: {
                        anchor: '100%'
                        ,msgTarget: 'under'
                        ,validationEvent: 'change'
                        ,validateOnBlur: false
                    }
                    ,items: [{
                        xtype: 'textfield'
                        ,name: 'name'
                        ,fieldLabel: _('name')
                        ,allowBlank: false
                        ,maxLength: 50
                        ,value: config.record.name || ''
                    }]
                },{
                    columnWidth: 0.5
                    ,defaults: {
                        anchor: '100%'
                        ,msgTarget: 'under'
                    }
                    ,items: [{
                        xtype: 'modx-combo-category'
                        ,name: 'category'
                        ,fieldLabel: _('category')
                        ,description: MODx.expandHelp ? '' : _('plugin_category_desc')
                        ,value: config.record.category || 0
                    },{
                        xtype: MODx.expandHelp ? 'label' : 'hidden'
                        ,html: _('plugin_category_desc')
                        ,cls: 'desc-under'
                    }]
                }]
            }]
        },{
            // row 2
            cls:'form-row-wrapper',
            defaults: {
                layout: 'column'
            }
            ,items: [{
                defaults: {
                    layout: 'form'
                    ,labelSeparator: ''
                    ,labelAlign: 'top'
                }
                ,items: [{
                    columnWidth: 0.5
                    ,defaults: {
                        anchor: '100%'
                        ,msgTarget: 'under'
                        ,validationEvent: 'change'
                        ,validateOnBlur: false
                    }
                    ,items: [{
                        xtype: 'textarea'
                        ,name: 'description'
                        ,description: MODx.expandHelp ? '' : _('plugin_description_desc')
                        ,fieldLabel: _('description')
                        ,grow: true
                        ,growMin: 50
                        ,growMax: this.isSmallScreen ? 90 : 120
                        ,value: config.record.description || ''
                    },{
                        xtype: MODx.expandHelp ? 'label' : 'hidden'
                        ,html: _('plugin_description_desc')
                        ,cls: 'desc-under'
                    }]
                },{
                    columnWidth: 0.5
                    ,defaults: {
                        anchor: '100%'
                        ,msgTarget: 'under'
                    }
                    ,items: [{
                        xtype: 'xcheckbox'
                        ,name: 'disabled'
                        ,hideLabel: true
                        ,boxLabel: _('plugin_disabled')
                        ,description: MODx.expandHelp ? '' : _('plugin_disabled_desc')
                        ,ctCls: 'add-label-space'
                        ,inputValue: 1
                        ,checked: config.record.disabled || 0
                    },{
                        xtype: MODx.expandHelp ? 'label' : 'hidden'
                        ,html: _('plugin_disabled_desc')
                        ,cls: 'desc-under toggle-slider-above'
                    }]
                }]
            }]
        },{
            // row 3
            cls:'form-row-wrapper',
            layout: 'form'
            ,labelSeparator: ''
            ,labelAlign: 'top'
            ,defaults: {
                anchor: '100%'
                ,msgTarget: 'under'
                ,validationEvent: 'change'
                ,validateOnBlur: false
            }
            ,items: [{
                xtype: 'textarea'
                ,fieldLabel: _('plugin_code')
                ,name: 'plugincode'
                ,grow: true
                ,growMin: 90
                ,growMax: this.isSmallScreen ? 160 : 300
                ,value: config.record.plugincode || ''
            }]
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
        ,action: 'Element/Plugin/Update'
        ,cls: 'qce-window qce-update'
        ,modxFbarButtons: 'c-s-sc'
    });
    MODx.window.QuickUpdatePlugin.superclass.constructor.call(this,config);
};
Ext.extend(MODx.window.QuickUpdatePlugin,MODx.window.QuickCreatePlugin);
Ext.reg('modx-window-quick-update-plugin',MODx.window.QuickUpdatePlugin);


MODx.window.QuickCreateTV = function(config) {

    config = config || {};
    const windowId = `window-qce-tv-${Ext.id()}`;

    Ext.applyIf(config,{
        id: windowId
        ,title: _('quick_create_tv')
        ,width: 640
        ,url: MODx.config.connector_url
        ,action: 'Element/TemplateVar/Create'
        ,cls: 'qce-window qce-create'
        ,modxPseudoModal: true
        ,modxFbarSaveSwitches: ['clearCache']
        // ,saveBtnText: 'Test Save'
        // ,cancelBtnText: 'Test Cancel'
        ,fields: [{
            xtype: 'hidden'
            ,name: 'id'
            ,value: config.record.id || 0
        },{
            // row 1
            cls:'form-row-wrapper',
            defaults: {
                layout: 'column'
            }
            ,items: [{
                defaults: {
                    layout: 'form'
                    ,labelSeparator: ''
                    ,labelAlign: 'top'
                }
                ,items: [{
                    columnWidth: 0.5
                    ,defaults: {
                        anchor: '100%'
                        ,msgTarget: 'under'
                        ,validationEvent: 'change'
                        ,validateOnBlur: false
                    }
                    ,items: [{
                        xtype: 'textfield'
                        ,name: 'name'
                        ,fieldLabel: _('name')
                        ,description: MODx.expandHelp ? '' : _('tv_name_desc', {
                            tag: `<span class="copy-this">[[*<span class="example-replace-name">${_('example_tag_tv_name')}</span>]]</span>`
                        })
                        ,allowBlank: false
                        ,maxLength: 50
                        ,value: config.record.name || ''
                        ,enableKeyEvents: true
                        ,listeners: {
                            keyup: {
                                fn: function(cmp, e) {
                                    let title = Ext.util.Format.stripTags(cmp.getValue()),
                                        tagTitle
                                        ;
                                    title = Ext.util.Format.htmlEncode(title);
                                    tagTitle = title.length > 0 ? title : _('example_tag_tv_name');
                                    cmp.nextSibling().getEl().child('.example-replace-name').update(tagTitle);
                                }
                                ,scope: this
                            }
                        }
                    },{
                        xtype: 'box'
                        ,hidden: MODx.expandHelp ? false : true
                        ,html: _('tv_name_desc', {
                            tag: `<span class="copy-this">[[*<span class="example-replace-name">${_('example_tag_tv_name')}</span>]]</span>`
                        })
                        ,cls: 'desc-under'
                        ,listeners: {
                            afterrender: {
                                fn: function(cmp) {
                                    MODx.util.insertTagCopyUtility(cmp, 'tv');
                                }
                                ,scope: this
                            }
                        }
                    }]
                },{
                    columnWidth: 0.5
                    ,defaults: {
                        anchor: '100%'
                        ,msgTarget: 'under'
                    }
                    ,items: [{
                        xtype: 'modx-combo-tv-input-type'
                        ,fieldLabel: _('tv_type')
                        ,name: 'type'
                        ,value: config.record.type || 'text'
                    },{
                        xtype: 'box'
                        ,hidden: MODx.expandHelp ? false : true
                        ,html: _('tv_type_desc')
                        ,cls: 'desc-under'
                    }]
                }]
            }]
        },{
            // row 2
            cls:'form-row-wrapper',
            defaults: {
                layout: 'column'
            }
            ,items: [{
                defaults: {
                    layout: 'form'
                    ,labelSeparator: ''
                    ,labelAlign: 'top'
                }
                ,items: [{
                    columnWidth: 0.5
                    ,defaults: {
                        anchor: '100%'
                        ,msgTarget: 'under'
                        ,validationEvent: 'change'
                        ,validateOnBlur: false
                    }
                    ,items: [{
                        xtype: 'textfield'
                        ,name: 'caption'
                        ,fieldLabel: _('caption')
                        ,description: MODx.expandHelp ? '' : _('tv_caption_desc')
                        ,maxLength: 50
                        ,value: config.record.caption || ''
                    },{
                        xtype: 'box'
                        ,hidden: MODx.expandHelp ? false : true
                        ,html: _('tv_caption_desc')
                        ,cls: 'desc-under'
                    }]
                },{
                    columnWidth: 0.5
                    ,defaults: {
                        anchor: '100%'
                        ,msgTarget: 'under'
                    }
                    ,items: [{
                        xtype: 'modx-combo-category'
                        ,name: 'category'
                        ,fieldLabel: _('category')
                        ,description: MODx.expandHelp ? '' : _('tv_category_desc')
                        ,value: config.record.category || 0
                    },{
                        xtype: MODx.expandHelp ? 'box' : 'hidden'
                        ,html: _('tv_category_desc')
                        ,cls: 'desc-under'
                    }]
                }]
            }]
        },{
            // row 3
            cls:'form-row-wrapper',
            defaults: {
                layout: 'column'
            }
            ,items: [{
                defaults: {
                    layout: 'form'
                    ,labelSeparator: ''
                    ,labelAlign: 'top'
                }
                ,items: [{
                    columnWidth: 1
                    ,defaults: {
                        anchor: '100%'
                        ,msgTarget: 'under'
                        ,validationEvent: 'change'
                        ,validateOnBlur: false
                    }
                    ,items: [{
                        xtype: 'textarea'
                        ,name: 'description'
                        ,fieldLabel: _('description')
                        ,description: MODx.expandHelp ? '' : _('tv_description_desc')
                        ,grow: true
                        ,growMin: 30
                        ,growMax: this.isSmallScreen ? 90 : 120
                        ,value: config.record.description || ''
                    },{
                        xtype: MODx.expandHelp ? 'label' : 'hidden'
                        ,html: _('tv_description_desc')
                        ,cls: 'desc-under'
                    }]
                }]
            }]
        },{
            // row 4
            cls:'form-row-wrapper',
            defaults: {
                layout: 'column'
            }
            ,items: [{
                defaults: {
                    layout: 'form'
                    ,labelSeparator: ''
                    ,labelAlign: 'top'
                }
                ,items: [{
                    columnWidth: 1
                    ,defaults: {
                        anchor: '100%'
                        ,msgTarget: 'under'
                        ,validationEvent: 'change'
                        ,validateOnBlur: false
                    }
                    ,items: [{
                        xtype: 'textarea'
                        ,name: 'els'
                        ,fieldLabel: _('tv_elements')
                        ,description: MODx.expandHelp ? '' : _('tv_elements_short_desc')
                        ,grow: true
                        ,growMin: 30
                        ,growMax: this.isSmallScreen ? 90 : 120
                        ,value: config.record.els || ''
                    },{
                        xtype: MODx.expandHelp ? 'label' : 'hidden'
                        ,html: _('tv_elements_short_desc')
                        ,cls: 'desc-under'
                    }]
                }]
            }]
        },{
            // row 5
            cls:'form-row-wrapper',
            defaults: {
                layout: 'column'
            }
            ,items: [{
                defaults: {
                    layout: 'form'
                    ,labelSeparator: ''
                    ,labelAlign: 'top'
                }
                ,items: [{
                    columnWidth: 0.5
                    ,defaults: {
                        anchor: '100%'
                        ,msgTarget: 'under'
                        ,validationEvent: 'change'
                        ,validateOnBlur: false
                    }
                    ,items: [{
                        xtype: 'textarea'
                        ,name: 'default_text'
                        ,fieldLabel: _('tv_default')
                        ,description: MODx.expandHelp ? '' : _('tv_default_desc')
                        ,grow: true
                        ,growMin: 30
                        ,growMax: 60
                        ,value: config.record.default_text || ''
                    },{
                        xtype: MODx.expandHelp ? 'label' : 'hidden'
                        ,html: _('tv_default_desc')
                        ,cls: 'desc-under'
                    }]
                },{
                    // using empty column here to allow full-width of previous column in mobile contexts
                    columnWidth: 0.5
                    ,items: []
                }]
            }]
        }]
        ,keys: [{
            key: Ext.EventObject.ENTER
            ,shift: true
            ,fn: this.submit
            ,scope: this
        }]
        /*
        ,buttons: [{
            text: 'Test One'
        },{
            text: 'Test Two'
            ,cls: 'primary-button'
        }]
        */
    });
    MODx.window.QuickCreateTV.superclass.constructor.call(this,config);
};
Ext.extend(MODx.window.QuickCreateTV,MODx.Window);
Ext.reg('modx-window-quick-create-tv',MODx.window.QuickCreateTV);

MODx.window.QuickUpdateTV = function(config) {
    config = config || {};

    Ext.applyIf(config,{
        title: _('quick_update_tv')
        ,action: 'Element/TemplateVar/Update'
        ,cls: 'qce-window qce-update'
        ,modxFbarButtons: 'c-s-sc'
    });
    MODx.window.QuickUpdateTV.superclass.constructor.call(this,config);
};
Ext.extend(MODx.window.QuickUpdateTV,MODx.window.QuickCreateTV);
Ext.reg('modx-window-quick-update-tv',MODx.window.QuickUpdateTV);


MODx.window.DuplicateContext = function(config) {

    config = config || {};
    Ext.Ajax.timeout = 0;
    const   windowId = `window-dup-context-${Ext.id()}`,
            preserveAliasCmpId = `${windowId}-modx-preserve_alias`,
            preserveMenuIndexCmpId = `${windowId}-modx-preserve_menuindex`
    ;

    Ext.applyIf(config,{
        id: windowId
        ,title: _('duplicate')
        ,url: MODx.config.connector_url
        ,action: 'Context/Duplicate'
        ,modxPseudoModal: true
        ,fields: [{
            xtype: 'statictextfield'
            ,name: 'key'
            ,fieldLabel: _('old_key')
            ,submitValue: true
        },{
            xtype: 'textfield'
            ,name: 'newkey'
            ,fieldLabel: _('new_key')
            ,value: ''
        },{
            xtype: 'checkbox'
            ,name: 'preserve_resources'
            ,hideLabel: true
            ,boxLabel: _('preserve_resources')
            ,checked: true
            ,listeners: {
                check: {
                    fn: function(cb, checked) {
                        const form = this.fp.getForm();
                        if (checked) {
                            form.findField(preserveAliasCmpId).setValue(true).enable();
                            form.findField(preserveMenuIndexCmpId).setValue(true).enable();
                        } else {
                            form.findField(preserveAliasCmpId).setValue(false).disable();
                            form.findField(preserveMenuIndexCmpId).setValue(false).disable();
                        }
                    },
                    scope: this
                }
            }
        },{
            xtype: 'checkbox'
            ,id: preserveAliasCmpId
            ,name: 'preserve_alias'
            ,hideLabel: true
            ,boxLabel: _('preserve_alias')
            ,checked: true
        },{
            xtype: 'checkbox'
            ,id: preserveMenuIndexCmpId
            ,name: 'preserve_menuindex'
            ,hideLabel: true
            ,boxLabel: _('preserve_menuindex')
            ,checked: true
        }]
    });
    MODx.window.DuplicateContext.superclass.constructor.call(this,config);
};
Ext.extend(MODx.window.DuplicateContext,MODx.Window);
Ext.reg('modx-window-context-duplicate',MODx.window.DuplicateContext);

MODx.window.Login = function(config) {

    config = config || {};
    Ext.Ajax.timeout = 0;
    const windowId = `window-login-extend-${Ext.id()}`;

    Ext.applyIf(config,{
        id: windowId
        ,title: _('login')
        ,url: MODx.config.connectors_url
        ,action: 'Security/Login'
        ,fields: [{
            html: '<p>'+_('session_logging_out')+'</p>'
            ,xtype: 'modx-description'
        },{
            xtype: 'textfield'
            ,name: 'username'
            ,fieldLabel: _('username')
        },{
            xtype: 'textfield'
            ,name: 'password'
            ,inputType: 'password'
            ,fieldLabel: _('password')
        },{
            xtype: 'hidden'
            ,name: 'rememberme'
            ,value: 1
        }]
        ,buttons: [{
            text: _('logout')
            ,scope: this
            ,handler: function() {
                location.href = '?logout=1'
            }
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
