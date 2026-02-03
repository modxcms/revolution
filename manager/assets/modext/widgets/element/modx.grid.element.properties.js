MODx.panel.ElementProperties = function(config = {}) {
    Ext.applyIf(config, {
        id: 'modx-panel-element-properties',
        title: _('properties'),
        header: false,
        defaults: {
            collapsible: false,
            autoHeight: true,
            border: false
        },
        layout: 'form',
        items: [{
            html: `<p>${_('element_properties_desc')}</p>`,
            itemId: 'desc-properties',
            xtype: 'modx-description'
        }, {
            xtype: 'modx-grid-element-properties',
            cls: 'main-wrapper',
            id: 'modx-grid-element-properties',
            itemId: 'grid-properties',
            autoHeight: true,
            border: true,
            panel: config.elementPanel,
            elementId: config.elementId,
            elementType: config.elementType
        }, {
            layout: 'form',
            labelAlign: 'top',
            border: false,
            cls: 'main-wrapper',
            items: [{
                xtype: 'xcheckbox',
                boxLabel: _('property_preprocess'),
                description: MODx.expandHelp ? '' : _('property_preprocess_msg'),
                name: 'property_preprocess',
                inputValue: true,
                hideLabel: true,
                checked: config.record.property_preprocess || 0,
                listeners: {
                    check: {
                        fn: function() {
                            Ext.getCmp(this.config.elementPanel).markDirty();
                        },
                        scope: this
                    }
                }
            }, {
                xtype: 'box',
                hidden: !MODx.expandHelp,
                html: _('property_preprocess_msg'),
                cls: 'desc-under'
            }]
        }]
    });
    MODx.panel.ElementProperties.superclass.constructor.call(this, config);
};
Ext.extend(MODx.panel.ElementProperties, MODx.Panel);
Ext.reg('modx-panel-element-properties', MODx.panel.ElementProperties);

