MODx.grid.Trash = function (config) {
    config = config || {};

    this.sm = new Ext.grid.CheckboxSelectionModel();

    Ext.applyIf(config, {
        url: MODx.config.connector_url,
        baseParams: {
            action: 'resource/trash/getlist'
        },
        fields: [
            'id',
            'pagetitle',
            'longtitle',
            'published',
            'context_key',
            'context_name',
            'parentPath', // ? wtf
            'deletedon',
            'deletedby',
            'deletedby_name',
            'cls'
        ],
        paging: true,
        autosave: true,
        save_action: 'resource/updatefromgrid',
        save_callback: function () {
            this.refreshEverything();
        },
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
            tooltip: "TODO: longtitle here"
        }, {
            header: _('long_title'),
            dataIndex: 'longtitle',
            width: 120,
            sortable: false
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
            editor: {xtype: 'combo-boolean', renderer: 'boolean'}
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
            renderer: function (value, metaData, record) {
                return record.data.deletedby_name;
            }
        }],

        tbar: [{
            text: _('bulk_actions'),
            menu: [{
                text: _('trash.selected_purge'),
                handler: this.purgeSelected,
                scope: this
            }, {
                text: _('trash.selected_restore'),
                handler: this.restoreSelected,
                scope: this
            }]
        }, {
            xtype: 'button',
            text: _('trash.purge_all'),
            id: 'modx-purge-all',
            cls: 'x-form-purge-all red',
            listeners: {
                'click': {fn: this.purgeAll, scope: this}
            }
        }, {
            xtype: 'button',
            text: _('trash.restore_all'),
            id: 'modx-restore-all',
            cls: 'x-form-restore-all green',
            listeners: {
                'click': {fn: this.restoreAll, scope: this}
            }
        }, '->', {
            xtype: 'textfield',
            name: 'search',
            id: 'modx-source-search',
            cls: 'x-form-filter',
            emptyText: _('search_ellipsis'),
            listeners: {
                'change': {fn: this.search, scope: this},
                'render': {
                    fn: function (cmp) {
                        new Ext.KeyMap(cmp.getEl(), {
                            key: Ext.EventObject.ENTER,
                            fn: this.blur,
                            scope: cmp
                        });
                    }, scope: this
                }
            }
        }, {
            xtype: 'button',
            text: _('filter_clear'),
            id: 'modx-filter-clear',
            cls: 'x-form-filter-clear',
            listeners: {
                'click': {fn: this.clearFilter, scope: this}
            }
        }]
    });

    MODx.grid.Trash.superclass.constructor.call(this, config);
};

