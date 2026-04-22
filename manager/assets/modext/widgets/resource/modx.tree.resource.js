/**
 * Generates the Resource Tree in Ext
 *
 * @class MODx.tree.Resource
 * @extends MODx.tree.Tree
 * @param {Object} config An object of options.
 * @xtype modx-tree-resource
 */
MODx.tree.Resource = function(config = {}) {
    Ext.applyIf(config, {
        url: MODx.config.connector_url,
        action: 'Resource/GetNodes',
        title: '',
        rootVisible: false,
        expandFirst: true,
        enableDD: (parseInt(MODx.config.enable_dragdrop, 10) !== 0),
        ddGroup: 'modx-treedrop-dd',
        sortAction: 'Resource/Sort',
        sortBy: this.getDefaultSortBy(config),
        tbarCfg: {
            id: config.id ? `${config.id}-tbar` : 'modx-tree-resource-tbar'
        },
        baseParams: {
            sortBy: this.getDefaultSortBy(config),
            currentResource: MODx.request.id || 0,
            currentAction: MODx.request.a || 0
        }
    });
    MODx.tree.Resource.superclass.constructor.call(this, config);
    this.addEvents('loadCreateMenus', 'emptyTrash');
    this.on('afterSort', this._handleAfterDrop, this);
};
Ext.extend(MODx.tree.Resource, MODx.tree.Tree, {
    forms: {},
    windows: {},
    stores: {},

    getToolbar: function() {
        return [];
    },

    _initExpand: function() {
        const treeState = Ext.state.Manager.get(this.treestate_id);
        if ((Ext.isString(treeState) || Ext.isEmpty(treeState)) && this.root) {
            if (this.root) {
                this.root.expand();
            }
            const defaultNode = this.getNodeById('web_0');
            if (defaultNode && this.config.expandFirst) {
                defaultNode.select();
                defaultNode.expand();
            }
        } else {
            // If we have disabled context sort, make sure dragging and dropping is disabled on the root elements
            // in the tree. This corresponds to the context nodes.
            if (parseInt(MODx.config.context_tree_sort, 10) !== 1) {
                if (typeof this.root !== 'undefined' && typeof this.root.childNodes !== 'undefined') {
                    for (let i = 0; i < this.root.childNodes.length; i++) {
                        this.root.childNodes[i].draggable = false;
                    }
                }
            }

            for (let i = 0; i < treeState.length; i++) {
                this.expandPath(treeState[i]);
            }
        }
    },

    /**
     * Shows the current context menu.
     * @param {Ext.tree.TreeNode} n The current node
     * @param {Ext.EventObject} e The event object run.
     */
    _showContextMenu: function(n, e) {
        this.cm.activeNode = n;
        this.cm.removeAll();
        if (n.attributes.menu && n.attributes.menu.items) {
            /** @todo 2025-09-26 This condition appears to be unused; suggest removal unless usage can be indicated */
            this.addContextMenuItem(n.attributes.menu.items);
        } else {
            let menu = [];
            switch (n.attributes.type) {
                case 'MODX\\Revolution\\modResource':
                case 'MODX\\Revolution\\modDocument':
                    menu = this._getModResourceMenu(n);
                    break;
                case 'MODX\\Revolution\\modContext':
                    menu = this._getModContextMenu(n);
                    break;
                // no default
            }

            this.addContextMenuItem(menu);
        }
        this.cm.showAt(e.xy);
        e.stopEvent();
    },

    duplicateResource: function(item, e) {
        const
            node = this.cm.activeNode,
            id = node.id.split('_')[1],
            name = Ext.util.Format.htmlEncode(node.ui.textNode.innerText),
            record = {
                resource: id,
                is_folder: node.getUI().hasClass('folder')
            },
            window = MODx.load({
                xtype: 'modx-window-resource-duplicate',
                resource: id,
                pagetitle: name,
                hasChildren: node.attributes.hasChildren,
                childCount: node.attributes.childCount,
                redirect: false,
                listeners: {
                    success: {
                        fn: function(response) {
                            const responseData = Ext.decode(response.a.response.responseText);
                            if (responseData.object.redirect) {
                                MODx.loadPage('resource/update', `id=${responseData.object.id}`);
                            } else {
                                node.parentNode.attributes.childCount = parseInt(node.parentNode.attributes.childCount, 10) + 1;
                                this.refreshNode(node.id);
                            }
                        },
                        scope: this
                    }
                }
            })
        ;
        window.config.hasChildren = node.attributes.hasChildren;
        window.setValues(record);
        window.show(e.target);
    },

    duplicateContext: function(itm, e) {
        const
            node = this.cm.activeNode,
            key = node.attributes.pk,
            record = {
                key: key,
                newkey: ''
            },
            window = MODx.load({
                xtype: 'modx-window-context-duplicate',
                record: record,
                listeners: {
                    success: {
                        fn: function() {
                            this.refresh();
                        },
                        scope: this
                    }
                }
            })
        ;
        window.show(e.target);
    },

    removeContext: function(itm, e) {
        const
            node = this.cm.activeNode,
            key = node.attributes.pk
        ;
        MODx.msg.confirm({
            title: _('remove_context'),
            text: _('context_remove_confirm'),
            url: MODx.config.connector_url,
            params: {
                action: 'Context/Remove',
                key: key
            },
            listeners: {
                success: {
                    fn: function() {
                        const contextsGrid = Ext.getCmp('modx-grid-context');
                        if (contextsGrid) {
                            contextsGrid.refresh();
                        }
                        this.refresh();
                    },
                    scope: this
                }
            }
        });
    },

    preview: function() {
        window.open(this.cm.activeNode.attributes.preview_url);
    },

    deleteDocument: function() {
        const
            node = this.cm.activeNode,
            id = node.id.split('_')[1],
            resource = Ext.util.Format.htmlEncode(node.ui.textNode.innerText)
        ;
        MODx.msg.confirm({
            text: _('resource_delete_confirm', { resource }),
            url: MODx.config.connector_url,
            params: {
                action: 'Resource/Delete',
                id: id
            },
            listeners: {
                success: {
                    fn: data => {
                        const deletedCount = +data.object.deletedCount;
                        Ext.getCmp('modx-trash-link')?.updateState(deletedCount);

                        const nodeUI = node.getUI();
                        nodeUI.addClass('deleted');
                        node.cascade(childNode => childNode.getUI().addClass('deleted'), this);

                        // Refresh the trash manager if possible
                        Ext.getCmp('modx-trash-resources')?.refresh();

                        Ext.get(nodeUI.getEl()).frame();

                        // Handle deleted resource in update panel
                        const updatePanel = Ext.getCmp('modx-panel-resource');
                        if (updatePanel && MODx.request.a === 'resource/update' && MODx.request.id === id) {
                            updatePanel.handleDeleted(true);
                            updatePanel.updatePreviewButton(data.object);
                        }
                        node.attributes.preview_url = data.object.preview_url;
                    },
                    scope: this
                }
            }
        });
    },

    undeleteDocument: function() {
        const
            node = this.cm.activeNode,
            id = node.id.split('_')[1]
        ;
        MODx.Ajax.request({
            url: MODx.config.connector_url,
            params: {
                action: 'Resource/Undelete',
                id: id
            },
            listeners: {
                success: {
                    fn: function(response) {
                        const deletedCount = +response.object.deletedCount;
                        Ext.getCmp('modx-trash-link')?.updateState(deletedCount);

                        const activeNodeUI = node.getUI();

                        activeNodeUI.removeClass('deleted');
                        node.cascade(childNode => childNode.getUI().removeClass('deleted'), this);

                        const trashResourcesPanel = Ext.getCmp('modx-trash-resources');
                        if (trashResourcesPanel) {
                            trashResourcesPanel.refresh();
                        }

                        Ext.get(activeNodeUI.getEl()).frame();

                        const updatePanel = Ext.getCmp('modx-panel-resource');
                        if (updatePanel && MODx.request.a === 'resource/update' && MODx.request.id === id) {
                            updatePanel.handleDeleted(false);
                            updatePanel.updatePreviewButton(response.object);
                        }
                        node.attributes.preview_url = response.object.preview_url;
                    },
                    scope: this
                }
            }
        });
    },

    purgeDocument: function(itm, e) {
        const
            node = this.cm.activeNode,
            id = node.id.split('_')[1],
            name = Ext.util.Format.htmlEncode(node.ui.textNode.innerText)
        ;
        MODx.msg.confirm({
            text: _('resource_purge_confirm', {
                resource: `${name} (${id})`
            }),
            url: MODx.config.connector_url,
            params: {
                action: 'Resource/Trash/Purge',
                ids: id
            },
            listeners: {
                success: {
                    fn: function(data) {
                        if (MODx.request.a === 'resource/update' && MODx.request.id === id) {
                            const updatePanel = Ext.getCmp('modx-panel-resource');
                            if (updatePanel) {
                                updatePanel.warnUnsavedChanges = false;
                            }
                            MODx.loadPage('?');

                            return;
                        }

                        Ext.getCmp('modx-trash-link')?.updateState(+data.object.deletedCount);

                        node.remove();

                        // refresh the trash manager if possible
                        Ext.getCmp('modx-trash-resources')?.refresh();

                        MODx.msg.status({
                            title: _('success'),
                            message: data.message
                        });
                    },
                    scope: this
                }
            }
        });
    },

    publishDocument: function(itm, e) {
        const
            node = this.cm.activeNode,
            id = node.id.split('_')[1]
        ;
        MODx.msg.confirm({
            title: _('resource_publish'),
            text: _('resource_publish_confirm'),
            url: MODx.config.connector_url,
            params: {
                action: 'Resource/Publish',
                id: id
            },
            listeners: {
                success: {
                    fn: function() {
                        const ui = this.cm.activeNode.getUI();
                        ui.removeClass('unpublished');
                        Ext.get(ui.getEl()).frame();
                    },
                    scope: this
                }
            }
        });
    },

    unpublishDocument: function(itm, e) {
        const
            node = this.cm.activeNode,
            id = node.id.split('_')[1]
        ;
        MODx.msg.confirm({
            title: _('resource_unpublish'),
            text: _('resource_unpublish_confirm'),
            url: MODx.config.connector_url,
            params: {
                action: 'Resource/Unpublish',
                id: id
            },
            listeners: {
                success: {
                    fn: function() {
                        const ui = this.cm.activeNode.getUI();
                        ui.addClass('unpublished');
                        Ext.get(ui.getEl()).frame();
                    },
                    scope: this
                }
            }
        });
    },

    getDefaultSortBy: function(config) {
        let sortBy = 'menuindex';
        if (!Ext.isEmpty(config) && !Ext.isEmpty(config.sortBy)) {
            sortBy = config.sortBy;
        } else {
            const savedSort = Ext.state.Manager.get(`${this.treestate_id}-sort-default`);
            if (savedSort !== MODx.config.tree_default_sort) {
                sortBy = MODx.config.tree_default_sort;
                Ext.state.Manager.set(`${this.treestate_id}-sort-default`, sortBy);
                Ext.state.Manager.set(`${this.treestate_id}-sort`, sortBy);
            } else {
                sortBy = Ext.state.Manager.get(`${this.treestate_id}-sort`) || MODx.config.tree_default_sort;
            }
        }
        return sortBy;
    },

    filterSort: function(itm, e) {
        this.getLoader().baseParams = {
            action: this.config.action,
            sortBy: itm.sortBy,
            sortDir: itm.sortDir,
            node: this.cm.activeNode.ide
        };
        this.refreshActiveNode();
    },

    hideFilter: function(itm, e) {
        this.filterBar.destroy();
        this._filterVisible = false;
    },

    _handleAfterDrop: function(o, r) {
        const
            targetNode = o.event.target,
            { dropNode } = o.event
        ;
        if (o.event.point === 'append' && targetNode) {
            const ui = targetNode.getUI();
            ui.addClass('haschildren');
            ui.removeClass('icon-resource');
        }
        if ((MODx.request.a === 'resource/update')) {
            if (dropNode.attributes.pk === MODx.request.id) {
                const
                    parentFieldCmb = Ext.getCmp('modx-resource-parent'),
                    parentFieldHidden = Ext.getCmp('modx-resource-parent-hidden')
                ;
                if (parentFieldCmb && parentFieldHidden) {
                    parentFieldHidden.setValue(dropNode.parentNode.attributes.pk);
                    parentFieldCmb.setValue(dropNode.parentNode.attributes.text.replace(/(<([^>]+)>)/ig, ''));
                }
            }
            const
                menuindexField = Ext.getCmp('modx-resource-menuindex'),
                isfolderFieldCmb = Ext.getCmp('modx-resource-isfolder')
            ;
            if (menuindexField && o.result.object.menuindex !== undefined) {
                menuindexField.setValue(o.result.object.menuindex);
            }
            if (isfolderFieldCmb && typeof o.result.object.isfolder === 'boolean') {
                isfolderFieldCmb.setValue(o.result.object.isfolder);
            }
        }
    },

    _handleDrop: function(e) {
        const
            { dropNode } = e,
            targetParent = e.target;
        if (targetParent.findChild('id', dropNode.attributes.id) !== null) {
            return false;
        }

        if (
            dropNode.attributes.type === 'modContext'
            && (targetParent.getDepth() > 1 || (targetParent.attributes.id === `${targetParent.attributes.pk}_0` && e.point === 'append'))
        ) {
            return false;
        }

        if (dropNode.attributes.type !== 'modContext' && targetParent.getDepth() <= 1 && e.point !== 'append') {
            return false;
        }

        /** @var {String|Number} resourceTypeAllowsDrop Value, if present, indicates whether the class of the currently dragged Resource (fully-qualified path, e.g., Collections\Model\CollectionContainer) allows drop behavior; applies to custom Resource classes only */
        const resourceTypeAllowsDrop = MODx.config.resource_classes_drop[targetParent.attributes.classKey];
        if (resourceTypeAllowsDrop === undefined) {
            if (targetParent.attributes.hide_children_in_tree) {
                return false;
            }
        } else if (parseInt(resourceTypeAllowsDrop, 10) === 0) {
            return false;
        }

        return dropNode.attributes.text !== 'root' && dropNode.attributes.text !== ''
            && targetParent.attributes.text !== 'root' && targetParent.attributes.text !== '';
    },

    /**
     * Gets the value of the specified system setting based the setting in the Resource's
     * Context (or the Context itself if it was clicked on). Return value is used specifically for the
     * Quick Create contextual action to automatically apply it in the editing form.
     *
     * @param {Ext.tree.AsyncTreeNode} node The node being right-clicked on to initiate Quick Create
     * @param {String} contextKey The Context being acted on (e.g., web)
     * @param {String} setting The requested setting name (e.g., default_template)
     * @param {?String|?Number} defaultValue The global system setting value
     * @returns {?String|?Number}
     */
    getContextSettingForNode: function(node, contextKey, setting, defaultValue) {
        let value = defaultValue || null;

        /** @todo 2025-09-26 Check whether this if/else check actually does anything; when a Context is clicked on, the type is 'MODX\Revolution\modContext' not 'modContext'. Probably only need what is inside the if block */
        if (node.attributes.type !== 'modContext') {
            const
                tree = node.getOwnerTree(),
                rootNode = tree.getRootNode(),
                contextNode = rootNode.findChild('ctx', contextKey, false);
            if (contextNode) {
                value = contextNode.attributes.settings[setting];
            }
        } else {
            value = node.attributes.settings[setting];
        }
        return value;
    },

    quickCreate: function(itm, e, cls, ctx, p) {
        cls = cls || 'MODX\\Revolution\\modDocument';
        const
            { activeNode } = this.cm,
            record = {
                class_key: cls,
                context_key: ctx || 'web',
                parent: p || 0
            },
            settings = {
                template: 'default_template',
                richtext: 'richtext_default',
                hidemenu: 'hidemenu_default',
                searchable: 'search_default',
                cacheable: 'cache_default',
                published: 'publish_default',
                content_type: 'default_content_type'
            }
        ;

        Object.keys(settings).forEach(key => {
            const recordValue = this.getContextSettingForNode(activeNode, ctx, settings[key], MODx.config[settings[key]]);
            record[key] = !Ext.isEmpty(recordValue)
                ? parseInt(recordValue, 10)
                : recordValue
            ;
        });

        const window = MODx.load({
            xtype: 'modx-window-quick-create-modResource',
            record: record,
            listeners: {
                success: {
                    fn: function() {
                        this.refreshNode(this.cm.activeNode.id, this.cm.activeNode.childNodes.length > 0);
                    },
                    scope: this
                },
                hide: {
                    fn: function() {
                        this.destroy();
                    }
                },
                show: {
                    fn: function() {
                        this.center();
                    }
                }
            }
        });
        window.setValues(record);
        window.show(e.target, () => {
            // eslint-disable-next-line no-unused-expressions
            Ext.isSafari ? window.setPosition(null, 30) : window.center();
        }, this);
    },

    quickUpdate: function(itm, e, cls) {
        MODx.Ajax.request({
            url: MODx.config.connector_url,
            params: {
                action: 'Resource/Get',
                id: this.cm.activeNode.attributes.pk,
                skipFormatDates: true
            },
            listeners: {
                success: {
                    fn: function(response) {
                        const resourceRecord = response.object;
                        resourceRecord.class_key = cls;

                        const window = MODx.load({
                            xtype: 'modx-window-quick-update-modResource',
                            record: resourceRecord,
                            listeners: {
                                success: {
                                    fn: function(windowResponse) {
                                        this.refreshNode(this.cm.activeNode.id);
                                        const
                                            pageTitle = windowResponse.f.findField('pagetitle').getValue(),
                                            newTitle = `<span dir="ltr">${pageTitle} (${window.record.id})</span>`
                                        ;
                                        window.setTitle(window.title.replace(/<span.*\/span>/, newTitle));
                                    },
                                    scope: this
                                },
                                hide: { fn: function() { this.destroy(); } }
                            }
                        });
                        window.title += `: <span dir="ltr">${Ext.util.Format.htmlEncode(window.record.pagetitle)} (${window.record.id})</span>`;
                        window.setValues(response.object);
                        window.show(e.target, () => {
                            // eslint-disable-next-line no-unused-expressions
                            Ext.isSafari ? window.setPosition(null, 30) : window.center();
                        }, this);
                    },
                    scope: this
                }
            }
        });
    },

    _getModContextMenu: function(node) {
        const
            nodeAttributes = node.attributes,
            ui = node.getUI(),
            menu = []
        ;
        menu.push({
            text: `<b>${nodeAttributes.text} (${nodeAttributes.ctx})</b>`,
            handler: function() {
                return false;
            },
            header: true
        });
        menu.push('-');
        menu.push({
            text: _('refresh_context'),
            handler: function() {
                this.refreshNode(this.cm.activeNode.id, true);
            }
        });
        if (ui.hasClass('pedit')) {
            menu.push({
                text: _('edit_context'),
                handler: function() {
                    const { attributes } = this.cm.activeNode;
                    this.loadAction(`a=context/update&key=${attributes.pk}`);
                }
            });
        }
        if (ui.hasClass('pnew')) {
            menu.push({
                text: _('duplicate_context'),
                handler: this.duplicateContext
            });
        }
        if (ui.hasClass('pdelete')) {
            menu.push('-');
            menu.push({
                text: _('remove_context'),
                handler: this.removeContext
            });
        }
        if (ui.hasClass('pnewdoc')) {
            menu.push('-');
            this._getCreateMenus(menu, '0', ui);
        }
        if (!ui.hasClass('x-tree-node-leaf')) {
            menu.push('-');
            menu.push(this._getSortMenu());
        }

        return menu;
    },

    overviewResource: function() {
        this.loadAction('a=resource/data');
    },

    quickUpdateResource: function(itm, e) {
        this.quickUpdate(itm, e, itm.classKey);
    },

    editResource: function() {
        this.loadAction('a=resource/update');
    },

    _getModResourceMenu: function(node) {
        const
            nodeAttributes = node.attributes,
            ui = node.getUI(),
            menu = []
        ;
        menu.push({
            text: `<b>${nodeAttributes.text}</b>`,
            handler: function() { return false; },
            header: true
        });
        menu.push('-');
        if (ui.hasClass('pview')) {
            menu.push({
                text: _('resource_overview'),
                handler: this.overviewResource
            });
        }
        if (ui.hasClass('pedit')) {
            menu.push({
                text: _('resource_edit'),
                handler: this.editResource
            });
        }
        if (ui.hasClass('pqupdate')) {
            menu.push({
                text: _('quick_update_resource'),
                classKey: nodeAttributes.classKey,
                handler: this.quickUpdateResource
            });
        }
        if (ui.hasClass('pduplicate')) {
            menu.push({
                text: _('resource_duplicate'),
                handler: this.duplicateResource
            });
        }
        menu.push({
            text: _('resource_refresh'),
            handler: this.refreshResource,
            scope: this
        });

        if (ui.hasClass('pnew')) {
            menu.push('-');
            this._getCreateMenus(menu, null, ui);
        }

        if (ui.hasClass('psave')) {
            menu.push('-');
            if (ui.hasClass('ppublish') && ui.hasClass('unpublished')) {
                menu.push({
                    text: _('resource_publish'),
                    handler: this.publishDocument
                });
            } else if (ui.hasClass('punpublish')) {
                menu.push({
                    text: _('resource_unpublish'),
                    handler: this.unpublishDocument
                });
            }
            if (ui.hasClass('pundelete') && ui.hasClass('deleted')) {
                menu.push({
                    text: _('resource_undelete'),
                    handler: this.undeleteDocument
                });
                menu.push({
                    text: _('resource_purge'),
                    handler: this.purgeDocument
                });
            } else if (ui.hasClass('pdelete') && !ui.hasClass('deleted')) {
                menu.push({
                    text: _('resource_delete'),
                    handler: this.deleteDocument
                });
            }
        }

        if (!ui.hasClass('x-tree-node-leaf')) {
            menu.push('-');
            menu.push(this._getSortMenu());
        }

        if (ui.hasClass('pview')) {
            menu.push('-');
            menu.push({
                text: _('resource_view'),
                disabled: nodeAttributes.preview_url === '',
                handler: this.preview
            });
        }
        return menu;
    },

    refreshResource: function() {
        this.refreshNode(this.cm.activeNode.id);
    },

    createResourceHere: function(itm) {
        const
            nodeAttributes = this.cm.activeNode.attributes,
            parentId = itm.usePk ? itm.usePk : nodeAttributes.pk
        ;
        if (parseInt(MODx.config.enable_template_picker_in_tree, 10)) {
            MODx.createResource({
                class_key: itm.classKey,
                parent: parentId,
                context_key: nodeAttributes.ctx || MODx.config.default_context
            });
        } else {
            this.loadAction(
                `a=resource/create&class_key=${itm.classKey}&parent=${parentId}${nodeAttributes.ctx ? `&context_key=${nodeAttributes.ctx}` : ''}`
            );
        }
    },

    createResource: function(itm, e) {
        const
            nodeAttributes = this.cm.activeNode.attributes,
            parentId = itm.usePk ? itm.usePk : nodeAttributes.pk
        ;
        this.quickCreate(itm, e, itm.classKey, nodeAttributes.ctx, parentId);
    },

    _getCreateMenus: function(menu, pk, ui) {
        const
            /** @var {Object} types Contains a set of Resource class types (e.g., MODX\Revolution\modDocument) with menu text config for each */
            types = MODx.config.resource_classes,
            o = this.fireEvent('loadCreateMenus', types)
        ;
        if (Ext.isObject(o)) {
            Ext.apply(types, o);
        }
        const
            coreTypes = [
                'MODX\\Revolution\\modDocument',
                'MODX\\Revolution\\modWebLink',
                'MODX\\Revolution\\modSymLink',
                'MODX\\Revolution\\modStaticResource'
            ],
            createMenuItems = [],
            quickCreateMenuItems = []
        ;
        Object.keys(types).forEach(resourceType => {
            const shortType = resourceType.split('\\').pop();
            if (coreTypes.includes(resourceType)) {
                if (!ui.hasClass(`pnew_${shortType}`)) {
                    /** @todo 2025-09-26 May not need this if check, as it looks like only the permitted Resource classes are made available in MODx.config.resource_classes  */
                    return;
                }
            }
            createMenuItems.push({
                text: types[resourceType].text_create_here,
                classKey: resourceType,
                usePk: !Ext.isEmpty(pk) ? pk : false,
                handler: this.createResourceHere,
                scope: this
            });
            if (ui && ui.hasClass('pqcreate')) {
                quickCreateMenuItems.push({
                    text: types[resourceType].text_create,
                    classKey: resourceType,
                    handler: this.createResource,
                    scope: this
                });
            }
        });
        menu.push({
            text: _('create'),
            handler: function() {
                return false;
            },
            menu: {
                items: createMenuItems
            }
        });
        if (ui && ui.hasClass('pqcreate')) {
            menu.push({
                text: _('quick_create'),
                handler: function() {
                    return false;
                },
                menu: {
                    items: quickCreateMenuItems
                }
            });
        }
        return menu;
    },

    /**
     * Handles all drag events into the tree.
     * @param {Object} dropEvent The node dropped on the parent node.
     */
    _handleDrag: function(dropEvent) {
        function simplifyNodes(node) {
            const
                resultNode = {},
                { childNodes } = node,
                countChildNodes = childNodes.length
            ;
            for (let i = 0; i < countChildNodes; i++) {
                resultNode[childNodes[i].id] = simplifyNodes(childNodes[i]);
            }
            return resultNode;
        }

        const encNodes = Ext.encode(simplifyNodes(dropEvent.tree.root));
        this.fireEvent('beforeSort', encNodes);
        MODx.Ajax.request({
            url: this.config.url,
            params: {
                target: dropEvent.target.attributes.id,
                activeTarget: MODx.request.a === 'resource/update' ? MODx.request.id : '',
                source: dropEvent.source.dragData.node.attributes.id,
                point: dropEvent.point,
                data: encodeURIComponent(encNodes),
                action: this.config.sortAction || 'sort'
            },
            listeners: {
                success: {
                    fn: function(response) {
                        const el = dropEvent.dropNode.getUI().getTextEl();
                        if (el) {
                            Ext.get(el).frame();
                        }
                        this.fireEvent('afterSort', {
                            event: dropEvent,
                            result: response
                        });
                    },
                    scope: this
                },
                failure: {
                    fn: function(response) {
                        MODx.form.Handler.errorJSON(response);
                        this.refresh();
                        return false;
                    },
                    scope: this
                }
            }
        });
    },

    _getSortMenu: function() {
        return [{
            text: _('sort_by'),
            handler: function() { return false; },
            menu: {
                items: [{
                    text: _('tree_order'),
                    sortBy: 'menuindex',
                    sortDir: 'ASC',
                    handler: this.filterSort,
                    scope: this
                }, {
                    text: _('recently_updated'),
                    sortBy: 'editedon',
                    sortDir: 'ASC',
                    handler: this.filterSort,
                    scope: this
                }, {
                    text: _('newest'),
                    sortBy: 'createdon',
                    sortDir: 'DESC',
                    handler: this.filterSort,
                    scope: this
                }, {
                    text: _('oldest'),
                    sortBy: 'createdon',
                    sortDir: 'ASC',
                    handler: this.filterSort,
                    scope: this
                }, {
                    text: _('publish_date'),
                    sortBy: 'pub_date',
                    sortDir: 'ASC',
                    handler: this.filterSort,
                    scope: this
                }, {
                    text: _('unpublish_date'),
                    sortBy: 'unpub_date',
                    sortDir: 'ASC',
                    handler: this.filterSort,
                    scope: this
                }, {
                    text: _('publishedon'),
                    sortBy: 'publishedon',
                    sortDir: 'ASC',
                    handler: this.filterSort,
                    scope: this
                }, {
                    text: _('title'),
                    sortBy: 'pagetitle',
                    sortDir: 'ASC',
                    handler: this.filterSort,
                    scope: this
                }, {
                    text: _('alias'),
                    sortBy: 'alias',
                    sortDir: 'ASC',
                    handler: this.filterSort,
                    scope: this
                }]
            }
        }];
    },

    handleCreateClick: function(node) {
        this.cm.activeNode = node;
        const itm = {
            usePk: '0',
            classKey: 'MODX\\Revolution\\modDocument'
        };

        this.createResourceHere(itm);
    },

    handleDirectCreateClick: function(node) {
        this.cm.activeNode = node;
        this.createResourceHere({
            classKey: 'MODX\\Revolution\\modDocument'
        });
    },

    /**
     * Renders the item text without any special formatting. The Resource/GetNodes processor already protects against XSS.
     */
    renderItemText: function(item) {
        return item.text;
    }
});
Ext.reg('modx-tree-resource', MODx.tree.Resource);

