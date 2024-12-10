MODx.grid.Trash = function(config = {}) {
    this.sm = new Ext.grid.CheckboxSelectionModel();
    Ext.applyIf(config, {
        url: MODx.config.connector_url,
        baseParams: {
            action: 'Resource/Trash/GetList',
            context: MODx.request.context || null
        },
        fields: [
            'id',
            'pagetitle',
            'longtitle',
            'published',
            'context_key',
            'context_name',
            'parentPath',
            'deletedon',
            'deletedby',
            'deletedby_name'
        ],
        paging: true,
        autosave: true,
        save_action: 'Resource/UpdateFromGrid',
        save_callback: this.refreshEverything,
        remoteSort: true,
        sm: this.sm,
        columns: [this.sm, {
            header: _('id'),
            dataIndex: 'id',
            width: 20,
            sortable: true
        }, {
            header: _('pagetitle'),
            dataIndex: 'pagetitle',
            width: 80,
            sortable: true,
            renderer: this.renderTooltip
        }, {
            header: _('trash.context_title'),
            dataIndex: 'context_name',
            width: 60,
            sortable: false
        }, {
            header: _('published'),
            dataIndex: 'published',
            width: 40,
            sortable: true,
            editor: {
                xtype: 'combo-boolean'
            },
            renderer: {
                fn: function(value, metaData, record) {
                    /*
                        This field depends on permission other than the typicaledit,
                        thus not using the base setEditableCellClasses() method here
                    */
                    if (!record.json.permissions.publish) {
                        // eslint-disable-next-line no-param-reassign
                        metaData.css = 'editor-disabled';
                    }
                    return this.rendYesNo(value, metaData);
                },
                scope: this
            }
        }, {
            header: _('trash.deletedon_title'),
            dataIndex: 'deletedon',
            width: 75,
            sortable: true
        }, {
            header: _('trash.deletedbyUser_title'),
            dataIndex: 'deletedby',
            width: 75,
            sortable: true,
            renderer: function(value, metaData, record) {
                return record.data.deletedby_name;
            }
        }],
        tbar: [
            /*
                Not using base getBulkActionsButton() method here, as this menu utilizes
                methods/actions specific to this class not supported by that method
            */
            {
                text: _('bulk_actions'),
                id: 'modx-btn-bulk-actions',
                menu: [{
                    text: _('trash.selected_purge'),
                    itemId: 'modx-bulk-menu-opt-purge',
                    handler: this.purgeSelected,
                    scope: this
                }, {
                    text: _('trash.selected_restore'),
                    itemId: 'modx-bulk-menu-opt-restore',
                    handler: this.restoreSelected,
                    scope: this
                }],
                listeners: {
                    click: {
                        fn: function(btn) {
                            const
                                menuOptPurge = btn.menu.getComponent('modx-bulk-menu-opt-purge'),
                                menuOptUndelete = btn.menu.getComponent('modx-bulk-menu-opt-restore')
                            ;
                            if (this.getSelectionModel().getCount() === 0) {
                                menuOptPurge.disable();
                                menuOptUndelete.disable();
                            } else {
                                if (this.userCanPurge) {
                                    menuOptPurge.enable();
                                }
                                if (this.userCanUndelete) {
                                    menuOptUndelete.enable();
                                }
                            }
                        },
                        scope: this
                    }
                }
            }, {
                text: _('trash.purge_all'),
                id: 'modx-btn-purge-all',
                cls: 'x-btn-purge-all',
                handler: this.purgeAll
            }, {
                text: _('trash.restore_all'),
                id: 'modx-btn-restore-all',
                cls: 'x-btn-restore-all',
                handler: this.restoreAll
            },
            '->',
            {
                xtype: 'modx-combo-context',
                itemId: 'filter-context',
                emptyText: _('context'),
                value: MODx.request.context || null,
                baseParams: {
                    action: 'Context/GetList',
                    exclude: 'mgr',
                    isGridFilter: true,
                    targetGrid: 'MODx.grid.Trash'
                },
                listeners: {
                    select: {
                        fn: function(cmp, record, selectedIndex) {
                            this.applyGridFilter(cmp, 'context');
                        },
                        scope: this
                    }
                }
            },
            this.getQueryFilterField(),
            this.getClearFiltersButton('filter-context, filter-query')
        ]
    });

    MODx.grid.Trash.superclass.constructor.call(this, config);

    this.gridMenuActions = ['purge', 'undelete'];
    this.setUserHasPermissions('purge', ['purge_deleted']);
    this.setUserHasPermissions('undelete', ['undelete_document']);
    this.setShowActionsMenu();

    this.on({
        render: grid => {
            const buttonsToHide = [];
            if (!this.userCanPurge && !this.userCanUndelete) {
                buttonsToHide.push('modx-btn-bulk-actions', 'modx-btn-purge-all', 'modx-btn-restore-all');
            } else {
                const bulkMenu = Ext.getCmp('modx-btn-bulk-actions').menu;
                if (!this.userCanPurge) {
                    buttonsToHide.push('modx-btn-purge-all');
                    bulkMenu.getComponent('modx-bulk-menu-opt-purge').disable();
                }
                if (!this.userCanUndelete) {
                    buttonsToHide.push('modx-btn-restore-all');
                    bulkMenu.getComponent('modx-bulk-menu-opt-restore').disable();
                }
            }
            if (buttonsToHide.length > 0) {
                buttonsToHide.forEach(btnId => Ext.getCmp(btnId)?.hide());
            }
        },
        beforeedit: function(e) {
            if (e.field === 'published' && !this.userCanEditRecord(e.record, 'publish')) {
                return false;
            }
        }
    });
};