MODx.grid.ElementProperties = function(config = {}) {
    this.exp = new Ext.grid.RowExpander({
        tpl: new Ext.Template(
            '<p class="modx-property-description"><i>{desc_trans}</i></p>'
        )
    });
    Ext.applyIf(config, {
        title: _('properties'),
        id: 'modx-grid-element-properties',
        maxHeight: 300,
        fields: [
            'name',
            'desc',
            'xtype',
            'options',
            'value',
            'lexicon',
            'overridden',
            'desc_trans',
            'area',
            'area_trans',
            'permissions'
        ],
        autoExpandColumn: 'value',
        sortBy: 'name',
        anchor: '100%',
        sm: new Ext.grid.RowSelectionModel({
            singleSelect: false
        }),
        loadMask: true,
        lockProperties: true,
        plugins: [this.exp],
        grouping: true,
        groupBy: 'area_trans',
        singleText: _('property'),
        pluralText: _('properties'),
        columns: [this.exp, {
            header: _('name'),
            dataIndex: 'name',
            width: 200,
            sortable: true,
            renderer: this._renderName
        }, {
            header: _('type'),
            dataIndex: 'xtype',
            width: 100,
            renderer: this._renderType,
            sortable: true
        }, {
            header: _('value'),
            dataIndex: 'value',
            id: 'value',
            width: 250,
            renderer: this.renderDynField.createDelegate(this, [this], true),
            sortable: true
        }, {
            header: _('area'),
            dataIndex: 'area_trans',
            id: 'area',
            width: 150,
            sortable: true,
            hidden: true
        }],
        tbar: [{
            text: _('property_create'),
            id: 'modx-btn-property-create',
            handler: this.create,
            scope: this,
            disabled: true
        }, {
            text: _('properties_default_locked'),
            id: 'modx-btn-propset-lock',
            handler: this.togglePropertiesLock,
            enableToggle: true,
            pressed: true,
            disabled: !MODx.perm.unlock_element_properties,
            scope: this
        }, '->', {
            xtype: 'modx-combo-property-set',
            id: 'modx-combo-property-set',
            baseParams: {
                action: 'Element/PropertySet/GetList',
                showAssociated: true,
                elementId: config.elementId,
                elementType: config.elementType,
                combo: true
            },
            value: 0,
            listeners: {
                select: {
                    fn: this.changePropertySet,
                    scope: this
                }
            }
        }, {
            text: _('propertyset_add'),
            id: 'modx-btn-property-set-add',
            handler: this.addPropertySet,
            scope: this
        }, {
            text: _('propertyset_save'),
            id: 'modx-btn-property-set-save',
            cls: 'primary-button',
            handler: this.save,
            scope: this,
            hidden: !MODx.request.id
        }],
        bbar: [{
            text: _('property_revert_all'),
            id: 'modx-btn-property-revert-all',
            handler: this.revertAll,
            scope: this,
            disabled: true
        }, {
            text: _('import'),
            id: 'modx-btn-property-import',
            handler: this.importProperties,
            scope: this
        }, {
            text: _('export'),
            handler: this.exportProperties,
            scope: this
        }],
        collapseFirst: false,
        tools: [{
            id: 'plus',
            qtip: _('expand_all'),
            handler: this.expandAll,
            scope: this
        }, {
            id: 'minus',
            hidden: true,
            qtip: _('collapse_all'),
            handler: this.collapseAll,
            scope: this
        }]
    });
    MODx.grid.ElementProperties.superclass.constructor.call(this, config);

    // Omitting 'revert' action, as it is effectively the same as 'edit'
    this.gridMenuActions = ['edit', 'delete'];

    // Note there are currently no action-specific permissions for Dashboards
    this.setUserCanEdit(['edit_propertyset', 'save_propertyset']);
    this.setUserCanCreate(['new_propertyset', 'save_propertyset']);
    this.setUserCanDelete(['delete_propertyset']);
    this.setShowActionsMenu();

    this.on({
        render: grid => {
            const buttonsToHide = [];
            this.mask = new Ext.LoadMask(this.getEl());
            if (this.config.lockProperties) {
                this.lockMask = MODx.load({
                    xtype: 'modx-lockmask',
                    el: this.getGridEl(),
                    msg: _('properties_default_locked')
                });
                this.lockMask.toggle();
            }
            if (!this.userCanCreate) {
                buttonsToHide.push('modx-btn-property-set-add', 'modx-btn-property-import');
            }
            if (!this.userCanEdit) {
                buttonsToHide.push('modx-btn-property-create', 'modx-btn-property-revert-all');
                if (!this.userCanCreate) {
                    buttonsToHide.push('modx-btn-property-set-save');
                }
            }
            if (
                !MODx.perm.unlock_element_properties
                && !this.id === 'modx-grid-element-properties'
            ) {
                buttonsToHide.push('modx-btn-propset-lock');
            }
            if (buttonsToHide.length > 0) {
                buttonsToHide.forEach(btnId => Ext.getCmp(btnId)?.hide());
            }
        },
        beforeedit: e => {
            if (e.record[this.permissionsProviderProp].isProtected || !this.userCanEditRecord(e.record)) {
                return false;
            }
        },
        afteredit: e => {
            this.propertyChanged();
        },
        afterRemoveRow: record => {
            this.propertyChanged();
        }
    });
};
Ext.extend(MODx.grid.ElementProperties, MODx.grid.LocalProperty, {
    defaultProperties: [],

    onDirty: function() {
        if (this.config.panel) {
            Ext.getCmp(this.config.panel).fireEvent('fieldChange');
        }
    },

    _renderType: function(value, metaData, record, rowIndex) {
        switch (value) {
            case 'combo-boolean': return _('yesno');
            case 'datefield': return _('date');
            case 'numberfield': return _('integer');
            case 'file': return _('file');
            case 'color': return _('color');
            // no default
        }
        return _(value);
    },

    _renderName: function(value, metaData, record, rowIndex) {
        switch (record.data.overridden) {
            case 1:
                return `<span style="color: green;">${value}</span>`;
            case 2:
                return `<span style="color: purple;">${value}</span>`;
            default:
                return `<span>${value}</span>`;
        }
    },

    save: function() {
        const
            data = this.encode(),
            propSetCombo = Ext.getCmp('modx-combo-property-set')
        ;
        if (!propSetCombo) {
            this.getStore().commitChanges();
            this.onDirty();
            return true;
        }
        const params = {
            action: 'Element/PropertySet/UpdateFromElement',
            id: propSetCombo.getValue(),
            data: data
        };
        if (this.config.elementId) {
            Ext.apply(params, {
                elementId: this.config.elementId,
                elementType: this.config.elementType
            });
        }
        try {
            if (!this.mask) {
                this.mask = new Ext.LoadMask(this.getEl());
            }
            if (this.mask) { this.mask.show(); }
        // eslint-disable-next-line no-empty
        } catch (e) {}

        MODx.Ajax.request({
            url: MODx.config.connector_url,
            params: params,
            listeners: {
                success: {
                    fn: function(response) {
                        this.getStore().commitChanges();
                        this.changePropertySet(propSetCombo);
                        this.onDirty();
                        if (this.mask) { this.mask.hide(); }
                        MODx.msg.status({
                            title: _('success'),
                            message: _('save_successful'),
                            dontHide: !Ext.isEmpty(response.message)
                        });
                    },
                    scope: this
                }
            }
        });
    },

    addPropertySet: function(btn, e) {
        this.loadWindow(btn, e, {
            xtype: 'modx-window-element-property-set-add',
            record: {
                elementId: this.config.elementId !== 0 ? this.config.elementId : '',
                elementType: this.config.elementType
            },
            listeners: {
                success: {
                    fn: function(response) {
                        const propSetCombo = Ext.getCmp('modx-combo-property-set');
                        propSetCombo.store.reload({
                            callback: function() {
                                propSetCombo.setValue(response.a.result.object.id);
                                this.changePropertySet(propSetCombo);
                            },
                            scope: this
                        });
                        this.onDirty();
                    },
                    scope: this
                }
            }
        });
    },

    togglePropertiesLock: function() {
        const propSetId = Ext.getCmp('modx-combo-property-set').getValue();
        if (propSetId === 0 || propSetId === _('default')) {
            Ext.getCmp('modx-btn-propset-lock').setText(this.lockMask.locked
                ? _('properties_default_unlocked')
                : _('properties_default_locked'))
            ;
            this.lockMask.toggle();
            this.toggleButtons(this.lockMask.locked);
        }
    },

    toggleButtons: function(value) {
        const btn = Ext.getCmp('modx-btn-property-create');
        if (btn) {
            Ext.getCmp('modx-btn-property-create').setDisabled(value);
            Ext.getCmp('modx-btn-property-revert-all').setDisabled(value);
        }
    },

    changePropertySet: function(propSetCombo) {
        const
            propSetId = propSetCombo.getValue(),
            lockbtn = Ext.getCmp('modx-btn-propset-lock')
        ;
        if (propSetId === 0 || propSetId === _('default')) {
            if (MODx.perm.unlock_element_properties) {
                if (lockbtn) {
                    lockbtn.setDisabled(false);
                }
            }
            if (this.lockMask && this.lockMask.locked) {
                this.lockMask.show();
                this.toggleButtons(true);
            }
        } else {
            if (lockbtn) {
                lockbtn.setDisabled(true);
            }
            if (this.lockMask) {
                this.lockMask.hide();
            }
            this.toggleButtons(false);
        }

        MODx.Ajax.request({
            url: MODx.config.connector_url,
            params: {
                action: 'Element/PropertySet/Get',
                id: propSetId,
                elementId: this.config.elementId,
                elementType: this.config.elementType
            },
            listeners: {
                success: {
                    fn: function(response) {
                        const
                            store = this.getStore(),
                            data = Ext.decode(response.object.data)
                        ;
                        store.removeAll();
                        store.loadData(data);
                    },
                    scope: this
                }
            }
        });
    },

    create: function(btn, e) {
        this.loadWindow(btn, e, {
            xtype: 'modx-window-element-property-create',
            blankValues: true,
            listeners: {
                success: {
                    fn: function(response) {
                        const record = new this.propRecord({
                            name: response.name,
                            desc: response.desc,
                            desc_trans: response.desc,
                            xtype: response.xtype,
                            options: response.options,
                            value: response.value,
                            lexicon: response.lexicon,
                            overridden: this.isDefaultPropSet() ? 0 : 2,
                            area: response.area,
                            area_trans: response.area
                        });
                        this.getStore().add(record);
                        this.propertyChanged();
                        this.onDirty();
                    },
                    scope: this
                }
            }
        });
    },

    update: function(btn, e) {
        this.loadWindow(btn, e, {
            xtype: 'modx-window-element-property-update',
            record: this.menu.record,
            listeners: {
                success: {
                    fn: function(response) {
                        const
                            isDefaultSet = this.isDefaultPropSet(),
                            store = this.getStore(),
                            record = store.getAt(this.menu.recordIndex)
                        ;
                        record.set('name', response.name);
                        record.set('desc', response.desc);
                        record.set('desc_trans', response.desc);
                        record.set('xtype', response.xtype);
                        record.set('options', response.options);
                        record.set('value', response.value);
                        record.set('lexicon', response.lexicon);
                        // eslint-disable-next-line no-nested-ternary
                        record.set('overridden', response.overridden === 2 ? 2 : (!isDefaultSet ? 1 : 0));
                        record.set('area', response.area);
                        record.set('area_trans', response.area);
                        this.getView().refresh();
                        this.onDirty();
                    },
                    scope: this
                }
            }
        });
    },

    revert: function(btn, e) {
        Ext.Msg.confirm(_('warning'), _('property_revert_confirm'), function(e) {
            if (e === 'yes') {
                const
                    { recordIndex } = this.menu,
                    propData = this.defaultProperties[recordIndex]
                ;
                if (propData) {
                    const record = this.getStore().getAt(recordIndex);
                    record.set('name', propData[0]);
                    record.set('desc', propData[1]);
                    record.set('desc_trans', propData[1]);
                    record.set('xtype', propData[2]);
                    record.set('options', propData[3]);
                    record.set('value', propData[4]);
                    record.set('overridden', 0);
                    record.set('area', propData[5]);
                    record.set('area_trans', propData[5]);
                    record.commit();
                }
            }
        }, this);
    },

    revertAll: function(btn, e) {
        Ext.Msg.confirm(_('warning'), _('property_revert_all_confirm'), function(e) {
            if (e === 'yes') {
                this.getStore().loadData(this.defaultProperties);
            }
        }, this);
    },

    removeMultiple: function(btn, e) {
        const
            rows = this.getSelectionModel().getSelections(),
            rids = []
        ;
        for (let i = 0; i < rows.length; i++) {
            rids.push(rows[i].data.id);
        }
        Ext.Msg.confirm(_('warning'), _('properties_remove_confirm'), function(e) {
            if (e === 'yes') {
                for (let i = 0; i < rows.length; i++) {
                    this.store.remove(rows[i]);
                }
            }
        }, this);
    },

    exportProperties: function(btn, e) {
        const
            propSetId = Ext.getCmp('modx-combo-property-set').getValue(),
            data = this.encode()
        ;
        window.location.href = `${MODx.config.connector_url}?action=Element/ExportProperties&download=1&id=${propSetId}&data=${data}&HTTP_MODAUTH=${MODx.siteId}`;
    },

    importProperties: function(btn, e) {
        this.loadWindow(btn, e, {
            xtype: 'modx-window-properties-import',
            record: this.menu.record,
            listeners: {
                success: {
                    fn: function(response) {
                        const
                            store = this.getStore(),
                            data = response.a.result.object
                        ;
                        data.forEach((record, i) => {
                            [4, 5, 1].forEach(index => {
                                if (record[index]) {
                                    record[index] = record[index].replace(/&gt;/g, '>').replace(/&lt;/g, '<');
                                }
                            });
                        });
                        store.loadData(data);
                        const newRecords = store.getRange(0, store.getTotalCount());
                        newRecords.forEach(record => record.markDirty());
                        this.getView().refresh();
                    },
                    scope: this
                }
            }
        });
    },

    isDefaultPropSet: function() {
        const propSetId = Ext.getCmp('modx-combo-property-set').getValue();
        return (propSetId === 0 || propSetId === _('default'));
    },

    getMenu: function() {
        const
            isDefaultSet = this.isDefaultPropSet(),
            model = this.getSelectionModel(),
            record = model.getSelected(),
            propIsCustom = record.data.overridden === 2,
            propIsOverriden = record.data.overridden === 1,
            propUnchanged = [0, false].includes(record.data.overridden),
            menu = []
        ;
        if (model.getCount() > 1 && this.userCanDelete) {
            menu.push({
                text: _('properties_remove'),
                handler: this.removeMultiple,
                scope: this
            });
        } else {
            if (this.userCanEdit) {
                menu.push({
                    text: _('property_update'),
                    scope: this,
                    handler: this.update
                });
                if (propIsOverriden) {
                    menu.push({
                        text: _('property_revert'),
                        scope: this,
                        handler: this.revert
                    });
                }
            }
            if (
                this.userCanDelete
                && ((!isDefaultSet && (propUnchanged || propIsCustom))
                || (isDefaultSet && !propIsOverriden))
            ) {
                if (menu.length > 0) {
                    menu.push('-');
                }
                menu.push({
                    text: _('property_remove'),
                    scope: this,
                    handler: this.remove.createDelegate(this, [{
                        title: _('warning'),
                        text: _('property_remove_confirm')
                    }])
                });
            }
        }
        return menu;
    },

    /**
     * Updates hidden field with the current set of serialized properties to
     * be persisted to the database. Only applies to an Element's editing panel
     * (in its Properties tab), not to the standalone Property Sets editor.
     */
    propertyChanged: function() {
        const elementPanel = Ext.getCmp(this.config.panel);
        if (!elementPanel) {
            return false;
        }
        const propsValueField = this.config.hiddenPropField || 'props';
        elementPanel.getForm().findField(propsValueField).setValue('1');
        elementPanel.fireEvent('fieldChange', {
            field: propsValueField,
            form: elementPanel.getForm()
        });
        return true;
    }
});
Ext.reg('modx-grid-element-properties', MODx.grid.ElementProperties);

