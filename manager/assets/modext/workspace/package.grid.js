/**
 * Loads a grid of Packages.
 *
 * @class MODx.grid.Package
 * @extends MODx.grid.Grid
 * @param {Object} config An object of options.
 * @xtype modx-grid-package
 */
MODx.grid.Package = function(config = {}) {
    this.exp = new Ext.grid.RowExpander({
        tpl: new Ext.XTemplate(
            '<p class="package-readme"><i>{readme}</i></p>'
        )
    });

    /* Package name + action button renderer */
    this.mainColumnTpl = new Ext.XTemplate(`<tpl for=".">
        <h3 class="main-column{state:defaultValue("")}">{name}</h3>
        <tpl if="actions !== null">
            <ul class="actions">
                <tpl for="actions">
                    <li><button type="button" class="controlBtn {className}">{text}</button></li>
                </tpl>
            </ul>
        </tpl>
        <tpl if="message !== null">
            <tpl for="message">
                <div class="{className}">{text}</div>
            </tpl>
        </tpl>
    </tpl>`, {
        compiled: true
    });

    let downloadButton;
    if (MODx.curlEnabled) {
        downloadButton = {
            text: _('download_extras'),
            xtype: 'splitbutton',
            cls: 'primary-button',
            handler: this.onDownloadMoreExtra,
            menu: {
                items: [{
                    text: _('provider_select'),
                    handler: this.changeProvider,
                    scope: this
                }, {
                    text: _('package_search_local_title'),
                    handler: this.searchLocal,
                    scope: this
                }, {
                    text: _('transport_package_upload'),
                    handler: this.uploadTransportPackage,
                    scope: this
                }]
            }
        };
    } else {
        downloadButton = {
            text: _('package_search_local_title'),
            xtype: 'splitbutton',
            handler: this.searchLocal,
            scope: this,
            menu: {
                items: [{
                    text: _('transport_package_upload'),
                    handler: this.uploadTransportPackage,
                    scope: this
                }]
            }
        };
    }

    Ext.applyIf(config, {
        title: _('packages'),
        // Deprecate id, change to modx-grid-package in future release
        id: 'modx-package-grid',
        url: MODx.config.connector_url,
        action: 'Workspace/Packages/GetList',
        fields: [
            'signature',
            'name',
            'version',
            'release',
            'created',
            'updated',
            'installed',
            'isInstalled',
            'state',
            'workspace',
            'provider',
            'provider_name',
            'disabled',
            'source',
            'attributes',
            'readme',
            'menu',
            'install',
            'textaction',
            'iconaction',
            'updateable'
        ],
        showActionsColumn: false,
        plugins: [this.exp],
        pageSize: Math.min(parseInt(MODx.config.default_per_page, 10), 25),
        disableContextMenuAction: true,
        columns: [
            this.exp,
            {
                header: _('name'),
                dataIndex: 'name',
                id: 'main',
                renderer: { fn: this.mainColumnRenderer, scope: this }
            }, {
                header: _('version'),
                dataIndex: 'version',
                id: 'meta-col',
                fixed: true,
                width: 120,
                renderer: function(value, metaData, record) {
                    return `${value}-${record.data.release}`;
                }
            }, {
                header: _('installed'),
                dataIndex: 'installed',
                id: 'info-col',
                fixed: true,
                width: 160,
                renderer: this.dateColumnRenderer
            }, {
                header: _('provider'),
                dataIndex: 'provider_name',
                id: 'text-col',
                fixed: true,
                width: 120
            }
        ],
        primaryKey: 'signature',
        paging: true,
        autosave: true,
        tbar: [
            downloadButton,
            {
                text: _('packages_purge'),
                handler: this.purgePackages
            },
            '->',
            this.getQueryFilterField(),
            this.getClearFiltersButton()
        ]
    });
    MODx.grid.Package.superclass.constructor.call(this, config);
    this.on('render', function() {
        this.mask = new Ext.LoadMask(this.body.dom, {
            msg: _('checking_for_package_updates')
        });
        if (!this.loaded) {
            this.mask.show();
        }
    }, this);
    this.getStore().on('load', function(s) {
        if (this.mask) {
            this.mask.hide();
        }
        this.loaded = true;
    }, this);
    this.on('afterrender', function() {
        this.uploader = new MODx.util.MultiUploadDialog.Upload({
            url: MODx.config.connector_url,
            base_params: {
                action: 'Workspace/Packages/Upload',
                wctx: MODx.ctx || '',
                source: MODx.config.default_media_source,
                path: `${MODx.config.core_path}packages/`
            },
            permitted_extensions: ['zip']
        });
        this.uploader.addDropZone(this.ownerCt);
        this.uploader.on('uploadsuccess', function() {
            this.searchLocalWithoutPrompt();
        }, this);
    }, this);
    this.on('click', this.onClick, this);
};
Ext.extend(MODx.grid.Package, MODx.grid.Grid, {
    console: null,

    activate: function() {
        const west = Ext.getCmp('modx-leftbar-tabs');
        let stateId = 'modx-leftbar-tabs';
        if (west && west.stateId) {
            stateId = west.stateId;
        }
        const state = Ext.state.Manager.get(stateId);
        if (state && state.collapsed === false) {
            // Panel was not collapsed before, lets restore it
            Ext.getCmp('modx-layout').showLeftbar();
        }
        Ext.getCmp('modx-panel-packages').getLayout().setActiveItem(this.id);
        this.updateBreadcrumbs(_('packages_desc'));
    },

    updateBreadcrumbs: function(msg, highlight) {
        msg = Ext.getCmp('packages-breadcrumbs').desc;
        if (highlight) {
            msg.text = msg;
            msg.className = 'highlight';
        }
        Ext.getCmp('packages-breadcrumbs').reset(msg);
    },

    /* Main column renderer */
    mainColumnRenderer: function(value, metaData, record, rowIndex, colIndex, store) {
        const
            { data } = record,
            state = data.isInstalled ? ' installed' : ' not-installed',
            values = {
                name: value,
                state: state,
                actions: null,
                message: null
            },
            actions = []
        ;
        if (data.isInstalled) {
            actions.push({ className: 'uninstall', text: data.textaction });
            actions.push({ className: 'reinstall', text: _('package_reinstall_action_button') });
        } else {
            actions.push({ className: 'install primary-button', text: data.textaction });
        }
        if (data.updateable) {
            actions.push({ className: 'update orange', text: _('package_update_action_button') });
        } else if (data.provider !== 0) {
            actions.push({ className: 'checkupdate', text: _('package_check_for_updates') });
        }
        actions.push({ className: 'remove', text: _('package_remove_action_button') });
        actions.push({ className: 'details', text: _('view_details') });
        values.actions = actions;
        return this.mainColumnTpl.apply(values);
    },

    dateColumnRenderer: function(value, metaData) {
        if (Ext.isEmpty(value) || value.includes(_('not_installed'))) {
            metaData.css = 'not-installed';
        }
        return value;
    },

    onClick: function(e) {
        const
            target = e.getTarget(),
            targetType = target.className.split(' ')[0]
        ;
        if (targetType === 'controlBtn') {
            const
                action = target.className.split(' ')[1],
                record = this.getSelectionModel().getSelected()
            ;
            this.menu.record = record.data;
            switch (action) {
                case 'remove':
                    this.remove(record, e);
                    break;
                case 'install':
                case 'reinstall':
                    this.install(record);
                    break;
                case 'uninstall':
                    this.uninstall(record, e);
                    break;
                case 'update':
                case 'checkupdate':
                    this.update(record, e);
                    break;
                case 'details':
                    this.viewPackage(record, e);
                    break;
                default:
                    break;
            }
        }
    },

    /* Install a package */
    install: function(record) {
        Ext.Ajax.request({
            url: MODx.config.connector_url,
            params: {
                action: 'Workspace/Packages/GetAttribute',
                attributes: 'license,readme,changelog,setup-options,requires',
                signature: record.data.signature
            },
            method: 'GET',
            scope: this,
            success: function(result, request) {
                this.processResult(result.responseText, record);
            },
            failure: function(result, request) {
                Ext.MessageBox.alert(_('failed'), result.responseText);
            }
        });
    },

    /* Go through the install process */
    processResult: function(response, record) {
        const data = Ext.util.JSON.decode(response);

        if (data.object.license !== null && data.object.readme !== null && data.object.changelog !== null) {
            /* Show license/changelog panel */
            const infoPanel = Ext.getCmp('modx-package-beforeinstall');
            infoPanel.activate();
            infoPanel.updatePanel(data.object, record);
        } else if (data.object['setup-options'] !== null) {
            /* No license/changelog, show setup-options */
            Ext.getCmp('package-show-setupoptions-btn').signature = record.data.signature;
            Ext.getCmp('modx-panel-packages').onSetupOptions();
        } else {
            /* No license/changelog, no setup-options, install directly */
            Ext.getCmp('package-install-btn').signature = record.data.signature;
            Ext.getCmp('modx-panel-packages').install();
        }
    },

    /* Launch Package Browser */
    onDownloadMoreExtra: function(btn, e) {
        MODx.provider = MODx.defaultProvider;
        Ext.getCmp('modx-panel-packages-browser').activate();
    },

    changeProvider: function(btn, e) {
        this.loadWindow(btn, e, {
            xtype: 'modx-package-changeprovider'
        });
    },

    /**
     * Open a window allowing user to upload a transport package directly
     */
    uploadTransportPackage: function() {
        this.uploader.setBaseParams({ source: 1 });
        this.uploader.show();
    },

    searchLocal: function() {
        MODx.msg.confirm({
            title: _('package_search_local_title'),
            text: _('package_search_local_confirm'),
            url: MODx.config.connector_url,
            params: {
                action: 'Workspace/Packages/ScanLocal'
            },
            listeners: {
                success: {
                    fn: function(response) {
                        this.getStore().reload();
                    },
                    scope: this
                }
            }
        });
    },

    /**
     * Scan for new packages, without the pointless & annoying confirmation box
     */
    searchLocalWithoutPrompt: function() {
        MODx.Ajax.request({
            url: MODx.config.connector_url,
            params: {
                action: 'Workspace/Packages/ScanLocal'
            },
            listeners: {
                success: {
                    fn: function(response) {
                        this.getStore().reload();
                    },
                    scope: this
                }
            }
        });
    },

    /* Go to package details @TODO : Stay on the same page */
    viewPackage: function(btn, e) {
        MODx.loadPage('workspaces/package/view', `signature=${this.menu.record.signature}&package_name=${this.menu.record.name}`);
    },

    /* Search for a package update - only for installed package */
    update: function(btn, e) {
        if (this.windows['modx-window-package-update']) {
            this.windows['modx-window-package-update'].destroy();
        }
        MODx.Ajax.request({
            url: this.config.url,
            params: {
                action: 'Workspace/Packages/CheckForUpdates',
                signature: this.menu.record.signature
            },
            listeners: {
                success: {
                    fn: function(response) {
                        this.loadWindow(btn, e, {
                            xtype: 'modx-window-package-update',
                            cls: 'modx-alert',
                            packages: response.object,
                            record: this.menu.record,
                            force: true,
                            listeners: {
                                success: {
                                    fn: function(o) {
                                        this.refresh();
                                        this.menu.record = { data: o.a.result.object };
                                        this.install(this.menu.record);
                                    },
                                    scope: this
                                }
                            }
                        });
                    },
                    scope: this
                },
                failure: {
                    fn: function(response) {
                        MODx.msg.alert(_('package_update'), response.message);
                        return false;
                    },
                    scope: this
                }
            }
        });
    },

    /* Uninstall a package */
    uninstall: function(btn, e) {
        this.loadWindow(btn, e, {
            xtype: 'modx-window-package-uninstall',
            listeners: {
                success: {
                    fn: function(params) {
                        this._uninstall(this.menu.record, btn, params);
                    },
                    scope: this
                }
            }
        });
    },

    _uninstall: function(record, btn, params = {}) {
        const topic = `/workspace/package/uninstall/${record.signature}/`;
        this.loadConsole(btn, topic);
        Ext.apply(params, {
            action: 'Workspace/Packages/Uninstall',
            signature: record.signature,
            register: 'mgr',
            topic: topic
        });

        MODx.Ajax.request({
            url: this.config.url,
            params: params,
            listeners: {
                success: {
                    fn: function(response) {
                        Ext.Msg.hide();
                        this.refresh();
                        Ext.getCmp('modx-layout').refreshTrees();
                    },
                    scope: this
                },
                failure: {
                    fn: function(response) {
                        Ext.Msg.hide();
                        this.refresh();
                    },
                    scope: this
                }
            }
        });
    },

    /* Remove a package entirely */
    remove: function(btn, e) {
        if (this.destroying) {
            return MODx.grid.Package.superclass.remove.apply(this, arguments);
        }
        const
            { record } = this.menu,
            topic = `/workspace/package/remove/${record.signature}/`
        ;
        this.loadWindow(btn, e, {
            xtype: 'modx-window-package-remove',
            record: {
                signature: record.signature,
                topic: topic,
                register: 'mgr'
            }
        });
    },

    /* Purge old packages */
    purgePackages: function(btn, e) {
        const topic = '/Workspace/Packages/Purge/';

        this.loadWindow(btn, e, {
            xtype: 'modx-window-packages-purge',
            record: {
                packagename: '*',
                topic: topic,
                register: 'mgr'
            },
            listeners: {
                success: {
                    fn: function(o) {
                        this.refresh();
                    },
                    scope: this
                }
            }
        });
    },

    /* Load the console */
    loadConsole: function(btn, topic) {
        this.console = MODx.load({
            xtype: 'modx-console',
            register: 'mgr',
            topic: topic
        });
        this.console.show(btn);
    },

    getConsole: function() {
        return this.console;
    }
});
Ext.reg('modx-grid-package', MODx.grid.Package);
// Deprecated, keeping for backward compatability; remove in future version
Ext.reg('modx-package-grid', MODx.grid.Package);

