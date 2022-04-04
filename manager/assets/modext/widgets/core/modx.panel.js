Ext.namespace('MODx.panel');

MODx.Panel = function(config) {
    config = config || {};
    Ext.applyIf(config,{
        cls: 'modx-panel'
        ,title: ''
    });
    MODx.Panel.superclass.constructor.call(this,config);
    this.config = config;
};
Ext.extend(MODx.Panel,Ext.Panel);
Ext.reg('modx-panel',MODx.Panel);

MODx.FormPanel = function(config) {
    config = config || {};
    Ext.applyIf(config,{
        autoHeight: true
        ,collapsible: true
        ,bodyStyle: ''
        ,layout: 'anchor'
        ,border: false
        ,header: false
        ,method: 'POST'
        ,cls: 'modx-form'
        ,allowDrop: true
        ,errorReader: MODx.util.JSONReader
        ,checkDirty: true
        ,useLoadingMask: false
        ,defaults: {
            collapsible: false
            ,autoHeight: true
            ,border: false
        }
    });
    if (config.items) {
        this.addChangeEvent(config.items);
    }

    MODx.FormPanel.superclass.constructor.call(this,config);
    this.config = config;

    this.addEvents({
        setup: true
        ,fieldChange: true
        ,ready: true
        ,beforeSubmit: true
        ,success: true
        ,failure: true
        ,save: true
        ,actionNew: true
        ,actionContinue: true
        ,actionClose: true
        ,postReady: true
    });
    this.getForm().addEvents({
        success: true
        ,failure: true
    });
    this.dropTargets = [];
    this.on('ready',this.onReady);
    if (this.config.useLoadingMask) {
        this.on('render', function() {
            this.mask = new Ext.LoadMask(this.getEl());
            this.mask.show();
        });
    }
    if (this.fireEvent('setup',config)) {
        this.clearDirty();
    }
    this.focusFirstField();
};
Ext.extend(MODx.FormPanel,Ext.FormPanel,{
    isReady: false
    ,defaultValues: []
    ,initialized: false
    /**
     * @property {Boolean} isStatic - Used to track the state of the static file switch
     * and its toggled fieldset; present in element editing form panels
     */
    ,isStatic: false

    /*
        Use these errorHandling properties to specify which tab components
        should and should not be inspected for field errors
    */
    ,errorHandlingTabs: []
    ,errorHandlingIgnoreTabs: []

    ,submit: function(o) {
        var fm = this.getForm();
        if (fm.isValid() || o.bypassValidCheck) {
            o = o || {};
            o.headers = {
                'Powered-By': 'MODx'
                ,'modAuth': MODx.siteId
            };
            if (this.fireEvent('beforeSubmit',{
               form: fm
               ,options: o
               ,config: this.config
            })) {
                fm.submit({
                    waitMsg: this.config.saveMsg || _('saving')
                    ,scope: this
                    ,headers: o.headers
                    ,clientValidation: (o.bypassValidCheck ? false : true)
                    ,failure: function(f,a) {
                    	if (this.fireEvent('failure',{
                    	   form: f
                    	   ,result: a.result
                    	   ,options: o
                    	   ,config: this.config
                    	})) {
                            MODx.form.Handler.errorExt(a.result,f);
                    	}
                    }
                    ,success: function(f,a) {
                        if (this.config.success) {
                            Ext.callback(this.config.success,this.config.scope || this,[f,a]);
                        }
                        this.fireEvent('success',{
                            form: f
                            ,result: a.result
                            ,options: o
                            ,config: this.config
                        });
                        this.clearDirty();
                        this.fireEvent('setup',this.config);

                        //get our Active input value and keep focus
                        var lastActiveEle = Ext.state.Manager.get('curFocus');
                        if (lastActiveEle && lastActiveEle != '') {
                            Ext.state.Manager.clear('curFocus');
                            var initFocus = document.getElementById(lastActiveEle);
                            if(initFocus) initFocus.focus();
                        }
                    }
                });
            }
        } else {
            return false;
        }
        return true;
    }

    ,failure: function(o) {
        this.warnUnsavedChanges = true;
        if(this.getForm().baseParams.action.search(/\/create/i) !== -1) {
            const btn = Ext.getCmp('modx-abtn-save');
            if (btn) {
                btn.enable();
            }
        }
        this.fireEvent('failureSubmit');
    }

    ,focusFirstField: function() {
        if (this.getForm().items.getCount() > 0) {
            var fld = this.findFirstTextField();
            if (fld) { fld.focus(false,200); }
        }
    }

    ,findFirstTextField: function(i) {
        i = i || 0;
        var fld = this.getForm().items.itemAt(i);
        if (!fld) return false;
        if (fld.isXType('combo') || fld.isXType('checkbox') || fld.isXType('radio') || fld.isXType('displayfield') || fld.isXType('statictextfield') || fld.isXType('hidden')) {
            i = i+1;
            fld = this.findFirstTextField(i);
        }
        return fld;
    }

    ,addChangeEvent: function(items) {
    	if (!items) { return false; }
    	if (typeof(items) == 'object' && items.items) {
            items = items.items;
    	}

        for (var f=0;f<items.length;f++) {
            var cmp = items[f];
            if (cmp.items) {
                this.addChangeEvent(cmp.items);
            } else if (cmp.xtype) {
                if (!cmp.listeners) { cmp.listeners = {}; }
                var ctypes = ['change'];
                cmp.enableKeyEvents = true;
                switch (cmp.xtype) {
                    case 'numberfield':
                    case 'textfield':
                    case 'textarea':
                        ctypes = ['keydown', 'change'];
                        break;
                    case 'checkbox':
                    case 'xcheckbox':
                    case 'radio':
                        ctypes = ['check'];
                        break;
                }
                if (cmp.xtype && cmp.xtype.indexOf('modx-combo') == 0) {
                    ctypes = ['select'];
                }

                var that = this;
                Ext.iterate(ctypes, function(ctype) {
                    if (cmp.listeners[ctype] && cmp.listeners[ctype].fn) {
                        cmp.listeners[ctype] = {fn:that.fieldChangeEvent.createSequence(cmp.listeners[ctype].fn,cmp.listeners[ctype].scope),scope:that}
                    } else {
                        cmp.listeners[ctype] = {fn:that.fieldChangeEvent,scope:that};
                    }
                });
            }
        }
    }

    ,fieldChangeEvent: function(fld,nv,ov,f) {
        if (!this.isReady) { return false; }
        var f = this.config.onDirtyForm ? Ext.getCmp(this.config.onDirtyForm) : this.getForm();
        this.fireEvent('fieldChange',{
            field: fld
            ,nv: nv
            ,ov: ov
            ,form: f
        });
    }

    ,markDirty: function() {
        this.fireEvent('fieldChange');
    }

    ,isDirty: function() {
        var f = this.config.onDirtyForm ? Ext.getCmp(this.config.onDirtyForm) : this.getForm();
    	return f.isDirty();
    }

    ,clearDirty: function() {
        var f = this.config.onDirtyForm ? Ext.getCmp(this.config.onDirtyForm) : this.getForm();
    	return f.clearDirty();
    }

    ,onReady: function(r) {
    	this.isReady = true;
        if (this.config.allowDrop) { this.loadDropZones(); }
        if (this.config.useLoadingMask && this.mask) {
            this.mask.hide();
        }
        this.fireEvent('postReady');
    }

    ,loadDropZones: function() {
        var dropTargets = this.dropTargets;
        var flds = this.getForm().items;
        flds.each(function(fld) {
            if (fld.isFormField && (
                fld.isXType('textfield') || fld.isXType('textarea')
            ) && !fld.isXType('combo')) {
                var el = fld.getEl();
                if (el) {
                    var target = new MODx.load({
                        xtype: 'modx-treedrop'
                        ,target: fld
                        ,targetEl: el.dom
                    });
                    dropTargets.push(target);
                }
            }
        });
    }

    ,getField: function(f) {
        var fld = false;
        if (typeof f == 'string') {
            fld = this.getForm().findField(f);
            if (!fld) { fld = Ext.getCmp(f); }
        }
        return fld;
    }

    /**
     * Called exclusively from MODx.hideField (modx.js) for form customization
     *
     * @param {String} fieldId - Text id or name of field whose label is being hidden
     * @return {void}
     */
    ,hideField: function(fieldId) {
        const field = this.getField(fieldId);
        if (!field) {
            return;
        }
        field.hide();
        const label = field.getEl().up('.x-form-item');
        if (label) {
            label.setDisplayed(false);
        }
    }

    ,showField: function(flds) {
        if (!Ext.isArray(flds)) { flds = flds[flds]; }
        var f;
        for (var i=0;i<flds.length;i++) {
            f = this.getField(flds[i]);
            if (!f) return;
            f.enable();
            f.show();
            var d = f.getEl().up('.x-form-item');
            if (d) { d.setDisplayed(true); }
        }
    }

    /**
     * Called exclusively from MODx.renameLabel (modx.js) for form customization
     *
     * @param {String} fieldId - Text id or name of field whose label is being renamed
     * @param {String} newLabel - The replacement label text
     * @return {void}
     */
    ,setLabel: function(fieldId, newLabel){
        const field = this.getField(fieldId);
        if (!field) {
            return;
        }
        switch (field.xtype) {
            case 'checkbox':
            case 'xcheckbox':
            case 'radio':
                field.setBoxLabel(newLabel);
                break;
            default:
                if (field.label) {
                    field.label.update(newLabel);
            }
        }
    }

    /**
     * @property {Function} setMediaSources - Updates one or more file fields with the specified Media Source(s)
     *
     * @param {String} formId - Id specifying the element type (template, chunk, snippet, etc.)
     * @param {String|Array} fieldKeys - Comma-separated list of unique file field identifiers used to access field
     * components having the following naming convention: 'modx-[formId]-[fieldKey]-file|source' (e.g., 'modx-tv-static-file')
     * @param {String} sharedSourceKey - When more than one file input is present,
     * enter one of the fieldKeys to specify it as the source for all inputs
     */
    ,setMediaSources: function(formId, fieldKeys = 'static', sharedSourceKey = '') {
        if (!Array.isArray(fieldKeys)) {
            fieldKeys = fieldKeys.split(',');
        }
        fieldKeys.forEach(fieldKey => {
            fieldKey = fieldKey.trim();
            const   fieldCmpId = `modx-${formId}-${fieldKey}-file`,
                    sourceKey = !Ext.isEmpty(sharedSourceKey) ? sharedSourceKey : fieldKey,
                    sourceCmpId = `modx-${formId}-${sourceKey}-source`,
                    fieldCmp = Ext.getCmp(fieldCmpId),
                    sourceCmp = Ext.getCmp(sourceCmpId)
            ;
            if (fieldCmp && sourceCmp) {
                fieldCmp.config.source = sourceCmp.getValue();
            }
        });
    }

    ,destroy: function() {
        for (var i = 0; i < this.dropTargets.length; i++) {
            this.dropTargets[i].destroy();
        }
        MODx.FormPanel.superclass.destroy.call(this);
    }

    /**
     * @property {Function} getTabIdsFromKeys - Get tab ids for use in further processing
     *
     * @param {Object} map - the items.map object of the primary tabs panel
     * @param {Array} keys - an array of keys matching those in the tabsObj items.keys
     */
    ,getTabIdsFromKeys: function(map, keys) {

        const tabIds = [];

        if (typeof map == 'object') {
            if (Array.isArray(keys) && keys.length > 0) {
                keys.forEach(function(key) {
                    if(map.hasOwnProperty(key) && typeof map[key].id == 'string') {
                        tabIds.push(map[key].id);
                    } else if (key == 'modx-panel-resource-tv' && MODx.config.tvs_below_content == 1) {
                        /*
                            When evaluating a resource panel with TVs moved below content,
                            the panel id needs to be added explicitly as, in this case, the TV panel
                            is not part of the main tabs component
                        */
                        tabIds.push(key);
                }
                });
            }
        }
        return tabIds;
        }

    /**
     * @property {Function} showErroredTab - Find errored field in the panel and activates the tab where the first error was found.
     *
     * @param {Array} targetForms - array of form tab itemIds to search for errors
     * @param {String} tabsId - id of primary tab component for a given panel
     */
    ,showErroredTab: function(targetForms, tabsId) {

        const mainTabs = Ext.getCmp(tabsId),
              searchTabs = this.getTabIdsFromKeys(mainTabs.items.map, targetForms)
        ;
        let mainTabName = null,
            mainTabIndex = null,
            component,
            erroredNode = null
        ;
        /*
            Add any custom panels, created on the fly via manager customization or CMPs,
            to the searchTabs
        */
        if (mainTabs.items.length > mainTabs.initialConfig.items.length) {
            mainTabs.items.keys.forEach(function(key) {
                if (mainTabs.items.map[key].hasOwnProperty('id')) {
                    if(!this.errorHandlingIgnoreTabs.includes(mainTabs.items.map[key].id) && !searchTabs.includes(mainTabs.items.map[key].id)) {
                        searchTabs.push(mainTabs.items.map[key].id);
                    }
                }
            }, this);
        }

        for (let i = 0; i < searchTabs.length; i++) {
            component = Ext.getCmp(searchTabs[i]);
            if (component && component.el && component.el.dom) {
                erroredNode = this.detectErrors(component.el.dom);
                if (erroredNode !== false) {
                    mainTabName = component.itemId ? component.itemId : searchTabs[i];
                    break;
                }
            }
        }

        if (mainTabName !== null) {

            const errFld = document.getElementById(erroredNode);

            if (mainTabs && mainTabs.items && mainTabs.items.keys) {
                mainTabIndex = mainTabs.items.keys.indexOf(mainTabName);
                if (component.id == 'modx-panel-resource-tv' && MODx.config.tvs_below_content == 0 || component.id != 'modx-panel-resource-tv') {
                    if (mainTabs.items.items[mainTabIndex].hidden) {
                        mainTabs.activate(mainTabName);
                    }
                }
            }

            if (component.id == 'modx-panel-resource-tv') {
                const errFldPanelId = errFld.closest('.x-panel').id,
                      tvTabs = Ext.getCmp('modx-resource-vtabs')
                ;
                if (tvTabs && tvTabs.items && tvTabs.items.keys) {
                    const tvTabIndex = tvTabs.items.keys.indexOf(errFldPanelId);
                    if (tvTabs.items.items[tvTabIndex] && tvTabs.items.items[tvTabIndex].hidden)  {
                        tvTabs.activate(errFldPanelId);
                    }
                }
            }
            errFld.focus();
        }
    }

    ,detectErrors: function(node) {
        const erroredFlds = document.getElementById(node.id).querySelectorAll('.x-form-invalid'),
              numErrors = erroredFlds.length
        ;
        if (numErrors > 0) {
            return erroredFlds[0].id;
        } else {
        return false;
        }
    }

    /**
     * @property {Function} insertTagCopyUtility - Updates placeholder tag in element name's help
     * field to the current element name and attaches a listener to copy the tag when clicked on
     *
     * @param {Object} cmp - The help field's Ext.Component object
     * @param {String} elType - The MODX element type (i.e., tv, chunk, or snippet)
     */
    ,insertTagCopyUtility: function(cmp, elType) {
        const helpTag = cmp.getEl().child('.example-replace-name'),
              elTag = cmp.getEl().child('.copy-this')
        ;
        let nameVal = cmp.previousSibling().getValue(),
            tagText
        ;

        // If the helptag isn't available, skip here. This may happen when a lexicon is missing or outdated
        // and doesn't contain the `example-replace-name` class.
        if (!helpTag) {
            return;
        }

        if (nameVal.length > 0) {
            helpTag.update(nameVal);
            tagText = elTag.dom.innerText;
        }

        helpTag.on({
            click: function() {
                nameVal = cmp.previousSibling().getValue();
                if (nameVal.length > 0) {
                    tagText = elTag.dom.innerText;
                    const tmp = document.createElement('textarea');
                    tmp.value = tagText;
                    document.body.appendChild(tmp);
                    tmp.select();
                    if (document.execCommand('copy')) {
                        const feedback = document.createElement('span');
                        feedback.className = 'element-panel feedback item-copied';
                        feedback.textContent = _(elType+'_tag_copied');
                        elTag.insertSibling(feedback, 'after');
                        setTimeout(function(){
                            feedback.style.opacity = 0;
                            setTimeout(function(){
                                feedback.remove();
                            }, 1200);
                        }, 10);
                    }
                    tmp.remove();
                }
            }
        });
    }

    /**
     * @property {Function} onChangeStaticSource - Updates the static file field based
     * on the chosen source.
     *
     * @param {Object} cmp - The media source field's Ext.Component object
     * @param {String} elType - The MODX element type (i.e., tv, chunk, or snippet)
     */
    ,onChangeStaticSource: function(cmp, elType) {

        const   isStatic = Ext.getCmp(`modx-${elType}-static`).getValue(),
                staticFileField = Ext.getCmp(`modx-${elType}-static-file`),
                staticFile = staticFileField.getValue(),
                staticDir = staticFile.slice(0, (staticFile.lastIndexOf('/') + 1)),
                staticFileFieldId = staticFileField.id,
                staticFileFieldContainer = Ext.getCmp(staticFileField.ownerCt.id),
                itemKey = staticFileFieldContainer.items.keys.indexOf(staticFileFieldId),
                previousSource = this.previousFileSource || 0,
                currentSource = cmp.getValue(),
                currentRecord = {
                    static: isStatic,
                    static_file: staticFile,
                    source: currentSource,
                    openTo: staticDir
                }
        ;
        let newStaticFile,
            changeFieldType = false,
            updateFieldSource = false
        ;
        if (elType === 'template') {
            // need these in method's global scope, so define with var instead of const/let
            var staticPreviewFileField = Ext.getCmp(`modx-${elType}-preview-file`),
                staticPreviewFile = staticPreviewFileField.getValue(),
                staticPreviewDir = staticPreviewFile.slice(0, (staticPreviewFile.lastIndexOf('/') + 1)),
                staticPreviewFileFieldId = staticPreviewFileField.id,
                staticPreviewFileFieldContainer = Ext.getCmp(staticPreviewFileField.ownerCt.id),
                previewItemKey = staticPreviewFileFieldContainer.items.keys.indexOf(staticPreviewFileFieldId),
                currentPreviewRecord = {
                    static: isStatic,
                    preview_file: staticPreviewFile,
                    source: currentSource,
                    openTo: staticPreviewDir
                },
                newPreviewFileField
            ;
        }

        this.previousFileSource = currentSource;

        if (previousSource > 0 && currentSource == 0) {
            // change staticFileField from combo to textfield
            newStaticFile = this.getStaticFileField(elType, currentRecord, false);
            if (elType === 'template') {
                newPreviewFileField = this.getTemplatePreviewImageField(currentPreviewRecord, false);
            }
            changeFieldType = true;
        } else if (previousSource == 0 && currentSource > 0) {
            // change staticFileField from textfield to combo
            newStaticFile = this.getStaticFileField(elType, currentRecord);
            if (elType === 'template') {
                newPreviewFileField = this.getTemplatePreviewImageField(currentPreviewRecord);
            }
            changeFieldType = true;
            updateFieldSource = true;
        } else {
            updateFieldSource = true;
        }

        if (updateFieldSource) {
            if (changeFieldType) {
                newStaticFile.source = currentSource;
                if (elType === 'template') {
                    newPreviewFileField.source = currentSource;
                }
            } else {
                staticFileField.config.source = currentSource;
                if (elType === 'template') {
                    staticPreviewFileField.config.source = currentSource;
                }
            }
        }

        if (changeFieldType) {
            staticFileField.clearInvalid();
            staticFileField.destroy();
            staticFileFieldContainer.insert(itemKey, newStaticFile);
            if (elType === 'template') {
                staticPreviewFileField.clearInvalid();
                staticPreviewFileField.destroy();
                staticPreviewFileFieldContainer.insert(previewItemKey, newPreviewFileField);
            }
            this.doLayout();
        }
    }

    /**
     * @property {Function} getStaticFileField - Builds the static field config based on the chosen media source.
     *
     * @param {String} elType - The MODX element type (i.e., tv, chunk, or snippet)
     * @param {Object} record - The FormPanel record
     * @param {Boolean} loadBrowserField - Whether to create a media browser combo for the static file field
     */
    ,getStaticFileField: function(elType, record, loadBrowserField = true) {
        const   sharedConfig = {
                    fieldLabel: _('static_file'),
                    description: MODx.expandHelp ? '' : _('static_file_desc'),
                    name: 'static_file',
                    id: `modx-${elType}-static-file`,
                    maxLength: 255,
                    anchor: '100%',
                    value: record.static_file || ''
        };
        let finalConfig;

        if (record.source === 0 || !record.hasOwnProperty('source') && Ext.isEmpty(MODx.config.default_media_source)) {
            loadBrowserField = false;
        }

        if (loadBrowserField) {
            finalConfig = Object.assign(sharedConfig, {
                xtype: 'modx-combo-browser',
                browserEl: 'modx-browser',
                triggerClass: 'x-form-code-trigger',
                source: record.source != null ? record.source : MODx.config.default_media_source,
                openTo: record.openTo || ''
            });
        } else {
            finalConfig = Object.assign(sharedConfig, {
                xtype: 'textfield'
            });
        }
        if (!record.static) {
            finalConfig.hidden = true;
        }
        return finalConfig;
    }

    /**
     * @property {Function} getTemplatePreviewImageField - Builds the template preview field config based on the chosen media source.
     *
     * @param {Object} record - The FormPanel record
     * @param {Boolean} loadBrowserField - Whether to create a media browser combo for the preview image field
     */
    ,getTemplatePreviewImageField: function(record, loadBrowserField = true) {
        const   sharedConfig = {
                    fieldLabel: _('template_preview'),
                    description: MODx.expandHelp ? '' : _('template_preview_description'),
                    name: 'preview_file',
                    id: 'modx-template-preview-file',
                    allowedFileTypes: 'jpg,jpeg,png,gif,bmp',
                    maxLength: 255,
                    anchor: '100%',
                    value: record.preview_file || ''
        };
        let finalConfig;

        if (record.source === 0 || !record.hasOwnProperty('source') && Ext.isEmpty(MODx.config.default_media_source)) {
            loadBrowserField = false;
        }

        if (loadBrowserField) {
            finalConfig = Object.assign(sharedConfig, {
                xtype: 'modx-combo-browser',
                browserEl: 'modx-browser',
                triggerClass: 'x-form-image-trigger',
                source: record.source != null ? record.source : MODx.config.default_media_source,
                openTo: record.openTo || ''
            });
        } else {
            finalConfig = Object.assign(sharedConfig, {
                xtype: 'textfield'
            });
        }
        return finalConfig;
    }

    /*
     * @property {Function} toggleFieldVisibility - Shows or hides a specified set of fields and their containing component
     *
     * @param {String} ctrlId - Id of the checkbox or listbox component that sets the toggle state
     * @param {String} containerId - Id of the container with fields to be toggled
     * @param {Array} fieldIds - An array of component ids to be toggled
     * @param {Boolean} ctrlValToShow - The boolean value from the toggle component that indicates fields should be shown
     * @param {Boolean} addSibling - Indicates if found field's next sibling (label) should be toggled as well; this applies when toggling items with separate help descriptions
     *
     */
    ,toggleFieldVisibility: function(ctrlId, containerId, fieldIds, ctrlValToShow, addSibling) {

        const ctrlCmp = Ext.getCmp(ctrlId),
              containerCmp = Ext.getCmp(containerId)
        ;
        if (!ctrlCmp || typeof ctrlCmp === 'undefined') {
            console.error(`toggleFieldVisibility: Could not get the control component with the id '${ctrlId}'`);
            return false;
        }
        if (containerId && (!containerCmp || typeof containerCmp === 'undefined')) {
            console.error(`toggleFieldVisibility: Could not get the container component with the id '${containerId}'`);
            return false;
        }

        addSibling = addSibling === false ? false : true ;
        ctrlValToShow = ctrlValToShow === false ? false : true ;

        const showVal = ctrlCmp.xtype === 'combo-boolean' ? ctrlCmp.getValue() : ctrlCmp.checked ,
              show = ctrlValToShow === false ? !showVal : showVal
        ;
        if (show) {
            containerCmp.show();
            // Must doLayout here to ensure field dimensions are calculated correctly before being shown
            containerCmp.doLayout();
        } else {
            containerCmp.hide();
        }

        fieldIds.forEach(field => {
            const fieldCmp = Ext.getCmp(field),
                  sibling = fieldCmp.nextSibling(),
                  siblingIsHelp = sibling && sibling.xtype === 'label'
            ;
            if (fieldCmp) {
                if (show) {
                    fieldCmp.show();
                    if (addSibling && siblingIsHelp) {
                        sibling.show();
                    }
                } else {
                    fieldCmp.hide();
                    if (addSibling && siblingIsHelp) {
                        sibling.hide();
                    }
                }
            }
        });
    }

    /**
     * @property {Function} formatMainPanelTitle - Adds display information to saved title
     *
     * @param {String} formId - Id specifying resource or element type
     * @param {Object} record - The data object for the current resource or element
     * @param {String} realtimeValue - The current title value provided by keyup listener
     * @param {Boolean} returnBaseTitle - Whether to output the non-pre/postfixed tag name (for chunks, tvs, and snippets)
     */
    ,formatMainPanelTitle: function(formId, record, realtimeValue = null, returnBaseTitle = false) {

        let title = '',
            baseTitle = ''
        ;
        const   modeCreate = record.hasOwnProperty('id') && record.id > 0 ? false : true,
                modeLabel =  modeCreate ? _('create') + ' ' : _('edit') + ' ',
                prefixSeparator = modeCreate && !realtimeValue ? '' : ': ',
                formTypeLexKey = formId === 'resource' ? 'document' : formId,
                prefix = modeLabel + _(formTypeLexKey) + prefixSeparator
        ;
        if (!Ext.isEmpty(record)) {
            const postfix = MODx.perm.tree_show_resource_ids && !Ext.isEmpty(record.id)
                ? ` <small>(${record.id})</small>`
                : ''
                ;
            if (formId === 'resource') {
                const headerCmp = Ext.getCmp('modx-header-breadcrumbs');
                title = realtimeValue ? realtimeValue : record.pagetitle ;
                baseTitle = this.encodeTitle(title, false);
                title = typeof title === 'undefined' ? prefix : this.encodeTitle(title) + postfix ;
                if (headerCmp) {
                    headerCmp.updateHeader(title);
                } else {
                    Ext.getCmp('modx-resource-header').el.dom.innerText = title;
                }
            } else {
                const headerCmpId = `modx-${formId}-header`;
                if (realtimeValue) {
                    baseTitle = this.encodeTitle(realtimeValue);
                } else {
                    title = formId === 'template' ? record.templatename : record.name ;
                    baseTitle = this.encodeTitle(title);
                }
                title = typeof title === 'undefined' ? prefix : prefix + baseTitle + postfix ;
                Ext.getCmp(headerCmpId).getEl().update(title);
            }
        }
        if (returnBaseTitle) {
            return baseTitle;
        }
    }

    /**
     * @property {Function} encodeTitle - Prepares raw title for display in form titles or fields
     *
     * @param {String} title - The raw title value, from the record or provided by keyup listener
     * @param {Boolean} htmlEncode - Set to false when preparing title for use in alias, etc.
     */
    ,encodeTitle: function(title, htmlEncode = true) {
        if (title) {
            title = htmlEncode
                ? Ext.util.Format.htmlEncode(Ext.util.Format.stripTags(title))
                : Ext.util.Format.stripTags(title)
                ;
        }
        return title;
    }

    /**
    * @property {Function} getElementProperties - Gets grid data for an element's properties
    *
    * @param {Array} properties - The set of properties from the current record
     */
    ,getElementProperties: function(properties) {
        if (!Ext.isEmpty(properties)) {
            const gridCmp = Ext.getCmp('modx-grid-element-properties');
            if (gridCmp) {
                gridCmp.defaultProperties = properties;
                gridCmp.getStore().loadData(properties);
            }
        }
    }
});
Ext.reg('modx-formpanel',MODx.FormPanel);

