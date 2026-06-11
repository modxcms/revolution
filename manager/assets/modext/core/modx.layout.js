/* eslint-disable wrap-iife */
/* eslint-disable no-loop-func */
/* eslint-disable no-inner-declarations */
/**
 * Loads the MODx Ext-driven Layout
 *
 * @class MODx.Layout
 * @extends Ext.Viewport
 * @param {Object} config An object of config options.
 * @xtype modx-layout
 */
Ext.apply(Ext, {
    isFirebug: (window.console && window.console.firebug)
});

MODx.Layout = function(config = {}) {
    Ext.BLANK_IMAGE_URL = `${MODx.config.manager_url}assets/ext3/resources/images/default/s.gif`;
    Ext.Ajax.defaultHeaders = {
        modAuth: config.auth
    };
    Ext.Ajax.extraParams = {
        HTTP_MODAUTH: config.auth
    };
    MODx.siteId = config.auth;
    MODx.expandHelp = !!+MODx.config.inline_help;

    const sp = new MODx.HttpProvider();
    Ext.state.Manager.setProvider(sp);
    sp.initState(MODx.defaultState);

    config.showTree = false;

    if (config.search) {
        new MODx.SearchBar();
    }

    Ext.applyIf(config, {
        layout: 'border',
        id: 'modx-layout',
        stateSave: true,
        items: this.buildLayout(config)
    });
    MODx.Layout.superclass.constructor.call(this, config);
    this.config = config;

    this.addEvents({
        afterLayout: true,
        loadKeyMap: true,
        loadTabs: true
    });
    this.loadKeys();
    if (!config.showTree) {
        Ext.getCmp('modx-leftbar-tabs').collapse(false);
    }
    this.fireEvent('afterLayout');
};
Ext.extend(MODx.Layout, Ext.Viewport, {
    /**
     * @property {Number} menuBarWidth - The standard width for main left menu (tablet and larger layout)
     */
    menuBarWidth: 70,

    /**
     * @property {Number} splitBarMargin - Standard spacing for the split bar
     */
    splitBarMargin: 8,

    /**
     * @property {Object} focusRestoreEl - Set Focus back on this Element
     */
    focusRestoreEl: [],

    /**
     * @property {Function} getSplitBarMargin - Utility getter for splitBarMargin
     * @returns {Number}
     */
    getSplitBarMargin: function() {
        return this.splitBarMargin;
    },

    /**
     * Wrapper method to build the layout regions
     *
     * @param {Object} config
     *
     * @returns {Array}
     */
    buildLayout: function(config) {
        const
            items = [],
            north = this.getNorth(config),
            west = this.getWest(config),
            center = this.getCenter(config),
            south = this.getSouth(config),
            east = this.getEast(config)
        ;
        if (north && Ext.isObject(north)) {
            items.push(north);
        }
        if (west && Ext.isObject(west)) {
            items.push(west);
        }
        if (center && Ext.isObject(center)) {
            items.push(center);
        }
        if (south && Ext.isObject(south)) {
            items.push(south);
        }
        if (east && Ext.isObject(east)) {
            items.push(east);
        }

        return items;
    },

    /**
     * Build the north region (header)
     *
     * @param {Object} config
     *
     * @returns {Object|void}
     */
    getNorth: function(config) {
        if (window.innerWidth <= 640) {
            return {
                xtype: 'box',
                region: 'north',
                applyTo: 'modx-header',
                listeners: {
                    afterrender: this.initPopper,
                    scope: this
                }
            };
        }

        return false;
    },

    /**
     * Build the west region (main menu bar)
     *
     * @param {Object} config
     *
     * @returns {Object|void}
     */
    getWest: function(config) {
        if (window.innerWidth <= 640) {
            return this.getTree(config);
        }

        return {
            region: 'west',
            xtype: 'box',
            id: 'modx-header',
            applyTo: 'modx-header',
            width: this.menuBarWidth,
            listeners: {
                afterrender: {
                    fn: this.initPopper,
                    scope: this
                }
            }
        };
    },

    /**
     * Build the center region (main content)
     *
     * @param {Object} config
     *
     * @returns {Object|void}
     */
    getCenter: function(config) {
        const center = {
            region: 'center',
            applyTo: 'modx-content',
            padding: '0 1px 0 0',
            margins: {
                top: 0,
                right: 0,
                bottom: 0,
                left: 0
            },
            style: 'width:100%',
            bodyStyle: 'background-color:transparent;',
            id: 'modx-content',
            autoScroll: true
        };

        if (window.innerWidth <= 640) {
            return center;
        }

        const tree = this.getTree(config);

        center.margins.right = -this.menuBarWidth;

        tree.margins = {
            left: this.menuBarWidth
        };

        return {
            region: 'center',
            layout: 'border',
            id: 'modx-split-wrapper',
            items: [tree, center],
            listeners: {
                render: {
                    fn: function(cmp) {
                        if (!cmp.collapsed) {
                            cmp.items.map['modx-content'].margins.left = -this.getSplitBarMargin();
                        }
                    },
                    scope: this
                }
            }
        };
    },

    /**
     * Build the south region (footer)
     *
     * @param {Object} config
     *
     * @returns {Object|void}
     */
    getSouth: function(config) {
    },

    /**
     * Build the east region
     *
     * @param {Object} config
     *
     * @returns {Object|void}
     */
    getEast: function(config) {
    },

    getTree: function(config) {
        const
            tabs = [],
            activeTab = 0,
            layout = this
        ;
        if (MODx.perm.resource_tree) {
            tabs.push({
                title: _('resources'),
                xtype: 'modx-tree-resource',
                id: 'modx-resource-tree'
            });
            config.showTree = true;
        }
        if (MODx.perm.element_tree) {
            tabs.push({
                title: _('elements'),
                xtype: 'modx-tree-element',
                id: 'modx-tree-element'
            });
            config.showTree = true;
        }
        if (MODx.perm.file_tree) {
            tabs.push({
                title: _('files'),
                xtype: 'modx-panel-filetree',
                id: 'modx-file-tree'
            });
            config.showTree = true;
        }

        return {
            region: 'west',
            applyTo: 'modx-leftbar',
            id: 'modx-leftbar-tabs',
            split: true,
            width: 300,
            minSize: 280,
            autoScroll: true,
            unstyled: true,
            useSplitTips: true,
            monitorResize: true,
            layout: 'anchor',
            headerCfg: window.innerWidth <= 640 ? {} : {
                tag: 'div',
                cls: 'none',
                id: 'modx-leftbar-header',
                html: MODx.config.site_name
            },
            items: [{
                xtype: 'modx-tabs',
                plain: true,
                defaults: {
                    autoScroll: true,
                    fitToFrame: true
                },
                id: 'modx-leftbar-tabpanel',
                border: false,
                activeTab: activeTab,
                stateful: true,
                stateEvents: ['tabchange'],
                getState: function() {
                    return {
                        activeTab: this.items.indexOf(this.getActiveTab())
                    };
                },
                items: tabs,
                listeners: {
                    afterrender: function() {
                        const baseTabs = this,
                              header = Ext.get('modx-leftbar-header')
                        ;
                        MODx.Ajax.request({
                            url: MODx.config.connector_url,
                            params: {
                                action: 'Resource/GetToolbar'
                            },
                            listeners: {
                                success: {
                                    fn: function(response) {
                                        const trashTrigger = Object.values(response.object).find(item => item.id === 'emptifier');
                                        if (trashTrigger) {
                                            const trashTab = baseTabs.add({
                                                id: 'modx-trash-link',
                                                title: '<a href="?resource/trash"><i class="icon icon-trash-o"></i></a>',
                                                updateState: function(deletedCount = 0) {
                                                    const
                                                        tab = this,
                                                        { tabEl } = tab,
                                                        tooltipTarget = new Ext.Element(tabEl)
                                                    ;
                                                    if (deletedCount === 0) {
                                                        tab.disable();
                                                        tabEl.classList.remove('active');
                                                    } else {
                                                        tab.enable();
                                                        tabEl.classList.add('active');
                                                    }

                                                    tab.tooltip = new Ext.ToolTip({
                                                        target: tooltipTarget,
                                                        title: _('trash.manage_recycle_bin_tooltip', { count: deletedCount })
                                                    });
                                                }
                                            });
                                            if (!trashTrigger.disabled) {
                                                trashTab.tabEl.classList.add('active');
                                            }
                                            if (trashTrigger.tooltip) {
                                                trashTab.tooltip = new Ext.ToolTip({
                                                    target: new Ext.Element(trashTab.tabEl),
                                                    title: trashTrigger.tooltip
                                                });
                                            }
                                        }
                                    },
                                    scope: this
                                }
                            }
                        });

                        if (header) {
                            let html = '';
                            const el = document.createElement('a');
                            if (MODx.config.manager_logo !== '' && MODx.config.manager_logo !== undefined) {
                                html += `<img src="${MODx.config.manager_logo}">`;
                            }
                            el.href = MODx.config.default_site_url || MODx.config.site_url;
                            el.title = MODx.config.site_name;
                            el.innerText = Ext.util.Format.ellipsis(MODx.config.site_name, 45, true);
                            el.target = '_blank';
                            html += el.outerHTML;
                            header.dom.innerHTML = html;
                        }
                    },
                    beforetabchange: {
                        fn: function(panel, tab) {
                            if (tab && tab.id === 'modx-trash-link') {
                                if (tab.tabEl.classList.contains('active')) {
                                    const tree = Ext.getCmp('modx-resource-tree');
                                    if (tree) {
                                        tree.redirect('?a=resource/trash');
                                    }
                                }
                                return false;
                            }
                        },
                        scope: this
                    }
                }
            }],
            getState: function() {
                return {
                    collapsed: this.collapsed,
                    width: this.width
                };
            },
            collapse: function(animate) {
                if (this.collapsed || this.el.hasFxBlock() || this.fireEvent('beforecollapse', this, animate) === false) {
                    return;
                }
                const contentRegion = Ext.getCmp('modx-content');
                if (contentRegion) {
                    contentRegion.margins.left = 0;
                    Ext.getCmp('modx-layout').doLayout();
                }
                if (animate && window.innerWidth > 960) {
                    const tree = Ext.getCmp('modx-leftbar-tabpanel').getEl();
                    tree.dom.style.opacity = 0;
                    this.el.dom.style.left = `-${this.el.dom.style.width}`;
                } else {
                    this.el.dom.style.display = 'none';
                }
                this.collapsed = true;
                Ext.get('modx-leftbar-trigger').addClass('collapsed');
                this.saveState();
                this.fireEvent('collapse', this);
                return this;
            },
            expand: function(animate) {
                if (!this.collapsed || this.el.hasFxBlock() || this.fireEvent('beforeexpand', this, animate) === false) {
                    return;
                }
                const contentRegion = Ext.getCmp('modx-content');
                if (contentRegion) {
                    contentRegion.margins.left = -layout.getSplitBarMargin();
                }
                if (animate && window.innerWidth > 960) {
                    const tree = Ext.getCmp('modx-leftbar-tabpanel').getEl();
                    window.setTimeout(() => {
                        tree.dom.style.visibility = 'visible';
                        tree.dom.style.opacity = 1;
                    }, 100);
                } else {
                    this.el.dom.style.display = '';
                }
                this.collapsed = false;
                Ext.get('modx-leftbar-trigger').removeClass('collapsed');
                this.saveState();
                this.fireEvent('expand', this);
                return this;
            },
            listeners: {
                beforestatesave: {
                    fn: this.onBeforeSaveState,
                    scope: this
                },
                afterrender: function() {
                    const trigger = Ext.get('modx-leftbar-trigger');
                    trigger.on('click', function() {
                        if (this.collapsed) {
                            this.expand(true);
                        } else {
                            this.collapse(true);
                        }
                    }, this);
                }
            }
        };
    },

    initPopper: function() {
        const
            el = this,
            buttons = document.getElementById('modx-navbar').getElementsByClassName('top'),
            position = window.innerWidth <= 960 ? 'bottom' : 'right'
        ;
        for (let i = 0; i < buttons.length; i++) {
            const submenu = document.getElementById(`${buttons[i].id}-submenu`);
            if (submenu) {
                // eslint-disable-next-line no-new, no-undef
                new Popper(buttons[i], submenu, {
                    placement: position,
                    modifiers: {
                        arrow: {
                            element: submenu.getElementsByClassName('modx-subnav-arrow')[0]
                        },
                        flip: {
                            enabled: false
                        },
                        applyStyle: {
                            enabled: true,
                            fn: function(data) {
                                Object.keys(data.offsets.popper).forEach(prop => {
                                    if (prop !== 'bottom' && prop !== 'right') {
                                        data.instance.popper.style[prop] = !Number.isNaN(parseFloat(data.offsets.popper[prop]))
                                            ? `${data.offsets.popper[prop]}px`
                                            : data.offsets.popper[prop]
                                        ;
                                    }
                                    if (data.offsets.arrow.top !== '') {
                                        data.arrowElement.style.top = `${data.offsets.arrow.top}px`;
                                    }
                                    if (data.offsets.arrow.left) {
                                        data.arrowElement.style.left = `${data.offsets.arrow.left}px`;
                                    }
                                });
                            }
                        },
                        preventOverflow: {
                            boundariesElement: document.getElementById('modx-container'),
                            priority: position === 'right'
                                ? ['bottom', 'top']
                                : ['left', 'right']
                        }
                    }
                });
                buttons[i].addEventListener('click', function(e) {
                    e.stopPropagation();
                    // eslint-disable-next-line prefer-destructuring
                    el.focusRestoreEl = this.querySelectorAll('a')[0];
                    el.showMenu(this);
                });
            }
        }
        window.addEventListener('click', () => {
            el.hideMenu();
        });
        if (window.innerWidth > 960) {
            this.initSubPopper();
        }
    },

    initSubPopper: function() {
        const
            buttons = document.querySelectorAll('#modx-header .sub, #modx-footer .sub'),
            position = window.innerWidth <= 960 ? 'bottom' : 'right'
        ;
        for (let i = 0; i < buttons.length; i++) {
            let popperInstance = null;

            function create(button, submenu) {
                // eslint-disable-next-line no-undef
                popperInstance = new Popper(button, submenu, {
                    placement: position,
                    modifiers: {
                        flip: {
                            enabled: false
                        },
                        applyStyle: {
                            enabled: true,
                            fn: function(data) {
                                Object.keys(data.offsets.popper).forEach(prop => {
                                    if (prop !== 'bottom' && prop !== 'right') {
                                        data.instance.popper.style[prop] = !Number.isNaN(parseFloat(data.offsets.popper[prop]))
                                            ? `${data.offsets.popper[prop]}px`
                                            : data.offsets.popper[prop]
                                        ;
                                    }
                                });
                            }
                        },
                        preventOverflow: {
                            boundariesElement: document.getElementById('modx-container'),
                            priority: position === 'right'
                                ? ['bottom', 'top']
                                : ['left', 'right']
                        }
                    }
                });
            }

            function destroy() {
                if (popperInstance) {
                    popperInstance.destroy();
                    popperInstance = null;
                }
            }

            function show(button) {
                const
                    submenu = button.getElementsByTagName('ul')[0],
                    focusRestore = e => {
                        requestAnimationFrame(() => {
                            if (!submenu.contains(document.activeElement)) {
                                submenu.classList.remove('active');
                                window.removeEventListener('focusout', focusRestore);
                            }
                        });
                    }
                ;
                button.classList.add('active');
                submenu.classList.add('active');
                create(button, submenu);
                window.addEventListener('focusout', focusRestore);
            }

            function hide(button) {
                const
                    parentmenu = button.closest('ul'),
                    buttons = parentmenu.querySelectorAll('.sub')
                ;
                button.classList.remove('active');
                for (let i = 0; i < buttons.length; i++) {
                    const submenu = buttons[i].getElementsByTagName('ul')[0];
                    submenu.classList.remove('active');
                    submenu.removeAttribute('style');
                    buttons[i].classList.remove('active');
                }
                destroy();
            }
            buttons[i].addEventListener('mouseenter', function(e) {
                e.stopPropagation();
                show(this);
            });
            buttons[i].querySelectorAll('a')[0].addEventListener('focus', function(e) {
                e.stopPropagation();
                requestAnimationFrame(() => {
                    show(this.parentNode);
                });
            });
            buttons[i].addEventListener('mouseleave', function(e) {
                e.stopPropagation();
                hide(this);
            });
        }
    },

    showMenu: function(el) {
        const submenu = document.getElementById(`${el.id}-submenu`);
        if (submenu.classList.contains('active')) {
            submenu.classList.remove('active');
        } else {
            let isClick = false;
            this.hideMenu();
            submenu.classList.add('active');
            setTimeout(() => {
                const firstFocusEl = submenu.querySelectorAll('a')[0];
                if (!firstFocusEl) {
                    return;
                }
                firstFocusEl.focus();
            }, 50);
            const
                menuItemClicked = e => {
                    isClick = true;
                    window.removeEventListener('click', menuItemClicked);
                },
                focusRestore = e => {
                    requestAnimationFrame(() => {
                        if (!submenu.contains(document.activeElement)) {
                            if (!isClick) {
                                this.focusRestoreEl?.focus();
                            }
                            this.hideMenu();
                            window.removeEventListener('focusout', focusRestore);
                        }
                    });
                },
                menuArrowKeysNavigation = e => {
                    if (e.code === 'Escape') {
                        this.hideMenu();
                        this.focusRestoreEl?.focus();
                        window.removeEventListener('keyup', menuArrowKeysNavigation);
                    }
                }
            ;
            window.addEventListener('click', menuItemClicked);
            window.addEventListener('focusout', focusRestore);
            window.addEventListener('keyup', menuArrowKeysNavigation);
        }
        this.hideSubMenu();
    },

    hideMenu: function() {
        const submenus = document.getElementsByClassName('modx-subnav');
        for (let i = 0; i < submenus.length; i++) {
            submenus[i].classList.remove('active');
        }
    },

    hideSubMenu: function() {
        const buttons = document.getElementById('modx-footer').querySelectorAll('.sub');
        for (let i = 0; i < buttons.length; i++) {
            const submenu = buttons[i].getElementsByTagName('ul')[0];
            submenu.classList.remove('active');
            buttons[i].classList.remove('active');
        }
    },

    /**
     * Convenient method to target the west region
     *
     * @returns {Ext.Component|void}
     */
    getLeftBar: function() {
        const nav = Ext.getCmp('modx-leftbar-tabpanel');
        if (nav) {
            return nav;
        }

        return null;
    },

    /**
     * Add the given item(s) to the west container
     *
     * @param {Object|Array} items
     */
    addToLeftBar: function(items) {
        const nav = this.getLeftBar();
        if (nav && items) {
            nav.add(items);
            this.onAfterLeftBarAdded(nav, items);
        }
    },

    /**
     * Method executed after some item(s) has been added to the west container
     *
     * @param {Ext.Component} nav The container
     * @param {Object|Array} items Added item(s)
     */
    onAfterLeftBarAdded: function(nav, items) {

    },

    /**
     * Set keyboard shortcuts
     */
    loadKeys: function() {
        Ext.KeyMap.prototype.stopEvent = true;
        const k = new Ext.KeyMap(Ext.get(document));
        // ctrl + shift + h : toggle left bar
        k.addBinding({
            key: Ext.EventObject.H,
            ctrl: true,
            shift: true,
            fn: this.toggleLeftbar,
            scope: this,
            stopEvent: true
        });
        // ctrl + shift + n : new document
        k.addBinding({
            key: Ext.EventObject.N,
            ctrl: true,
            shift: true,
            fn: function() {
                const t = Ext.getCmp('modx-resource-tree');
                if (t) { t.quickCreate(document, {}, 'MODX\\Revolution\\modDocument', 'web', 0); }
            },
            stopEvent: true
        });
        // ctrl + shift + u : clear cache
        k.addBinding({
            key: Ext.EventObject.U,
            ctrl: true,
            shift: true,
            alt: false,
            fn: MODx.clearCache,
            scope: this,
            stopEvent: true
        });

        this.fireEvent('loadKeyMap', {
            keymap: k
        });
    },

    /**
     * Wrapper method to refresh all available trees
     */
    refreshTrees: function() {
        let t;
        t = Ext.getCmp('modx-resource-tree');
        if (t && t.rendered) {
            t.refresh();
        }
        t = Ext.getCmp('modx-tree-element');
        if (t && t.rendered) {
            t.refresh();
        }
        t = Ext.getCmp('modx-file-tree');
        if (t && t.rendered) {
            // Iterate over panel's items (trees) to refresh them
            t.items.each(function(tree, idx) {
                tree.refresh();
            });
        }
    },

    /**
     * Toggle left bar
     */
    toggleLeftbar: function() {
        // eslint-disable-next-line no-unused-expressions
        Ext.getCmp('modx-leftbar-tabs').collapsed
            ? this.showLeftbar(true)
            : this.hideLeftbar(true)
        ;
    },

    /**
     * Hide the left bar
     *
     * @param {Boolean} [anim] Whether or not to animate the transition
     * @param {Boolean} [state] Whether or not to save the component's state
     */
    hideLeftbar: function(anim, state) {
        Ext.get('modx-leftbar-trigger').addClass('collapsed');
        Ext.getCmp('modx-leftbar-tabs').collapse(anim);
        if (Ext.isBoolean(state)) {
            this.stateSave = state;
        }
    },

    /**
     * Show the left bar
     *
     * @param {Boolean} [anim] Whether or not to animate the transition
     */
    showLeftbar: function(anim) {
        Ext.get('modx-leftbar-trigger').removeClass('collapsed');
        Ext.getCmp('modx-leftbar-tabs').expand(anim);
    },

    /**
     * Actions performed before we save the component state
     *
     * @param {Ext.Component} component
     * @param {Object} state
     */
    onBeforeSaveState: function(component, state) {
        const { collapsed } = state;
        if (collapsed && !this.stateSave) {
            // Stateful status changed to prevent saving the state
            this.stateSave = true;
            return false;
        }
        if (!collapsed) {
            const wrap = Ext.get('modx-leftbar').down('div');
            if (!wrap.isVisible()) {
                // Set the "masking div" to visible
                wrap.setVisible(true);
                Ext.getCmp('modx-leftbar-tabpanel').expand(true);
            }
        }
    }
});

