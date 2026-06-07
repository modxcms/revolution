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
        tl,
        root
    ;
    if (this.config.url) {
        // @TODO extend TreeLoader here
        tl = new MODx.tree.TreeLoader(config.loaderConfig);
        tl.on('beforeload', function(l, node) {
            tl.dataUrl = `${this.config.url}?action=${this.config.action}&id=${node.attributes.id}`;
            if (node.attributes.type) {
                tl.dataUrl += `&type=${node.attributes.type}`;
            }
        }, this);
        tl.on('load', this.onLoad, this);
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
        tl = new Ext.tree.TreeLoader({
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
        loader: tl,
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
                    const node = this.activeNode;
                    if (node) {
                        if (!node.isSelected()) {
                            node?.ui?.removeClass('x-tree-selected');
                        }
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
            success: function(r) {
                r = Ext.decode(r.responseText);
                const
                    itms = this._formatToolbar(r.object),
                    tb = this.getTopToolbar()
                ;
                if (tb) {
                    for (let i = 0; i < itms.length; i++) {
                        tb.add(itms[i]);
                    }
                    tb.doLayout();
                }
            },
            scope: this
        });
        config.tbar = { bodyStyle: 'padding: 0' };
    } else {
        let tb = this.getToolbar();
        if (config.tbar && config.useDefaultToolbar) {
            for (let i = 0; i < config.tbar.length; i++) {
                tb.push(config.tbar[i]);
            }
        } else if (config.tbar) {
            tb = config.tbar;
        }
        Ext.apply(config, { tbar: tb });
    }
    this.setup(config);
    this.config = config;

    this.on('append', this._onAppend, this);
};
// eslint-disable-next-line object-shorthand
Ext.extend(MODx.tree.Tree, Ext.tree.TreePanel, {
    menu: null,
    options: {},
    disableHref: false,

    onLoad: function(ldr, node, resp) {
        // add custom buttons to child nodes
        this.prepareNodes(node);

        // no select() here, just addClass, using Active Input Cookie Value to set focus
        const r = Ext.decode(resp.responseText);
        if (r.message) {
            const el = this.getTreeEl();
            el.addClass('modx-tree-load-msg');
            el.update(r.message);
            let w = 270;
            if (this.config.width > 150) {
                w = this.config.width;
            }
            el.setWidth(w);
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
                const tl = this.getLoader();
                Ext.apply(tl, { fullMask: new Ext.LoadMask(this.getEl()) });
                tl.fullMask.removeMask = false;
                tl.on({
                    load: function() {
                        this.fullMask.hide();
                    },
                    loadexception: function() {
                        this.fullMask.hide();
                    },
                    beforeload: function() {
                        this.fullMask.show();
                    },
                    scope: tl
                });
            },
            scope: this
        };
        MODx.tree.Tree.superclass.constructor.call(this, config);
        this.addEvents('afterSort', 'beforeSort', 'refresh');
        this.cm = new Ext.menu.Menu(config.menuConfig);
        this.on('contextmenu', this._showContextMenu, this);
        this.on('beforenodedrop', this._handleDrop, this);
        this.on('nodedragover', this._handleDrop, this);
        this.on('nodedrop', this._handleDrag, this);
        this.on('click', this._saveState, this);
        this.on('contextmenu', this._saveState, this);
        this.on('click', this._handleClick, this);

        this.treestate_id = this.config.id || Ext.id();
        this.on('load', this._initExpand, this, { single: true });
        this.on('expandnode', this._saveState, this);
        this.on('collapsenode', this._saveState, this);

        /* Absolute positionning fix  */
        this.on('expandnode', function() {
            const cnt = Ext.getCmp('modx-content');
            if (cnt) {
                cnt.doLayout();
            }
        }, this);
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
        const a = items,
              l = a.length;
        for (let i = 0; i < l; i++) {
            a[i].scope = a[i].scope || this;
            if (a[i].handler && typeof a[i].handler == 'string') {
                // eslint-disable-next-line no-eval
                a[i].handler = eval(a[i].handler);
            }
            this.cm.add(a[i]);
        }
    },

    /**
     *
     *
     * @param node
     */
    prepareNodes: function(node) {
        const params = {};
        if (window.location.search) {
            const parts = window.location.search.substring(1).split('&');

            for (let i = 0; i < parts.length; i++) {
                const nv = parts[i].split('=');
                if (!nv[0]) {
                    continue;
                }
                params[nv[0]] = nv[1] || true;
            }
        }
        const activeFile = params.file;

        Ext.each(node.childNodes, function(node) {
            if (node.attributes.selected || node.id === activeFile) {
                node.ui.addClass('x-tree-selected');
            }

            // add the special buttons to node
            this.addNodeButtons(node);
        }, this);
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
                    /** @todo If childtemplate property is available, directly use that instead of "document" */
                    title: `${_('create_document_inside')} <strong>${node.attributes.text}</strong>`,
                    target: this
                }),
                node: node,
                handler: function(btn, evt) {
                    evt.stopPropagation(evt);
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
            m,
            handled = false
        ;
        if (!Ext.isEmpty(node.attributes.treeHandler) || (node.isRoot && !Ext.isEmpty(node.childNodes[0].attributes.treeHandler))) {
            const h = Ext.getCmp(node.isRoot ? node.childNodes[0].attributes.treeHandler : node.attributes.treeHandler);
            if (h) {
                if (node.isRoot) {
                    node.attributes.type = 'root';
                }
                m = h.getMenu(this, node, e);
                handled = true;
            }
        }
        if (!handled) {
            if (this.getMenu) {
                m = this.getMenu(node, e);
            } else if (node.attributes.menu && node.attributes.menu.items) {
                m = node.attributes.menu.items;
            }
        }
        if (m && m.length > 0) {
            this.addContextMenuItem(m);
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
    refresh: function(func, scope = this, args = []) {
        const treeState = Ext.state.Manager.get(this.treestate_id);
        this.root.reload();
        this.fireEvent('refresh', {});
        if (treeState === undefined) {
            this.root.expand();
        } else if (Ext.isArray(treeState)) {
            // Make sure we have a valid state array
            // eslint-disable-next-line func-names
            Ext.each(treeState, function(path, idx) {
                this.expandPath(path);
            }, this);
        }
        if (func) {
            // eslint-disable-next-line prefer-arrow-callback
            this.root.on('load', function() {
                Ext.callback(func, scope, args);
            }, scope);
        }
        return true;
    },

    removeChildren: function(node) {
        while (node.firstChild) {
            const c = node.firstChild;
            node.removeChild(c);
            c.destroy();
        }
    },

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
            p = { action: this.config.removeAction || 'remove' },
            pk = this.config.primaryKey || 'id'
        ;
        p[pk] = id;
        MODx.msg.confirm({
            title: this.config.removeTitle || _('warning'),
            text: _(text),
            url: this.config.url,
            params: p,
            listeners: {
                success: { fn: this.refresh, scope: this }
            }
        });
    },

    _extractId: function(id, substr = false, split = false) {
        if (substr !== false) {
            id = id.substr(substr);
        }
        if (split !== false) {
            id = id.split('_');
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
     * @param {Ext.tree.TreeNode} n The most recent expanded or collapsed node.
     */
    _saveState: function(n) {
        if (!this.stateful) {
            return true;
        }
        let s = Ext.state.Manager.get(this.treestate_id);
        const p = n.getPath();
        let i;
        if (!Ext.isObject(s) && !Ext.isArray(s)) {
            s = [s];
            /* backwards compat */
        } else {
            s = s.slice();
        }
        if (Ext.isEmpty(p) || p === undefined) {
            return;
        }
        /* ignore invalid paths */
        if (n.expanded) { /* if expanding, add to state */
            if (Ext.isString(p) && s.indexOf(p) === -1) {
                let
                    f = false,
                    sr
                ;
                for (i = 0; i < s.length; i++) {
                    if (s[i] === undefined || typeof s[i] != 'string') {
                        s.splice(i, 1);
                        continue;
                    }
                    sr = s[i].search(p);
                    if (sr !== -1 && s[sr]) { /* dont add if already in */
                        if (s[sr].length > s[i].length) {
                            f = true;
                        }
                    }
                }
                if (!f) { /* if not in, add */
                    s.push(p);
                }
            }
        } else { /* if collapsing, remove from state */
            s = s.remove(p);
            /* remove all children of node */
            for (i = 0; i < s.length; i++) {
                if (s[i] === undefined || typeof s[i] != 'string') {
                    s.splice(i, 1);
                    continue;
                }
                if (s[i].search(p) !== -1) {
                    delete s[i];
                }
            }
        }
        /* clear out undefineds */
        for (i = 0; i < s.length; i++) {
            if (s[i] === undefined || typeof s[i] != 'string') {
                s.splice(i, 1);
                continue;
            }
        }
        Ext.state.Manager.set(this.treestate_id, s);
    },

    /**
     * Handles tree clicks
     * @param {Object} n The node clicked
     * @param {Object} e The event object
     */
    _handleClick: function(n, e) {
        e.stopEvent();
        e.preventDefault();

        if (this.disableHref) {
            return true;
        }
        if (n.attributes.page && n.attributes.page !== '') {
            /** @todo Check if this first condition can be removed; looks like middle click might be caught and handled in modx.layout.js */
            if (e.button === 1) {
                // Middle click to open new browser tab
                return window.open(n.attributes.page, '_blank');
            }
            if (e.ctrlKey || e.metaKey || e.shiftKey) {
                // metaKey (Mac command button, Windows button) does not seem to be passed in e; remove?
                return window.open(n.attributes.page);
            }
            if (e.target.tagName === 'SPAN') {
                // only open the edit page when clicking on the text and nothing else (e.g. icon/empty space)
                MODx.loadPage(n.attributes.page);
            } else if (n.isExpandable()) {
                // when clicking anything except the node-text, just open (if available) the node
                n.toggle();
            } else {
                // for non container nodes, they can be edited by clicking anywhere on the node
                MODx.loadPage(n.attributes.page);
            }
        } else if (n.attributes.type && n.attributes.type === 'dir') {
            if (!n.expanded) {
                n.toggle();
            }
        } else if (n.isExpandable()) {
            n.toggle();
        }
        return true;
    },

    encode: function(node) {
        if (!node) {
            node = this.getRootNode();
        }
        const
            _encode = function(node) {
                const
                    resultNode = {},
                    kids = node.childNodes
                ;
                for (let i = 0; i < kids.length; i++) {
                    const n = kids[i];
                    resultNode[n.id] = {
                        id: n.id,
                        checked: n.ui.isChecked(),
                        type: n.attributes.type || '',
                        data: n.attributes.data || {},
                        children: _encode(n)
                    };
                }
                return resultNode;
            },
            nodes = _encode(node)
        ;
        return Ext.encode(nodes);
    },

    /**
     * Handles all drag events into the tree.
     * @param {Object} dropEvent The node dropped on the parent node.
     */
    _handleDrag: function(dropEvent) {
        function simplifyNodes(node) {
            const
                resultNode = {},
                kids = node.childNodes,
                len = kids.length
            ;
            for (let i = 0; i < len; i++) {
                resultNode[kids[i].id] = simplifyNodes(kids[i]);
            }
            return resultNode;
        }

        const encNodes = Ext.encode(simplifyNodes(dropEvent.tree.root)),
              source = dropEvent.dropNode;

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
                    fn: function(r) {
                        const el = dropEvent.dropNode.getUI().getTextEl();
                        if (el) {
                            if (dropEvent.target.childNodes.length === 1) { dropEvent.dropNode.ensureVisible(); }

                            Ext.get(el).frame();
                        }
                        this.fireEvent('afterSort', { event: dropEvent, result: r });
                    },
                    scope: this
                },
                failure: {
                    fn: function(r) {
                        MODx.form.Handler.errorJSON(r);
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
        if (node.isRoot) { return false; }

        if (!Ext.isEmpty(node.attributes.treeHandler)) {
            const h = Ext.getCmp(node.attributes.treeHandler);
            if (h) {
                return h.handleDrop(this, dropEvent);
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

    loadAction: function(p) {
        let id = '';
        if (this.cm.activeNode && this.cm.activeNode.id) {
            const pid = this.cm.activeNode.id.split('_');
            id = `id=${pid[1]}`;
        }
        MODx.loadPage(`?${id}&${p}`);
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
            const n = self ? node : node.parentNode;
            this.getLoader().load(n, function() {
                n.expand();
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
            tooltip: { text: _('tree_expand') },
            handler: this.expandNodes,
            scope: this
        }, {
            icon: `${iu}arrow_up.png`,
            cls: 'x-btn-icon arrow_up',
            tooltip: { text: _('tree_collapse') },
            handler: this.collapseNodes,
            scope: this
        }, {
            icon: `${iu}refresh.png`,
            cls: 'x-btn-icon refresh',
            tooltip: { text: _('tree_refresh') },
            handler: this.refresh,
            scope: this
        }];
    },

    /**
     * Add Items to the toolbar.
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
            setTimeout((function(tree) {
                return function() {
                    const
                        elId = `${node.ui.elNode.id}_tools`,
                        el = document.createElement('div')
                    ;
                    el.id = elId;
                    el.className = 'modx-tree-node-tool-ct';

                    node.ui.elNode.appendChild(el);

                    const
                        inlineButtonsLang = tree.getInlineButtonsLang(node),
                        createButton = MODx.load({
                            xtype: 'modx-button',
                            text: '',
                            scope: this,
                            tooltip: new Ext.ToolTip({
                                title: inlineButtonsLang.add,
                                target: this
                            }),
                            node: node,
                            handler: function(btn, evt) {
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
                        }),
                        refreshButton = MODx.load({
                            xtype: 'modx-button',
                            text: '',
                            scope: this,
                            tooltip: new Ext.ToolTip({
                                title: inlineButtonsLang.refresh,
                                target: this
                            }),
                            node: node,
                            handler: function(btn, evt) {
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
                        })
                    ;

                    /** @todo Verify this global storage of these buttons has some use */
                    window.BTNS.push(createButton, refreshButton);
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

    expandTreePath(dir = '/') {
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

window.BTNS = [];