MODx.panel.Wizard = function(config) {
    config = config || {};
    Ext.applyIf(config,{
        layout: 'card'
        ,activeItem: 0
        ,resizable: true
        ,collapsible: true
        ,maximizable: true
        ,autoHeight: true
        ,width: 750
        ,firstPanel: ''
        ,lastPanel: ''
        ,defaults: { border: false }
        ,modal: true
        ,txtFinish: _('finish')
        ,txtNext: _('next')
        ,txtBack: _('back')
        ,bbar: [{
            id: 'pi-btn-bck'
            ,itemId: 'btn-back'
            ,text: config.txtBack || _('back')
            ,handler: this.navHandler.createDelegate(this,[-1])
            ,scope: this
            ,disabled: true
        },{
            id: 'pi-btn-fwd'
            ,itemId: 'btn-fwd'
            ,text: config.txtNext || _('next')
            ,handler: this.navHandler.createDelegate(this,[1])
            ,scope: this
        }]
    });
    MODx.panel.Wizard.superclass.constructor.call(this,config);
    this.config = config;
    this.lastActiveItem = this.config.firstPanel;
    this._go();
};
Ext.extend(MODx.panel.Wizard,Ext.Panel,{
    windows: {}

    ,_go: function() {
        this.getBottomToolbar().items.item(1).setText(this.config.txtNext);
        this.proceed(this.config.firstPanel);
    }

    ,navHandler: function(dir) {
        this.doLayout();
        var a = this.getLayout().activeItem;
        if (dir == -1) {
            this.proceed(a.config.back || a.config.id);
        } else {
            a.submit({
                scope: this
                ,proceed: this.proceed
            });
        }
    }

    ,proceed: function(id) {
        this.doLayout();
        this.getLayout().setActiveItem(id);
        if (id == this.config.firstPanel) {
            this.getBottomToolbar().items.item(0).setDisabled(true);
            this.getBottomToolbar().items.item(1).setText(this.config.txtNext);
        } else if (id == this.config.lastPanel) {
            this.getBottomToolbar().items.item(1).setText(this.config.txtFinish);
        } else {
            this.getBottomToolbar().items.item(0).setDisabled(false);
            this.getBottomToolbar().items.item(1).setText(this.config.txtNext);
        }
    }
});
Ext.reg('modx-panel-wizard',MODx.panel.Wizard);