Ext.extend(MODx.grid.Trash, MODx.grid.Grid, {

    getMenu: function () {
        var r = this.getSelectionModel().getSelected();
        var p = r.data.cls;

        var m = [];
        if (this.getSelectionModel().getCount() > 1) {
            m.push({
                text: _('trash.selected_purge'),
                handler: this.purgeSelected,
                scope: this
            });
            m.push({
                text: _('trash.selected_restore'),
                handler: this.restoreSelected,
                scope: this
            });
        } else {
            if (p.indexOf('trashpurge') !== -1) {
                m.push({
                    text: _('trash.purge'),
                    handler: this.purgeResource
                });
            }
            if (p.indexOf('trashundelete') !== -1) {
                m.push({
                    text: _('trash.restore'),
                    handler: this.restoreResource
                });
            }
        }
        if (m.length > 0) {
            this.addContextMenuItem(m);
        }
    },

    purgeResource: function () {
        MODx.msg.confirm({
            title: _('trash.purge_confirm_title'),
            text: _('trash.purge_confirm_message', {
                'list': this.listResources('<br/>')
            }),
            url: this.config.url,
            params: {
                action: 'resource/trash/purge',
                id: this.menu.record.id
            },
            listeners: {
                'success': {
                    fn: function (data) {
                        this.refreshEverything(data.total);
                    }, scope: this
                }
            }
        });
    },

    restoreResource: function () {
        var withPublish = '';
        if (this.menu.record.published) {
            withPublish = '_with_publish';
        }
        MODx.msg.confirm({
            title: _('trash.restore_confirm_title'),
            text: _('trash.restore_confirm_message' + withPublish, {
                'list': this.listResources('<br/>')
            }),
            url: this.config.url,
            params: {
                action: 'resource/undelete',
                id: this.menu.record.id
            },
            listeners: {
                'success': {
                    fn: function (data) {
                        this.refreshEverything(data.total);
                    }, scope: this
                }
            }
        });
    },

    purgeSelected: function () {
        var cs = this.getSelectedAsList();
        if (cs === false) return false;

        MODx.msg.confirm({
            title: _('trash.purge_confirm_title'),
            text: _('trash.purge_confirm_message', {
                'list': this.listResources('')
            }),
            url: this.config.url,
            params: {
                action: 'resource/trash/purge',
                ids: cs
            },
            listeners: {
                'success': {
                    fn: function (data) {
                        this.getSelectionModel().clearSelections(true);
                        this.refreshEverything(data.total);
                    }, scope: this
                }
            }
        });

        return true;
    },

    purgeAll: function () {
        var sm = this.getSelectionModel();
        sm.selectAll();
        var cs = this.getSelectedAsList();
        if (cs === false) return false;

        MODx.msg.confirm({
            title: _('trash.purge_confirm_title'),
            text: _('trash.purgeall_confirm_message', {
                'count': sm.selections.length,
                'list': this.listResources('')
            }),
            url: this.config.url,
            params: {
                action: 'resource/trash/purge',
                ids: -1  // this causes the processor to delete everything you have access to
            },
            listeners: {
                'success': {
                    fn: function (data) {
                        MODx.msg.status({
                            title: _('success'),
                            message: data.message
                        });
                        if (data.object.count_success > 0) {
                            this.refreshEverything(data.total);       // no need to refresh if nothing was purged
                            this.fireEvent('emptyTrash');
                        }
                    }, scope: this
                },
                'error': {
                    fn: function (data) {
                        MODx.msg.status({
                            title: _('error'),
                            message: data.message
                        });
                    }, scope: this
                }
            }
        })
    },

    restoreAll: function () {
        var sm = this.getSelectionModel();
        sm.selectAll();
        var cs = this.getSelectedAsList();
        if (cs === false) return false;

        MODx.msg.confirm({
            title: _('trash.restore_confirm_title'),
            text: _('trash.restoreall_confirm_message', {
                'count': sm.selections.length,
                'list': this.listResources('')
            }),
            url: this.config.url,
            params: {
                action: 'resource/trash/restore',
                // we can't just restore everything, because it might happen that in
                // the meantime something was deleted by another user which is not yet
                // shown in the trash manager list because of missing reload.
                // in that case we would restore something unreviewed/blindly.
                // therefore we have to pass all ids which are shown in our list here
                ids: cs
            },
            listeners: {
                'success': {
                    fn: function (data) {
                        MODx.msg.status({
                            title: _('success'),
                            message: data.message
                        });
                        if (data.object.count_success > 0) {
                            this.refreshEverything(data.total);       // no need to refresh if nothing was purged
                            this.fireEvent('emptyTrash');
                        }
                    }, scope: this
                },
                'error': {
                    fn: function (data) {
                        MODx.msg.status({
                            title: _('error'),
                            message: data.message
                        });
                    }, scope: this
                }
            }
        })
    },

    refreshTree: function () {
        var t = Ext.getCmp('modx-resource-tree');
        t.refresh();
        this.refreshRecycleBinButton();
    },

    refreshEverything: function (total) {
        this.refresh();
        this.refreshTree();
        this.refreshRecycleBinButton(total);
    },

    refreshRecycleBinButton: function (total) {
        var t = Ext.getCmp('modx-resource-tree');
        var trashButton = t.getTopToolbar().findById('emptifier');

        var count = this.getStore().getTotalCount();

        // TODO we need to now the number of deleted resources here.

        // if no resource is deleted, we disable the icon.
        // otherwise we hae to update the tooltip
        if (total !== undefined) {
            if (total = 0) {
                trashButton.disable();
                trashButton.setTooltip(_('trash.manage_recycle_bin_tooltip'));
            } else {
                trashButton.enable();
                trashButton.setTooltip(_('trash.manage_recycle_bin_tooltip', total));
            }
        }
    },

    restoreSelected: function () {
        var cs = this.getSelectedAsList();
        if (cs === false) return false;

        MODx.msg.confirm({
            title: _('trash.restore_confirm_title'),
            text: _('trash.restore_confirm_message', {
                'list': this.listResources('')
            }),
            url: this.config.url,
            params: {
                action: 'resource/trash/restore',
                ids: cs
            },
            listeners: {
                'success': {
                    fn: function (data) {
                        this.refreshEverything(data.total);
                    }, scope: this
                }
            }
        });
        return true;
    },

    listResources: function (separator) {
        separator = separator || ',';

        // creates a textual representation of the selected resources
        // we create a textlist of the resources here to show them again in the confirmation box
        var selections = this.getSelectionModel().getSelections();
        var text = [], t;
        selections.forEach(function (selection) {
            //t = selection.data.pagetitle + " (" + selection.data.id + ")";
            t = selection.data.parentPath + "<strong>" + selection.data.pagetitle + " (" + selection.data.id + ")" + "</strong>";
            if (selection.data.published) {
                t = '<em>' + t + '</em>';
            }
            t = "<div style='white-space:nowrap'>" + t + "</div>";
            text.push(t);
        });
        return text.join(separator);
    }
});
Ext.reg('modx-grid-trash', MODx.grid.Trash);