MODx.window.QuickCreateResource = function(config = {}) {
    this.ident = config.ident || `window-quick-create-resource-${Ext.id()}`;
    Ext.applyIf(config, {
        title: _('quick_create_resource'),
        id: this.ident,
        bwrapCssClass: 'x-window-with-tabs',
        width: 700,
        layout: 'anchor',
        url: MODx.config.connector_url,
        action: 'Resource/Create',
        fields: [{
            xtype: 'modx-tabs',
            bodyStyle: { background: 'transparent' },
            border: true,
            deferredRender: false,
            autoHeight: false,
            autoScroll: false,
            anchor: '100% 100%',
            items: [{
                title: _('resource'),
                layout: 'form',
                cls: 'modx-panel',
                autoHeight: false,
                anchor: '100% 100%',
                labelWidth: 100,
                items: [{
                    xtype: 'hidden',
                    name: 'id'
                }, {
                    layout: 'column',
                    border: false,
                    items: [{
                        columnWidth: 0.6,
                        border: false,
                        layout: 'form',
                        items: [{
                            xtype: 'textfield',
                            name: 'pagetitle',
                            id: `modx-${this.ident}-pagetitle`,
                            fieldLabel: _('resource_pagetitle'),
                            description: `<b>[[*pagetitle]]</b><br>${_('resource_pagetitle_help')}`,
                            anchor: '100%',
                            allowBlank: false
                        }, {
                            xtype: 'textfield',
                            name: 'longtitle',
                            id: `modx-${this.ident}-longtitle`,
                            fieldLabel: _('resource_longtitle'),
                            description: `<b>[[*longtitle]]</b><br>${_('resource_longtitle_help')}`,
                            anchor: '100%'
                        }, {
                            xtype: 'textarea',
                            name: 'description',
                            id: `modx-${this.ident}-description`,
                            fieldLabel: _('resource_description'),
                            description: `<b>[[*description]]</b><br>${_('resource_description_help')}`,
                            anchor: '100%',
                            grow: false,
                            height: 50
                        }, {
                            xtype: 'textarea',
                            name: 'introtext',
                            id: `modx-${this.ident}-introtext`,
                            fieldLabel: _('resource_summary'),
                            description: `<b>[[*introtext]]</b><br>${_('resource_summary_help')}`,
                            anchor: '100%',
                            height: 50
                        }]
                    }, {
                        columnWidth: 0.4,
                        border: false,
                        layout: 'form',
                        items: [{
                            xtype: 'modx-combo-template',
                            name: 'template',
                            id: `modx-${this.ident}-template`,
                            fieldLabel: _('resource_template'),
                            description: `<b>[[*template]]</b><br>${_('resource_template_help')}`,
                            editable: false,
                            anchor: '100%',
                            baseParams: {
                                action: 'Element/Template/GetList',
                                combo: true
                            },
                            value: MODx.config.default_template
                        }, {
                            xtype: 'textfield',
                            name: 'alias',
                            id: `modx-${this.ident}-alias`,
                            fieldLabel: _('resource_alias'),
                            description: `<b>[[*alias]]</b><br>${_('resource_alias_help')}`,
                            anchor: '100%'
                        }, {
                            xtype: 'textfield',
                            name: 'menutitle',
                            id: `modx-${this.ident}-menutitle`,
                            fieldLabel: _('resource_menutitle'),
                            description: `<b>[[*menutitle]]</b><br>${_('resource_menutitle_help')}`,
                            anchor: '100%'
                        }, {
                            xtype: 'textfield',
                            fieldLabel: _('resource_link_attributes'),
                            description: `<b>[[*link_attributes]]</b><br>${_('resource_link_attributes_help')}`,
                            name: 'link_attributes',
                            id: `modx-${this.ident}-attributes`,
                            maxLength: 255,
                            anchor: '100%'
                        }, {
                            xtype: 'xcheckbox',
                            boxLabel: _('resource_hide_from_menus'),
                            description: `<b>[[*hidemenu]]</b><br>${_('resource_hide_from_menus_help')}`,
                            hideLabel: true,
                            name: 'hidemenu',
                            id: `modx-${this.ident}-hidemenu`,
                            inputValue: 1,
                            checked: parseInt(MODx.config.hidemenu_default, 10) === 1 ? 1 : 0
                        }, {
                            xtype: 'xcheckbox',
                            boxLabel: _('resource_published'),
                            description: `<b>[[*published]]</b><br>${_('resource_published_help')}`,
                            hideLabel: true,
                            name: 'published',
                            id: `modx-${this.ident}-published`,
                            inputValue: 1,
                            checked: parseInt(MODx.config.publish_default, 10) === 1 ? 1 : 0
                        }, {
                            xtype: 'xcheckbox',
                            boxLabel: _('deleted'),
                            description: `<b>[[*deleted]]</b><br>${_('resource_delete')}`,
                            hideLabel: true,
                            name: 'deleted',
                            id: `modx-${this.ident}-deleted`,
                            inputValue: 1,
                            checked: parseInt(MODx.config.deleted_default, 10) === 1 ? 1 : 0
                        }]
                    }]
                }, MODx.getQuickCreateResourceContentField(this.ident, config.record.class_key)]
            }, {
                id: `modx-${this.ident}-settings`,
                title: _('settings'),
                layout: 'form',
                cls: 'modx-panel',
                autoHeight: true,
                forceLayout: true,
                labelWidth: 100,
                defaults: {
                    autoHeight: true,
                    border: false
                },
                items: MODx.getQuickCreateResourceSettingsFields(this.ident, config.record)
            }]
        }],
        keys: [{
            key: Ext.EventObject.ENTER,
            shift: true,
            fn: this.submit,
            scope: this
        }]
    });
    MODx.window.QuickCreateResource.superclass.constructor.call(this, config);
};
Ext.extend(MODx.window.QuickCreateResource, MODx.Window);
Ext.reg('modx-window-quick-create-modResource', MODx.window.QuickCreateResource);