MODx.grid.ElementPropertyOption = function(config = {}) {
    Ext.applyIf(config, {
        title: _('property_options'),
        id: 'modx-grid-element-property-options',
        autoHeight: true,
        maxHeight: 300,
        width: '100%',
        fields: [
            'text',
            'value',
            'name'
        ],
        data: [],
        columns: [{
            header: _('name'),
            dataIndex: 'text',
            width: 150,
            editor: {
                xtype: 'textfield',
                allowBlank: false
            }
        }, {
            header: _('value'),
            dataIndex: 'value',
            id: 'value',
            width: 250,
            editor: {
                xtype: 'textfield'
            }
        }],
        tbar: [{
            text: _('property_option_create'),
            cls: 'primary-button',
            handler: this.create,
            scope: this
        }]
    });
    MODx.grid.ElementPropertyOption.superclass.constructor.call(this, config);
    this.optRecord = Ext.data.Record.create([
        { name: 'text' },
        { name: 'value' }
    ]);
};
Ext.extend(MODx.grid.ElementPropertyOption, MODx.grid.LocalGrid, {
    create: function(btn, e) {
        this.loadWindow(btn, e, {
            xtype: 'modx-window-element-property-option-create',
            listeners: {
                success: {
                    fn: function(response) {
                        const record = new this.optRecord({
                            text: response.text,
                            value: response.value
                        });
                        this.getStore().add(record);
                    },
                    scope: this
                }
            }
        });
    },

    getMenu: function() {
        return [{
            text: _('property_option_remove'),
            scope: this,
            handler: this.remove.createDelegate(this, [{
                title: _('warning'),
                text: _('property_option_remove_confirm')
            }])
        }];
    }
});
Ext.reg('modx-grid-element-property-options', MODx.grid.ElementPropertyOption);

