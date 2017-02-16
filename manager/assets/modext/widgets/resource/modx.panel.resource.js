MODx.panel.Resource = function(config) {
    config = config || {record:{}};
    config.record = config.record || {};
    Ext.applyIf(config,{
        url: MODx.config.connector_url
        ,baseParams: {}
        ,id: 'modx-panel-resource'
        ,class_key: 'modDocument'
        ,resource: ''
        ,bodyStyle: ''
		,cls: 'container form-with-labels'
        ,defaults: { collapsible: false ,autoHeight: true }
        ,forceLayout: true
        ,items: this.getFields(config)
        ,fileUpload: true
        ,useLoadingMask: true
        ,listeners: {
            'setup': {fn:this.setup,scope:this}
            ,'success': {fn:this.success,scope:this}
            ,'failure': {fn:this.failure,scope:this}
            ,'beforeSubmit': {fn:this.beforeSubmit,scope:this}
            ,'fieldChange': {fn:this.onFieldChange,scope:this}
        }
    });
    MODx.panel.Resource.superclass.constructor.call(this,config);
    var ta = Ext.get('ta');
    if (ta) { ta.on('keydown',this.fieldChangeEvent,this); }
    this.on('ready',this.onReady,this);
    var urio = Ext.getCmp('modx-resource-uri-override');
    if (urio) { urio.on('check',this.freezeUri); }
    this.addEvents('tv-reset');
};
Ext.extend(MODx.panel.Resource,MODx.FormPanel,{
    initialized: false
    ,defaultClassKey: 'modDocument'
    ,classLexiconKey: 'document'
    ,rteElements: 'ta'
    ,rteLoaded: false
    ,warnUnsavedChanges: false
    ,setup: function() {
        if (!this.initialized) {
            this.getForm().setValues(this.config.record);
            var pcmb = this.getForm().findField('parent-cmb');
            if (pcmb && Ext.isEmpty(this.config.record.parent_pagetitle)) {
                pcmb.setValue('');
            } else if (pcmb) {
                pcmb.setValue(this.config.record.parent_pagetitle+' ('+this.config.record.parent+')');
            }
            if (!Ext.isEmpty(this.config.record.pagetitle)) {
                Ext.getCmp('modx-resource-header').getEl().update('<h2>'+Ext.util.Format.stripTags(this.config.record.pagetitle)+'</h2>');
            }
            // initial check to enable realtime alias
            if (Ext.isEmpty(this.config.record.alias)) {
                this.config.aliaswasempty = true;
            } else {
                this.config.aliaswasempty = false;
            }
            this.config.translitloading = false; // the initial value for the realtime-alias throttling

            if (!Ext.isEmpty(this.config.record.resourceGroups)) {
                var g = Ext.getCmp('modx-grid-resource-security');
                if (g && Ext.isEmpty(g.config.url)) {
                    var s = g.getStore();
                    if (s) { s.loadData(this.config.record.resourceGroups); }
                }
            }

            this.defaultClassKey = this.config.record.class_key || this.defaultClassKey;
            this.defaultValues = this.config.record || {};
            if ((this.config.record && this.config.record.richtext) || MODx.request.reload || MODx.request.activeSave == 1) {
                this.markDirty();
            }

            // Prevent accidental navigation when stuff has not been saved
            if (MODx.config.confirm_navigation == 1) {
                var panel = this;
                window.onbeforeunload = function() {
                    if (panel.warnUnsavedChanges) return _('unsaved_changes');
                };
            }

            if (this.config.record.deleted) {
                this.handlePreview('hide');
            }
        }
        if (MODx.config.use_editor && MODx.loadRTE) {
            var f = this.getForm().findField('richtext');
            if (f && f.getValue() == 1 && !this.rteLoaded) {
                MODx.loadRTE(this.rteElements);
                this.rteLoaded = true;
            } else if (f && f.getValue() == 0 && this.rteLoaded) {
                if (MODx.unloadRTE) {
                    MODx.unloadRTE(this.rteElements);
                }
                this.rteLoaded = false;
            }
        }

        this.fireEvent('ready');
        this.initialized = true;

        MODx.fireEvent('ready');
        MODx.sleep(4); /* delay load event to allow FC rules to move before loading RTE */
        if (MODx.afterTVLoad) { MODx.afterTVLoad(); }
        this.fireEvent('load');
    }

    /**
     * Handle the preview button visibility according to the resource "deleted" status
     *
     * @param {string} action The action to perform on the preview button (hide/show)
     */
    ,handlePreview: function(action) {
        var previewBtn = Ext.getCmp('modx-abtn-preview');
        if (previewBtn == undefined) {
            // Button not found, let's try again in a few ms
            Ext.defer(function() {
                this.handlePreview(action);
            }, 200, this);
        } else {
            var toolBar = Ext.getCmp('modx-page-update-resource').ab
                ,btnIndex = toolBar.items.indexOf(previewBtn);

            // Do the desired action on the button and its sibling (a spacer)
            previewBtn[action]();
            toolBar.items.get(btnIndex + 1)[action]();
        }
    }

    ,beforeDestroy: function(){
        if (this.rteLoaded && MODx.unloadRTE){
            MODx.unloadRTE(this.rteElements);
            this.rteLoaded = false;
        }
        MODx.panel.Resource.superclass.beforeDestroy.call(this);
    }

    ,beforeSubmit: function(o) {
        var ta = Ext.get('ta');
        if (ta) {
            var v = ta.dom.value;
            var hc = Ext.getCmp('hiddenContent');
            if (hc) { hc.setValue(v); }
        }
        var g = Ext.getCmp('modx-grid-resource-security');
        if (g) {
            Ext.apply(o.form.baseParams,{
                resource_groups: g.encode()
            });
        }
        if (ta) {
            this.cleanupEditor();
        }
        if(this.getForm().baseParams.action == 'resource/create') {
            var btn = Ext.getCmp('modx-abtn-save');
            if (btn) { btn.disable(); }
        }
        return this.fireEvent('save',{
            values: this.getForm().getValues()
            ,stay: Ext.state.Manager.get('modx.stay.'+MODx.request.a,'stay')
        });
    }
    ,success: function(o) {
        this.warnUnsavedChanges = false;
        var g = Ext.getCmp('modx-grid-resource-security');
        if (g) { g.getStore().commitChanges(); }
        var t = Ext.getCmp('modx-resource-tree');

        if (t) {
            var ctx = Ext.getCmp('modx-resource-context-key').getValue();
            var pa = Ext.getCmp('modx-resource-parent-hidden').getValue();
            var pao = Ext.getCmp('modx-resource-parent-old-hidden').getValue();
            var v = ctx+'_'+pa;
            var n = t.getNodeById(v);
            if(pa !== pao) {
                t.refresh();
                Ext.getCmp('modx-resource-parent-old-hidden').setValue(pa);
            } else {
                if(typeof n!=="undefined"){
                    n.leaf = false;
                }
                t.refreshNode(v,true);
            }
        }
        var object = o.result.object;
        // object.parent is undefined on template changing.
        if (this.config.resource && object.parent !== undefined && (object.class_key != this.defaultClassKey || object.parent != this.defaultValues.parent)) {
            location.href = location.href;
        } else {
            if (object.deleted !== this.record.deleted) {
                if (object.deleted) {
                    var action = 'hide';
                } else {
                    action = 'show';
                }
                this.handlePreview(action);
            }
            this.record = object;
            this.getForm().setValues(object);
            Ext.getCmp('modx-page-update-resource').config.preview_url = object.preview_url;
        }
    }
    ,failure: function(o) {
        this.warnUnsavedChanges = true;
        if(this.getForm().baseParams.action == 'resource/create') {
            var btn = Ext.getCmp('modx-abtn-save');
            if (btn) { btn.enable(); }
        }
    }

    ,freezeUri: function(cb) {
        var uri = Ext.getCmp('modx-resource-uri');
        if (!uri) { return false; }
        if (cb.checked) {
            uri.show();
        } else {
            uri.hide();
        }
    }
    // used for realtime-alias transliteration
    ,translitAlias: function(string) {
        if (!this.config.translitloading) {
            this.config.translitloading = true;
            MODx.Ajax.request({
                url: MODx.config.connector_url
                ,params: {
                    action: 'resource/translit'
                    ,string: string
                }
                ,listeners: {
                    'success': {fn:function(r) {
                        var alias = Ext.getCmp('modx-resource-alias');
                        if (!Ext.isEmpty(r.object.transliteration)) {
                            alias.setValue(r.object.transliteration);
                            this.config.translitloading = false;
                        }
                    },scope:this}
                }
            });
        }
    }
    ,templateWarning: function() {
        var t = Ext.getCmp('modx-resource-template');
        if (!t) { return false; }
        if(t.getValue() !== t.originalValue) {
            Ext.Msg.confirm(_('warning'), _('resource_change_template_confirm'), function(e) {
                if (e == 'yes') {
                    var nt = t.getValue();
                    var f = Ext.getCmp('modx-page-update-resource');
                    f.config.action = 'resource/reload';
                    this.warnUnsavedChanges = false;
                    MODx.activePage.submitForm({
                        success: {fn:function(r) {
                            MODx.loadPage(r.result.object.action, 'id='+r.result.object.id+'&reload='+r.result.object.reload + '&class_key='+ r.result.object.class_key);
                        },scope:this}
                    },{
                        bypassValidCheck: true
                    },{
                        reloadOnly: true
                    });
                } else {
                    t.setValue(this.config.record.template);
                }
            },this);
        }
    }
    ,onFieldChange: function(o) {
        //a11y - Set Active Input
        if (o && o.field) {
            Ext.state.Manager.set('curFocus', o.field.id);

            if (o.field.name === 'syncsite') {
                return;
            }
        }

        if (this.isReady || MODx.request.reload) {
            this.warnUnsavedChanges = true;
        }
    }

    ,cleanupEditor: function() {
        if (MODx.onSaveEditor) {
            var fld = Ext.getCmp('ta');
            if (fld) { MODx.onSaveEditor(fld); }
        }
    }

    ,getFields: function(config) {
        var it = [];
        it.push({
            title: _(this.classLexiconKey)
            ,id: 'modx-resource-settings'
            ,cls: 'modx-resource-tab'
            ,layout: 'form'
            ,labelAlign: 'top'
            ,labelSeparator: ''
            ,bodyCssClass: 'tab-panel-wrapper main-wrapper'
            ,autoHeight: true
            ,defaults: {
                border: false
                ,msgTarget: 'under'
                ,width: 400
            }
            ,items: this.getMainFields(config)
        });
        it.push({
            id: 'modx-page-settings'
            ,title: _('settings')
            ,cls: 'modx-resource-tab'
            ,layout: 'form'
            ,forceLayout: true
            ,deferredRender: false
            ,labelWidth: 200
            ,bodyCssClass: 'main-wrapper'
            ,autoHeight: true
            ,defaults: {
                border: false
                ,msgTarget: 'under'
            }
            ,items: this.getSettingFields(config)
        });
        if (config.show_tvs && MODx.config.tvs_below_content != 1) {
            it.push(this.getTemplateVariablesPanel(config));
        }
        if (MODx.perm.resourcegroup_resource_list == 1) {
            it.push(this.getAccessPermissionsTab(config));
        }
        var its = [];
        its.push(this.getPageHeader(config),{
            id:'modx-resource-tabs'
            ,xtype: 'modx-tabs'
            ,forceLayout: true
            ,deferredRender: false
            ,collapsible: true
            ,animCollapse: false
            ,itemId: 'tabs'
            ,items: it
        });
        var ct = this.getContentField(config);
        if (ct) {
            its.push({
                title: _('resource_content')
                ,id: 'modx-resource-content'
                ,layout: 'form'
                ,bodyCssClass: 'main-wrapper'
                ,autoHeight: true
                ,collapsible: true
                ,animCollapse: false
                ,hideMode: 'offsets'
                ,items: ct
            });
        }
        if (MODx.config.tvs_below_content == 1) {
            var tvs = this.getTemplateVariablesPanel(config);
            its.push(tvs);
        }
        return its;
    }

    ,getPageHeader: function(config) {
        config = config || {record:{}};
        return {
            html: '<h2>'+_('document_new')+'</h2>'
            ,id: 'modx-resource-header'
            ,cls: 'modx-page-header'
            ,border: false
            ,forceLayout: true
            ,anchor: '100%'
        };
    }

    ,getTemplateVariablesPanel: function(config) {
        return {
            xtype: 'modx-panel-resource-tv'
            ,collapsed: false
            ,resource: config.resource
            ,class_key: config.record.class_key || 'modDocument'
            ,template: config.record.template
            ,anchor: '100%'
            ,border: true
            ,style: 'visibility: visible'
        };
    }

    ,getMainFields: function(config) {
        config = config || {record:{}};
        return [{
            layout:'column'
            ,border: false
            ,anchor: '100%'
            ,id: 'modx-resource-main-columns'
            ,defaults: {
                labelSeparator: ''
                ,labelAlign: 'top'
                ,border: false
                ,msgTarget: 'under'
            }
            ,items:[{
                columnWidth: .67
                ,layout: 'form'
                ,id: 'modx-resource-main-left'
                ,defaults: { msgTarget: 'under' }
                ,items: this.getMainLeftFields(config)
            },{
                columnWidth: .33
                ,layout: 'form'
                ,labelWidth: 0
                ,border: false
                ,id: 'modx-resource-main-right'
                ,style: 'margin-right: 0'
                ,defaults: { msgTarget: 'under' }
                ,items: this.getMainRightFields(config)
            }]
        },{
            html: MODx.onDocFormRender, border: false
        },{
            xtype: 'hidden'
            ,fieldLabel: _('id')
            ,hideLabel: true
            ,description: '<b>[[*id]]</b><br />'
            ,name: 'id'
            ,id: 'modx-resource-id'
            ,anchor: '100%'
            ,value: config.resource || config.record.id
            ,submitValue: true
        },{
            xtype: 'hidden'
            ,name: 'type'
            ,value: 'document'
        },{
            xtype: 'hidden'
            ,name: 'context_key'
            ,id: 'modx-resource-context-key'
            ,value: config.record.context_key || 'web'
        },{
            xtype: 'hidden'
            ,name: 'content'
            ,id: 'hiddenContent'
            ,value: (config.record.content || config.record.ta) || ''
        },{
            xtype: 'hidden'
            ,name: 'create-resource-token'
            ,id: 'modx-create-resource-token'
            ,value: config.record.create_resource_token || ''
        },{
            xtype: 'hidden'
            ,name: 'reloaded'
            ,value: !Ext.isEmpty(MODx.request.reload) ? 1 : 0
        },{
            xtype: 'hidden'
            ,name: 'parent'
            ,value: config.record.parent || 0
            ,id: 'modx-resource-parent-hidden'
        },{
            xtype: 'hidden'
            ,name: 'parent-original'
            ,value: config.record.parent || 0
            ,id: 'modx-resource-parent-old-hidden'
        }];
    }

    ,getMainLeftFields: function(config) {
        config = config || {record:{}};
        return [{
            xtype: 'textfield'
            ,fieldLabel: _('resource_pagetitle')+'<span class="required">*</span>'
            ,description: '<b>[[*pagetitle]]</b><br />'+_('resource_pagetitle_help')
            ,name: 'pagetitle'
            ,id: 'modx-resource-pagetitle'
            ,maxLength: 255
            ,anchor: '100%'
            ,allowBlank: false
            ,enableKeyEvents: true
            ,listeners: {
                'keyup': {fn: function(f,e) {
                    var titlePrefix = MODx.request.a == 'resource/create' ? _('new_document') : _('document');
                    var title = Ext.util.Format.stripTags(f.getValue());
                    Ext.getCmp('modx-resource-header').getEl().update('<h2>'+title+'</h2>');

                    // check some system settings before doing real time alias transliteration
                    if (parseInt(MODx.config.friendly_alias_realtime, 10) && parseInt(MODx.config.automatic_alias, 10)) {
                        // handles the realtime-alias transliteration
                        if (this.config.aliaswasempty && title !== '') {
                            this.translitAlias(title);
                        }
                    }
                }, scope: this}
                // also do realtime transliteration of alias on blur of pagetitle field
                // as sometimes (when typing very fast) the last letter(s) are not catched
                ,'blur': {fn: function(f,e) {
                    var title = Ext.util.Format.stripTags(f.getValue());

                    // check some system settings before doing real time alias transliteration
                    if (parseInt(MODx.config.friendly_alias_realtime, 10) && parseInt(MODx.config.automatic_alias, 10)) {
                        // handles the realtime-alias transliteration
                        if (this.config.aliaswasempty && title !== '') {
                            this.translitAlias(title);
                        }
                    }
                }, scope: this}
            }
        },{
            xtype: 'textfield'
            ,fieldLabel: _('resource_longtitle')
            ,description: '<b>[[*longtitle]]</b><br />'+_('resource_longtitle_help')
            ,name: 'longtitle'
            ,id: 'modx-resource-longtitle'
            ,maxLength: 255
            ,anchor: '100%'
            ,value: config.record.longtitle || ''
        },{
            xtype: 'textarea'
            ,fieldLabel: _('resource_description')
            ,description: '<b>[[*description]]</b><br />'+_('resource_description_help')
            ,name: 'description'
            ,id: 'modx-resource-description'
            ,maxLength: 255
            ,anchor: '100%'
            ,value: config.record.description || ''

        },{
            xtype: 'textarea'
            ,fieldLabel: _('resource_summary')
            ,description: '<b>[[*introtext]]</b><br />'+_('resource_summary_help')
            ,name: 'introtext'
            ,id: 'modx-resource-introtext'
            ,grow: true
            ,anchor: '100%'
            ,value: config.record.introtext || ''
        }];
    }

    ,getMainRightFields: function(config) {
        config = config || {};

        var aliasLength = ~~MODx.config['friendly_alias_max_length'] || 0;

        return [{
            xtype: 'modx-combo-template'
            ,fieldLabel: _('resource_template')
            ,description: '<b>[[*template]]</b><br />'+_('resource_template_help')
            ,name: 'template'
            ,id: 'modx-resource-template'
            ,anchor: '100%'
            ,editable: true
            ,typeAhead: true
            ,typeAheadDelay: 300
            ,forceSelection: true
            ,baseParams: {
                action: 'element/template/getList'
                ,combo: '1'
                ,limit: 0
            }
            ,listeners: {
                'select': {fn: this.templateWarning,scope: this}
            }
        },{
            xtype: 'textfield'
            ,fieldLabel: _('resource_alias')
            ,description: '<b>[[*alias]]</b><br />'+_('resource_alias_help')
            ,name: 'alias'
            ,id: 'modx-resource-alias'
            ,maxLength: (aliasLength > 255 || aliasLength === 0) ? 255 : aliasLength
            ,anchor: '100%'
            ,value: config.record.alias || ''
            ,listeners: {
                'change': {fn: function(f,e) {
                    // when the alias is manually cleared, enable real time alias
                    if (Ext.isEmpty(f.getValue())) {
                        this.config.aliaswasempty = true;
                    }
                }, scope: this}
            }
        },{
            xtype: 'textfield'
            ,fieldLabel: _('resource_menutitle')
            ,description: '<b>[[*menutitle]]</b><br />'+_('resource_menutitle_help')
            ,name: 'menutitle'
            ,id: 'modx-resource-menutitle'
            ,maxLength: 255
            ,anchor: '100%'
            ,value: config.record.menutitle || ''
        },{
            xtype: 'textfield'
            ,fieldLabel: _('resource_link_attributes')
            ,description: '<b>[[*link_attributes]]</b><br />'+_('resource_link_attributes_help')
            ,name: 'link_attributes'
            ,id: 'modx-resource-link-attributes'
            ,maxLength: 255
            ,anchor: '100%'
            ,value: config.record.link_attributes || ''
        },{
            xtype: 'xcheckbox'
            ,boxLabel: _('resource_hide_from_menus')
            ,hideLabel: true
            ,description: '<b>[[*hidemenu]]</b><br />'+_('resource_hide_from_menus_help')
            ,name: 'hidemenu'
            ,id: 'modx-resource-hidemenu'
            ,inputValue: 1
            ,checked: parseInt(config.record.hidemenu) || false
        },{
            xtype: 'xcheckbox'
            ,boxLabel: _('resource_published')
            ,hideLabel: true
            ,description: '<b>[[*published]]</b><br />'+_('resource_published_help')
            ,name: 'published'
            ,id: 'modx-resource-published'
            ,inputValue: 1
            ,checked: parseInt(config.record.published)
        }]
    }

    ,getSettingFields: function(config) {
        config = config || {record:{}};

        var s = [{
            layout:'column'
            ,border: false
            ,anchor: '100%'
            ,defaults: {
                labelSeparator: ''
                ,labelAlign: 'top'
                ,border: false
                ,layout: 'form'
                ,msgTarget: 'under'
            }
            ,items:[{
                columnWidth: .5
                ,id: 'modx-page-settings-left'
                ,defaults: { msgTarget: 'under' }
                ,items: this.getSettingLeftFields(config)
            },{
                columnWidth: .5
                ,id: 'modx-page-settings-right'
                ,defaults: { msgTarget: 'under' }
                ,items: this.getSettingRightFields(config)
            }]
        }];
        return s;
    }

    ,getSettingLeftFields: function(config) {
        return [{
            xtype: 'modx-field-parent-change'
            ,fieldLabel: _('resource_parent')
            ,description: '<b>[[*parent]]</b><br />'+_('resource_parent_help')
            ,name: 'parent-cmb'
            ,id: 'modx-resource-parent'
            ,value: config.record.parent || 0
            ,anchor: '100%'
        },{
            xtype: 'modx-combo-class-derivatives'
            ,fieldLabel: _('resource_type')
            ,description: '<b>[[*class_key]]</b><br />'
            ,name: 'class_key'
            ,hiddenName: 'class_key'
            ,id: 'modx-resource-class-key'
            ,allowBlank: false
            ,value: config.record.class_key || 'modDocument'
            ,anchor: '100%'
        },{
            xtype: 'modx-combo-content-type'
            ,fieldLabel: _('resource_content_type')
            ,description: '<b>[[*content_type]]</b><br />'+_('resource_content_type_help')
            ,name: 'content_type'
            ,hiddenName: 'content_type'
            ,id: 'modx-resource-content-type'
            ,anchor: '100%'
            ,value: config.record.content_type || (MODx.config.default_content_type || 1)

        },{
            xtype: 'modx-combo-content-disposition'
            ,fieldLabel: _('resource_contentdispo')
            ,description: '<b>[[*content_dispo]]</b><br />'+_('resource_contentdispo_help')
            ,name: 'content_dispo'
            ,hiddenName: 'content_dispo'
            ,id: 'modx-resource-content-dispo'
            ,anchor: '100%'
            ,value: config.record.content_dispo || 0

        },{
            xtype: 'numberfield'
            ,fieldLabel: _('resource_menuindex')
            ,description: '<b>[[*menuindex]]</b><br />'+_('resource_menuindex_help')
            ,name: 'menuindex'
            ,id: 'modx-resource-menuindex'
            ,width: 75
            ,value: parseInt(config.record.menuindex) || 0
        }];
    }

    ,getSettingRightFields: function(config) {
        return [{
            xtype: 'xdatetime'
            ,fieldLabel: _('resource_publishedon')
            ,description: '<b>[[*publishedon]]</b><br />'+_('resource_publishedon_help')
            ,name: 'publishedon'
            ,id: 'modx-resource-publishedon'
            ,allowBlank: true
            ,dateFormat: MODx.config.manager_date_format
            ,timeFormat: MODx.config.manager_time_format
            ,startDay: parseInt(MODx.config.manager_week_start)
            ,dateWidth: 120
            ,timeWidth: 120
            ,offset_time: MODx.config.server_offset_time
            ,value: config.record.publishedon
        },{
            xtype: MODx.config.publish_document ? 'xdatetime' : 'hidden'
            ,fieldLabel: _('resource_publishdate')
            ,description: '<b>[[*pub_date]]</b><br />'+_('resource_publishdate_help')
            ,name: 'pub_date'
            ,id: 'modx-resource-pub-date'
            ,allowBlank: true
            ,dateFormat: MODx.config.manager_date_format
            ,timeFormat: MODx.config.manager_time_format
            ,startDay: parseInt(MODx.config.manager_week_start)
            ,dateWidth: 120
            ,timeWidth: 120
            ,offset_time: MODx.config.server_offset_time
            ,value: config.record.pub_date
        },{
            xtype: MODx.config.publish_document ? 'xdatetime' : 'hidden'
            ,fieldLabel: _('resource_unpublishdate')
            ,description: '<b>[[*unpub_date]]</b><br />'+_('resource_unpublishdate_help')
            ,name: 'unpub_date'
            ,id: 'modx-resource-unpub-date'
            ,allowBlank: true
            ,dateFormat: MODx.config.manager_date_format
            ,timeFormat: MODx.config.manager_time_format
            ,startDay: parseInt(MODx.config.manager_week_start)
            ,dateWidth: 120
            ,timeWidth: 120
            ,offset_time: MODx.config.server_offset_time
            ,value: config.record.unpub_date
        },{
            xtype: 'fieldset'
            ,items: this.getSettingRightFieldset(config)
        }];
    }

    ,getSettingRightFieldset: function(config) {
        return [{
            layout: 'column'
            ,id: 'modx-page-settings-box-columns'
            ,border: false
            ,anchor: '100%'
            ,defaults: {
                labelSeparator: ''
                ,labelAlign: 'top'
                ,border: false
                ,layout: 'form'
                ,msgTarget: 'under'
            }
            ,items: [{
                columnWidth: .5
                ,id: 'modx-page-settings-right-box-left'
                ,defaults: { msgTarget: 'under' }
                ,items: this.getSettingRightFieldsetLeft(config)
            },{
                columnWidth: .5
                ,id: 'modx-page-settings-right-box-right'
                ,defaults: { msgTarget: 'under' }
                ,items: this.getSettingRightFieldsetRight(config)
            }]
        },{
            xtype: 'xcheckbox'
            ,boxLabel: _('resource_uri_override')
            ,description: _('resource_uri_override_help')
            ,hideLabel: true
            ,name: 'uri_override'
            ,value: 1
            ,checked: parseInt(config.record.uri_override) ? true : false
            ,id: 'modx-resource-uri-override'

        },{
            xtype: 'textfield'
            ,fieldLabel: _('resource_uri')
            ,description: '<b>[[*uri]]</b><br />'+_('resource_uri_help')
            ,name: 'uri'
            ,id: 'modx-resource-uri'
            ,maxLength: 255
            ,anchor: '70%'
            ,value: config.record.uri || ''
            ,hidden: !config.record.uri_override
        }];
    }

    ,getSettingRightFieldsetLeft: function(config) {
        return [{
            xtype: 'xcheckbox'
            ,boxLabel: _('resource_folder')
            ,description: '<b>[[*isfolder]]</b><br />'+_('resource_folder_help')
            ,hideLabel: true
            ,name: 'isfolder'
            ,id: 'modx-resource-isfolder'
            ,inputValue: 1
            ,checked: parseInt(config.record.isfolder) || 0

        },{
            xtype: 'xcheckbox'
            ,boxLabel: _('resource_searchable')
            ,description: '<b>[[*searchable]]</b><br />'+_('resource_searchable_help')
            ,hideLabel: true
            ,name: 'searchable'
            ,id: 'modx-resource-searchable'
            ,inputValue: 1
            ,checked: parseInt(config.record.searchable)
        },{
            xtype: 'xcheckbox'
            ,boxLabel: _('resource_richtext')
            ,description: '<b>[[*richtext]]</b><br />'+_('resource_richtext_help')
            ,hideLabel: true
            ,name: 'richtext'
            ,id: 'modx-resource-richtext'
            ,inputValue: 1
            ,checked: parseInt(config.record.richtext)
        }];
    }

    ,getSettingRightFieldsetRight: function(config) {
        return [{
            xtype: 'xcheckbox'
            ,boxLabel: _('resource_cacheable')
            ,description: '<b>[[*cacheable]]</b><br />'+_('resource_cacheable_help')
            ,hideLabel: true
            ,name: 'cacheable'
            ,id: 'modx-resource-cacheable'
            ,inputValue: 1
            ,checked: parseInt(config.record.cacheable)

        },{
            xtype: 'xcheckbox'
            ,boxLabel: _('resource_syncsite')
            ,description: _('resource_syncsite_help')
            ,hideLabel: true
            ,name: 'syncsite'
            ,id: 'modx-resource-syncsite'
            ,inputValue: 1
            ,checked: config.record.syncsite !== undefined && config.record.syncsite !== null ? parseInt(config.record.syncsite) : true

        },{
            xtype: 'xcheckbox'
            ,boxLabel: _('deleted')
            ,description: '<b>[[*deleted]]</b>'
            ,hideLabel: true
            ,name: 'deleted'
            ,id: 'modx-resource-deleted'
            ,inputValue: 1
            ,checked: parseInt(config.record.deleted) || false
        }];
    }

    ,getContentField: function(config) {
        return [{
            id: 'modx-content-above'
            ,border: false
        },{
            xtype: 'textarea'
            ,name: 'ta'
            ,id: 'ta'
            ,hideLabel: true
            ,anchor: '100%'
            ,height: 400
            ,grow: false
            ,value: (config.record.content || config.record.ta) || ''
        },{
            id: 'modx-content-below'
            ,border: false
        }];
    }

    ,getAccessPermissionsTab: function(config) {
        return {
            id: 'modx-resource-access-permissions'
            ,autoHeight: true
            ,title: _('resource_groups')
            ,layout: 'form'
            ,anchor: '100%'
            ,items: [{
                html: '<p>'+_('resource_access_message')+'</p>'
                ,bodyCssClass: 'panel-desc'
                ,border: false
            },{
                xtype: 'modx-grid-resource-security'
                ,cls: 'main-wrapper'
                ,preventRender: true
                ,resource: config.resource
                ,mode: config.mode || 'update'
                ,"parent": config.record["parent"] || 0
                ,"token": config.record.create_resource_token
                ,reloaded: !Ext.isEmpty(MODx.request.reload)
                ,listeners: {
                    'afteredit': {fn:this.fieldChangeEvent,scope:this}
                }
            }]
        };
    }
});
Ext.reg('modx-panel-resource',MODx.panel.Resource);

var triggerDirtyField = function(fld) {
    Ext.getCmp('modx-panel-resource').fieldChangeEvent(fld);
};
MODx.triggerRTEOnChange = function() {
    triggerDirtyField(Ext.getCmp('ta'));
};
MODx.fireResourceFormChange = function(f,nv,ov) {
    Ext.getCmp('modx-panel-resource').fireEvent('fieldChange');
};
