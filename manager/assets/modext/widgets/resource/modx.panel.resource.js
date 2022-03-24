MODx.panel.Resource = function(config) {
    config = config || {record:{}};
    config.record = config.record || {};
    config.default_title = config.default_title || _('document_new');
    Ext.applyIf(config,{
        url: MODx.config.connector_url
        ,baseParams: {}
        ,id: 'modx-panel-resource'
        ,class_key: 'MODX\\Revolution\\modDocument'
        ,resource: ''
        ,bodyStyle: ''
        ,cls: 'container form-with-labels'
        ,defaults: {
            collapsible: false,
            autoHeight: true
        }
        ,forceLayout: true
        ,items: this.getFields(config)
        ,fileUpload: true
        ,useLoadingMask: true
        ,listeners: {
            setup: {fn:this.setup,scope:this}
            ,success: {fn:this.success,scope:this}
            ,failure: {fn:this.failure,scope:this}
            ,beforeSubmit: {fn:this.beforeSubmit,scope:this}
            ,fieldChange: {fn:this.onFieldChange,scope:this}
            ,failureSubmit: {
                fn: function () {
                    this.showErroredTab(this.errorHandlingTabs, 'modx-resource-tabs')
                },
                scope: this
        }
        }
    });
    MODx.panel.Resource.superclass.constructor.call(this,config);
    var ta = Ext.get(this.contentField);
    if (ta) { ta.on('keydown',this.fieldChangeEvent,this); }
    this.on('ready',this.onReady,this);
    var urio = Ext.getCmp('modx-resource-uri-override');
    if (urio) { urio.on('check',this.freezeUri); }
    this.addEvents('tv-reset');
};
Ext.extend(MODx.panel.Resource,MODx.FormPanel,{

    initialized: false
    ,defaultClassKey: 'MODX\\Revolution\\modDocument'
    ,classLexiconKey: 'document'
    ,rteElements: 'ta'
    ,rteLoaded: false
    ,contentField: 'ta'
    ,warnUnsavedChanges: false

    ,setup: function() {

        if (!this.initialized) {
            /*
                The itemId (not id) of each form tab to be included/excluded; these correspond to the
                keys in each tab component's items property
            */
            this.errorHandlingTabs = ['modx-resource-settings','modx-page-settings','modx-panel-resource-tv'];
            this.errorHandlingIgnoreTabs = ['modx-resource-access-permissions'];

            this.getForm().setValues(this.config.record);

            var tpl = this.getForm().findField('modx-resource-template');
            if (tpl) {
                tpl.originalValue = this.config.record.template;
            }
            var pcmb = this.getForm().findField('parent-cmb');
            if (pcmb && Ext.isEmpty(this.config.record.parent_pagetitle)) {
                pcmb.setValue('');
            } else if (pcmb) {
                pcmb.setValue(this.config.record.parent_pagetitle+' ('+this.config.record.parent+')');
            }

            this.formatMainPanelTitle('resource', this.config.record);

            // initial check to enable realtime alias
            this.config.aliaswasempty = Ext.isEmpty(this.config.record.alias);
            // the initial value for the realtime-alias throttling
            this.config.translitloading = false;

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
    ,handlePreview: function(deleted) {
        var previewBtn = Ext.getCmp('modx-abtn-preview');
        if (previewBtn) {
            if (deleted) {
                previewBtn.hide();
            } else {
                previewBtn.show();
            }
        }
    }

    ,handleDeleted: function(deleted) {
        if (this.config.canDelete == 1 && !this.config.locked) {
            var deleteBtn = Ext.getCmp('modx-abtn-delete');
            var unDeleteBtn = Ext.getCmp('modx-abtn-undelete');
            var deleteChk = Ext.getCmp('modx-resource-deleted');
            if (deleteBtn && unDeleteBtn) {
                if (deleted) {
                    deleteBtn.hide();
                    unDeleteBtn.show();
                } else {
                    unDeleteBtn.hide();
                    deleteBtn.show();
                }
            }
            if (deleteChk) {
                deleteChk.setValue(deleted);
            }
        }
    }

    ,updateTree: function() {
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
    }

    ,beforeDestroy: function(){
        if (this.rteLoaded && MODx.unloadRTE){
            MODx.unloadRTE(this.rteElements);
            this.rteLoaded = false;
        }
        MODx.panel.Resource.superclass.beforeDestroy.call(this);
    }

    ,beforeSubmit: function(o) {
        var ta = Ext.get(this.contentField);
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
        if(this.getForm().baseParams.action == 'Resource/Create') {
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

        var object = o.result.object;
        // object.parent is undefined on template changing.
        if (this.config.resource && object.parent !== undefined && (object.class_key != this.defaultClassKey || object.parent != this.defaultValues.parent)) {
            location.reload();
        } else {
            if (object.deleted !== this.record.deleted) {
                if (object.deleted) {
                    var action = 'hide';
                } else {
                    action = 'show';
                }
                this.handlePreview(object.deleted);
                this.handleDeleted(object.deleted);
            }

            this.updateTree();

            this.record = object;
            this.getForm().setValues(object);
            Ext.getCmp('modx-page-update-resource').config.preview_url = object.preview_url;
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
                    action: 'Resource/Translit'
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

    ,generateAliasRealTime: function(title) {
        // check some system settings before doing real time alias transliteration
        if (parseInt(MODx.config.friendly_alias_realtime) && parseInt(MODx.config.automatic_alias)) {
            // handles the realtime-alias transliteration
            if (this.config.aliaswasempty && title !== '') {
                this.translitAlias(title);
            }
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
                    f.config.action = 'Resource/Reload';
                    this.warnUnsavedChanges = false;
                    MODx.activePage.submitForm({
                        success: {fn:function(r) {
                            MODx.loadPage(r.result.object.action, 'id='+(r.result.object.id || 0)+'&reload='+r.result.object.reload + '&class_key='+ r.result.object.class_key + '&context_key='+ r.result.object.context_key);
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
            ,labelAlign: 'top'
            ,bodyCssClass: 'tab-panel-wrapper main-wrapper'
            ,autoHeight: true
            ,items: this.getMainFields(config)
        });
        if (config.show_tvs && MODx.config.tvs_below_content != 1) {
            it.push(this.getTemplateVariablesPanel(config));
        }
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
                border: false,
                msgTarget: 'under'
            }
            ,items: this.getSettingFields(config)
        });
        if (MODx.perm.resourcegroup_resource_list) {
            it.push(this.getAccessPermissionsTab(config));
        }
        var its = [];
        its.push(this.getPageHeader(config),{
            id:'modx-resource-tabs'
            ,xtype: 'modx-tabs'
            ,forceLayout: true
            ,deferredRender: false
            ,collapsible: false
            ,animCollapse: false
            ,itemId: 'tabs'
            ,items: it
        });
        if (MODx.config.tvs_below_content == 1) {
            var tvs = this.getTemplateVariablesPanel(config);
            its.push(tvs);
        }
        return its;
    }

    ,getPageHeader: function(config) {
        config = config || {record:{}};
        var header = {
            html: config.record && config.record.pagetitle || config.default_title
            ,id: 'modx-resource-header'
            ,xtype: 'modx-header'
        };

        // Add breadcrumbs with parents
        if (config.record['parents'] && config.record['parents'].length) {
            var parents = config.record['parents'];
            var trail = [];

            for (var i = 0; i < parents.length; i++) {
                if (parents[i].id) {
                    if (parents[i].parent && i == 1) {
                        trail.push({
                            text: parents[i].parent && i == 1 ? '...' : parents[i].pagetitle
                            ,href: false
                        });
                    }
                    trail.push({
                        text: parents[i].pagetitle
                        ,href: MODx.config.manager_url + '?a=resource/update&id=' + parents[i].id
                        ,cls: function(data) {
                            var cls = [];
                            if (!data.published) {
                                cls.push('not_published');
                            }
                            if (data.hidemenu) {
                                cls.push('menu_hidden');
                            }
                            return cls.join(' ');
                        }(parents[i])
                    });
                } else {
                    trail.push({
                        text: parents[i].name || parents[i].key
                        ,href: false
                    });
                }
            }

            return MODx.util.getHeaderBreadCrumbs(header, trail);
        }

        return header;
    }

    ,getTemplateVariablesPanel: function(config) {
        return {
            xtype: 'modx-panel-resource-tv'
            ,collapsed: false
            ,resource: config.resource
            ,class_key: config.record.class_key || 'MODX\\Revolution\\modDocument'
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
            ,id: 'modx-resource-main-columns'
            ,defaults: {
                layout: 'form'
            }
            ,items:[{
                columnWidth: .75
                ,id: 'modx-resource-main-left'
                ,cls: 'modx-resource-panel'
                ,defaults: {
                    layout: 'form',
                    anchor: '100%',
                    validationEvent: 'change',
                    labelSeparator: '',
                    msgTarget: 'under',
                }
                ,collapsible: true
                ,stateful: true
                ,stateEvents: ['collapse', 'expand']
                ,getState: function() {
                    return { collapsed: this.collapsed };
                }
                ,title: _('resource')
                ,items: this.getMainLeftFields(config)
            },{
                columnWidth: .25
                ,id: 'modx-resource-main-right'
                ,style: 'margin-right: 0'
                ,defaults: {
                    layout: 'form',
                    anchor: '100%',
                    validationEvent: 'change',
                    labelSeparator: '',
                    msgTarget: 'under',
                    defaults: {
                        anchor: '100%',
                        validationEvent: 'change',
                        msgTarget: 'under'
                    }
                }
                ,items: this.getMainRightFields(config)
            }]
        },{
            html: MODx.onDocFormRender, border: false
        },{
            xtype: 'hidden'
            ,name: 'id'
            ,id: 'modx-resource-id'
            ,value: config.resource || config.record.id
        },{
            xtype: 'hidden'
            ,name: 'type'
            ,value: 'document'
        },{
            xtype: 'hidden'
            ,name: 'context_key'
            ,id: 'modx-resource-context-key'
            ,value: config.record.context_key || MODx.config.default_context
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
        const aliasLength = ~~MODx.config['friendly_alias_max_length'] || 0;
        return [{
            layout: 'column'
            ,defaults: {
                layout: 'form',
                labelSeparator: '',
                defaults: {
                    layout: 'form',
                    anchor: '100%',
                    validationEvent: 'change',
                    msgTarget: 'under'
            }
            }
            ,items: [{
                columnWidth: .7
                ,items: [{
                    xtype: 'textfield'
                    ,fieldLabel: _('resource_pagetitle')
                    ,required: true
                    ,description: '<b>[[*pagetitle]]</b><br>'+_('resource_pagetitle_help')
                    ,name: 'pagetitle'
                    ,id: 'modx-resource-pagetitle'
                    ,maxLength: 191
                    ,allowBlank: false
                    ,enableKeyEvents: true
                    ,listeners: {
                        keyup: {
                            fn: function(cmp) {
                                const title = this.formatMainPanelTitle('resource', this.config.record, cmp.getValue(), true);
                                this.generateAliasRealTime(title);

                                // check some system settings before doing real time alias transliteration
                                if (parseInt(MODx.config.friendly_alias_realtime, 10) && parseInt(MODx.config.automatic_alias, 10)) {
                                    // handles the realtime-alias transliteration
                                    if (this.config.aliaswasempty && title !== '') {
                                        this.translitAlias(title);
                                    }
                                }
                            },
                            scope: this
                        }
                        // also do realtime transliteration of alias on blur of pagetitle field
                        // as sometimes (when typing very fast) the last letter(s) are not caught
                        ,blur: {
                            fn: function(cmp, e) {
                                const title = Ext.util.Format.stripTags(cmp.getValue());
                                this.generateAliasRealTime(title);
                            },
                            scope: this
                        }
                    }
                }]
            },{
                columnWidth: .3
                ,items: [{
                    xtype: 'textfield'
                    ,fieldLabel: _('resource_alias')
                    ,description: '<b>[[*alias]]</b><br>'+_('resource_alias_help')
                    ,name: 'alias'
                    ,id: 'modx-resource-alias'
                    ,maxLength: (aliasLength > 191 || aliasLength === 0) ? 191 : aliasLength
                    ,value: config.record.alias || ''
                    ,listeners: {
                        change: {fn: function(f,e) {
                                // when the alias is manually cleared, enable real time alias
                                if (Ext.isEmpty(f.getValue())) {
                                    this.config.aliaswasempty = true;
                                }
                            }, scope: this}
                    }
                }]
            }]

        },{
            xtype: 'textfield'
            ,fieldLabel: _('resource_longtitle')
            ,description: '<b>[[*longtitle]]</b><br>'+_('resource_longtitle_help')
            ,name: 'longtitle'
            ,id: 'modx-resource-longtitle'
            ,maxLength: 191
            ,value: config.record.longtitle || ''
        },{
            layout: 'column'
            ,defaults: {
                labelSeparator: '',
                layout: 'form',
                defaults: {
                    anchor: '100%',
                    grow: true,
                    validationEvent: 'change',
                    msgTarget: 'under'
            }
            }
            ,items: [{
                columnWidth: .5
                ,items: [{
                    xtype: 'textarea'
                    ,fieldLabel: _('resource_description')
                    ,description: '<b>[[*description]]</b><br>'+_('resource_description_help')
                    ,name: 'description'
                    ,id: 'modx-resource-description'
                    ,value: config.record.description || ''

                }]
            },{
                columnWidth: .5
                ,items: [{
                    xtype: 'textarea'
                    ,fieldLabel: _('resource_summary')
                    ,description: '<b>[[*introtext]]</b><br>'+_('resource_summary_help')
                    ,name: 'introtext'
                    ,id: 'modx-resource-introtext'
                    ,value: config.record.introtext || ''
                }]
            }]

        }, this.getContentField(config)];
    }

    ,getMainRightFields: function(config) {
        config = config || {};
        return [{
            defaults: {
                layout: 'form',
                anchor: '100%',
                labelSeparator: '',
                validationEvent: 'change',
                msgTarget: 'under',
                defaults: {
                    layout: 'form',
                    anchor: '100%',
                    validationEvent: 'change',
                    msgTarget: 'under'
                }
            }
            ,id: 'modx-resource-main-right-top'
            ,cls: 'modx-resource-panel'
            ,title: _('resource_right_top_title')
            ,collapsible: true
            ,stateful: true
            ,stateEvents: ['collapse', 'expand']
            ,getState: function() {
                return { collapsed: this.collapsed };
            }
            ,items: [{
                items: [{
                    xtype: 'xcheckbox'
                    ,ctCls: 'display-switch'
                    ,boxLabel: _('resource_published')
                    ,hideLabel: true
                    ,description: '<b>[[*published]]</b><br>'+_('resource_published_help')
                    ,name: 'published'
                    ,id: 'modx-resource-published'
                    ,inputValue: 1
                    ,checked: parseInt(config.record.published)
                },{
                    xtype: 'xcheckbox'
                    ,ctCls: 'display-switch'
                    ,boxLabel: _('deleted')
                    ,description: '<b>[[*deleted]]</b><br>'+_('resource_delete')
                    ,hideLabel: true
                    ,cls: 'danger'
                    ,name: 'deleted'
                    ,id: 'modx-resource-deleted'
                    ,inputValue: 1
                    ,checked: parseInt(config.record.deleted) || false
                }]
            },{
                xtype: 'xdatetime'
                ,fieldLabel: _('resource_publishedon')
                ,description: '<b>[[*publishedon]]</b><br>'+_('resource_publishedon_help')
                ,name: 'publishedon'
                ,id: 'modx-resource-publishedon'
                ,allowBlank: true
                ,dateFormat: MODx.config.manager_date_format
                ,timeFormat: MODx.config.manager_time_format
                ,startDay: parseInt(MODx.config.manager_week_start)
                ,dateWidth: '100%'
                ,timeWidth: '100%'
                ,offset_time: MODx.config.server_offset_time
                ,value: config.record.publishedon
            },{
                xtype: MODx.config.publish_document ? 'xdatetime' : 'hidden'
                ,fieldLabel: _('resource_publishdate')
                ,description: '<b>[[*pub_date]]</b><br>'+_('resource_publishdate_help')
                ,name: 'pub_date'
                ,id: 'modx-resource-pub-date'
                ,allowBlank: true
                ,dateFormat: MODx.config.manager_date_format
                ,timeFormat: MODx.config.manager_time_format
                ,startDay: parseInt(MODx.config.manager_week_start)
                ,dateWidth: '100%'
                ,timeWidth: '100%'
                ,offset_time: MODx.config.server_offset_time
                ,value: config.record.pub_date
            },{
                xtype: MODx.config.publish_document ? 'xdatetime' : 'hidden'
                ,fieldLabel: _('resource_unpublishdate')
                ,description: '<b>[[*unpub_date]]</b><br>'+_('resource_unpublishdate_help')
                ,name: 'unpub_date'
                ,id: 'modx-resource-unpub-date'
                ,allowBlank: true
                ,dateFormat: MODx.config.manager_date_format
                ,timeFormat: MODx.config.manager_time_format
                ,startDay: parseInt(MODx.config.manager_week_start)
                ,dateWidth: '100%'
                ,timeWidth: '100%'
                ,offset_time: MODx.config.server_offset_time
                ,value: config.record.unpub_date
            }]
        },{
            id: 'modx-resource-main-right-middle'
            ,cls: 'modx-resource-panel'
            ,title: _('resource_right_middle_title')
            ,collapsible: true
            ,stateful: true
            ,stateEvents: ['collapse', 'expand']
            ,getState: function() {
                return { collapsed: this.collapsed };
            }
            ,items: [{
                xtype: 'modx-combo-template'
                ,fieldLabel: _('resource_template')
                ,description: '<b>[[*template]]</b><br>'+_('resource_template_help')
                ,name: 'template'
                ,id: 'modx-resource-template'
                ,listeners: {
                    'select': {fn: this.templateWarning,scope: this}
                }}]
        },{
            id: 'modx-resource-main-right-bottom'
            ,cls: 'modx-resource-panel'
            ,title: _('resource_right_bottom_title')
            ,collapsible: true
            ,stateful: true
            ,stateEvents: ['collapse', 'expand']
            ,getState: function() {
                return { collapsed: this.collapsed };
            }
            ,items: [{
                xtype: 'xcheckbox'
                ,ctCls: 'display-switch'
                ,boxLabel: _('resource_hide_from_menus')
                ,hideLabel: true
                ,cls: 'warning'
                ,description: '<b>[[*hidemenu]]</b><br>'+_('resource_hide_from_menus_help')
                ,name: 'hidemenu'
                ,id: 'modx-resource-hidemenu'
                ,inputValue: 1
                ,checked: parseInt(config.record.hidemenu) || false
            },{
                xtype: 'textfield'
                ,fieldLabel: _('resource_menutitle')
                ,description: '<b>[[*menutitle]]</b><br>'+_('resource_menutitle_help')
                ,name: 'menutitle'
                ,id: 'modx-resource-menutitle'
                ,maxLength: 255
                ,value: config.record.menutitle || ''
            },{
                xtype: 'textfield'
                ,fieldLabel: _('resource_link_attributes')
                ,description: '<b>[[*link_attributes]]</b><br>'+_('resource_link_attributes_help')
                ,name: 'link_attributes'
                ,id: 'modx-resource-link-attributes'
                ,maxLength: 255
                ,value: config.record.link_attributes || ''
            },{
                xtype: 'numberfield'
                ,fieldLabel: _('resource_menuindex')
                ,description: '<b>[[*menuindex]]</b><br>'+_('resource_menuindex_help')
                ,name: 'menuindex'
                ,id: 'modx-resource-menuindex'
                ,allowNegative: false
                ,allowDecimals: false
                ,enableKeyEvents: true
                ,value: parseInt(config.record.menuindex) || 0
                ,listeners: {
                    afterrender: function(field) {
                        field.el.set({autocomplete: 'off'});
                    },
                    specialkey: function(field, e) {
                        const currentVal = parseInt(field.value);
                        if (e.getKey() == e.UP) {
                            field.setValue(currentVal + 1);
                        } else if (currentVal >= 1 && e.getKey() == e.DOWN) {
                            field.setValue(currentVal - 1);
                        }
                    }
                }
            }]
        }]
    }

    ,getSettingFields: function(config) {
        config = config || {record:{}};
        return [{
            layout:'column'
            ,defaults: {
                defaults: {
                    layout: 'form',
                    labelAlign: 'top',
                    labelSeparator: '',
                    defaults: {
                        validationEvent: 'change',
                        anchor: '100%',
                        msgTarget: 'under'
            }
                }
            }
            ,items:[{
                columnWidth: .5
                ,items: [{
                    id: 'modx-page-settings-left'
                    ,items: this.getSettingLeftFields(config)
                },{
                    id: 'modx-page-settings-box-left'
                    ,items: this.getSettingRightFieldsetLeft(config)
                }]
            },{
                columnWidth: .5
                ,items: [{
                    id: 'modx-page-settings-right'
                    ,items: this.getSettingRightFields(config)
                },{
                    id: 'modx-page-settings-box-right'
                    ,items: this.getSettingRightFieldsetRight(config)
                }]

            }]
        }];
    }

    ,getSettingLeftFields: function(config) {
        return [{
            xtype: 'modx-combo-class-derivatives'
            ,fieldLabel: _('resource_type')
            ,description: '<b>[[*class_key]]</b><br>'
            ,name: 'class_key'
            ,hiddenName: 'class_key'
            ,id: 'modx-resource-class-key'
            ,allowBlank: false
            ,value: config.record.class_key || 'MODX\\Revolution\\modDocument'
        },{
            xtype: 'modx-combo-content-type'
            ,fieldLabel: _('resource_content_type')
            ,description: '<b>[[*content_type]]</b><br>'+_('resource_content_type_help')
            ,name: 'content_type'
            ,hiddenName: 'content_type'
            ,id: 'modx-resource-content-type'
            ,allowBlank: false
            ,value: config.record.content_type || (MODx.config.default_content_type || 1)
        }];
    }

    ,getSettingRightFields: function(config) {
        return [{
            xtype: 'modx-field-parent-change'
            ,fieldLabel: _('resource_parent')
            ,description: '<b>[[*parent]]</b><br>'+_('resource_parent_help')
            ,name: 'parent-cmb'
            ,id: 'modx-resource-parent'
            ,value: config.record.parent || 0
        },{
            xtype: 'modx-combo-content-disposition'
            ,fieldLabel: _('resource_contentdispo')
            ,description: '<b>[[*content_dispo]]</b><br>'+_('resource_contentdispo_help')
            ,name: 'content_dispo'
            ,hiddenName: 'content_dispo'
            ,id: 'modx-resource-content-dispo'
            ,value: config.record.content_dispo || 0

        }];
    }

    ,getSettingRightFieldsetLeft: function(config) {
        return [{
            xtype: 'xcheckbox'
            ,ctCls: 'display-switch'
            ,boxLabel: _('resource_folder')
            ,description: '<b>[[*isfolder]]</b><br>'+_('resource_folder_help')
            ,hideLabel: false
            ,name: 'isfolder'
            ,id: 'modx-resource-isfolder'
            ,inputValue: 1
            ,checked: parseInt(config.record.isfolder) || 0
        },{
            xtype: 'xcheckbox'
            ,ctCls: 'display-switch'
            ,boxLabel: _('resource_show_in_tree')
            ,description: '<b>[[*show_in_tree]]</b><br>'+_('resource_show_in_tree_help')
            ,hideLabel: true
            ,name: 'show_in_tree'
            ,id: 'modx-resource-show-in-tree'
            ,inputValue: 1
            ,checked: parseInt(config.record.show_in_tree)

        },{
            xtype: 'xcheckbox'
            ,ctCls: 'display-switch'
            ,boxLabel: _('resource_hide_children_in_tree')
            ,description: '<b>[[*hide_children_in_tree]]</b><br>'+_('resource_hide_children_in_tree_help')
            ,hideLabel: true
            ,name: 'hide_children_in_tree'
            ,id: 'modx-resource-hide-children-in-tree'
            ,cls: 'warning'
            ,inputValue: 1
            ,checked: parseInt(config.record.hide_children_in_tree)
        },{
            xtype: 'xcheckbox'
            ,ctCls: 'display-switch'
            ,boxLabel: _('resource_alias_visible')
            ,description: '<b>[[*alias_visible]]</b><br>'+_('resource_alias_visible_help')
            ,hideLabel: true
            ,name: 'alias_visible'
            ,id: 'modx-resource-alias-visible'
            ,inputValue: 1
            ,checked: parseInt(config.record.alias_visible) || 1
        },{
            xtype: 'xcheckbox'
            ,ctCls: 'display-switch'
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
            ,description: '<b>[[*uri]]</b><br>'+_('resource_uri_help')
            ,name: 'uri'
            ,id: 'modx-resource-uri'
            ,maxLength: 255
            ,value: config.record.uri || ''
            ,hidden: !config.record.uri_override
        }];
    }

    ,getSettingRightFieldsetRight: function(config) {
        return [{
            xtype: 'xcheckbox'
            ,ctCls: 'display-switch'
            ,boxLabel: _('resource_richtext')
            ,description: '<b>[[*richtext]]</b><br>'+_('resource_richtext_help')
            ,hideLabel: false
            ,name: 'richtext'
            ,id: 'modx-resource-richtext'
            ,inputValue: 1
            ,checked: parseInt(config.record.richtext)
        },{
            xtype: 'xcheckbox'
            ,ctCls: 'display-switch'
            ,boxLabel: _('resource_searchable')
            ,description: '<b>[[*searchable]]</b><br>'+_('resource_searchable_help')
            ,hideLabel: true
            ,name: 'searchable'
            ,id: 'modx-resource-searchable'
            ,inputValue: 1
            ,checked: parseInt(config.record.searchable)
        },{
            xtype: 'xcheckbox'
            ,ctCls: 'display-switch'
            ,boxLabel: _('resource_cacheable')
            ,description: '<b>[[*cacheable]]</b><br>'+_('resource_cacheable_help')
            ,hideLabel: true
            ,name: 'cacheable'
            ,id: 'modx-resource-cacheable'
            ,inputValue: 1
            ,checked: parseInt(config.record.cacheable)

        },{
            xtype: 'xcheckbox'
            ,ctCls: 'display-switch'
            ,boxLabel: _('resource_syncsite')
            ,description: _('resource_syncsite_help')
            ,hideLabel: true
            ,name: 'syncsite'
            ,id: 'modx-resource-syncsite'
            ,inputValue: 1
            ,checked: config.record.syncsite !== undefined && config.record.syncsite !== null ? parseInt(config.record.syncsite) : true
        }];
    }

    ,getContentField: function(config) {
        return {
            id: 'modx-resource-content'
            ,layout: 'form'
            ,autoHeight: true
            ,hideMode: 'offsets'
            ,items: [{
                id: 'modx-content-above'
                ,border: false
            },{
                xtype: 'textarea'
                ,name: 'ta'
                ,id: 'ta'
                ,fieldLabel: _('resource_content')
                ,anchor: '100%'
                ,height: 488
                ,grow: false
                ,value: (config.record.content || config.record.ta) || ''
            },{
                id: 'modx-content-below'
                ,border: false
            }]
        };
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
                ,xtype: 'modx-description'
            },{
                xtype: 'modx-grid-resource-security'
                ,cls: 'main-wrapper'
                ,preventRender: true
                ,resource: config.resource
                ,mode: config.mode || 'update'
                ,parent: config.record['parent'] || 0
                ,token: config.record.create_resource_token
                ,reloaded: !Ext.isEmpty(MODx.request.reload)
                ,listeners: {
                    afteredit: {fn:this.fieldChangeEvent,scope:this}
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