/**
 * @class MODx.window.CreateElementProperty
 * @extends MODx.Window
 * @param {Object} config An object of configuration properties
 * @xtype modx-window-element-property-create
 */
MODx.window.CreateElementProperty = function(config = {}) {
    const
        id = Ext.id(),
        action = config.isUpdate ? 'update' : 'create'
    ;
    this.id = `modx-window-${action}-property-${id}`;
    Ext.applyIf(config, {
        title: _('property_create'),
        width: 600,
        saveBtnText: _('done'),
        fields: [{
            layout: 'column',
            border: false,
            defaults: {
                layout: 'form',
                labelAlign: 'top',
                anchor: '100%',
                border: false
            },
            items: [{
                columnWidth: 0.6,
                items: [{
                    fieldLabel: _('name'),
                    description: MODx.expandHelp ? '' : _('property_name_desc'),
                    name: 'name',
                    xtype: 'textfield',
                    anchor: '100%',
                    allowBlank: false
                }, {
                    xtype: 'box',
                    hidden: !MODx.expandHelp,
                    html: _('property_name_desc'),
                    cls: 'desc-under'
                }, {
                    fieldLabel: _('description'),
                    description: MODx.expandHelp ? '' : _('property_description_desc'),
                    name: 'desc',
                    xtype: 'textarea',
                    anchor: '100%',
                    height: 120
                }, {
                    xtype: 'box',
                    hidden: !MODx.expandHelp,
                    html: _('property_description_desc'),
                    cls: 'desc-under'
                }]
            }, {
                columnWidth: 0.4,
                items: [{
                    fieldLabel: _('type'),
                    description: MODx.expandHelp ? '' : _('property_xtype_desc'),
                    name: 'xtype',
                    id: `modx-property-xtype--${this.id}`,
                    xtype: 'modx-combo-xtype',
                    anchor: '100%',
                    listeners: {
                        select: {
                            fn: function(combo) {
                                const optsGrid = Ext.getCmp(`modx-grid--property-options--${this.id}`);
                                if (!optsGrid) {
                                    return;
                                }
                                if (['list', 'color'].includes(combo.getValue())) {
                                    optsGrid.show();
                                } else {
                                    optsGrid.hide();
                                }
                                this.syncSize();
                            },
                            scope: this
                        }
                    }
                }, {
                    xtype: 'box',
                    hidden: !MODx.expandHelp,
                    html: _('property_xtype_desc'),
                    cls: 'desc-under'
                }, {
                    xtype: 'textfield',
                    fieldLabel: _('lexicon'),
                    description: MODx.expandHelp ? '' : _('property_lexicon_desc'),
                    name: 'lexicon',
                    anchor: '100%',
                    allowBlank: true
                }, {
                    xtype: 'box',
                    hidden: !MODx.expandHelp,
                    html: _('property_lexicon_desc'),
                    cls: 'desc-under'
                }, {
                    xtype: 'textfield',
                    fieldLabel: _('area'),
                    description: MODx.expandHelp ? '' : _('property_area_desc'),
                    name: 'area',
                    anchor: '100%',
                    allowBlank: true
                }, {
                    xtype: 'box',
                    hidden: !MODx.expandHelp,
                    html: _('property_area_desc'),
                    cls: 'desc-under'
                }]
            }]
        }, {
            xtype: 'modx-element-value-field',
            xtypeField: `modx-property-xtype--${this.id}`,
            anchor: '100%'
        }, {
            xtype: 'modx-grid-element-property-options',
            id: `modx-grid--property-options--${this.id}`,
            anchor: '100%'
        }],
        keys: []
    });
    MODx.window.CreateElementProperty.superclass.constructor.call(this, config);
    this.on('show', this.onShow, this);
};
Ext.extend(MODx.window.CreateElementProperty, MODx.Window, {
    submit: function() {
        const
            values = this.fp.getForm().getValues(),
            optsGrid = Ext.getCmp(`modx-grid--property-options--${this.id}`),
            // eslint-disable-next-line no-eval
            options = eval(optsGrid.encode())
        ;
        Ext.apply(values, {
            options: options
        });
        if (this.fp.getForm().isValid()) {
            if (this.fireEvent('success', values)) {
                this.fp.getForm().reset();
                this.hide();
                return true;
            }
        }
        return false;
    },

    onShow: function() {
        const optsGrid = Ext.getCmp(`modx-grid--property-options--${this.id}`);
        if (!optsGrid) {
            return;
        }
        optsGrid.getStore().removeAll();
        optsGrid.hide();
        if (
            this.config.isUpdate
            && ['list', 'color'].includes(this.fp.getForm().findField('xtype').getValue())
        ) {
            const
                propsGrid = Ext.getCmp('modx-grid-element-properties'),
                selectedRecord = propsGrid.getSelectionModel().getSelected()
            ;
            if (selectedRecord) {
                const
                    { options } = selectedRecord.data,
                    optionsData = []
                ;
                options.forEach(option => optionsData.push([option.text, option.value]));
                optsGrid.getStore().loadData(optionsData);
                optsGrid.show();
            }
        }
        this.syncSize();
        this.center();
    }
});
Ext.reg('modx-window-element-property-create', MODx.window.CreateElementProperty);

