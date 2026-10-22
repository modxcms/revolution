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

/** @type {WeakMap<HTMLElement, {update: Function}>} */
/** @type {WeakMap<HTMLElement, object>} */
/** @type {WeakMap<HTMLElement, ReturnType<typeof setTimeout>>} */
/** @type {WeakMap<HTMLElement, {parent: HTMLElement, handler: Function}>} */
/** @type {WeakSet<HTMLElement>} */
const
    menuScrollListeners = new WeakMap(),
    subPoppers = new WeakMap(),
    subHideTimers = new WeakMap(),
    subParentScroll = new WeakMap(),
    subFlyoutBound = new WeakSet();

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
            east = this.getEast(config);
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

    /**
     * Build left-bar tree tabs (resources, elements, files) when permitted.
     *
     * @param {Object} config
     *
     * @returns {Object}
     */
    getTree: function(config) {
        const
            tabs = [],
            activeTab = 0,
            layout = this;
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
                        const
                            baseTabs = this,
                            header = Ext.get('modx-leftbar-header');
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
                                                        tooltipTarget = new Ext.Element(tabEl);
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

    /**
     * Attach Popper to top-level header menus and wire click/focus open.
     */
    initPopper: function() {
        const
            el = this,
            buttons = document.getElementById('modx-navbar').getElementsByClassName('top'),
            position = window.innerWidth <= 960 ? 'bottom' : 'right';
        for (let i = 0; i < buttons.length; i++) {
            const submenu = document.getElementById(`${buttons[i].id}-submenu`);
            if (submenu) {
                // eslint-disable-next-line no-new, no-undef
                new Popper(buttons[i], submenu, {
                    placement: position,
                    positionFixed: true,
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
                                const
                                    popperStyle = data.instance.popper.style,
                                    arrowStyle = data.arrowElement.style,
                                    popperOffsets = data.offsets.popper,
                                    arrowOffsets = data.offsets.arrow;
                                Object.keys(popperOffsets).forEach(prop => {
                                    // Let CSS max-height own vertical size so long menus scroll.
                                    if (prop !== 'bottom' && prop !== 'right' && prop !== 'height') {
                                        popperStyle[prop] = !Number.isNaN(
                                            parseFloat(popperOffsets[prop])
                                        )
                                            ? `${popperOffsets[prop]}px`
                                            : popperOffsets[prop];
                                    }
                                    if (arrowOffsets.top !== '') {
                                        arrowStyle.top = `${arrowOffsets.top}px`;
                                    }
                                    if (arrowOffsets.left) {
                                        arrowStyle.left = `${arrowOffsets.left}px`;
                                    }
                                });
                            }
                        },
                        preventOverflow: {
                            boundariesElement: 'viewport',
                            priority: position === 'right' ? ['bottom', 'top'] : ['left', 'right']
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

    /**
     * Set .scrollable, .at-start, and .at-end from the menu scroll position.
     *
     * @param {HTMLElement} menu
     */
    updateMenuScrollState: function(menu) {
        if (!menu) {
            return;
        }
        const
            { scrollTop, scrollHeight, clientHeight } = menu,
            maxScroll = scrollHeight - clientHeight,
            scrollable = maxScroll > 1;
        menu.classList.toggle('scrollable', scrollable);
        menu.classList.toggle('at-start', !scrollable || scrollTop <= 1);
        menu.classList.toggle('at-end', !scrollable || scrollTop >= maxScroll - 1);
    },

    /**
     * Keep scroll edge classes updated on scroll and window resize.
     *
     * @param {HTMLElement} menu
     */
    bindMenuScrollState: function(menu) {
        if (!menu || menuScrollListeners.has(menu)) {
            return;
        }
        const update = () => {
            this.updateMenuScrollState(menu);
        };
        // Sync first so .scrollable (and overflow) apply before paint; rAF rechecks.
        update();
        menu.addEventListener('scroll', update, { passive: true });
        window.addEventListener('resize', update);
        menuScrollListeners.set(menu, { update });
        requestAnimationFrame(update);
    },

    /**
     * Remove scroll listeners and clear scroll classes on the menu.
     *
     * @param {HTMLElement} menu
     */
    unbindMenuScrollState: function(menu) {
        if (!menu || !menuScrollListeners.has(menu)) {
            return;
        }
        const
            state = menuScrollListeners.get(menu),
            update = state && state.update;
        if (update) {
            menu.removeEventListener('scroll', update);
            window.removeEventListener('resize', update);
        }
        menu.classList.remove('scrollable', 'at-start', 'at-end');
        menuScrollListeners.delete(menu);
    },

    /**
     * Wire nested .sub menus: Popper flyouts, hover open/close, scroll sync.
     * Desktop only (viewport wider than 960px).
     */
    initSubPopper: function() {
        const
            el = this,
            buttons = document.querySelectorAll('#modx-header .sub, #modx-footer .sub'),
            position = window.innerWidth <= 960 ? 'bottom' : 'right';
        for (let i = 0; i < buttons.length; i++) {
            /**
             * Create or recreate the Popper instance for a nested flyout.
             *
             * @param {HTMLElement} button
             * @param {HTMLElement} submenu
             */
            function create(button, submenu) {
                destroy(button);
                // Avoid leftover absolute bottom/height before Popper measures.
                submenu.style.bottom = 'auto';
                submenu.style.height = 'auto';
                // eslint-disable-next-line no-undef
                const popper = new Popper(button, submenu, {
                    placement: position,
                    // Keep nested flyouts outside the scrolling parent clip.
                    positionFixed: true,
                    // Parent .modx-subnav is itself a scrollport; skip Popper scroll
                    // listeners to avoid update feedback while the menu scrolls.
                    eventsEnabled: false,
                    modifiers: {
                        flip: {
                            enabled: false
                        },
                        applyStyle: {
                            enabled: true,
                            fn: function(data) {
                                const
                                    popperStyle = data.instance.popper.style,
                                    popperOffsets = data.offsets.popper;
                                // Clear CSS bottom/height so fixed + top does not stretch the panel.
                                popperStyle.bottom = 'auto';
                                popperStyle.height = 'auto';
                                Object.keys(popperOffsets).forEach(prop => {
                                    // Let CSS max-height own vertical size so long menus scroll.
                                    if (prop !== 'bottom' && prop !== 'right' && prop !== 'height') {
                                        popperStyle[prop] = !Number.isNaN(
                                            parseFloat(popperOffsets[prop])
                                        )
                                            ? `${popperOffsets[prop]}px`
                                            : popperOffsets[prop];
                                    }
                                });
                            }
                        },
                        preventOverflow: {
                            boundariesElement: 'viewport',
                            priority: position === 'right' ? ['bottom', 'top'] : ['left', 'right']
                        },
                        // Keep a 1px gap so the fixed flyout does not sit under the caret.
                        offset: {
                            offset: position === 'right' ? '0, 1' : '0, 0'
                        }
                    }
                });
                subPoppers.set(button, popper);
            }

            /**
             * Destroy the Popper for this button, if any.
             *
             * @param {HTMLElement} button
             */
            function destroy(button) {
                const popper = button && subPoppers.get(button);
                if (popper) {
                    popper.destroy();
                    subPoppers.delete(button);
                }
            }

            /**
             * Cancel a pending delayed hide for this button.
             *
             * @param {HTMLElement} button
             */
            function clearHideTimer(button) {
                const timer = button && subHideTimers.get(button);
                if (timer) {
                    clearTimeout(timer);
                    subHideTimers.delete(button);
                }
            }

            /**
             * Hide the flyout after a short delay unless the pointer is still over it.
             *
             * @param {HTMLElement} button
             */
            function scheduleHide(button) {
                clearHideTimer(button);
                const timer = setTimeout(() => {
                    subHideTimers.delete(button);
                    const submenu = button.getElementsByTagName('ul')[0];
                    if (button.matches(':hover') || (submenu && submenu.matches(':hover'))) {
                        return;
                    }
                    hide(button);
                }, 120);
                subHideTimers.set(button, timer);
            }

            /**
             * Close this flyout and any nested .sub panels inside it.
             *
             * @param {HTMLElement} button
             */
            function hide(button) {
                clearHideTimer(button);
                const submenu = button.getElementsByTagName('ul')[0];
                button.classList.remove('active');
                if (!submenu) {
                    destroy(button);
                    return;
                }
                // Close deeper nested .sub panels inside this flyout only.
                const nestedButtons = submenu.querySelectorAll(':scope > li.sub');
                for (let n = 0; n < nestedButtons.length; n++) {
                    hide(nestedButtons[n]);
                }
                const parentScroll = subParentScroll.get(submenu);
                if (parentScroll) {
                    parentScroll.parent.removeEventListener('scroll', parentScroll.handler);
                    subParentScroll.delete(submenu);
                }
                el.unbindMenuScrollState(submenu);
                submenu.classList.remove('active');
                submenu.removeAttribute('style');
                destroy(button);
            }

            /**
             * Open the nested flyout for this .sub item; close sibling flyouts only.
             *
             * @param {HTMLElement} button
             */
            function show(button) {
                const submenu = button.getElementsByTagName('ul')[0];
                if (!submenu) {
                    return;
                }
                clearHideTimer(button);

                // Close only sibling items at this level so parent flyouts stay open.
                const parentUl = button.parentElement;
                if (parentUl) {
                    const siblings = parentUl.querySelectorAll(':scope > li.sub');
                    for (let s = 0; s < siblings.length; s++) {
                        if (siblings[s] !== button) {
                            hide(siblings[s]);
                        }
                    }
                }

                const parentMenu = button.closest('.modx-subnav, .modx-subsubnav');

                /**
                 * Reposition the flyout when the parent scrollport moves.
                 */
                function onParentScroll() {
                    const popper = subPoppers.get(button);
                    if (popper) {
                        popper.scheduleUpdate();
                    }
                }

                /**
                 * Close the flyout when focus leaves it and its trigger.
                 */
                function focusRestore() {
                    requestAnimationFrame(() => {
                        if (!submenu.contains(document.activeElement)) {
                            submenu.classList.remove('active');
                            el.unbindMenuScrollState(submenu);
                            const parentScroll = subParentScroll.get(submenu);
                            if (parentScroll) {
                                parentScroll.parent.removeEventListener(
                                    'scroll',
                                    parentScroll.handler
                                );
                                subParentScroll.delete(submenu);
                            }
                            destroy(button);
                            button.classList.remove('active');
                            window.removeEventListener('focusout', focusRestore);
                        }
                    });
                }

                button.classList.add('active');
                submenu.classList.add('active');
                create(button, submenu);
                el.bindMenuScrollState(submenu);
                if (parentMenu) {
                    const existing = subParentScroll.get(submenu);
                    if (existing) {
                        existing.parent.removeEventListener('scroll', existing.handler);
                    }
                    parentMenu.addEventListener('scroll', onParentScroll, { passive: true });
                    subParentScroll.set(submenu, {
                        parent: parentMenu,
                        handler: onParentScroll
                    });
                }
                if (!subFlyoutBound.has(submenu)) {
                    submenu.addEventListener('mouseenter', () => {
                        clearHideTimer(button);
                        // Keep ancestor flyouts open while the pointer is in this panel.
                        document.querySelectorAll('li.sub.active').forEach(li => {
                            clearHideTimer(li);
                        });
                    });
                    submenu.addEventListener('mouseleave', e => {
                        const next = e.relatedTarget;
                        if (button.contains(next) || submenu.contains(next)) {
                            return;
                        }
                        // Nested flyouts are position:fixed outside this panel; moving
                        // onto them must not close the parent chain (e.g. More → miniShop3).
                        const childFlyouts = submenu.querySelectorAll(':scope > li.sub > ul');
                        for (let c = 0; c < childFlyouts.length; c++) {
                            const fly = childFlyouts[c];
                            if (fly.classList.contains('active') && (fly === next || fly.contains(next))) {
                                return;
                            }
                        }
                        scheduleHide(button);
                    });
                    subFlyoutBound.add(submenu);
                }
                window.addEventListener('focusout', focusRestore);
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
                const button = this,
                      submenu = button.getElementsByTagName('ul')[0];
                // position:fixed flyouts sit outside the li box; moving onto them
                // must not count as leaving the item (avoids show/hide thrash).
                if (submenu && e.relatedTarget && (
                    submenu === e.relatedTarget || submenu.contains(e.relatedTarget)
                )) {
                    return;
                }
                scheduleHide(button);
            });
        }
    },

    /**
     * Toggle a top-level header submenu open or closed.
     *
     * @param {HTMLElement} el Top menu trigger (e.g. limenu-*)
     */
    showMenu: function(el) {
        const submenu = document.getElementById(`${el.id}-submenu`);
        if (submenu.classList.contains('active')) {
            this.unbindMenuScrollState(submenu);
            submenu.classList.remove('active');
        } else {
            let isClick = false;
            this.hideMenu();
            submenu.classList.add('active');
            this.bindMenuScrollState(submenu);
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
                };
            window.addEventListener('click', menuItemClicked);
            window.addEventListener('focusout', focusRestore);
            window.addEventListener('keyup', menuArrowKeysNavigation);
        }
        this.hideSubMenu();
    },

    /**
     * Close every open .modx-subnav / .modx-subsubnav and clear scroll state.
     */
    hideMenu: function() {
        const submenus = document.querySelectorAll('.modx-subnav, .modx-subsubnav');
        for (let i = 0; i < submenus.length; i++) {
            this.unbindMenuScrollState(submenus[i]);
            submenus[i].classList.remove('active');
        }
    },

    /**
     * Close nested flyouts under #modx-footer (user menu and similar).
     */
    hideSubMenu: function() {
        const footer = document.getElementById('modx-footer');
        if (!footer) {
            return;
        }
        const buttons = footer.querySelectorAll('.sub');
        for (let i = 0; i < buttons.length; i++) {
            const submenu = buttons[i].getElementsByTagName('ul')[0];
            if (submenu) {
                this.unbindMenuScrollState(submenu);
                submenu.classList.remove('active');
                buttons[i].classList.remove('active');
            }
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
            t.items.each(tree => {
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
            : this.hideLeftbar(true);
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
        /**
         * Build a manager URL from an action name and optional query params.
         *
         * @param {string} [action]
         * @param {string|Object} [parameters]
         *
         * @returns {string}
         */
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
        /**
         * Go to a manager page. Pass the click event when you have one so
         * middle-click / modifier keys can open a new tab.
         *
         * @param {string} action
         * @param {string|Object} [parameters]
         * @param {Event} [e]
         *
         * @returns {Window|boolean}
         */
        loadPage: function(action, parameters, e) {
            const url = MODx.LayoutMgr.getPage(action, parameters);
            if (MODx.fireEvent('beforeLoadPage', url)) {
                const
                    middleMouseButtonClick = e && (e.button === 4 || e.which === 2),
                    keyboardKeyPressed = e && (
                        e.button === 1
                        || e.ctrlKey === true
                        || e.metaKey === true
                        || e.shiftKey === true
                    );
                if (middleMouseButtonClick || keyboardKeyPressed) {
                    // Middle mouse or modifier key: let the browser open a new tab/window.
                    return window.open(url);
                }

                window.location.href = url;
            }
            return false;
        },
        /**
         * Mark the given menu item active and clear the previous one.
         *
         * @param {*} a Unused (legacy signature)
         * @param {string} sm Element id of the menu item to activate
         *
         * @returns {boolean}
         */
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
