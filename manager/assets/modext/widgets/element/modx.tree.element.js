/* eslint-disable no-underscore-dangle */
/**
 * Generates the Element Tree
 *
 * @class MODx.tree.Element
 * @extends MODx.tree.Tree
 * @param {Object} config An object of options.
 * @xtype modx-tree-element
 */
MODx.tree.Element = function(config = {}) {
    Ext.applyIf(config, {
        rootVisible: false,
        enableDD: !Ext.isEmpty(MODx.config.enable_dragdrop),
        ddGroup: 'modx-treedrop-elements-dd',
        title: '',
        url: MODx.config.connector_url,
        action: 'Element/GetNodes',
        sortAction: 'Element/Sort',
        baseParams: {
            currentElement: MODx.request.id || 0,
            currentAction: MODx.request.a || 0
        }
    });
    MODx.tree.Element.superclass.constructor.call(this, config);
    this.on('afterSort', this.afterSort);
};
Ext.extend(MODx.tree.Element, MODx.tree.Tree, {
    forms: {},
    windows: {},
    stores: {},

    getToolbar: function() {
        return [];
    },

    createCategory: function(node, e) {
        const record = {};
        if (this.cm.activeNode && this.cm.activeNode.attributes.data) {
            record.parent = this.cm.activeNode.attributes.data.id;
        }

        const window = MODx.load({
            xtype: 'modx-window-category-create',
            record: record,
            listeners: {
                success: {
                    fn: function() {
                        const
                            nodeId = (this.cm.activeNode) ? this.cm.activeNode.id : 'n_category',
                            self = nodeId.indexOf('_category_') !== -1
                        ;
                        this.refreshNode(nodeId, self);
                    },
                    scope: this
                },
                hide: {
                    fn: function() {
                        this.destroy();
                    }
                }
            }
        });
        window.show(e.target);
    },

    renameCategory: function(item, e) {
        const
            { data } = this.cm.activeNode.attributes,
            window = MODx.load({
                xtype: 'modx-window-category-rename',
                record: data,
                listeners: {
                    success: {
                        fn: function(response) {
                            const
                                categoryData = response.a.result.object,
                                node = this.cm.activeNode
                            ;
                            node.setText(`${categoryData.category} (${categoryData.id})`);
                            Ext.get(node.getUI().getEl()).frame();
                            node.attributes.data.id = categoryData.id;
                            node.attributes.data.category = categoryData.category;
                            node.attributes.data.rank = categoryData.rank;
                        },
                        scope: this
                    },
                    hide: {
                        fn: function() {
                            this.destroy();
                        }
                    }
                }
            });
        window.show(e.target);
    },

    removeCategory: function(item, e) {
        const { id } = this.cm.activeNode.attributes.data;
        MODx.msg.confirm({
            title: _('warning'),
            text: _('category_confirm_delete'),
            url: MODx.config.connector_url,
            params: {
                action: 'Element/Category/Remove',
                id: id
            },
            listeners: {
                success: {
                    fn: function() {
                        this.cm.activeNode.remove();
                    },
                    scope: this
                }
            }
        });
    },

    duplicateElement: function(item, e, id, type) {
        MODx.Ajax.request({
            url: MODx.config.connector_url,
            params: {
                action: `element/${type}/get`,
                id: id
            },
            listeners: {
                success: {
                    fn: function(results) {
                        const
                            record = {
                                id: id,
                                type: type,
                                name: _('duplicate_of', { name: this.cm.activeNode.attributes.name }),
                                caption: _('duplicate_of', { name: this.cm.activeNode.attributes.caption }),
                                category: results.object.category,
                                source: results.object.source,
                                static: results.object.static,
                                static_file: results.object.static_file
                            },
                            window = MODx.load({
                                xtype: 'modx-window-element-duplicate',
                                record: record,
                                redirect: false,
                                listeners: {
                                    success: {
                                        fn: function(response) {
                                            const responseData = Ext.decode(response.a.response.responseText);
                                            if (responseData.object.redirect) {
                                                MODx.loadPage(`element/${record.type}/update`, `id=${responseData.object.id}`);
                                            } else {
                                                this.refreshNode(this.cm.activeNode.id);
                                            }
                                        },
                                        scope: this
                                    },
                                    hide: {
                                        fn: function() {
                                            this.destroy();
                                        }
                                    }
                                }
                            })
                        ;
                        window.show(e.target);
                    },
                    scope: this
                }
            }
        });
    },

    /**
     * @property {Function} extractElementIdentifiersFromActiveNode Gets an Element's type, id, and category id from an active Node's id
     *
     * @param {Ext.tree.Node} activeNode The Node currently being acted upon
     * @return {Object} An object containing relevant identifiers of the Element this Node represents
     */
    extractElementIdentifiersFromActiveNode: function(activeNode) {
        let startIndex;
        const extractedData = {};

        switch (true) {
            // When creating Elements in the root of their tree
            case activeNode.id.indexOf('n_type_') === 0:
                startIndex = 7;
                break;
            // When altering or removing an Element from within the Categories tree
            case activeNode.id.indexOf('n_c_') === 0:
                startIndex = 4;
                break;
            default:
                startIndex = 2;
        }
        const identifiers = activeNode.id.substr(startIndex).split('_');

        /*
            Expected array items:
            - When working in the Categories tree: [element type, node type ('element'), element id, element's category id]
            - When working in any of the five Element trees: [element type, node type ('category'), element's category id]
            - When creating and Element in the root of it's type's tree: [element type]
        */

        [extractedData.type] = identifiers;

        switch (identifiers.length) {
            case 4:
                return {
                    ...extractedData,
                    elementId: parseInt(identifiers[2], 10),
                    categoryId: parseInt(identifiers[3], 10)
                };
            case 3:
                return {
                    ...extractedData,
                    categoryId: parseInt(identifiers[2], 10)
                };
            case 1:
                return extractedData;
            // no default
        }
        return false;
    },

    removeElement: function(item, e) {
        const elementIdentifiers = this.extractElementIdentifiersFromActiveNode(this.cm.activeNode);
        MODx.msg.confirm({
            title: _('warning'),
            text: _('remove_this_confirm', {
                type: _(elementIdentifiers.type),
                name: this.cm.activeNode.attributes.name
            }),
            url: MODx.config.connector_url,
            params: {
                action: `element/${elementIdentifiers.type}/remove`,
                id: elementIdentifiers.elementId
            },
            listeners: {
                success: {
                    fn: function() {
                        this.cm.activeNode.remove();
                        /* if editing the element being removed */
                        if (
                            MODx.request.a === `element/${elementIdentifiers.type}/update`
                            && parseInt(MODx.request.id, 10) === elementIdentifiers.elementId
                        ) {
                            MODx.loadPage('welcome');
                        }
                    },
                    scope: this
                }
            }
        });
    },

    activatePlugin: function(item, e) {
        const elementIdentifiers = this.extractElementIdentifiersFromActiveNode(this.cm.activeNode);
        MODx.Ajax.request({
            url: MODx.config.connector_url,
            params: {
                action: 'Element/Plugin/Activate',
                id: elementIdentifiers.elementId
            },
            listeners: {
                success: {
                    fn: function() {
                        this.refreshParentNode();
                    },
                    scope: this
                }
            }
        });
    },

    deactivatePlugin: function(item, e) {
        const elementIdentifiers = this.extractElementIdentifiersFromActiveNode(this.cm.activeNode);
        MODx.Ajax.request({
            url: MODx.config.connector_url,
            params: {
                action: 'Element/Plugin/Deactivate',
                id: elementIdentifiers.elementId
            },
            listeners: {
                success: {
                    fn: function() {
                        this.refreshParentNode();
                    },
                    scope: this
                }
            }
        });
    },

    quickCreate: function(item, e, type) {
        const
            record = {
                category: this.cm.activeNode.attributes.pk || ''
            },
            window = MODx.load({
                xtype: `modx-window-quick-create-${type}`,
                record: record,
                listeners: {
                    success: {
                        fn: function() {
                            this.refreshNode(this.cm.activeNode.id, true);
                        },
                        scope: this
                    },
                    hide: {
                        fn: function() {
                            this.destroy();
                        }
                    }
                }
            })
        ;
        window.setValues(record);
        window.show(e.target);
    },

    quickUpdate: function(item, e, type) {
        MODx.Ajax.request({
            url: MODx.config.connector_url,
            params: {
                action: `element/${type}/get`,
                id: this.cm.activeNode.attributes.pk
            },
            listeners: {
                success: {
                    fn: function(response) {
                        const
                            nameField = (type === 'template') ? 'templatename' : 'name',
                            record = response.object,
                            window = MODx.load({
                                xtype: `modx-window-quick-update-${type}`,
                                record: record,
                                listeners: {
                                    success: {
                                        fn: function(response2) {
                                            this.refreshNode(this.cm.activeNode.id);
                                            const
                                                elementName = response2.f.findField(nameField).getValue(),
                                                newTitle = `<span dir="ltr">${elementName} (${window.record.id})</span>`
                                            ;
                                            window.setTitle(window.title.replace(/<span.*\/span>/, newTitle));
                                        },
                                        scope: this
                                    },
                                    hide: {
                                        fn: function() {
                                            this.destroy();
                                        }
                                    }
                                }
                            })
                        ;
                        window.title += `: <span dir="ltr">${window.record[nameField]} (${window.record.id})</span>`;
                        window.setValues(record);
                        window.show(e.target);
                    },
                    scope: this
                }
            }
        });
    },

    _createElement: function(item, e, t) {
        const
            elementIdentifiers = this.extractElementIdentifiersFromActiveNode(this.cm.activeNode),
            { type, categoryId } = elementIdentifiers
        ;
        let path = `?a=element/${type}/create`;
        if (!Ext.isEmpty(categoryId)) {
            path += `&category=${categoryId}`;
        }
        this.redirect(path);
        this.cm.hide();
        return false;
    },

    afterSort: function(o) {
        const targetNode = o.event.target.attributes;
        if (targetNode.type === 'category') {
            const dropNode = o.event.dropNode.attributes;
            if (targetNode.id !== 'n_category' && dropNode.type === 'category') {
                o.event.target.expand();
            } else {
                this.refreshNode(o.event.target.attributes.id, true);
                this.refreshNode(`n_type_${o.event.dropNode.attributes.type}`, true);
            }
        }
    },

    _handleDrop: function(e) {
        const { target } = e;
        if (e.point === 'above' || e.point === 'below') {
            return false;
        }
        if (target.attributes.classKey !== 'MODX\\Revolution\\modCategory' && target.attributes.classKey !== 'root') {
            return false;
        }
        if (!this.isCorrectType(e.dropNode, target)) {
            return false;
        }
        if (target.attributes.type === 'category' && e.point === 'append') {
            return true;
        }
        return target.getDepth() > 0;
    },

    isCorrectType: function(dropNode, targetNode) {
        let result = false;
        /* types must be the same */
        if (targetNode.attributes.type === dropNode.attributes.type) {
            /* do not allow anything to be dropped on an element */
            if (!(targetNode.parentNode
                && ((dropNode.attributes.cls === 'folder'
                && targetNode.attributes.cls === 'folder'
                && dropNode.parentNode.id === targetNode.parentNode.id
                ) || targetNode.attributes.cls === 'file'))) {
                result = true;
            }
        }
        return result;
    },

    /**
     * Shows the current context menu.
     * @param {Ext.tree.TreeNode} node The current node
     * @param {Ext.EventObject} e The event object run.
     */
    _showContextMenu: function(node, e) {
        this.cm.activeNode = node;
        this.cm.removeAll();
        if (node.attributes.menu && node.attributes.menu.items) {
            this.addContextMenuItem(node.attributes.menu.items);
            this.cm.show(node.getUI().getEl(), 't?');
        } else {
            let menu = [];
            switch (node.attributes.classKey) {
                case 'root':
                    menu = this._getRootMenu(node);
                    break;
                case 'MODX\\Revolution\\modCategory':
                    menu = this._getCategoryMenu(node);
                    break;
                default:
                    menu = this._getElementMenu(node);
                    break;
            }

            this.addContextMenuItem(menu);
            this.cm.showAt(e.xy);
        }
        e.stopEvent();
    },

    _getQuickCreateMenu: function(node, menu) {
        const
            ui = node.getUI(),
            qcMenu = [],
            types = ['template', 'tv', 'chunk', 'snippet', 'plugin']
        ;
        types.forEach(elType => {
            if (ui.hasClass(`pnew_${elType}`)) {
                qcMenu.push({
                    text: _(elType),
                    scope: this,
                    type: elType,
                    handler: function(item, e) {
                        this.quickCreate(item, e, item.type);
                    }
                });
            }
        });
        if (qcMenu.length > 0) {
            menu.push({
                text: _('quick_create'),
                handler: function() {
                    return false;
                },
                menu: {
                    items: qcMenu
                }
            });
        }
        return menu;
    },

    _getElementMenu: function(node) {
        const { attributes } = node,
              ui = node.getUI(),
              menu = [];

        menu.push({
            text: `<strong>${attributes.text}</strong>`,
            handler: function() { return false; },
            header: true
        });
        menu.push('-');

        if (ui.hasClass('pedit')) {
            menu.push({
                text: _(`edit_${attributes.type}`),
                type: attributes.type,
                pk: attributes.pk,
                handler: function(item, e) {
                    MODx.loadPage(`element/${item.type}/update`, `id=${item.pk}`);
                }
            });
            menu.push({
                text: _(`quick_update_${attributes.type}`),
                type: attributes.type,
                handler: function(item, e) {
                    this.quickUpdate(item, e, item.type);
                }
            });
            if (attributes.classKey === 'MODX\\Revolution\\modPlugin') {
                if (attributes.active) {
                    menu.push({
                        text: _('plugin_deactivate'),
                        type: attributes.type,
                        handler: this.deactivatePlugin
                    });
                } else {
                    menu.push({
                        text: _('plugin_activate'),
                        type: attributes.type,
                        handler: this.activatePlugin
                    });
                }
            }
        }
        if (ui.hasClass('pnew')) {
            menu.push({
                text: _(`duplicate_${attributes.type}`),
                pk: attributes.pk,
                type: attributes.type,
                handler: function(item, e) {
                    this.duplicateElement(item, e, item.pk, item.type);
                }
            });
        }
        if (ui.hasClass('pdelete')) {
            menu.push('-');
            menu.push({
                text: _(`remove_${attributes.type}`),
                handler: this.removeElement
            });
        }
        menu.push('-');
        if (ui.hasClass('pnew')) {
            menu.push({
                text: _(`add_to_category_${attributes.type}`),
                handler: this._createElement
            });
        }
        if (ui.hasClass('pnewcat')) {
            menu.push({
                text: _('category_create'),
                handler: this.createCategory
            });
        }
        return menu;
    },

    _getCategoryMenu: function(node) {
        const { attributes } = node,
              ui = node.getUI(),
              menu = [];

        menu.push({
            text: `<strong>${attributes.text}</strong>`,
            handler: function() {
                return false;
            },
            header: true
        });
        menu.push('-');
        if (ui.hasClass('pnewcat')) {
            menu.push({
                text: _('category_create'),
                handler: this.createCategory
            });
        }
        if (ui.hasClass('peditcat')) {
            menu.push({
                text: _('category_rename'),
                handler: this.renameCategory
            });
        }
        if (menu.length > 2) {
            menu.push('-');
        }

        if (ui.hasClass(`pnew_${attributes.type}`)) {
            menu.push({
                text: _(`add_to_category_${attributes.type}`),
                handler: this._createElement
            });
        }
        this._getQuickCreateMenu(node, menu);

        if (ui.hasClass('pdelcat')) {
            menu.push('-');
            menu.push({
                text: _('category_remove'),
                handler: this.removeCategory
            });
        }
        return menu;
    },

    _getRootMenu: function(node) {
        const { attributes } = node,
              ui = node.getUI(),
              menu = [];

        if (ui.hasClass('pnew')) {
            menu.push({
                text: _(`new_${attributes.type}`),
                handler: this._createElement
            });
            menu.push({
                text: _(`quick_create_${attributes.type}`),
                type: attributes.type,
                handler: function(item, e) {
                    this.quickCreate(item, e, item.type);
                }
            });
        }

        if (ui.hasClass('pnewcat')) {
            if (ui.hasClass('pnew')) {
                menu.push('-');
            }
            menu.push({
                text: _('category_create'),
                handler: this.createCategory
            });
        }

        if (node.isLoaded()) {
            const { childNodes } = node;

            if (childNodes.some(child => !child.leaf)) {
                // If any childNode has own children
                menu.push('-');

                if (node.isExpanded() && childNodes.some(child => child.isExpanded())) {
                    // If any childNode is expanded
                    menu.push({
                        text: _('collapse_all'),
                        handler: function() {
                            node.collapseChildNodes();
                        }
                    });
                }

                menu.push({
                    text: _('expand_all'),
                    handler: function() {
                        if (node.isExpandable()) {
                            node.expand(true);
                        }
                    }
                });
            }
        }

        return menu;
    },

    handleCreateClick: function(node) {
        this.cm.activeNode = node;
        const type = this.cm.activeNode.id.substr(2).split('_');
        if (type[0] !== 'category') {
            this._createElement(null, null, null);
        } else {
            this.createCategory(null, { target: this });
        }
    }
});
Ext.reg('modx-tree-element', MODx.tree.Element);