/**
 * @class MODx.window.UpdateElementProperty
 * @extends MODx.window.CreateElementProperty
 * @param {Object} config An object of configuration properties
 * @xtype modx-window-element-property-update
 */
MODx.window.UpdateElementProperty = function(config = {}) {
    Ext.applyIf(config, {
        title: _('property_update'),
        isUpdate: true
    });
    MODx.window.UpdateElementProperty.superclass.constructor.call(this, config);
};
Ext.extend(MODx.window.UpdateElementProperty, MODx.window.CreateElementProperty);
Ext.reg('modx-window-element-property-update', MODx.window.UpdateElementProperty);

/**
 * @class MODx.window.CreateElementPropertyOption
 * @extends MODx.Window
 * @param {Object} config An object of configuration properties
 * @xtype modx-window-element-property-option-create
 */
MODx.window.CreateElementPropertyOption = function(config = {}) {
    Ext.applyIf(config, {
        title: _('property_option_create'),
        id: 'modx-window-element-property-option-create',
        saveBtnText: _('done'),
        fields: [{
            fieldLabel: _('name'),
            name: 'text',
            id: 'modx-cepo-text',
            xtype: 'textfield',
            anchor: '100%'
        }, {
            fieldLabel: _('value'),
            name: 'value',
            id: 'modx-cepo-value',
            xtype: 'textfield',
            anchor: '100%'
        }]
    });
    MODx.window.CreateElementPropertyOption.superclass.constructor.call(this, config);
};
Ext.extend(MODx.window.CreateElementPropertyOption, MODx.Window, {
    submit: function() {
        if (this.fp.getForm().isValid()) {
            if (this.fireEvent('success', this.fp.getForm().getValues())) {
                this.fp.getForm().reset();
                this.hide();
                return true;
            }
        }
        return false;
    }
});
Ext.reg('modx-window-element-property-option-create', MODx.window.CreateElementPropertyOption);