Ext.extend(MODx.grid.Trash, MODx.grid.Grid, {

    getMenu: function() {
        const
            model = this.getSelectionModel(),
            record = model.getSelected(),
            canPurge = this.userCanPurge && this.userCanDeleteRecord(record, 'purge'),
            canUndelete = this.userCanUndelete && this.userCanEditRecord(record, 'undelete'),
            menu = []
        ;
        if (model.getCount() > 1) {
            if (canPurge) {
                menu.push({
                    text: _('trash.selected_purge'),
                    handler: this.purgeSelected,
                    scope: this
                });
            }
            if (canUndelete) {
                menu.push({
                    text: _('trash.selected_restore'),
                    handler: this.restoreSelected,
                    scope: this
                });
            }
        } else {
            if (canPurge) {
                menu.push({
                    text: _('trash.purge'),
                    handler: this.purgeResource
                });
            }
            if (canUndelete) {
                menu.push({
                    text: _('trash.restore'),
                    handler: this.restoreResource
                });
            }
        }
        return menu;
    },

    purgeResource: function() {
        MODx.msg.confirm({
            minWidth: 500,
            title: _('trash.purge_confirm_title'),
            text: _('trash.purge_confirm_message', {
                list: this.listResources('')
            }),
            url: this.config.url,
            params: {
                action: 'Resource/Trash/Purge',
                ids: this.menu.record.id
            },
            listeners: {
                success: {
                    fn: function(data) {
                        this.refreshEverything(data.total);
                    },
                    scope: this
                },
                error: {
                    fn: function(data) {
                        MODx.msg.status({
                            title: _('error'),
                            message: data.message
                        });
                    },
                    scope: this
                }
            }
        });
    },

    restoreResource: function() {
        let withPublish = '';
        if (this.menu.record.published) {
            withPublish = '_with_publish';
        }
        MODx.msg.confirm({
            minWidth: 500,
            title: _('trash.restore_confirm_title'),
            text: _(`trash.restore_confirm_message${withPublish}`, {
                list: this.listResources('')
            }),
            url: this.config.url,
            params: {
                action: 'Resource/Undelete',
                id: this.menu.record.id
            },
            listeners: {
                success: {
                    fn: function(data) {
                        this.refreshEverything(data.total);
                    },
                    scope: this
                },
                error: {
                    fn: function(data) {
                        MODx.msg.status({
                            title: _('error'),
                            message: data.message
                        });
                    },
                    scope: this
                }
            }
        });
    },

    purgeSelected: function() {
        const selections = this.getSelectedAsList();
        if (selections === false) {
            return false;
        }
        MODx.msg.confirm({
            minWidth: 500,
            title: _('trash.purge_confirm_title'),
            text: _('trash.purge_confirm_message', {
                list: this.listResources('')
            }),
            url: this.config.url,
            params: {
                action: 'Resource/Trash/Purge',
                ids: selections
            },
            listeners: {
                success: {
                    fn: function(data) {
                        this.getSelectionModel().clearSelections(true);
                        this.refreshEverything(data.object.deletedCount);
                    },
                    scope: this
                },
                error: {
                    fn: function(data) {
                        MODx.msg.status({
                            title: _('error'),
                            message: data.message
                        });
                    },
                    scope: this
                }
            }
        });

        return true;
    },

    restoreSelected: function() {
        const selections = this.getSelectedAsList();
        if (selections === false) {
            return false;
        }
        MODx.msg.confirm({
            minWidth: 500,
            title: _('trash.restore_confirm_title'),
            text: _('trash.restore_confirm_message', {
                list: this.listResources('')
            }),
            url: this.config.url,
            params: {
                action: 'Resource/Trash/Restore',
                ids: selections
            },
            listeners: {
                success: {
                    fn: function(data) {
                        this.refreshEverything(data.total);
                    },
                    scope: this
                },
                error: {
                    fn: function(data) {
                        MODx.msg.status({
                            title: _('error'),
                            message: data.message
                        });
                    },
                    scope: this
                }
            }
        });
        return true;
    },

    purgeAll: function() {
        const model = this.getSelectionModel();
        model.selectAll();
        const selections = this.getSelectedAsList();
        if (selections === false) {
            return false;
        }
        MODx.msg.confirm({
            minWidth: 500,
            title: _('trash.purge_confirm_title'),
            text: _('trash.purge_all_confirm_message', {
                count: model.selections.length,
                list: this.listResources('')
            }),
            url: this.config.url,
            params: {
                action: 'Resource/Trash/Purge',
                // we can't just purge everything, because it might happen that in
                // the meantime something was deleted by another user which is not yet
                // shown in the trash manager list because of missing reload.
                // in that case we would purge something unreviewed/blindly.
                // therefore we have to pass all ids which are shown in our list here
                ids: selections
            },
            listeners: {
                success: {
                    fn: function(data) {
                        MODx.msg.status({
                            title: _('success'),
                            message: data.message
                        });
                        if (data.object.count_success > 0) {
                            this.refreshEverything(data.total); // no need to refresh if nothing was purged
                            this.fireEvent('emptyTrash');
                        }
                    },
                    scope: this
                },
                error: {
                    fn: function(data) {
                        MODx.msg.status({
                            title: _('error'),
                            message: data.message
                        });
                    },
                    scope: this
                }
            }
        });
    },

    restoreAll: function() {
        const model = this.getSelectionModel();
        model.selectAll();
        const selections = this.getSelectedAsList();
        if (selections === false) {
            return false;
        }
        MODx.msg.confirm({
            minWidth: 500,
            title: _('trash.restore_confirm_title'),
            text: _('trash.restore_all_confirm_message', {
                count: model.selections.length,
                list: this.listResources('')
            }),
            url: this.config.url,
            params: {
                action: 'Resource/Trash/Restore',
                // we can't just restore everything, because it might happen that in
                // the meantime something was deleted by another user which is not yet
                // shown in the trash manager list because of missing reload.
                // in that case we would restore something unreviewed/blindly.
                // therefore we have to pass all ids which are shown in our list here
                ids: selections
            },
            listeners: {
                success: {
                    fn: function(data) {
                        MODx.msg.status({
                            title: _('success'),
                            message: data.message
                        });
                        if (data.object.count_success > 0) {
                            this.refreshEverything(data.total); // no need to refresh if nothing was purged
                            this.fireEvent('emptyTrash');
                        }
                    },
                    scope: this
                },
                error: {
                    fn: function(data) {
                        MODx.msg.status({
                            title: _('error'),
                            message: data.message
                        });
                    },
                    scope: this
                }
            }
        });
    },

    refreshTree: function() {
        const tree = Ext.getCmp('modx-resource-tree');
        tree.refresh();
        this.refreshRecycleBinButton();
    },

    refreshEverything: function(total) {
        this.refresh();
        this.refreshTree();
        this.refreshRecycleBinButton(total);
    },

    refreshRecycleBinButton: function(total) {
        Ext.getCmp('modx-trash-link')?.updateState(+total);
    },

    listResources: function(separator = '') {
        // creates a textual representation of the selected resources
        // we create a textlist of the resources here to show them again in the confirmation box
        const
            selections = this.getSelectionModel().getSelections(),
            text = []
        ;
        let resourceRef;
        selections.forEach(function(selection) {
            resourceRef = `${selection.data.parentPath}<strong>${selection.data.pagetitle} (${selection.data.id})</strong>`;
            if (selection.data.published) {
                resourceRef = `<em>${resourceRef}</em>`;
            }
            resourceRef = `<div style='white-space:nowrap'>${resourceRef}</div>`;
            text.push(resourceRef);
        });
        return text.join(separator);
    },

    renderTooltip: function(value, metadata, record) {
        if (value) {
            let preview = ((record.json.pagetitle) ? `<p><strong>${_('pagetitle')}:</strong> ${record.json.pagetitle}</p>` : '')
                + ((record.json.longtitle) ? `<p><strong>${_('long_title')}:</strong> ${record.json.longtitle}</p>` : '')
                + ((record.data.parentPath) ? `<p><strong>${_('trash.parent_path')}:</strong> ${record.data.parentPath}</p>` : '')
                + ((record.json.content) ? `<p><strong>${_('content')}:</strong> ${Ext.util.Format.ellipsis(record.json.content.replace(/<\/?[^>]+>/gi, ''), 100)}</p>` : '');
            preview = Ext.util.Format.htmlEncode(preview);
            return `<div ext:qtip="${preview}">${value}</div>`;
        }
        return '';
    }
});
Ext.reg('modx-grid-trash', MODx.grid.Trash);