MODx.window.QuickUpdateResource = function(config = {}) {
    this.ident = config.ident || `window-quick-update-resource-${Ext.id()}`;
    Ext.applyIf(config, {
        title: _('quick_update_resource'),
        id: this.ident,
        action: 'Resource/Update',
        buttons: [{
            text: config.cancelBtnText || _('cancel'),
            scope: this,
            handler: function() { this.hide(); }
        }, {
            text: config.saveBtnText || _('save'),
            scope: this,
            handler: function() { this.submit(false); }
        }, {
            text: config.saveBtnText || _('save_and_close'),
            cls: 'primary-button',
            scope: this,
            handler: this.submit
        }]
    });
    MODx.window.QuickUpdateResource.superclass.constructor.call(this, config);
};
Ext.extend(MODx.window.QuickUpdateResource, MODx.window.QuickCreateResource);
Ext.reg('modx-window-quick-update-modResource', MODx.window.QuickUpdateResource);

MODx.getQuickCreateResourceContentField = function(id = 'quick-update-resource', cls = 'MODX\\Revolution\\modDocument') {
    let field = {};
    switch (cls) {
        case 'MODX\\Revolution\\modSymLink':
            field = {
                xtype: 'textfield',
                fieldLabel: _('symlink'),
                name: 'content',
                id: `modx-${id}-content`,
                anchor: '100%',
                maxLength: 255
            };
            break;
        case 'MODX\\Revolution\\modWebLink':
            field = {
                xtype: 'textfield',
                fieldLabel: _('weblink'),
                name: 'content',
                id: `modx-${id}-content`,
                anchor: '100%',
                maxLength: 255,
                value: ''
            };
            break;
        case 'MODX\\Revolution\\modStaticResource':
            field = {
                xtype: 'modx-combo-browser',
                browserEl: 'modx-browser',
                prependPath: false,
                prependUrl: false,
                fieldLabel: _('static_resource'),
                name: 'content',
                id: `modx-${id}-content`,
                anchor: '100%',
                maxLength: 255,
                value: '',
                listeners: {
                    select: {
                        fn: function(data) {
                            if (data.url.substring(0, 1) === '/') {
                                Ext.getCmp(`modx-${id}-content`).setValue(data.url.substring(1));
                            }
                        },
                        scope: this
                    }
                }
            };
            break;
        case 'MODX\\Revolution\\modResource':
        case 'MODX\\Revolution\\modDocument':
        default:
            field = {
                xtype: 'textarea',
                name: 'content',
                id: `modx-${id}-content`,
                fieldLabel: _('content'),
                labelSeparator: '',
                anchor: '100%',
                style: 'min-height: 200px',
                grow: true
            };
            break;
    }
    return field;
};