/**
 * Displays a xtype combobox
 *
 * @class MODx.combo.xType
 * @extends Ext.form.ComboBox
 * @param {Object} config An object of configuration properties
 * @xtype modx-combo-xtype
 */
MODx.combo.xType = function(config = {}) {
    Ext.applyIf(config, {
        store: new Ext.data.SimpleStore({
            fields: ['d', 'v'],
            data: [
                [_('textfield'), 'textfield'],
                [_('textarea'), 'textarea'],
                [_('yesno'), 'combo-boolean'],
                [_('date'), 'datefield'],
                [_('list'), 'list'],
                [_('integer'), 'numberfield'],
                [_('file'), 'file'],
                [_('color'), 'color']
            ]
        }),
        displayField: 'd',
        valueField: 'v',
        mode: 'local',
        name: 'xtype',
        hiddenName: 'xtype',
        triggerAction: 'all',
        editable: false,
        selectOnFocus: false,
        value: 'textfield'
    });
    MODx.combo.xType.superclass.constructor.call(this, config);
};
Ext.extend(MODx.combo.xType, Ext.form.ComboBox);
Ext.reg('modx-combo-xtype', MODx.combo.xType);

MODx.form.ElementValueField = function(config = {}) {
    Ext.applyIf(config, {
        fieldLabel: _('value'),
        name: 'value',
        xtype: 'textfield'
    });
    MODx.form.ElementValueField.superclass.constructor.call(this, config);
    this.config = config;
    this.on('change', this.checkValue, this);
};
Ext.extend(MODx.form.ElementValueField, Ext.form.TextField, {
    checkValue: function(field, newValue, oldValue) {
        const xtype = Ext.getCmp(this.config.xtypeField).getValue();
        if (xtype === 'combo-boolean') {
            let value = field.getValue();
            value = [1, '1', true, 'true', _('yes'), 'yes'].includes(value) ? 1 : 0;
            field.setValue(value);
        }
    }
});
Ext.reg('modx-element-value-field', MODx.form.ElementValueField);

