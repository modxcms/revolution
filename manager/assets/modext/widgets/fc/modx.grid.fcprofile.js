/**
 * @class MODx.panel.FCProfiles
 * @extends MODx.FormPanel
 * @param {Object} config An object of configuration options
 * @xtype modx-panel-fc-profiles
 */
MODx.panel.FCProfiles = function(config = {}) {
    Ext.applyIf(config, {
        id: 'modx-panel-fc-profiles',
        cls: 'container',
        defaults: {
            collapsible: false,
            autoHeight: true
        },
        items: [{
            html: _('form_customization'),
            id: 'modx-fcp-header',
            xtype: 'modx-header'
        }, MODx.getPageStructure([{
            title: _('profiles'),
            autoHeight: true,
            layout: 'form',
            items: [{
                html: `<p>${_('form_customization_msg')}</p>`,
                xtype: 'modx-description'
            }, {
                title: '',
                preventRender: true,
                xtype: 'modx-grid-fc-profile',
                cls: 'main-wrapper'
            }]
        }], {
            id: 'modx-form-customization-tabs'
        })]
    });
    MODx.panel.FCProfiles.superclass.constructor.call(this, config);
};
Ext.extend(MODx.panel.FCProfiles, MODx.FormPanel);
Ext.reg('modx-panel-fc-profiles', MODx.panel.FCProfiles);

/**
 * @class MODx.grid.FCProfile
 * @extends MODx.grid.Grid
 * @param {Object} config An object of configuration properties
 * @xtype modx-grid-fc-profile
 */