/**
 * Gets the form fields for the Quick Create Resource settings tab
 *
 * @param {String} id The currently-active quick create window id
 * @param {Object} parentData Subset of record data relating to the clicked upon Resource or Context
 * @returns Ext configuration for settings fields
 */
MODx.getQuickCreateResourceSettingsFields = function(id, parentData) {
    id = id || 'window-quick-create-resource-default';
    return [{
        layout: 'column',
        border: false,
        anchor: '100%',
        defaults: {
            labelSeparator: '',
            labelAlign: 'top',
            border: false,
            layout: 'form'
        },
        items: [{
            columnWidth: 0.5,
            items: [{
                xtype: 'hidden',
                name: 'parent',
                id: `modx-${id}-parent`,
                value: parentData.parent
            }, {
                xtype: 'hidden',
                name: 'context_key',
                id: `modx-${id}-context_key`,
                value: parentData.context_key
            }, {
                xtype: 'hidden',
                name: 'class_key',
                id: `modx-${id}-class_key`,
                value: parentData.class_key
            }, {
                xtype: 'hidden',
                name: 'publishedon',
                id: `modx-${id}-publishedon`,
                value: parentData.publishedon
            }, {
                xtype: 'modx-field-parent-change',
                fieldLabel: _('resource_parent'),
                description: `<b>[[*parent]]</b><br>${_('resource_parent_help')}`,
                name: 'parent-cmb',
                id: `modx-${id}-parent-change`,
                value: parentData.parent || 0,
                anchor: '100%',
                parentcmp: `modx-${id}-parent`,
                contextcmp: `modx-${id}-context_key`,
                currentid: parentData.id || 0
            }, {
                xtype: 'modx-combo-class-derivatives',
                fieldLabel: _('resource_type'),
                description: '<b>[[*class_key]]</b><br>',
                name: 'class_key',
                hiddenName: 'class_key',
                id: `modx-${id}-class-key`,
                anchor: '100%',
                value: parentData.class_key !== undefined ? parentData.class_key : 'MODX\\Revolution\\modDocument'
            }, {
                xtype: 'modx-combo-content-type',
                fieldLabel: _('resource_content_type'),
                description: `<b>[[*content_type]]</b><br>${_('resource_content_type_help')}`,
                name: 'content_type',
                hiddenName: 'content_type',
                id: `modx-${id}-type`,
                anchor: '100%',
                value: parentData.content_type !== undefined ? parentData.content_type : (MODx.config.default_content_type || 1)

            }, {
                xtype: 'modx-combo-content-disposition',
                fieldLabel: _('resource_contentdispo'),
                description: `<b>[[*content_dispo]]</b><br>${_('resource_contentdispo_help')}`,
                name: 'content_dispo',
                hiddenName: 'content_dispo',
                id: `modx-${id}-dispo`,
                anchor: '100%',
                value: parentData.content_dispo !== undefined ? parentData.content_dispo : 0
            }, {
                xtype: 'numberfield',
                fieldLabel: _('resource_menuindex'),
                description: `<b>[[*menuindex]]</b><br>${_('resource_menuindex_help')}`,
                name: 'menuindex',
                id: `modx-${id}-menuindex`,
                width: 75,
                value: parentData.menuindex || 0
            }]
        }, {
            columnWidth: 0.5,
            items: [{
                xtype: 'xdatetime',
                fieldLabel: _('resource_publishedon'),
                description: `<b>[[*publishedon]]</b><br>${_('resource_publishedon_help')}`,
                name: 'publishedon',
                id: `modx-${id}-publishedon`,
                allowBlank: true,
                dateFormat: MODx.config.manager_date_format,
                timeFormat: MODx.config.manager_time_format,
                startDay: parseInt(MODx.config.manager_week_start, 10),
                dateWidth: 153,
                timeWidth: 153,
                offset_time: MODx.config.server_offset_time,
                value: parentData.publishedon
            }, {
                xtype: parentData.canpublish ? 'xdatetime' : 'hidden',
                fieldLabel: _('resource_publishdate'),
                description: `<b>[[*pub_date]]</b><br>${_('resource_publishdate_help')}`,
                name: 'pub_date',
                id: `modx-${id}-pub-date`,
                allowBlank: true,
                dateFormat: MODx.config.manager_date_format,
                timeFormat: MODx.config.manager_time_format,
                startDay: parseInt(MODx.config.manager_week_start, 10),
                dateWidth: 153,
                timeWidth: 153,
                offset_time: MODx.config.server_offset_time,
                value: parentData.pub_date
            }, {
                xtype: parentData.canpublish ? 'xdatetime' : 'hidden',
                fieldLabel: _('resource_unpublishdate'),
                description: `<b>[[*unpub_date]]</b><br>${_('resource_unpublishdate_help')}`,
                name: 'unpub_date',
                id: `modx-${id}-unpub-date`,
                allowBlank: true,
                dateFormat: MODx.config.manager_date_format,
                timeFormat: MODx.config.manager_time_format,
                startDay: parseInt(MODx.config.manager_week_start, 10),
                dateWidth: 153,
                timeWidth: 153,
                offset_time: MODx.config.server_offset_time,
                value: parentData.unpub_date
            }, {
                xtype: 'xcheckbox',
                boxLabel: _('resource_folder'),
                description: _('resource_folder_help'),
                hideLabel: true,
                name: 'isfolder',
                id: `modx-${id}-isfolder`,
                inputValue: 1,
                checked: parentData.isfolder !== undefined ? parentData.isfolder : false
            }, {
                xtype: 'xcheckbox',
                boxLabel: _('resource_show_in_tree'),
                description: _('resource_show_in_tree_help'),
                hideLabel: true,
                name: 'show_in_tree',
                id: `modx-${id}-show_in_tree`,
                inputValue: 1,
                checked: parentData.show_in_tree !== undefined ? parentData.show_in_tree : 1
            }, {
                xtype: 'xcheckbox',
                boxLabel: _('resource_hide_children_in_tree'),
                description: _('resource_hide_children_in_tree_help'),
                hideLabel: true,
                name: 'hide_children_in_tree',
                id: `modx-${id}-hide_children_in_tree`,
                inputValue: 1,
                checked: parentData.hide_children_in_tree !== undefined ? parentData.hide_children_in_tree : false
            }, {
                xtype: 'xcheckbox',
                boxLabel: _('resource_alias_visible'),
                description: _('resource_alias_visible_help'),
                hideLabel: true,
                name: 'alias_visible',
                id: `modx-${id}-alias-visible`,
                inputValue: 1,
                checked: parentData.alias_visible !== undefined ? parentData.alias_visible : 1
            }, {
                xtype: 'xcheckbox',
                boxLabel: _('resource_uri_override'),
                description: _('resource_uri_override_help'),
                hideLabel: true,
                name: 'uri_override',
                id: `modx-${id}-uri-override`,
                value: 1,
                checked: parseInt(parentData.uri_override, 10) === 1,
                listeners: {
                    check: {
                        fn: MODx.handleFreezeUri
                    }
                }
            }, {
                xtype: 'textfield',
                fieldLabel: _('resource_uri'),
                description: `<b>[[*uri]]</b><br>${_('resource_uri_help')}`,
                name: 'uri',
                id: `modx-${id}-uri`,
                maxLength: 255,
                anchor: '100%',
                value: parentData.uri || '',
                hidden: !parentData.uri_override
            }, {
                xtype: 'xcheckbox',
                boxLabel: _('resource_richtext'),
                description: _('resource_richtext_help'),
                hideLabel: true,
                name: 'richtext',
                id: `modx-${id}-richtext`,
                inputValue: 1,
                checked: (parentData.richtext !== undefined && parentData.richtext) || parseInt(MODx.config.richtext_default, 10) === 1 ? 1 : 0
            }, {
                xtype: 'xcheckbox',
                boxLabel: _('resource_searchable'),
                description: _('resource_searchable_help'),
                hideLabel: true,
                name: 'searchable',
                id: `modx-${id}-searchable`,
                inputValue: 1,
                checked: (parentData.searchable !== undefined && parentData.searchable) || parseInt(MODx.config.search_default, 10) === 1 ? 1 : 0
            }, {
                xtype: 'xcheckbox',
                boxLabel: _('resource_cacheable'),
                description: _('resource_cacheable_help'),
                hideLabel: true,
                name: 'cacheable',
                id: `modx-${id}-cacheable`,
                inputValue: 1,
                checked: (parentData.cacheable !== undefined && parentData.cacheable) || parseInt(MODx.config.cache_default, 10) === 1 ? 1 : 0
            }, {
                xtype: 'xcheckbox',
                name: 'clearCache',
                id: `modx-${id}-clearcache`,
                boxLabel: _('resource_syncsite'),
                description: _('resource_syncsite_help'),
                hideLabel: true,
                inputValue: 1,
                checked: (parentData.clearCache !== undefined && parentData.clearCache) || parseInt(MODx.config.syncsite_default, 10) === 1 ? 1 : 0
            }]
        }]
    }];
};

MODx.handleFreezeUri = function(cb) {
    const uri = Ext.getCmp(cb.id.replace('-override', ''));
    if (!uri) {
        return false;
    }
    if (cb.checked) {
        uri.show();
    } else {
        uri.hide();
    }
};

// Reference old class names to new
MODx.getQRContentField = MODx.getQuickCreateResourceContentField;
MODx.getQRSettings = MODx.getQuickCreateResourceSettingsFields;
