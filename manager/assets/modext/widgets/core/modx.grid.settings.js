/**
 * Loads a grid for managing Settings.
 *
 * @class MODx.grid.SettingsGrid
 * @extends MODx.grid.Grid
 * @param {Object} config An object of configuration properties
 * @xtype modx-grid-settings
 */
MODx.grid.SettingsGrid = function(config = {}) {
    const
        settingsType = this.settingsType || 'system',
        queryValue = this.querySpec ? this.applyRequestFilter(...this.querySpec) : MODx.util.url.getParamValue('query', true)
    ;
    this.exp = new Ext.grid.RowExpander({
        tpl: new Ext.XTemplate(
            '<p class="desc">{[MODx.util.safeHtml(values.description_trans)]}</p>'
        )
    });
    this.areaFilterValue = MODx.util.url.getParamValue('area');
    // Settings grid in User Group ACLs view needs special handling when applying filter via request param
    this.namespaceFilterValue = settingsType === 'usergroup'
        ? this.applyRequestFilter(3, 'ns', 'tab')
        : MODx.util.url.getParamValue('ns')
    ;
    if (!config.tbar) {
        config.tbar = [{
            text: _('create')
            ,scope: this
            ,cls: 'primary-button'
            ,handler: {
                xtype: 'modx-window-setting-create'
                ,url: config.url || MODx.config.connector_url
                ,blankValues: true
                ,listeners: {
                    success: {
                        fn: function(response) {
                            this.refresh();
                            this.fireEvent('createSetting', response);
                        },
                        scope: this
                    }
                }
            }
        }];
    }
    config.tbar.push(
        '->',
        {
            /**
             * @deprecated use of id config property deprecated in 3.0, to be removed in 3.1
             *
             * To access this combo in the future, get a reference to the topToolbar and use
             * the getComponent method ( e.g., [gridObj].getTopToolbar().getComponent([itemId]) )
             *
             * Also, itemId to be renamed 'filter-namespace' in 3.1
             */
            xtype: 'modx-combo-namespace'
            ,id: 'modx-filter-namespace'
            ,itemId: 'filter-ns'
            ,emptyText: _('namespace_filter')
            ,typeAhead: true
            ,minChars: 2
            ,forceSelection: true
            ,width: 200
            ,value: this.namespaceFilterValue
            ,baseParams: {
                action: 'Workspace/PackageNamespace/GetList',
                area: this.areaFilterValue,
                isGridFilter: true,
                targetGrid: 'MODx.grid.SettingsGrid',
                targetSettingsType: this.getSettingsType(),
                foreignKey: config.fk || false
            }
            ,listeners: {
                select: {
                    fn: function(cmp, record, selectedIndex) {
                        this.updateDependentFilter('filter-area', 'namespace', record.data.name);
                        this.applyGridFilter(cmp, 'ns');
                    },
                    scope: this
                },
                change: {
                // Support typed-in value (where the select event is not triggered)
                    fn: function(cmp, newValue, oldValue) {
                        /*
                            Note that clicking the combo trigger when there is no value chosen initially,
                            (the combo's value is null) but not choosing an item fires change event and
                            changes the combo's value to an empty string; this can cause issues in some
                            instances, so explicity reset to null
                        */
                        if (newValue === '') {
                            cmp.setValue(null);
                        }
                        this.updateDependentFilter('filter-area', 'namespace', newValue);
                        this.applyGridFilter(cmp, 'ns');
                    },
                    scope: this
                }
            }
        },
        {
            /**
             * @deprecated use of id config property deprecated in 3.0, to be removed in 3.1
             *
             * To access this combo in the future, get a reference to the topToolbar and use
             * the getComponent method ( e.g., [gridObj].getTopToolbar().getComponent([itemId]) )
             */
            xtype: 'modx-combo-area'
            ,id: 'modx-filter-area'
            ,itemId: 'filter-area'
            ,emptyText: _('area_filter')
            ,value: this.areaFilterValue
            ,baseParams: {
                action: 'System/Settings/GetAreas',
                namespace: MODx.request.ns || null,
                isGridFilter: true,
                targetGrid: 'MODx.grid.SettingsGrid',
                targetSettingsType: this.getSettingsType(),
                foreignKey: config.fk || false
            }
            ,width: 250
            ,allowBlank: true
            ,editable: true
            ,typeAhead: true
            ,minChars: 2
            ,forceSelection: true
            ,listeners: {
                select: {
                    fn: function(cmp, record, selectedIndex) {
                        this.updateDependentFilter('filter-ns', 'area', record.data.v);
                        this.applyGridFilter(cmp, 'area');
                    },
                    scope: this
                },
                change: {
                // Support typed-in value (where the select event is not triggered)
                    fn: function(cmp, newValue, oldValue) {
                        // See note in namespace combo above re setting to null
                        if (newValue === '') {
                            cmp.setValue(null);
                        }
                        this.updateDependentFilter('filter-ns', 'area', newValue);
                        this.applyGridFilter(cmp, 'area');
                    },
                    scope: this
                }
            }
        },
        this.getQueryFilterField(`filter-query:${queryValue}`),
        this.getClearFiltersButton(
            'filter-ns:, filter-area:, filter-query',
            'filter-area:namespace, filter-ns:area'
        )
    );

    this.cm = new Ext.grid.ColumnModel({
        columns: [this.exp,{
            header: _('name')
            ,dataIndex: 'name_trans'
            ,sortable: true
            ,editable: false
            ,width: 175
        },{
            header: _('key')
            ,dataIndex: 'key'
            ,sortable: true
            ,editable: false
            ,width: 150
        },{
            header: _('value')
            ,dataIndex: 'value'
            ,sortable: true
            ,editable: true
            ,renderer: this.renderDynField.createDelegate(this,[this],true)
            ,width: 260
        },{
            header: _('last_modified')
            ,dataIndex: 'editedon'
            ,sortable: true
            ,editable: false
            ,renderer: this.renderLastModDate.createDelegate(this,[this],true)
            ,width: 100
        },{
            header: _('area')
            ,dataIndex: 'area_text'
            ,sortable: true
            ,hidden: true
            ,editable: false
        }],
        isCellEditable: function(col, row) {
            var record = config.store.getAt(row);
            if (record.get('xtype') === 'modx-grid-json' || record.get('xtype') === 'grid-json') {
                Ext.MessageBox.show({
                    title: _('info')
                    ,msg:  _('setting_err_not_editable')
                    ,buttons: Ext.MessageBox.OK
                    ,icon: Ext.MessageBox.INFO
                    ,modal: true
                });
                return false;
            }
            return Ext.grid.ColumnModel.prototype.isCellEditable.call(this, col, row);
        }
        /* Editors are pushed here. I think that they should be in general grid
         * definitions (modx.grid.js) and activated via a config property (loadEditor: true) */
        ,getCellEditor: function(colIndex, rowIndex) {
            var field = this.getDataIndex(colIndex);
            if (field == 'value') {
                var rec = config.store.getAt(rowIndex);
                var xt = {xtype: 'textfield'};
                if (rec) {
                    xt.xtype = rec.get('xtype');
                    if (xt == 'text-password') {
                        xt.xtype = 'textfield';
                        xt.inputType = 'password';
                    }
                }
                var o = MODx.load(xt);
                return new Ext.grid.GridEditor(o);
            }
            return Ext.grid.ColumnModel.prototype.getCellEditor.call(this, colIndex, rowIndex);
        }
    });

    Ext.applyIf(config, {
        cm: this.cm
        ,fields: [
            'key',
            'name',
            'value',
            'description',
            'xtype',
            'namespace',
            'area',
            'area_text',
            'editedon',
            'oldkey',
            'menu',
            'name_trans',
            'description_trans'
        ]
        ,url: MODx.config.connector_url
        ,baseParams: {
            action: 'System/Settings/GetList',
            namespace: this.namespaceFilterValue,
            area: this.areaFilterValue
        }
        ,clicksToEdit: 2
        ,grouping: true
        ,groupBy: 'area_text'
        ,singleText: _('setting')
        ,pluralText: _('settings')
        ,sortBy: 'key'
        ,plugins: this.exp
        ,primaryKey: 'key'
        ,autosave: true
        ,save_action: 'System/Settings/UpdateFromGrid'
        ,pageSize: parseInt(MODx.config.default_per_page) || 20
        ,paging: true
        ,collapseFirst: false
        ,tools: [{
            id: 'plus'
            ,qtip: _('expand_all')
            ,handler: this.expandAll
            ,scope: this
        },{
            id: 'minus'
            ,hidden: true
            ,qtip: _('collapse_all')
            ,handler: this.collapseAll
            ,scope: this
        }]
    });

    this.view = new Ext.grid.GroupingView({
        emptyText: config.emptyText || _('ext_emptymsg')
        ,forceFit: true
        ,autoFill: true
        ,showPreview: true
        ,enableRowBody: true
        ,scrollOffset: 0
    });
    MODx.grid.SettingsGrid.superclass.constructor.call(this,config);
    this.addEvents('createSetting', 'updateSetting', 'updateSettingFromGrid', 'removeSettingFromGrid');
    console.log(`Default Src: ${MODx.config.default_media_source}`);

    const gridFilterData = [
        { filterId: 'filter-ns', dependentParams: ['area'] },
        { filterId: 'filter-area', dependentParams: ['namespace'] }
    ];

    this.on({
        createSetting: function(...args) {
            // console.log('createSetting args', ...args);
            if (args[0].a.response.status === 200) {
                this.refreshFilterOptions(gridFilterData);
                if (args[0].a.result.object.key.indexOf('mask_') === 0) {
                    MODx.maskConfig.fireEvent('syncSettingFromGrid', {
                        action: 'create',
                        data: args[0].a.result.object,
                        gridType: this.settingsType
                    });
                    // MODx.maskConfig.fireEvent('createSettingFromGrid', args[0].a.result.object);
                }
            }
        },
        updateSetting: function(...args) {
            // console.log('updateSetting args', ...args);
            if (args[0].a.response.status === 200) {
                this.refreshFilterOptions(gridFilterData);
                if (args[0].a.result.object.key.indexOf('mask_') === 0) {
                    MODx.maskConfig.fireEvent('syncSettingFromGrid', {
                        action: 'update',
                        data: args[0].a.result.object,
                        gridType: this.settingsType
                    });
                    // MODx.maskConfig.fireEvent('updateSettingFromGrid', args[0].a.result.object);
                }
            }
        },
        afterAutoSave: function(response) {
            if (response.success && response.object.key.indexOf('mask_') === 0) {
                MODx.maskConfig.fireEvent('syncSettingFromGrid', {
                    action: 'update',
                    data: response.object,
                    gridType: this.settingsType
                });
                // MODx.maskConfig.fireEvent('updateSettingFromGrid', response.object);
            }
        },
        afterRemoveRow: function(record) {
            this.refreshFilterOptions(gridFilterData);
            if (record && record.key.indexOf('mask_') === 0) {
                // const target = this.settingsType || this.getSettingsType();
                MODx.maskConfig.fireEvent('syncSettingFromGrid', {
                    action: 'remove',
                    data: record,
                    gridType: this.settingsType
                });
                // MODx.maskConfig.fireEvent('removeSettingFromGrid', {
                //     record,
                //     target
                // });
            }
        }
    });

    // prevents navigation to next cell editor field when pressing the ENTER key
    this.selModel.onEditorKey = this.selModel.onEditorKey.createInterceptor(function(field, e) {
        if (e.getKey() == Ext.EventObject.ENTER && !e.ctrlKey) {
            e.stopEvent();
            return false;
        }
    });
};
Ext.extend(MODx.grid.SettingsGrid,MODx.grid.Grid,{
    _addEnterKeyHandler: function() {
        this.getEl().addKeyListener(Ext.EventObject.ENTER,function() {
            this.fireEvent('change');
        },this);
    }

    ,_showMenu: function(g,ri,e) {
        e.stopEvent();
        e.preventDefault();
        this.menu.record = this.getStore().getAt(ri).data;
        if (!this.getSelectionModel().isSelected(ri)) {
            this.getSelectionModel().selectRow(ri);
        }
        this.menu.removeAll();

        var m = [];
        if (this.menu.record.menu) {
            m = this.menu.record.menu;
        } else {
            m.push({
                text: _('edit')
                ,handler: this.updateSetting
            },'-',{
                text: _('delete')
                ,handler: this.removeSetting
            });
        }
        if (m.length > 0) {
            this.addContextMenuItem(m);
            this.menu.showAt(e.xy);
        }
    }

    ,removeSetting: function() {
        return this.remove('setting_remove_confirm', 'System/Settings/Remove');
    }
    /*
        TBD: Remove child settings grids' update methods and adjust this base one to accommodate those grids; only difference is the child window configs include and action property. Maybe use createDelegate to pass additional var.
     */
    ,updateSetting: function(btn, e) {
        const { record } = this.menu;
        record.fk = this.config?.fk || 0;
        this.windows.updateSetting = MODx.load({
            xtype: 'modx-window-setting-update',
            record: record,
            grid: this,
            listeners: {
                success: {
                    fn: function(response) {
                        this.refresh();
                        this.fireEvent('updateSetting', response);
                    },
                    scope: this
                }
            }
        });
        this.windows.updateSetting.setValues(record);
        this.windows.updateSetting.show(e.target);
    }

    ,renderDynField: function(v,md,rec,ri,ci,s,g) {
        var r = s.getAt(ri).data;
        v = Ext.util.Format.htmlEncode(v);
        var f;
        if (r.xtype === 'grid-json' || r.xtype === 'modx-grid-json') {
            return v;
        } else if (r.xtype === 'combo-boolean' || r.xtype === 'modx-combo-boolean') {
            f = MODx.grid.Grid.prototype.rendYesNo;
            return this.renderEditableColumn(f)(v,md,rec,ri,ci,s,g);
        } else if (r.xtype === 'datefield') {
            f = Ext.util.Format.dateRenderer(MODx.config.manager_date_format);
            return this.renderEditableColumn(f)(v,md,rec,ri,ci,s,g);
        } else if (r.xtype === 'text-password' || r.xtype == 'modx-text-password') {
            f = MODx.grid.Grid.prototype.rendPassword;
            return this.renderEditableColumn(f)(v,md,rec,ri,ci,s,g);
        } else if (r.xtype.substr(0,5) == 'combo' || r.xtype.substr(0,10) == 'modx-combo') {
            var cm = g.getColumnModel();
            var ed = cm.getCellEditor(ci,ri);
            if (!ed) {
                var o = Ext.ComponentMgr.create({xtype: r.xtype || 'textfield'});
                ed = new Ext.grid.GridEditor(o);
                cm.setEditor(ci,ed);
            }
            if (ed.store && !ed.store.isLoaded && ed.config.mode != 'local') {
                ed.store.load();
                ed.store.isLoaded = true;
            }
            f = Ext.util.Format.comboRenderer(ed.field,v);
            return this.renderEditableColumn(f)(v,md,rec,ri,ci,s,g);
        }
        return this.renderEditableColumn()(v,md,rec,ri,ci,s,g);
    }

    /**
     * Prevent display updated date for unmodified records
     *
     * @param {String} value
     *
     * @returns {String}
     */
    ,renderLastModDate: function(value) {
        if (Ext.isEmpty(value)) {
            return '—';
        }

        // Return formatted date (server side)
        return value;
    }

    /**
     * Gets an identifier for the type of setting being worked with;
     * expected format: 'modx-grid-[type]-setting'
     *
     * @return {String}
     */
    ,getSettingsType: function() {
        const type = Object.getPrototypeOf(this).constructor.xtype;
        return type.split('-')[2];
    }
});
Ext.reg('modx-grid-settings',MODx.grid.SettingsGrid);