MODx.panel.WizardPanel = function(config) {
	config = config || {};
	Ext.applyIf(config,{
        wizard: null
        ,checkDirty: false
        ,bodyStyle: 'padding: 3em 3em'
        ,hideMode: 'offsets'
	});
	MODx.panel.WizardPanel.superclass.constructor.call(this,config);
};
Ext.extend(MODx.panel.WizardPanel,MODx.FormPanel);
Ext.reg('modx-wizard-panel',MODx.panel.WizardPanel);


MODx.PanelSpacer = {
    html: '<br />'
    ,border: false
};

/**
 * A template panel base class
 *
 * @class MODx.TemplatePanel
 * @extends Ext.Panel
 * @param {Object} config An object of options.
 * @xtype modx-template-panel
 */
MODx.TemplatePanel = function(config) {
    config = config || {};
	Ext.applyIf(config,{
		frame:false
		,startingMarkup: '<tpl for=".">'
			+'<div class="empty-text-wrapper"><p>{text}</p></div>'
		+'</tpl>'
		,startingText: 'Loading...'
		,markup: null
		,plain:true
		,border: false
	});
	MODx.TemplatePanel.superclass.constructor.call(this,config);
	this.on('render', this.init, this);
}
Ext.extend(MODx.TemplatePanel,Ext.Panel,{
	init: function(){
		this.defaultMarkup = new Ext.XTemplate(this.startingMarkup, { compiled: true });
		this.reset();
		this.tpl = new Ext.XTemplate(this.markup, { compiled: true });
	}

	,reset: function(){
		this.body.hide();
		this.defaultMarkup.overwrite(this.body, {text: this.startingText});
		this.body.slideIn('r', {stopFx:true, duration:.2});
		setTimeout(function(){
			Ext.getCmp('modx-content').doLayout();
		}, 500);
	}

	,updateDetail: function(data) {
		this.body.hide();
		this.tpl.overwrite(this.body, data);
		this.body.slideIn('r', {stopFx:true, duration:.2});
		setTimeout(function(){
			Ext.getCmp('modx-content').doLayout();
		}, 500);
	}
});
Ext.reg('modx-template-panel',MODx.TemplatePanel);

