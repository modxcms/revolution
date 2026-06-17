/**
 * Loads the resource update page
 *
 * @class MODx.page.UpdateResource
 * @extends MODx.Component
 * @param {Object} config An object of config properties
 * @xtype modx-page-resource-update
 */
MODx.page.UpdateResource = function(config) {
    config = config || { record: {} };
    config.record = config.record || {};
    Ext.apply(config.record, {
        'parent-cmb': config.record.parent
    });
    Ext.applyIf(config, {
        url: MODx.config.connector_url,
        which_editor: 'none',
        formpanel: 'modx-panel-resource',
        id: 'modx-page-update-resource',
        action: 'Resource/Update',
        components: [{
            xtype: config.panelXType || 'modx-panel-resource',
            renderTo: config.panelRenderTo || 'modx-panel-resource-div',
            resource: config.resource,
            record: config.record || {},
            publish_document: config.publish_document,
            show_tvs: config.show_tvs,
            mode: config.mode,
            url: config.url,
            canDelete: config.canDelete,
            locked: config.locked
        }],
        buttons: this.getButtons(config)
    });
    MODx.page.UpdateResource.superclass.constructor.call(this, config);
    if (!Ext.isIE) {
        Ext.EventManager.on(window, 'beforeunload', function(e) {
            MODx.releaseLock(this.config.resource);
            MODx.sleep(400);
            return false;
        }, this);
    }
    new Ext.KeyMap(Ext.getBody(), {
        key: 'p',
        alt: true,
        ctrl: true,
        fn: this.preview,
        scope: this
    });
};
Ext.extend(MODx.page.UpdateResource, MODx.Component, {
    preview: function() {
        window.open(this.config.preview_url);
        return false;
    },

    duplicateResource: function(btn, e) {
        const
            tree = Ext.getCmp('modx-resource-tree'),
            id = this.config.resource,
            node = tree.getNodeById(`${this.config.record.context_key}_${id}`),
            data = {
                resource: id,
                pagetitle: this.config.record.pagetitle,
                hasChildren: false,
                is_folder: this.config.record.isfolder
            }
        ;
        if (node) {
            data.pagetitle = node.ui.textNode.innerText;
            data.hasChildren = node.attributes.hasChildren;
            data.childCount = node.attributes.childCount;
            data.is_folder = node.getUI().hasClass('folder');
        }
        const window = MODx.load({
            xtype: 'modx-window-resource-duplicate',
            resource: id,
            pagetitle: data.pagetitle,
            hasChildren: data.hasChildren,
            childCount: data.childCount,
            redirect: true,
            listeners: {
                success: {
                    fn: function(response) {
                        const responseData = Ext.decode(response.a.response.responseText);
                        if (responseData.object.redirect) {
                            MODx.loadPage('resource/update', `id=${responseData.object.id}`);
                        } else if (node) {
                            node.parentNode.attributes.childCount = parseInt(node.parentNode.attributes.childCount, 10) + 1;
                            tree.refreshNode(node.id);
                        }
                    },
                    scope: this
                }
            }
        });
        window.setValues(data);
        window.show(e.target);
    },

    deleteResource: function(btn, e) {
        MODx.msg.confirm({
            text: _('resource_delete_confirm', {
                resource: `${Ext.util.Format.htmlEncode(this.config.record.pagetitle)} (${this.config.resource})`
            }),
            url: MODx.config.connector_url,
            params: {
                action: 'Resource/Delete',
                id: this.config.resource
            },
            listeners: {
                success: {
                    fn: function(response) {
                        const panel = Ext.getCmp('modx-panel-resource');
                        if (panel) {
                            panel.updatePreviewButton(response.object);
                            panel.handleDeleted(true);
                        }
                        Ext.getCmp('modx-resource-tree')?.refresh();
                        Ext.getCmp('modx-trash-link')?.updateState(+response.object.deletedCount);
                    },
                    scope: this
                }
            }
        });
    },

    unDeleteResource: function() {
        MODx.Ajax.request({
            url: MODx.config.connector_url,
            params: {
                action: 'Resource/Undelete',
                id: this.config.resource
            },
            listeners: {
                success: {
                    fn: function(response) {
                        const panel = Ext.getCmp('modx-panel-resource');
                        if (panel) {
                            panel.updatePreviewButton(response.object);
                            panel.handleDeleted(false);
                        }

                        const tree = Ext.getCmp('modx-resource-tree');
                        if (tree?.rendered) {
                            tree.refresh();
                        }
                        Ext.getCmp('modx-trash-link')?.updateState(+response.object.deletedCount);
                    },
                    scope: this
                }
            }
        });
    },

    purgeResource: function() {
        MODx.msg.confirm({
            text: _('resource_purge_confirm', {
                resource: `${Ext.util.Format.htmlEncode(this.config.record.pagetitle)} (${this.config.resource})`
            }),
            url: MODx.config.connector_url,
            params: {
                action: 'Resource/Trash/Purge',
                ids: this.config.resource
            },
            listeners: {
                success: {
                    fn: function() {
                        const fp = Ext.getCmp(this.config.formpanel);
                        fp.warnUnsavedChanges = false;
                        MODx.loadPage('?');
                    },
                    scope: this
                }
            }
        });
    },

    cancel: function(btn, e) {
        const fp = Ext.getCmp(this.config.formpanel);
        if (fp && fp.isDirty()) {
            Ext.Msg.confirm(_('warning'), _('resource_cancel_dirty_confirm'), function(event) {
                if (event === 'yes') {
                    fp.warnUnsavedChanges = false;
                    MODx.releaseLock(MODx.request.id);
                    MODx.sleep(400);
                    MODx.loadPage('?');
                }
            }, this);
        } else {
            MODx.releaseLock(MODx.request.id);
            MODx.loadPage('?');
        }
    },

    getButtons: function(config) {
        const buttons = [{
            process: 'Resource/Update',
            text: _('save'),
            id: 'modx-abtn-save',
            cls: 'primary-button',
            method: 'remote',
            hidden: !(config.canSave === 1),
            keys: [{
                key: MODx.config.keymap_save || 's',
                ctrl: true
            }]
        }, {
            text: (config.lockedText || '<i class="icon icon-lock"></i>'),
            id: 'modx-abtn-locked',
            handler: Ext.emptyFn,
            hidden: (config.canSave === 1),
            disabled: true
        }];

        if (config.canDuplicate === 1 && (config.record.parent !== parseInt(MODx.config.tree_root_id, 10) || config.canCreateRoot === 1)) {
            buttons.push({
                text: _('duplicate'),
                id: 'modx-abtn-duplicate',
                handler: this.duplicateResource,
                scope: this
            });
        }

        buttons.push({
            text: _('view'),
            id: 'modx-abtn-preview',
            handler: this.preview,
            hidden: config.record.deleted,
            disabled: !config.preview_url.startsWith('http'),
            scope: this
        }, {
            text: _('cancel'),
            id: 'modx-abtn-cancel',
            handler: this.cancel,
            scope: this
        });

        if (config.canDelete === 1 && !config.locked) {
            buttons.push({
                text: '<i class="icon icon-repeat"></i>',
                id: 'modx-abtn-undelete',
                handler: this.unDeleteResource,
                hidden: !config.record.deleted,
                scope: this
            });

            buttons.push({
                text: '<i class="icon icon-trash-o"></i>',
                id: 'modx-abtn-delete',
                handler: this.deleteResource,
                hidden: config.record.deleted,
                scope: this
            });
        }

        if (config.canPurge === 1 && !config.locked) {
            buttons.push({
                text: '<i class="icon icon-remove"></i>',
                id: 'modx-abtn-purge',
                handler: this.purgeResource,
                hidden: !config.record.deleted,
                scope: this
            });
        }

        buttons.push({
            text: '<i class="icon icon-question-circle"></i>',
            id: 'modx-abtn-help',
            handler: MODx.loadHelpPane
        });

        return buttons;
    }
});
Ext.reg('modx-page-resource-update', MODx.page.UpdateResource);
