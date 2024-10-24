Ext.namespace('MODx.tree');
/**
 * Generates the Tree in Ext. All modTree classes extend this base class.
 *
 * @class MODx.tree.Tree
 * @extends Ext.tree.TreePanel
 * @constructor
 * @param {Object} config An object of options.
 * @xtype modx-tree
 */
MODx.tree.Tree = function(config = {}) {
    Ext.applyIf(config, {
        baseParams: {},
        action: 'getNodes',
        loaderConfig: {}
    });
    if (config.action) {
        config.baseParams.action = config.action;
    }
    config.loaderConfig.dataUrl = config.url;
    config.loaderConfig.baseParams = config.baseParams;
    Ext.applyIf(config.loaderConfig, {
        preloadChildren: true,
        clearOnLoad: true
    });

    this.config = config;
    let
        loader,
        root
    ;
    if (this.config.url) {
        // @TODO extend TreeLoader here
        loader = new MODx.tree.TreeLoader(config.loaderConfig);
        loader.on('beforeload', function(l, node) {
            loader.dataUrl = `${this.config.url}?action=${this.config.action}&id=${node.attributes.id}`;
            if (node.attributes.type) {
                loader.dataUrl += `&type=${node.attributes.type}`;
            }
        }, this);
        loader.on('load', this.onLoad, this);
        root = {
            nodeType: 'async',
            text: config.root_name || config.rootName || '',
            qtip: config.root_qtip || config.rootQtip || '',
            draggable: false,
            id: config.root_id || config.rootId || 'root',
            pseudoroot: true,
            attributes: {
                pseudoroot: true
            },
            cls: 'tree-pseudoroot-node',
            iconCls: config.root_iconCls || config.rootIconCls || ''
        };
    } else {
        loader = new Ext.tree.TreeLoader({
            preloadChildren: true,
            baseAttrs: {
                uiProvider: MODx.tree.CheckboxNodeUI
            }
        });
        root = new Ext.tree.TreeNode({
            text: this.config.rootName || '',
            draggable: false,
            id: this.config.rootId || 'root',
            children: this.config.data || [],
            pseudoroot: true
        });
    }
    Ext.applyIf(config, {
        useArrows: true,
        autoScroll: true,
        animate: true,
        enableDD: true,
        enableDrop: true,
        ddAppendOnly: false,
        containerScroll: true,
        collapsible: true,
        border: false,
        autoHeight: true,
        rootVisible: true,
        loader: loader,
        header: false,
        hideBorders: true,
        bodyBorder: false,
        cls: 'modx-tree',
        root: root,
        preventRender: false,
        stateful: true,
        menuConfig: {
            defaultAlign: 'tl-b?',
            enableScrolling: false,
            listeners: {
                show: function() {
                    const node = this.activeNode;
                    if (node) {
                        node.ui.addClass('x-tree-selected');
                    }
                },
                hide: function() {
                    const
                        node = this.activeNode,
                        isSelected = node ? node.isSelected() : false
                    ;
                    if (node && node.ui && !isSelected) {
                        node.ui.removeClass('x-tree-selected');
                    }
                }
            }
        }
    });
    if (config.remoteToolbar === true && (config.tbar === undefined || config.tbar === null)) {
        Ext.Ajax.request({
            url: config.remoteToolbarUrl || config.url,
            params: {
                action: config.remoteToolbarAction || 'getToolbar'
            },
            success: function(response) {
                const
                    responseData = Ext.decode(response.responseText),
                    tools = this._formatToolbar(responseData.object),
                    topToolbar = this.getTopToolbar()
                ;
                if (topToolbar) {
                    for (let i = 0; i < tools.length; i++) {
                        topToolbar.add(tools[i]);
                    }
                    topToolbar.doLayout();
                }
            },
            scope: this
        });
        config.tbar = {
            bodyStyle: 'padding: 0'
        };
    } else {
        let toolbar = this.getToolbar();
        if (config.tbar && config.useDefaultToolbar) {
            for (let i = 0; i < config.tbar.length; i++) {
                toolbar.push(config.tbar[i]);
            }
        } else if (config.tbar) {
            toolbar = config.tbar;
        }
        Ext.apply(config, {
            tbar: toolbar
        });
    }
    this.setup(config);
    this.config = config;

    this.on('append', this._onAppend, this);
};
Ext.extend(MODx.tree.Tree, Ext.tree.TreePanel, {
    menu: null,
    options: {},
    disableHref: false,

    onLoad: function(ldr, node, response) {
        // add custom buttons to child nodes
        this.prepareNodes(node);

        // no select() here, just addClass, using Active Input Cookie Value to set focus
        const responseData = Ext.decode(response.responseText);
        if (responseData.message) {
            const
                el = this.getTreeEl(),
                width = this.config.width > 150 ? this.config.width : 270
            ;
            el.addClass('modx-tree-load-msg');
            el.update(responseData.message);
            el.setWidth(width);
            this.doLayout();
        }
    },

    /**
     * Sets up the tree and initializes it with the specified options.
     */
    setup: function(config) {
        config.listeners = config.listeners || {};
        config.listeners.render = {
            fn: function() {
                if (config.autoExpandRoot !== false || !Object.hasOwn(config, 'autoExpandRoot')) {
                    this.root.expand();
                }
                const loader = this.getLoader();
                Ext.apply(loader, {
                    fullMask: new Ext.LoadMask(this.getEl())
                });
                loader.fullMask.removeMask = false;
                loader.on({
                    load: function() {
                        this.fullMask.hide();
                    },
                    loadexception: function() {
                        this.fullMask.hide();
                    },
                    beforeload: function() {
                        this.fullMask.show();
                    },
                    scope: loader
                });
            },
            scope: this
        };
        MODx.tree.Tree.superclass.constructor.call(this, config);

        this.addEvents('afterSort', 'beforeSort', 'refresh');

        this.cm = new Ext.menu.Menu(config.menuConfig);
        this.treestate_id = this.config.id || Ext.id();

        this.on({
            load: {
                fn: this._initExpand,
                single: true
            },
            contextmenu: {
                fn: function(node, e) {
                    this._showContextMenu(node, e);
                    this._saveState(node);
                }
            },
            click: {
                fn: function(node, e) {
                    this._handleClick(node, e);
                    this._saveState(node);
                },
                scope: this
            },
            expandnode: {
                fn: function(node) {
                    // Absolute positionning fix
                    const contentCmp = Ext.getCmp('modx-content');
                    if (contentCmp) {
                        contentCmp.doLayout();
                    }
                    this._saveState(node);
                }
            },
            collapsenode: this._saveState,
            beforenodedrop: this._handleDrop,
            // Note that these next two handler assignments are correct, though unintuitive
            nodedrop: this._handleDrag,
            nodedragover: this._handleDrop
        });
    },

    /**
     * Expand the tree upon initialization.
     */
    _initExpand: function() {
        const treeState = Ext.state.Manager.get(this.treestate_id);
        if (Ext.isEmpty(treeState) && this.root) {
            this.root.expand();
            if (this.root.firstChild && this.config.expandFirst) {
                this.root.firstChild.expand();
            }
        } else {
            for (let i = 0; i < treeState.length; i++) {
                this.expandPath(treeState[i]);
            }
        }
    },

    /**
     * Add context menu items to the tree.
     * @param {Object, Array} items Either an Object config or array of Object configs.
     */
    addContextMenuItem: function(items) {
        items.forEach(item => {
            const action = item;
            action.scope = action.scope || this;
            if (action.handler && typeof action.handler === 'string') {
                // eslint-disable-next-line no-eval
                action.handler = eval(action.handler);
            }
            this.cm.add(action);
        });
    },

    /**
     * Iterates visible nodes, adding direct action button(s) and
     * setting style on the active node when applicable
     *
     * @param node
     */
    prepareNodes: function(node) {
        let activeFile = null;
        if (window.location.search) {
            const params = MODx.util.UrlParams.get();
            if (Object.hasOwn(params, 'file')) {
                activeFile = encodeURIComponent(params.file);
            }
        }
        node.childNodes.forEach(childNode => {
            if (childNode.attributes.selected || childNode.id === activeFile) {
                childNode.ui.addClass('x-tree-selected');
            }
            // add the special buttons to node
            this.addNodeButtons(childNode);
        });
    },

    /**
     * Adds direct access buttons to a node. Currently the only added button is
     * for directly creating a new child document.
     *
     * @param node
     */
    addNodeButtons: function(node) {
        const
            elId = `${node.ui.elNode.id}_tools`,
            el = document.createElement('div')
        ;
        el.id = elId;
        el.className = 'modx-tree-node-btn-create';

        if (!node.attributes.pseudoroot && node.ui.hasClass('pnew_modDocument')) {
            node.ui.elNode.appendChild(el);

            MODx.load({
                xtype: 'modx-button',
                text: '',
                scope: this,
                tooltip: new Ext.ToolTip({
                    // TODO if childtemplate property is available, directly use that instead of "document"
                    title: `${_('create_document_inside')} <strong>${node.attributes.text}</strong>`,
                    target: this
                }),
                node: node,
                handler: function(button, e) {
                    e.stopPropagation(e);
                    node.getOwnerTree().handleDirectCreateClick(node);
                },
                iconCls: 'icon-plus-circle',
                renderTo: elId,
                listeners: {
                    mouseover: function(button, e) {
                        button.tooltip.onTargetOver(e);
                    },
                    mouseout: function(button, e) {
                        button.tooltip.onTargetOut(e);
                    }
                }
            });
        }
    },

    /**
     * Shows the current context menu.
     * @param {Ext.tree.TreeNode} node The
     * @param {Ext.EventObject} e The event object run.
     */
    _showContextMenu: function(node, e) {
        this.cm.activeNode = node;
        this.cm.removeAll();
        let
            menu,
            handled = false
        ;
        if (!Ext.isEmpty(node.attributes.treeHandler) || (node.isRoot && !Ext.isEmpty(node.childNodes[0].attributes.treeHandler))) {
            const handler = Ext.getCmp(node.isRoot ? node.childNodes[0].attributes.treeHandler : node.attributes.treeHandler);
            if (handler) {
                if (node.isRoot) {
                    node.attributes.type = 'root';
                }
                menu = handler.getMenu(this, node, e);
                handled = true;
            }
        }
        if (!handled) {
            if (this.getMenu) {
                menu = this.getMenu(node, e);
            } else if (node.attributes.menu && node.attributes.menu.items) {
                menu = node.attributes.menu.items;
            }
        }
        if (menu && menu.length > 0) {
            this.addContextMenuItem(menu);
            this.cm.showAt(e.xy);
        }
        e.preventDefault();
        e.stopEvent();
    },

    /**
     * Checks to see if a node exists in a tree node's children.
     * @param {Object} t The parent node.
     * @param {Object} n The node to find.
     * @return {Boolean} True if the node exists in the parent's children.
     */
    hasNode: function(t, n) {
        return (t.findChild('id', n.id)) || (t.leaf === true && t.parentNode.findChild('id', n.id));
    },

    /**
     * Refreshes the tree and runs an optional func.
     * @param {Function} func The function to run.
     * @param {Object} scope The scope to run the function in.
     * @param {Array} args An array of arguments to run with.
     * @return {Boolean} True if successful.
     */
    refresh: function(func, scope, args) {
        /*
            Used in Contexts, Resource Groups
        */
        const expandedPaths = Ext.state.Manager.get(this.treestate_id);
        this.root.reload();
        this.fireEvent('refresh', {});
        if (expandedPaths === undefined) {
            this.root.expand();
        } else if (Array.isArray(expandedPaths)) {
            expandedPaths.forEach(path => this.expandPath(path));
        }
        if (func) {
            scope = scope || this;
            args = args || [];
            this.root.on('load', function() {
                Ext.callback(func, scope, args);
            }, scope);
        }
        return true;
    },

    removeChildren: function(node) {
        while (node.firstChild) {
            const child = node.firstChild;
            node.removeChild(child);
            child.destroy();
        }
    },

    /*
        Is this used? Only other place found as of 3.1-dev is
        in modx.tree.checkbox.js, where only this same definition
        is found; no calls made to this method

        REMOVE?
    */
    loadRemoteData: function(data) {
        this.removeChildren(this.getRootNode());
        for (const c in data) {
            if (typeof data[c] === 'object') {
                this.getRootNode().appendChild(data[c]);
            }
        }
    },

    reloadNode: function(n) {
        this.getLoader().load(n);
        n.expand();
    },

    /**
     * Abstracted remove function
     */
    remove: function(text, substr, split) {
        if (this.destroying) {
            return MODx.tree.Tree.superclass.remove.apply(this, arguments);
        }
        const
            node = this.cm.activeNode,
            id = this._extractId(node.id, substr, split),
            params = {
                action: this.config.removeAction || 'remove'
            },
            pk = this.config.primaryKey || 'id'
        ;
        params[pk] = id;
        MODx.msg.confirm({
            title: this.config.removeTitle || _('warning'),
            text: _(text),
            url: this.config.url,
            params: params,
            listeners: {
                success: {
                    fn: this.refresh,
                    scope: this
                }
            }
        });
    },

    _extractId: function(nodeId, substr, split) {
        let id;
        substr = substr || false;
        split = split || false;
        if (substr !== false) {
            id = nodeId.substr(substr);
        }
        if (split !== false) {
            id = nodeId.split('_');
            id = id[split];
        }
        return id;
    },

    /**
     * Expand the tree and all children.
     */
    expandNodes: function() {
        if (this.root) {
            this.root.expand();
            this.root.expandChildNodes(true);
        }
    },

    /**
     * Completely collapse the tree.
     */
    collapseNodes: function() {
        if (this.root) {
            this.root.collapseChildNodes(true);
            this.root.collapse();
        }
    },

    /**
     * Save the state of the tree's open children.
     * @param {Ext.tree.TreeNode} node The most recent expanded or collapsed node.
     */
    _saveState: function(node) {
        if (!this.stateful) {
            return true;
        }
        const currentPath = node.getPath();
        let treeState = Ext.state.Manager.get(this.treestate_id);

        if (!Ext.isObject(treeState) && !Ext.isArray(treeState)) {
            treeState = [treeState];
            // backwards compat
        } else {
            treeState = treeState.slice();
        }
        if (Ext.isEmpty(currentPath) || currentPath === undefined) {
            return;
        }
        if (node.expanded) {
            // On expand, add this node's path to state
            if (Ext.isString(currentPath) && treeState.indexOf(currentPath) === -1) {
                let
                    found = false,
                    existingPath
                ;
                for (let i = 0; i < treeState.length; i++) {
                    if (treeState[i] === undefined || typeof treeState[i] !== 'string') {
                        treeState.splice(i, 1);
                    } else {
                        existingPath = treeState[i].search(currentPath);
                        if (
                            existingPath !== -1
                            && treeState[existingPath]
                            && treeState[existingPath].length > treeState[i].length
                        ) {
                            found = true;
                        }
                    }
                }
                if (!found) {
                    treeState.push(currentPath);
                }
            }
        } else {
            // On collapse, remove path of this node and any of its children from state
            treeState = treeState.remove(currentPath);
            for (let i = 0; i < treeState.length; i++) {
                if (treeState[i] === undefined || typeof treeState[i] !== 'string') {
                    treeState.splice(i, 1);
                } else if (treeState[i].search(currentPath) !== -1) {
                    delete treeState[i];
                }
            }
        }
        // Remove any remaining invalid paths from state
        for (let i = 0; i < treeState.length; i++) {
            if (treeState[i] === undefined || typeof treeState[i] !== 'string') {
                treeState.splice(i, 1);
            }
        }
        Ext.state.Manager.set(this.treestate_id, treeState);
    },

    /**
     * Handles tree clicks
     * @param {Object} node The node clicked
     * @param {Object} e The event object
     */
    _handleClick: function(node, e) {
        e.stopEvent();
        e.preventDefault();
        if (this.disableHref) {
            return true;
        }
        if (node.attributes.page && node.attributes.page !== '') {
            if (e.button === 1) {
                return window.open(node.attributes.page, '_blank');
            }
            if (e.ctrlKey === 1 || e.metaKey === 1 || e.shiftKey === 1) {
                return window.open(node.attributes.page);
            }
            if (e.target.tagName === 'SPAN') {
                // only open the edit page when clicking on the text and nothing else (e.g. icon/empty space)
                MODx.loadPage(node.attributes.page);
            } else if (node.isExpandable()) {
                // when clicking anything except the node-text, just open (if available) the node
                node.toggle();
            } else {
                // for non container nodes, they can be edited by clicking anywhere on the node
                MODx.loadPage(node.attributes.page);
            }
        } else if (
            (node.attributes.type
            && node.attributes.type === 'dir'
            && !node.expanded)
            || node.isExpandable()
        ) {
            node.toggle();
        }
        return true;
    },

    encode: function(node) {
        if (!node) {
            node = this.getRootNode();
        }
        const
            encodeRecursive = function(currentNode) {
                const
                    resultNode = {},
                    { childNodes } = currentNode
                ;
                childNodes.forEach(childNode => {
                    resultNode[childNode.id] = {
                        id: childNode.id,
                        checked: childNode.ui.isChecked(),
                        type: childNode.attributes.type || '',
                        data: childNode.attributes.data || {},
                        children: encodeRecursive(childNode)
                    };
                });
                return resultNode;
            },
            nodes = encodeRecursive(node)
        ;
        return Ext.encode(nodes);
    },

    /**
     * Handles all drag events into the tree.
     * @param {Object} dropEvent The node dropped on the parent node.
     */
    _handleDrag: function(dropEvent) {
        const
            simplifyNodes = function(currentNode) {
                const
                    resultNode = {},
                    { childNodes } = currentNode
                ;
                childNodes.forEach(childNode => {
                    resultNode[childNode.id] = simplifyNodes(childNode);
                });
                return resultNode;
            },
            encNodes = Ext.encode(simplifyNodes(dropEvent.tree.root)),
            source = dropEvent.dropNode
        ;
        this.fireEvent('beforeSort', encNodes);
        MODx.Ajax.request({
            url: this.config.url,
            params: {
                data: encodeURIComponent(encNodes),
                action: this.config.sortAction || 'sort',
                source_pk: source.attributes.pk,
                source_type: source.attributes.type
            },
            listeners: {
                success: {
                    fn: function(response) {
                        const el = dropEvent.dropNode.getUI().getTextEl();
                        if (el) {
                            if (dropEvent.target.childNodes.length === 1) {
                                dropEvent.dropNode.ensureVisible();
                            }
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

    /**
     * Abstract definition to handle drop events.
     */
    _handleDrop: function(dropEvent) {
        const node = dropEvent.dropNode;
        if (node.isRoot) {
            return false;
        }
        if (!Ext.isEmpty(node.attributes.treeHandler)) {
            const handler = Ext.getCmp(node.attributes.treeHandler);
            if (handler) {
                return handler.handleDrop(this, dropEvent);
            }
        }
    },

    /**
     * Semi unique ids across edits
     * @param {String} prefix Prefix the guid.
     * @return {String} The newly generated guid.
     */
    _guid: function(prefix) {
        return prefix + (new Date().getTime());
    },

    /**
     * Redirects the page or the content frame to the correct location.
     * @param {String} loc The URL to direct to.
     */
    redirect: function(loc) {
        MODx.loadPage(loc);
    },

    /**
     * Loads a page based on the passed param(s) and an id extracted from the active tree node.
     * Currently only Resource/Context related pages are loaded via this method
     * @param {String} urlParams The action and other optional params used for loading the page
     */
    loadAction: function(urlParams) {
        let id = '';
        if (this.cm.activeNode && this.cm.activeNode.id) {
            const pid = this.cm.activeNode.id.split('_');
            id = `id=${pid[1]}`;
        }
        MODx.loadPage(`?${id}&${urlParams}`);
    },
    /**
     * Loads the default toolbar for the tree.
     * @access private
     * @see Ext.Toolbar
     */
    _loadToolbar: function() {
    },

    /**
     * Refreshes a given tree node.
     * @access public
     * @param {String} id The ID of the node
     * @param {Boolean} self If true, will refresh self rather than parent.
     */
    refreshNode: function(id, self) {
        const node = this.getNodeById(id);
        if (node) {
            const targetNode = self ? node : node.parentNode;
            this.getLoader().load(targetNode, function() {
                targetNode.expand();
            }, this);
        }
    },

    /**
     * Refreshes selected active node
     * @access public
     */
    refreshActiveNode: function() {
        if (this.cm.activeNode) {
            this.getLoader().load(this.cm.activeNode, this.cm.activeNode.expand);
        } else {
            this.refresh();
        }
    },

    /**
     * Refreshes selected active node's parent
     * @access public
     */
    refreshParentNode: function() {
        if (this.cm.activeNode) {
            this.getLoader().load(this.cm.activeNode.parentNode || this.cm.activeNode, this.cm.activeNode.expand);
        } else {
            this.refresh();
        }
    },

    /**
     * Removes specified node
     * @param {String} id The node's ID
     */
    removeNode: function(id) {
        const node = this.getNodeById(id);
        if (node) {
            node.remove();
        }
    },

    /**
     * Dynamically removes active node
     */
    removeActiveNode: function() {
        this.cm.activeNode.remove();
    },

    /**
     * Gets a default toolbar setup
     */
    getToolbar: function() {
        const iu = `${MODx.config.manager_url}templates/default/images/restyle/icons/`;
        return [{
            icon: `${iu}arrow_down.png`,
            cls: 'x-btn-icon arrow_down',
            tooltip: {
                text: _('tree_expand')
            },
            handler: this.expandNodes,
            scope: this
        }, {
            icon: `${iu}arrow_up.png`,
            cls: 'x-btn-icon arrow_up',
            tooltip: {
                text: _('tree_collapse')
            },
            handler: this.collapseNodes,
            scope: this
        }, {
            icon: `${iu}refresh.png`,
            cls: 'x-btn-icon refresh',
            tooltip: {
                text: _('tree_refresh')
            },
            handler: this.refresh,
            scope: this
        }];
    },

    /**
     * Add Items to the toolbar.
     * @param {Object} a Contains the tools config
     * @todo Consider removal, as this appears to not be used in the core install; is only
     * used for remote toolbars which do not seem to be a part of MODX 3.x
     */
    _formatToolbar: function(a) {
        const l = a.length;
        for (let i = 0; i < l; i++) {
            if (a[i].handler) {
                // eslint-disable-next-line no-eval
                a[i].handler = eval(a[i].handler);
            }
            Ext.applyIf(a[i], {
                scope: this,
                cls: this.config.toolbarItemCls || 'x-btn-icon'
            });
        }
        return a;
    },

    /**
     * Allow pseudoroot actions
     * @param tree {self}
     * @param parent {Ext.tree.TreeNode} Parent node
     * @param node {Ext.tree.TreeNode} Node to be inserted
     */
    _onAppend: function(tree, parent, node) {
        if (node.attributes.pseudoroot) {
            setTimeout((function() {
                return function() {
                    let btn;
                    const
                        elId = `${node.ui.elNode.id}_tools`,
                        el = document.createElement('div')
                    ;
                    el.id = elId;
                    el.className = 'modx-tree-node-tool-ct';

                    node.ui.elNode.appendChild(el);

                    const inlineButtonsLang = tree.getInlineButtonsLang(node);

                    btn = MODx.load({
                        xtype: 'modx-button',
                        text: '',
                        scope: this,
                        tooltip: new Ext.ToolTip({
                            title: inlineButtonsLang.add,
                            target: this
                        }),
                        node: node,
                        handler: function(cmp, evt) {
                            evt.stopPropagation(evt);
                            node.getOwnerTree().handleCreateClick(node);
                        },
                        iconCls: 'icon-plus-circle',
                        renderTo: elId,
                        listeners: {
                            mouseover: function(button, e) {
                                button.tooltip.onTargetOver(e);
                            },
                            mouseout: function(button, e) {
                                button.tooltip.onTargetOut(e);
                            }
                        }
                    });

                    btn = MODx.load({
                        xtype: 'modx-button',
                        text: '',
                        scope: this,
                        tooltip: new Ext.ToolTip({
                            title: inlineButtonsLang.refresh,
                            target: this
                        }),
                        node: node,
                        handler: function(cmp, evt) {
                            evt.stopPropagation(evt);
                            node.reload();
                        },
                        iconCls: 'icon-refresh',
                        renderTo: elId,
                        listeners: {
                            mouseover: function(button, e) {
                                button.tooltip.onTargetOver(e);
                            },
                            mouseout: function(button, e) {
                                button.tooltip.onTargetOut(e);
                            }
                        }
                    });
                    /** @todo What is the use of this array? Don't see it utilized anywhere. Remove? */
                    window.BTNS.push(btn);
                };
            }(this)), 200);
            return false;
        }
    },

    /**
     * Handled inline add button click
     * Need to be extended in MODx.tree.Tree instances to work properly
     *
     * @param Ext.tree.AsyncTreeNode node
     */
    handleCreateClick: function(node) {
    },

    getInlineButtonsLang: function(node) {
        const langs = {};
        if (node.id !== undefined) {
            const type = node.id.substr(2).split('_');
            if (type[0] === 'type') {
                langs.add = _(`new_${type[1]}`);
            } else if (type[0] === 'category') {
                langs.add = _(`new_${type[0]}`);
            } else {
                langs.add = _('new_document');
            }
        }
        langs.refresh = _('ext_refresh');
        return langs;
    },

    expandTreePath: function(dir = '/') {
        const
            root = this.getRootNode().getPath('text'),
            path = `${root.replace(/\/$/, '')}/${dir.replace(/^\//, '')}`
        ;
        this.expandPath(path, 'text', () => {
            let node = this.getNodeById(encodeURIComponent(dir));
            if (!node) {
                node = this.getRootNode();
            }
            node.select();
            this.cm.activeNode = node;
        });
    }

});
Ext.reg('modx-tree', MODx.tree.Tree);

/** @todo What is the use of this array? Remove? */
window.BTNS = [];