/**
 * A breacrumb builder + the panel desc if necessary
 *
 * @class MODx.BreadcrumbsPanel
 * @extends Ext.Panel
 * @param {Object} config An object of options.
 * @xtype modx-breadcrumbs-panel
 */
MODx.BreadcrumbsPanel = function(config) {
    config = config || {};
	Ext.applyIf(config,{
		frame:false
		,plain:true
		,border: false
		,desc: 'This the description part of this panel'
		,bdMarkup: '<tpl if="typeof(trail) != &quot;undefined&quot;">'
			+'<div class="crumb_wrapper"><ul class="crumbs">'
				+'<tpl for="trail">'
					+'<li{[values.className != undefined ? \' class="\'+values.className+\'"\' : \'\' ]}>'
						+'<tpl if="typeof pnl != \'undefined\'">'
							+'<button type="button" class="controlBtn {pnl}{[values.root ? \' root\' : \'\' ]}">{text}</button>'
						+'</tpl>'
                        +'<tpl if="typeof install != \'undefined\'">'
							+'<button type="button" class="controlBtn install{[values.root ? \' root\' : \'\' ]}">{text}</button>'
						+'</tpl>'
						+'<tpl if="typeof pnl == \'undefined\' && typeof install == \'undefined\'"><span class="text{[values.root ? \' root\' : \'\' ]}">{text}</span></tpl>'
					+'</li>'
				+'</tpl>'
			+'</ul></div>'
		+'</tpl>'
		+'<tpl if="typeof(text) != &quot;undefined&quot;">'
			+'<div class="panel-desc{[values.className != undefined ? \' \'+values.className+\'"\' : \'\' ]}"><p>{text}</p></div>'
		+'</tpl>'
		,root : {
			text : 'Home'
			,className: 'first'
			,root: true
			,pnl: ''
		}
		,bodyCssClass: 'breadcrumbs'
	});
	MODx.BreadcrumbsPanel.superclass.constructor.call(this,config);
	this.on('render', this.init, this);
}