/**
 * @class MODx.window.PackageUpdate
 * @extends MODx.Window
 * @constructor
 * @param {Object} config An object of options.
 * @xtype modx-window-package-update
 */
MODx.window.PackageUpdate = function(config = {}) {
    Ext.applyIf(config, {
        title: _('package_update'),
        url: MODx.config.connector_url,
        action: 'Workspace/Packages/Rest/Download',
        id: 'modx-window-package-update',
        saveBtnText: _('update'),
        fields: this.setupOptions(config.packages, config.record)
    });
    MODx.window.PackageUpdate.superclass.constructor.call(this, config);
    this.on('hide', function() {
        this.destroy();
    }, this);
};
Ext.extend(MODx.window.PackageUpdate, MODx.Window, {
    setupOptions: function(availableUpdates, record) {
        const items = [{
            html: _('package_update_to_version'),
            border: false
        }, {
            xtype: 'hidden',
            name: 'provider',
            value: Ext.isDefined(record.provider) ? record.provider : MODx.provider
        }];

        availableUpdates.forEach((pkg, i) => {
            let label = pkg.signature;
            if (pkg.changelog) {
                // We have a changelog string, allow users to view it
                label += `<a href="javascript:;" class="changelog">${_('changelog')}</a>`;
            }
            items.push({
                xtype: 'radio',
                name: 'info',
                boxLabel: label,
                itemCls: 'radio-version',
                hideLabel: true,
                description: pkg.description,
                inputValue: pkg.info,
                labelSeparator: '',
                checked: i === 0,
                _changelog: pkg.changelog,
                listeners: {
                    afterrender: {
                        fn: function(radio) {
                            const changelog = radio.container.query('.changelog')[0];
                            if (!changelog) {
                                return;
                            }
                            // When the changelog link is clicked, display the changelog in a window
                            Ext.get(changelog).on('click', () => {
                                MODx.load({
                                    xtype: 'modx-window',
                                    title: _('changelog'),
                                    cls: 'modx-alert',
                                    width: 520,
                                    style: 'white-space: pre-wrap',
                                    fields: [{
                                        xtype: 'box',
                                        html: radio._changelog
                                    }],
                                    buttons: [{
                                        text: _('close'),
                                        handler: function(btn, e) {
                                            btn.ownerCt.ownerCt.close();
                                        }
                                    }]
                                }).show();
                            });
                        }
                    }
                }
            });
        });
        return items;
    }
});
Ext.reg('modx-window-package-update', MODx.window.PackageUpdate);