MODx.grid.FCProfile = function(config = {}) {
    this.sm = new Ext.grid.CheckboxSelectionModel();
    Ext.applyIf(config, {
        id: 'modx-grid-fc-profile',
        url: MODx.config.connector_url,
        baseParams: {
            action: 'Security/Forms/Profile/GetList'
        },
        fields: [
            'id',
            'name',
            'description',
            'usergroups',
            'active',
            'rank',
            'sets'
        ],
        paging: true,
        autosave: true,
        save_action: 'Security/Forms/Profile/UpdateFromGrid',
        sm: this.sm,
        remoteSort: true,
        columns: [this.sm, {
            header: _('id'),
            dataIndex: 'id',
            width: 40,
            sortable: true
        }, {
            header: _('name'),
            dataIndex: 'name',
            width: 200,
            sortable: true,
            editor: {
                xtype: 'textfield'
            },
            renderer: {
                fn: function(value, metaData, record) {
                    // eslint-disable-next-line no-param-reassign
                    metaData.css = this.setEditableCellClasses(record);
                    return this.renderLink(value, {
                        href: `?a=security/forms/profile/update&id=${record.data.id}`
                    });
                },
                scope: this
            }
        }, {
            header: _('description'),
            dataIndex: 'description',
            width: 250,
            sortable: true,
            editor: {
                xtype: 'textarea'
            },
            renderer: {
                fn: function(value, metaData, record) {
                    // eslint-disable-next-line no-param-reassign
                    metaData.css = this.setEditableCellClasses(record);
                    return value;
                },
                scope: this
            }
        }, {
            header: _('usergroups'),
            dataIndex: 'usergroups',
            width: 150
        }],
        tbar: [
            this.getCreateButton('profile', 'createProfile'),
            this.getBulkActionsButton('profile', 'Security/Forms/Profile/RemoveMultiple', 'int', 'activate', 'deactivate'),
            '->',
            this.getQueryFilterField(),
            this.getClearFiltersButton()
        ],
        viewConfig: this.getViewConfig(true, false, true)
    });
    MODx.grid.FCProfile.superclass.constructor.call(this, config);

    this.gridMenuActions = ['edit', 'delete', 'duplicate'];

    // Note there are currently no action-specific, object-specific permissions for FC Profiles
    this.setUserCanEdit(['customize_forms', 'save']);
    this.setUserCanCreate(['customize_forms', 'save']);
    this.setUserCanDelete(['customize_forms', 'remove']);
    this.setShowActionsMenu();

    this.on({
        render: function() {
            this.getStore().reload();
        },
        beforeedit: function(e) {
            if (!this.userCanEdit) {
                return false;
            }
        }
    });
};
Ext.extend(MODx.grid.FCProfile, MODx.grid.Grid, {
    getMenu: function() {
        const
            model = this.getSelectionModel(),
            record = model.getSelected(),
            menu = []
        ;
        if (model.getCount() > 1) {
            if (this.userCanEdit) {
                menu.push({
                    text: _('selected_activate'),
                    handler: this.activateSelected
                });
                menu.push({
                    text: _('selected_deactivate'),
                    handler: this.deactivateSelected
                });
            }
            if (this.userCanDelete) {
                menu.push('-');
                menu.push({
                    text: _('selected_remove'),
                    handler: this.removeSelected.bind(this, 'profile', 'Security/Forms/Profile/RemoveMultiple')
                });
            }
        } else {
            if (this.userCanEdit) {
                menu.push({
                    text: _('edit'),
                    handler: this.updateProfile
                }, {
                    text: _('duplicate'),
                    handler: this.duplicateProfile
                }, '-');
                if (record.data.active) {
                    menu.push({
                        text: _('deactivate'),
                        handler: this.deactivateProfile
                    });
                } else {
                    menu.push({
                        text: _('activate'),
                        handler: this.activateProfile
                    });
                }
            }
            if (this.userCanDelete) {
                menu.push('-', {
                    text: _('delete'),
                    handler: this.confirm.createDelegate(this, ['Security/Forms/Profile/Remove', 'profile_remove_confirm'])
                });
            }
        }
        return menu;
    },

    createProfile: function(btn, e) {
        if (!this.windows.cpro) {
            this.windows.cpro = MODx.load({
                xtype: 'modx-window-fc-profile-create',
                listeners: {
                    success: {
                        fn: this.refresh,
                        scope: this
                    }
                }
            });
        }
        this.windows.cpro.reset();
        this.windows.cpro.show(e.target);
    },

    updateProfile: function(btn, e) {
        const { record } = this.menu;
        window.location.href = `?a=security/forms/profile/update&id=${record.id}`;
    },

    duplicateProfile: function(btn, e) {
        MODx.Ajax.request({
            url: this.config.url,
            params: {
                action: 'security/forms/profile/duplicate',
                id: this.menu.record.id
            },
            listeners: {
                success: {
                    fn: this.refresh,
                    scope: this
                }
            }
        });
    },

    activateProfile: function(btn, e) {
        MODx.Ajax.request({
            url: this.config.url,
            params: {
                action: 'Security/Forms/Profile/Activate',
                id: this.menu.record.id
            },
            listeners: {
                success: {
                    fn: this.refresh,
                    scope: this
                }
            }
        });
    },

    deactivateProfile: function(btn, e) {
        MODx.Ajax.request({
            url: this.config.url,
            params: {
                action: 'Security/Forms/Profile/Deactivate',
                id: this.menu.record.id
            },
            listeners: {
                success: {
                    fn: this.refresh,
                    scope: this
                }
            }
        });
    },

    activateSelected: function() {
        const selections = this.getSelectedAsList();
        if (selections === false) {
            return false;
        }
        MODx.Ajax.request({
            url: this.config.url,
            params: {
                action: 'Security/Forms/Profile/ActivateMultiple',
                profiles: selections
            },
            listeners: {
                success: {
                    fn: function() {
                        this.getSelectionModel().clearSelections(true);
                        this.refresh();
                    },
                    scope: this
                }
            }
        });
        return true;
    },

    deactivateSelected: function() {
        const selections = this.getSelectedAsList();
        if (selections === false) {
            return false;
        }
        MODx.Ajax.request({
            url: this.config.url,
            params: {
                action: 'Security/Forms/Profile/DeactivateMultiple',
                profiles: selections
            },
            listeners: {
                success: {
                    fn: function() {
                        this.getSelectionModel().clearSelections(true);
                        this.refresh();
                    },
                    scope: this
                }
            }
        });
        return true;
    }
});
Ext.reg('modx-grid-fc-profile', MODx.grid.FCProfile);

/**
 * @class MODx.window.CreateFCProfile
 * @extends MODx.Window
 * @param {Object} config An object of options.
 * @xtype modx-window-fc-profile-create
 */
MODx.window.CreateFCProfile = function(config = {}) {
    Ext.applyIf(config, {
        title: _('create'),
        url: MODx.config.connector_url,
        action: 'Security/Forms/Profile/Create',
        formDefaults: {
            anchor: '100%',
            msgTarget: 'under',
            validationEvent: 'change',
            validateOnBlur: false
        },
        fields: [{
            xtype: 'textfield',
            name: 'name',
            fieldLabel: _('name'),
            allowBlank: false
        }, {
            xtype: 'textarea',
            name: 'description',
            fieldLabel: _('description')
        }, {
            xtype: 'xcheckbox',
            boxLabel: _('active'),
            hideLabel: true,
            name: 'active',
            inputValue: 1,
            value: 1,
            checked: true
        }],
        keys: []
    });
    MODx.window.CreateFCProfile.superclass.constructor.call(this, config);
};
Ext.extend(MODx.window.CreateFCProfile, MODx.Window);
Ext.reg('modx-window-fc-profile-create', MODx.window.CreateFCProfile);