MODx.combo.PropertySet = function(config = {}) {
    Ext.applyIf(config, {
        name: 'propertyset',
        hiddenName: 'propertyset',
        url: MODx.config.connector_url,
        baseParams: {
            action: 'Element/PropertySet/GetList',
            combo: true
        },
        displayField: 'name',
        valueField: 'id',
        fields: ['id', 'name', 'description', 'properties'],
        editable: false,
        value: 0,
        pageSize: 10
    });
    MODx.combo.PropertySet.superclass.constructor.call(this, config);
};
Ext.extend(MODx.combo.PropertySet, MODx.combo.ComboBox);
Ext.reg('modx-combo-property-set', MODx.combo.PropertySet);

/**
 * @class MODx.window.AddPropertySet
 * @extends MODx.Window
 * @param {Object} config An object of configuration properties
 * @xtype modx-window-element-property-set-add
 */
MODx.window.AddPropertySet = function(config = {}) {
    Ext.applyIf(config, {
        title: _('propertyset_add'),
        id: 'modx-window-element-property-set-add',
        url: MODx.config.connector_url,
        action: 'Element/PropertySet/Associate',
        autoHeight: true, // makes window grow when the fieldset is toggled
        fields: [{
            xtype: 'hidden',
            name: 'elementId'
        }, {
            xtype: 'hidden',
            name: 'elementType'
        }, {
            html: _('propertyset_panel_desc'),
            xtype: 'modx-description'

        }, MODx.PanelSpacer, {
            xtype: 'modx-combo-property-set',
            fieldLabel: _('propertyset'),
            name: 'propertyset',
            anchor: '100%',
            baseParams: {
                action: 'Element/PropertySet/GetList',
                showNotAssociated: true,
                elementId: config.record.elementId,
                elementType: config.record.elementType,
                combo: true
            }
        }, {
            xtype: 'hidden',
            name: 'propertyset_new',
            id: 'modx-aps-propertyset-new',
            value: false
        }, {
            xtype: 'fieldset',
            title: _('propertyset_create_new'),
            autoHeight: true,
            checkboxToggle: true,
            collapsed: true,
            forceLayout: true,
            listeners: {
                expand: {
                    fn: function(p) {
                        Ext.getCmp('modx-aps-propertyset-new').setValue(true);
                        this.center();
                    },
                    scope: this
                },
                collapse: {
                    fn: function(p) {
                        Ext.getCmp('modx-aps-propertyset-new').setValue(false);
                        this.center();
                    },
                    scope: this
                }
            },
            items: [{
                xtype: 'textfield',
                fieldLabel: _('name'),
                name: 'name',
                anchor: '100%'
            }, {
                xtype: 'textarea',
                fieldLabel: _('description'),
                name: 'description',
                anchor: '100%',
                grow: true
            }]
        }]
    });
    MODx.window.AddPropertySet.superclass.constructor.call(this, config);
};
Ext.extend(MODx.window.AddPropertySet, MODx.Window);
Ext.reg('modx-window-element-property-set-add', MODx.window.AddPropertySet);