MODx.combo.Area = function(config = {}) {
    Ext.applyIf(config,{
        name: 'area'
        ,hiddenName: 'area'
        ,displayField: 'd'
        ,valueField: 'v'
        ,fields: ['d','v']
        ,url: MODx.config.connector_url
        ,baseParams: {
            action: 'System/Settings/GetAreas'
        }
    });
    MODx.combo.Area.superclass.constructor.call(this,config);
};
Ext.extend(MODx.combo.Area,MODx.combo.ComboBox);
Ext.reg('modx-combo-area',MODx.combo.Area);

MODx.window.CreateSetting = function(config = {}) {
    config.keyField = config.keyField || {};
    Ext.applyIf(config,{
        title: _('create')
        ,width: 600
        ,url: config.url
        ,action: 'System/Settings/Create'
        ,autoHeight: true
        ,fields: [{
            layout: 'column'
            ,border: false
            ,defaults: {
                layout: 'form'
                ,labelAlign: 'top'
                ,anchor: '100%'
                ,border: false
            }
            ,autoHeight: true
            ,items: [{
                columnWidth: .5
                ,items: [{
                    xtype: 'hidden'
                    ,name: 'fk'
                    ,id: 'modx-cs-fk'
                    ,value: config.fk || 0
                },Ext.applyIf(config.keyField, {
                    xtype: 'textfield'
                    ,fieldLabel: _('key')
                    ,name: 'key'
                    ,id: 'modx-cs-key'
                    ,maxLength: 100
                    ,anchor: '100%'
                }),{
                    xtype: 'label'
                    ,forId: 'modx-cs-key'
                    ,html: _('key_desc')
                    ,cls: 'desc-under'
                },{
                    xtype: 'textfield'
                    ,fieldLabel: _('name')
                    ,name: 'name'
                    ,id: 'modx-cs-name'
                    ,anchor: '100%'
                },{
                    xtype: 'label'
                    ,forId: 'modx-cs-name'
                    ,html: _('name_desc')
                    ,cls: 'desc-under'
                },{
                    xtype: 'textarea'
                    ,fieldLabel: _('description')
                    ,name: 'description'
                    ,id: 'modx-cs-description'
                    ,allowBlank: true
                    ,anchor: '100%'
                },{
                    xtype: 'label'
                    ,forId: 'modx-cs-description'
                    ,html: _('description_desc')
                    ,cls: 'desc-under'
                }]
            },{
                columnWidth: .5
                ,items: [{
                    xtype: 'modx-combo-xtype-spec'
                    ,fieldLabel: _('xtype')
                    ,description: MODx.expandHelp ? '' : _('xtype_desc')
                    ,id: 'modx-cs-xtype'
                    ,anchor: '100%'
                },{
                    xtype: 'label'
                    ,forId: 'modx-cs-xtype'
                    ,html: _('xtype_desc')
                    ,cls: 'desc-under'
                },{
                    xtype: 'modx-combo-namespace'
                    ,fieldLabel: _('namespace')
                    ,name: 'namespace'
                    ,id: 'modx-cs-namespace'
                    ,value: config.grid.getTopToolbar().getComponent('filter-ns').getValue()
                    ,anchor: '100%'
                },{
                    xtype: 'label'
                    ,forId: 'modx-cs-namespace'
                    ,html: _('namespace_desc')
                    ,cls: 'desc-under'
                },{
                    xtype: 'textfield'
                    ,fieldLabel: _('area_lexicon_string')
                    ,description: _('area_lexicon_string_msg')
                    ,name: 'area'
                    ,id: 'modx-cs-area'
                    ,anchor: '100%'
                    ,value: config.grid.getTopToolbar().getComponent('filter-area').getValue()
                },{
                    xtype: 'label'
                    ,forId: 'modx-cs-area'
                    ,html: _('area_lexicon_string_msg')
                    ,cls: 'desc-under'
                }]
            }]
        },{
            xtype: 'textarea'
            ,fieldLabel: _('value')
            ,name: 'value'
            ,id: 'modx-cs-value'
            ,autoHeight: true
            ,anchor: '100%'
        }]
        ,keys: []
    });
    MODx.window.CreateSetting.superclass.constructor.call(this,config);
    this.on('show',function() {
        this.reset();
        this.setValues({
            namespace: config.grid.getTopToolbar().getComponent('filter-ns').value
            ,area: config.grid.getTopToolbar().getComponent('filter-area').value
        });
    },this);
};
Ext.extend(MODx.window.CreateSetting,MODx.Window);
Ext.reg('modx-window-setting-create',MODx.window.CreateSetting);

