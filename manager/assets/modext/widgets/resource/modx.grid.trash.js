MODx.grid.Trash = function (config) {
    config = config || {};

    this.sm = new Ext.grid.CheckboxSelectionModel();

    Ext.applyIf(config, {
        url: MODx.config.connector_url,
        baseParams: {
            action: 'Resource/Trash/GetList'
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
            'deletedby_name',
            'cls'
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
            cls: 'x-btn-purge-all',
            listeners: {
                'click': {fn: this.purgeAll, scope: this}
            }
        }, {
            xtype: 'button',
            text: _('trash.restore_all'),
            id: 'modx-restore-all',
            cls: 'x-btn-restore-all',
            listeners: {
                'click': {fn: this.restoreAll, scope: this}
            }
        }, '->', {
            xtype: 'modx-combo-context',
            id: 'modx-trash-context',
            emptyText: _('context'),
            exclude: 'mgr',
            listeners: {
                'select': {fn: this.searchContext, scope: this}
            }
        },{
            xtype: 'textfield',
            id: 'modx-trash-search',
            cls: 'x-form-filter',
            emptyText: _('search'),
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

    search: function (tf, newValue) {
        var nv = newValue || tf;
        this.getStore().baseParams.query = Ext.isEmpty(nv) || Ext.isObject(nv) ? '' : nv;
        this.getBottomToolbar().changePage(1);
        this.refresh();
        return true;
    },

    searchContext: function (tf) {
        this.getStore().baseParams.context = !Ext.isEmpty(tf) ? tf.value : '';
        this.getBottomToolbar().changePage(1);
        this.refresh();
        return true;
    },

    clearFilter: function () {
        this.getStore().baseParams.query = '';
        this.getStore().baseParams.context = '';
        Ext.getCmp('modx-trash-search').reset();
        Ext.getCmp('modx-trash-context').reset();
        this.getBottomToolbar().changePage(1);
        this.refresh();
    },

    purgeResource: function () {
        MODx.msg.confirm({
            minWidth: 500,
            title: _('trash.purge_confirm_title'),
            text: _('trash.purge_confirm_message', {
                'list': this.listResources('')
            }),
            url: this.config.url,
            params: {
                action: 'Resource/Trash/Purge',
                ids: this.menu.record.id
            },
            listeners: {
                'success': {
                    fn: function (data) {
                        this.refreshEverything(data.total);
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
        });
    },

    restoreResource: function () {
        var withPublish = '';
        if (this.menu.record.published) {
            withPublish = '_with_publish';
        }
        MODx.msg.confirm({
            minWidth: 500,
            title: _('trash.restore_confirm_title'),
            text: _('trash.restore_confirm_message' + withPublish, {
                'list': this.listResources('')
            }),
            url: this.config.url,
            params: {
                action: 'Resource/Undelete',
                id: this.menu.record.id
            },
            listeners: {
                'success': {
                    fn: function (data) {
                        this.refreshEverything(data.total);
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
        });
    },

    purgeSelected: function () {
        var cs = this.getSelectedAsList();
        if (cs === false) return false;

        MODx.msg.confirm({
            minWidth: 500,
            title: _('trash.purge_confirm_title'),
            text: _('trash.purge_confirm_message', {
                'list': this.listResources('')
            }),
            url: this.config.url,
            params: {
                action: 'Resource/Trash/Purge',
                ids: cs
            },
            listeners: {
                'success': {
                    fn: function (data) {
                        this.getSelectionModel().clearSelections(true);
                        this.refreshEverything(data.total);
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
        });

        return true;
    },

    restoreSelected: function () {
        var cs = this.getSelectedAsList();
        if (cs === false) return false;

        MODx.msg.confirm({
            minWidth: 500,
            title: _('trash.restore_confirm_title'),
            text: _('trash.restore_confirm_message', {
                'list': this.listResources('')
            }),
            url: this.config.url,
            params: {
                action: 'Resource/Trash/Restore',
                ids: cs
            },
            listeners: {
                'success': {
                    fn: function (data) {
                        this.refreshEverything(data.total);
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
        });
        return true;
    },

    purgeAll: function () {
        var sm = this.getSelectionModel();
        sm.selectAll();
        var cs = this.getSelectedAsList();
        if (cs === false) return false;

        MODx.msg.confirm({
            minWidth: 500,
            title: _('trash.purge_confirm_title'),
            text: _('trash.purge_all_confirm_message', {
                'count': sm.selections.length,
                'list': this.listResources('')
            }),
            url: this.config.url,
            params: {
                action: 'Resource/Trash/Purge',
                // we can't just purge everything, because it might happen that in
                // the meantime something was deleted by another user which is not yet
                // shown in the trash manager list because of missing reload.
                // in that case we would purge something unreviewed/blindly.
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
                            this.refreshEverything(data.total); // no need to refresh if nothing was purged
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
            minWidth: 500,
            title: _('trash.restore_confirm_title'),
            text: _('trash.restore_all_confirm_message', {
                'count': sm.selections.length,
                'list': this.listResources('')
            }),
            url: this.config.url,
            params: {
                action: 'Resource/Trash/Restore',
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
                            this.refreshEverything(data.total); // no need to refresh if nothing was purged
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
        var trashButton = Ext.getCmp('modx-trash-link');

        if (total !== undefined) {
            // if no resource is deleted, we disable the icon.
            // otherwise we have to update the tooltip
            if (total == 0) {
                trashButton.disable();
                trashButton.tooltip = new Ext.ToolTip({
                    target: trashButton.tabEl,
                    title: _('trash.manage_recycle_bin_tooltip')
                });
            } else {
                trashButton.enable();
                trashButton.tooltip = new Ext.ToolTip({
                    target: trashButton.tabEl,
                    title: _('trash.manage_recycle_bin_tooltip', {count: total})
                });
            }
        }
    },

    listResources: function (separator) {
        separator = separator || '';

        // creates a textual representation of the selected resources
        // we create a textlist of the resources here to show them again in the confirmation box
        var selections = this.getSelectionModel().getSelections();
        var text = [], t;
        selections.forEach(function (selection) {
            t = selection.data.parentPath + "<strong>" + selection.data.pagetitle + " (" + selection.data.id + ")" + "</strong>";
            if (selection.data.published) {
                t = '<em>' + t + '</em>';
            }
            t = "<div style='white-space:nowrap'>" + t + "</div>";
            text.push(t);
        });
        return text.join(separator);
    },

    renderTooltip: function (value, metadata, record) {
        if (value) {
            var preview = ((record.json.pagetitle) ? '<p><strong>' + _('pagetitle') + ':</strong> ' + record.json.pagetitle + '</p>' : '')
                + ((record.json.longtitle) ? '<p><strong>' + _('long_title') + ':</strong> ' + record.json.longtitle + '</p>' : '')
                + ((record.data.parentPath) ? '<p><strong>' + _('trash.parent_path') + ':</strong> ' + record.data.parentPath + '</p>' : '')
                + ((record.json.content) ? '<p><strong>' + _('content') + ':</strong> ' + Ext.util.Format.ellipsis(record.json.content.replace(/<\/?[^>]+>/gi, ''), 100) + '</p>' : '');
            preview = Ext.util.Format.htmlEncode(preview);
            return '<div ext:qtip="' + preview + '">' + value + '</div>';
        } else {
            return '';
        }
    }
});
Ext.reg('modx-grid-trash', MODx.grid.Trash);