MODx.window.ImportProperties = function(config = {}) {
    Ext.applyIf(config, {
        title: _('import'),
        id: 'modx-window-properties-import',
        url: MODx.config.connector_url,
        action: 'Element/ImportProperties',
        fileUpload: true,
        saveBtnText: _('import'),
        fields: [{
            html: _('properties_import_msg'),
            style: 'margin-bottom: 10px;',
            xtype: 'modx-description'
        }, {
            xtype: 'fileuploadfield',
            fieldLabel: _('file'),
            buttonText: _('upload.buttons.upload'),
            name: 'file',
            id: 'modx-impp-file',
            anchor: '100%'
        }]
    });
    MODx.window.ImportProperties.superclass.constructor.call(this, config);

    const
        fileCmp = Ext.getCmp('modx-impp-file'),
        onFileUploadFieldFileSelected = function(fileCmp, fakeFilePath) {
            const fileApi = fileCmp.fileInput.dom.files;
            fileCmp.el.dom.value = (typeof fileApi != 'undefined')
                ? fileApi[0].name
                : fakeFilePath.replace('C:\\fakepath\\', '')
            ;
        }
    ;
    fileCmp.on('fileselected', onFileUploadFieldFileSelected);
};
Ext.extend(MODx.window.ImportProperties, MODx.Window);
Ext.reg('modx-window-properties-import', MODx.window.ImportProperties);