MODx.combo.xType = function(config = {}) {
    Ext.applyIf(config,{
        store: new Ext.data.SimpleStore({
            fields: ['d','v']
            ,data: [[_('textfield'),'textfield']
                ,[_('textarea'),'textarea']
                ,[_('numberfield'),'numberfield']
                ,[_('yesno'),'combo-boolean']
                ,[_('password'),'text-password']
                ,[_('category'),'modx-combo-category']
                ,[_('charset'),'modx-combo-charset']
                ,[_('country'),'modx-combo-country']
                ,[_('context'),'modx-combo-context']
                ,[_('namespace'),'modx-combo-namespace']
                ,[_('template'),'modx-combo-template']
                ,[_('user'),'modx-combo-user']
                ,[_('usergroup'),'modx-combo-usergroup']
                ,[_('language'),'modx-combo-language']
                ,[_('source'),'modx-combo-source']
                ,[_('source_type'),'modx-combo-source-type']
                ,[_('setting_manager_theme'),'modx-combo-manager-theme']
                ,[_('json_grid'),'modx-grid-json']
            ]
        })
        ,displayField: 'd'
        ,valueField: 'v'
        ,mode: 'local'
        ,name: 'xtype'
        ,hiddenName: 'xtype'
        ,triggerAction: 'all'
        ,editable: false
        ,selectOnFocus: false
        ,value: 'textfield'
    });
    MODx.combo.xType.superclass.constructor.call(this,config);
};
Ext.extend(MODx.combo.xType,Ext.form.ComboBox);
Ext.reg('modx-combo-xtype-spec',MODx.combo.xType);