/**
 * Handles layout functions. In module format for easier privitization.
 * @class MODx.LayoutMgr
 */
MODx.LayoutMgr = function() {
    let _activeMenu = 'menu0';
    return {
        getPage: function(action, parameters) {
            const parts = [];
            if (action) {
                if (action.startsWith('?') || action.startsWith('index.php?')) {
                    parts.push(action);
                } else {
                    parts.push(`?a=${action.toLowerCase()}`);
                }
            }
            if (parameters) {
                if (typeof parameters === 'object') {
                    Object.entries(parameters).forEach(([key, value]) => {
                        parts.push(`${key}=${value}`);
                    });
                } else {
                    parts.push(parameters);
                }
            }
            return parts.join('&');
        },
        loadPage: function(action, parameters) {
            // Handles url, passed as first argument
            const url = MODx.LayoutMgr.getPage(action, parameters);
            if (MODx.fireEvent('beforeLoadPage', url)) {
                const
                    e = window.event,
                    middleMouseButtonClick = (e && (e.button === 4 || e.which === 2)),
                    keyboardKeyPressed = (e && (e.button === 1 || e.ctrlKey === true || e.metaKey === true || e.shiftKey === true))
                ;
                if (middleMouseButtonClick || keyboardKeyPressed) {
                    // Middle mouse button click or keyboard key pressed,
                    // let the browser handle the way it should be opened (new tab/window)
                    return window.open(url);
                }

                window.location.href = url;
            }
            return false;
        },
        changeMenu: function(a, sm) {
            if (sm === _activeMenu) {
                return false;
            }

            Ext.get(sm).addClass('active');
            const om = Ext.get(_activeMenu);
            if (om) {
                om.removeClass('active');
            }
            _activeMenu = sm;
            return false;
        }
    };
}();

/* aliases for quicker reference */
MODx.getPage = MODx.LayoutMgr.getPage;
MODx.loadPage = MODx.LayoutMgr.loadPage;
MODx.showDashboard = MODx.LayoutMgr.showDashboard;
MODx.hideDashboard = MODx.LayoutMgr.hideDashboard;
MODx.changeMenu = MODx.LayoutMgr.changeMenu;