Ext.extend(MODx.BreadcrumbsPanel,Ext.Panel,{
    data: {trail: []}

	,init: function(){
		this.tpl = new Ext.XTemplate(this.bdMarkup, { compiled: true });
		this.reset(this.desc);

        this.body.on('click', this.onClick, this);
	}

	,getResetText: function(srcInstance){
		if(typeof(srcInstance) != 'object' || srcInstance == null){
			return srcInstance;
		}
		var newInstance = srcInstance.constructor();
		for(var i in srcInstance){
			newInstance[i] = this.getResetText(srcInstance[i]);
		}
		//The trail is not a link
		if(newInstance.hasOwnProperty('pnl')){
			delete newInstance['pnl'];
		}
		return newInstance;
	}

	,updateDetail: function(data){
        this.data = data;
		// Automagically the trail root
		if(data.hasOwnProperty('trail')){
			var trail = data.trail;
			trail.unshift(this.root);
		}
		this._updatePanel(data);
	}

    ,getData: function() {
        return this.data;
    }

	,reset: function(msg){
		if(typeof(this.resetText) == "undefined"){
			this.resetText = this.getResetText(this.root);
		}
		this.data = { text : msg ,trail : [this.resetText] };
		this._updatePanel(this.data);
	}

	,onClick: function(e){
		var target = e.getTarget();

        var index = 1;
        var parent = target.parentElement;
        while ((parent = parent.previousSibling) != null) {
            index += 1;
        }

        var remove = this.data.trail.length - index;
        while (remove > 0) {
            this.data.trail.pop();
            remove -= 1;
        }

		elm = target.className.split(' ')[0];
		if(elm != "" && elm == 'controlBtn'){
			// Don't use "pnl" shorthand, it make the breadcrumb fail
			var panel = target.className.split(' ')[1];

            if (panel == 'install') {
                var last = this.data.trail[this.data.trail.length - 1];
                if (last != undefined && last.rec != undefined) {
                    this.data.trail.pop();
                    var grid = Ext.getCmp('modx-package-grid');
                    grid.install(last.rec);
                    return;
                }
            } else {
			    Ext.getCmp(panel).activate();
            }
		}
	}

	,_updatePanel: function(data){
		this.body.hide();
		this.tpl.overwrite(this.body, data);
		this.body.slideIn('r', {stopFx:true, duration:.2});
		setTimeout(function(){
			Ext.getCmp('modx-content').doLayout();
		}, 500);
	}
});
Ext.reg('modx-breadcrumbs-panel',MODx.BreadcrumbsPanel);