MODx.window.UpdateSetting = function(config = {}) {
    this.ident = config.ident || 'modx-uss-'+Ext.id();
    Ext.applyIf(config,{
        title: _('edit')
        ,width: 600
        ,url: config.grid.config.url
        ,action: 'System/Settings/Update'
        ,autoHeight: true
        ,fields: [{
            layout: 'column'
            ,border: false
            ,defaults: {
                layout: 'form'
                ,labelAlign: 'top'
                ,anchor: '100%'
                ,border: false
            }
            ,autoHeight: true
            ,items: [{
                columnWidth: .5
                ,items: [{
                    xtype: 'hidden'
                    ,name: 'fk'
                    ,id: 'modx-'+this.ident+'-fk'
                    ,value: config.fk || 0
                },{
                    xtype: 'statictextfield'
                    ,fieldLabel: _('key')
                    ,description: MODx.expandHelp ? '' : _('key_desc')
                    ,name: 'key'
                    ,id: 'modx-'+this.ident+'-key'
                    ,maxLength: 100
                    ,submitValue: true
                    ,anchor: '100%'
                },{
                    xtype: MODx.expandHelp ? 'label' : 'hidden'
                    ,forId: 'modx-'+this.ident+'-key'
                    ,html: _('key_desc')
                    ,cls: 'desc-under'
                },{
                    xtype: 'textfield'
                    ,fieldLabel: _('name')
                    ,description: MODx.expandHelp ? '' : _('name_desc')
                    ,name: 'name'
                    ,id: 'modx-'+this.ident+'-name'
                    ,anchor: '100%'
                },{
                    xtype: MODx.expandHelp ? 'label' : 'hidden'
                    ,forId: 'modx-'+this.ident+'-name'
                    ,html: _('name_desc')
                    ,cls: 'desc-under'
                },{
                    xtype: 'textarea'
                    ,fieldLabel: _('description')
                    ,description: MODx.expandHelp ? '' : _('description_desc')
                    ,name: 'description'
                    ,id: 'modx-'+this.ident+'-description'
                    ,allowBlank: true
                    ,anchor: '100%'
                },{
                    xtype: MODx.expandHelp ? 'label' : 'hidden'
                    ,forId: 'modx-'+this.ident+'-description'
                    ,html: _('description_desc')
                    ,cls: 'desc-under'
                }]
            },{
                columnWidth: .5
                ,items: [{
                    xtype: 'modx-combo-xtype-spec'
                    ,fieldLabel: _('xtype')
                    ,description: MODx.expandHelp ? '' : _('xtype_desc')
                    ,id: 'modx-'+this.ident+'-xtype'
                    ,anchor: '100%'
                },{
                    xtype: MODx.expandHelp ? 'label' : 'hidden'
                    ,forId: 'modx-'+this.ident+'-xtype'
                    ,html: _('xtype_desc')
                    ,cls: 'desc-under'
                },{
                    xtype: 'modx-combo-namespace'
                    ,fieldLabel: _('namespace')
                    ,description: MODx.expandHelp ? '' : _('namespace_desc')
                    ,name: 'namespace'
                    ,id: 'modx-'+this.ident+'-namespace'
                    ,value: 'core'
                    ,anchor: '100%'
                },{
                    xtype: MODx.expandHelp ? 'label' : 'hidden'
                    ,forId: 'modx-'+this.ident+'-namespace'
                    ,html: _('namespace_desc')
                    ,cls: 'desc-under'
                },{
                    xtype: 'textfield'
                    ,fieldLabel: _('area_lexicon_string')
                    ,description: MODx.expandHelp ? '' : _('area_lexicon_string_msg')
                    ,name: 'area'
                    ,id: 'modx-'+this.ident+'-area'
                    ,anchor: '100%'
                },{
                    xtype: MODx.expandHelp ? 'label' : 'hidden'
                    ,forId: 'modx-'+this.ident+'-area'
                    ,html: _('area_lexicon_string_msg')
                    ,cls: 'desc-under'
                }]
            }]
        },{
            xtype: config.record ? config.record.xtype : 'textarea'
            ,fieldLabel: _('value')
            ,name: 'value'
            ,hiddenName: 'value'
            ,id: 'modx-'+this.ident+'-value'
            ,autoHeight: true
            ,anchor: '100%'
        }]
        ,keys: []
    });
    MODx.window.UpdateSetting.superclass.constructor.call(this,config);
};
Ext.extend(MODx.window.UpdateSetting,MODx.Window);
Ext.reg('modx-window-setting-update',MODx.window.UpdateSetting);
