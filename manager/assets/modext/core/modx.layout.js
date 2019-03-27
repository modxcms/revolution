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

MODx.Layout = function(config){
    config = config || {};
    Ext.BLANK_IMAGE_URL = MODx.config.manager_url+'assets/ext3/resources/images/default/s.gif';
    Ext.Ajax.defaultHeaders = {
        'modAuth': config.auth
    };
    Ext.Ajax.extraParams = {
        'HTTP_MODAUTH': config.auth
    };
    MODx.siteId = config.auth;
    MODx.expandHelp = !!+MODx.config.inline_help;

    var sp = new MODx.HttpProvider();
    Ext.state.Manager.setProvider(sp);
    sp.initState(MODx.defaultState);

    config.showTree = false;
    if (config.search) {
        new MODx.SearchBar();
    }

    Ext.applyIf(config, {
        layout: 'border'
        ,id: 'modx-layout'
        ,stateSave: true
        ,items: this.buildLayout(config)
    });
    MODx.Layout.superclass.constructor.call(this,config);
    this.config = config;

    this.addEvents({
        'afterLayout': true
        ,'loadKeyMap': true
        ,'loadTabs': true
    });
    this.loadKeys();
    if (!config.showTree) {
        Ext.getCmp('modx-leftbar-tabs').collapse(false);
    }
    this.fireEvent('afterLayout');
};
Ext.extend(MODx.Layout, Ext.Viewport, {
    /**
     * Wrapper method to build the layout regions
     *
     * @param {Object} config
     *
     * @returns {Array}
     */
    buildLayout: function(config) {
        var items = []
            ,north = this.getNorth(config)
            ,west = this.getWest(config)
            ,center = this.getCenter(config)
            ,south = this.getSouth(config)
            ,east = this.getEast(config);

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
    }
    /**
     * Build the north region (header)
     *
     * @param {Object} config
     *
     * @returns {Object|void}
     */
    ,getNorth: function(config) {
        if (window.innerWidth <= 640) {
            return {
                xtype: 'box',
                region: 'north',
                applyTo: 'modx-header',
                listeners: {
                    afterrender: this.initPopper
                    ,scope: this
                }
            };
        }

        return false;
    }
    /**
     * Build the west region (trees)
     *
     * @param {Object} config
     *
     * @returns {Object|void}
     */
    ,getWest: function(config) {
        if (window.innerWidth <= 640) {
            return this.getTree(config);
        }

        return {
            region: 'west'
            ,xtype: 'box'
            ,id: 'modx-header'
            ,applyTo: 'modx-header'
            ,autoScroll: true
            ,width: 80
            ,listeners: {
                afterrender: this.initPopper
                ,scope: this
            }
        };
    }
    /**
     * Build the center region (main content)
     *
     * @param {Object} config
     *
     * @returns {Object|void}
     */
    ,getCenter: function(config) {
        var center = {
            region: 'center',
            applyTo: 'modx-content',
            padding: '0 1px 0 0',
            style: 'width:100%',
            bodyStyle: 'background-color:transparent;',
            id: 'modx-content',
            autoScroll: true,
        };
        if (window.innerWidth <= 640) {
            return center;
        }

        var tree = this.getTree(config);
        center.margins = {
            right: -80,
            left: -8,
        };
        tree.margins = {
            left: 80
        };

        return {
            region: 'center',
            layout: 'border',
            id: 'modx-split-wrapper',
            items: [tree, center]
        };
    }
    /**
     * Build the south region (footer)
     *
     * @param {Object} config
     *
     * @returns {Object|void}
     */
    ,getSouth: function(config) {
    }
    /**
     * Build the east region
     *
     * @param {Object} config
     *
     * @returns {Object|void}
     */
    ,getEast: function(config) {
    }

    ,getTree: function(config) {
        var tabs = [];
        if (MODx.perm.resource_tree) {
            tabs.push({
                title: _('resources')
                ,xtype: 'modx-tree-resource'
                ,id: 'modx-resource-tree'
            });
            config.showTree = true;
        }
        if (MODx.perm.element_tree) {
            tabs.push({
                title: _('elements')
                ,xtype: 'modx-tree-element'
                ,id: 'modx-tree-element'
            });
            config.showTree = true;
        }
        if (MODx.perm.file_tree) {
            tabs.push({
                title: _('files')
                ,xtype: 'modx-panel-filetree'
                ,id: 'modx-file-tree'
            });
            config.showTree = true;
        }
        var activeTab = 0;

        return {
            region: 'west'
            ,applyTo: 'modx-leftbar'
            ,id: 'modx-leftbar-tabs'
            ,split: true
            ,width: 270
            ,minSize: 270
            ,autoScroll: true
            ,unstyled: true
            ,useSplitTips: true
            ,monitorResize: true
            ,layout: 'anchor'
            ,headerCfg: window.innerWidth <= 640 ? {} : {
                tag: 'div',
                cls: 'none',
                id: 'modx-leftbar-header',
                html: MODx.config.site_name
            }
            ,items: [{
                xtype: 'modx-tabs'
                ,plain: true
                ,defaults: {
                    autoScroll: true
                    ,fitToFrame: true
                }
                ,id: 'modx-leftbar-tabpanel'
                ,border: false
                ,anchor: '100%'
                ,activeTab: activeTab
                ,stateful: true
                ,stateEvents: ['tabchange']
                ,getState:function() {
                    return {
                        activeTab: this.items.indexOf(this.getActiveTab())
                    };
                }
                ,items: tabs
                ,listeners: {
                    afterrender: function () {
                        var tabs = this;
                        MODx.Ajax.request({
                                url: MODx.config.connector_url,
                                params: {
                                    action: 'resource/gettoolbar',
                                },
                                listeners: {
                                    success: {fn: function (res) {
                                        for (var i in res.object) {
                                            if (res.object.hasOwnProperty(i)) {
                                                if (res.object[i].id != undefined && res.object[i].id == 'emptifier') {
                                                    var tab = tabs.add({
                                                        id: 'modx-trash-link',
                                                        title: '<i class="icon icon-trash-o"></i>',
                                                        handler: res.object[i].handler,
                                                    });
                                                    if (!res.object[i].disabled) {
                                                        tab.tabEl.classList.add('active');
                                                    }
                                                    if (res.object[i].tooltip) {
                                                        tab.tooltip = new Ext.ToolTip({
                                                            target: new Ext.Element(tab.tabEl),
                                                            title: res.object[i].tooltip
                                                        });
                                                    }
                                                    break;
                                                }
                                            }
                                        }
                                    }, scope: this}
                                }
                            }
                        );

                        var header = Ext.get('modx-leftbar-header');
                        if (header) {
                            var html = '';
                            if (MODx.config.manager_logo !== '' && MODx.config.manager_logo !== undefined) {
                                html += '<img src="' + MODx.config.manager_logo + '">';
                            }
                            var el = document.createElement('a');
                            el.href = MODx.config.default_site_url || MODx.config.site_url;
                            el.title = MODx.config.site_name;
                            el.innerText = MODx.config.site_name;
                            el.target = '_blank';

                            html += el.outerHTML;
                            header.dom.innerHTML = html;
                        }
                    }
                    ,beforetabchange: {fn: function(panel, tab) {
                        if (tab && tab.id == 'modx-trash-link') {
                            if (tab.tabEl.classList.contains('active')) {
                                var tree = Ext.getCmp('modx-resource-tree');
                                if (tree) {
                                    tree.redirect("?a=resource/trash");
                                }
                            }
                            return false;
                        }
                    }, scope: this}
                }
            }]
            ,getState: function() {
                return {
                    collapsed: this.collapsed,
                    width: this.width
                };
            }
            ,collapse: function(animate){
                if(this.collapsed || this.el.hasFxBlock() || this.fireEvent('beforecollapse', this, animate) === false){
                    return;
                }
                if (animate && window.innerWidth > 640) {
                    var tree = Ext.getCmp('modx-leftbar-tabpanel').getEl();
                    // tree.dom.style.visibility = 'hidden';
                    tree.dom.style.opacity = 0;
                    this.el.dom.style.left = '-' + this.el.dom.style.width;
                } else {
                    this.el.dom.style.display = 'none';
                }
                this.collapsed = true;
                this.saveState();
                this.fireEvent('collapse', this);
                return this;
            }
            ,expand : function(animate) {
                if(!this.collapsed || this.el.hasFxBlock() || this.fireEvent('beforeexpand', this, animate) === false){
                    return;
                }
                if (animate && window.innerWidth > 640) {
                    var tree = Ext.getCmp('modx-leftbar-tabpanel').getEl();
                    window.setTimeout(function() {
                        tree.dom.style.visibility = 'visible';
                        tree.dom.style.opacity = 1;
                    }, 100)
                } else {
                    this.el.dom.style.display = '';
                }
                this.collapsed = false;
                this.saveState();
                this.fireEvent('expand', this);
                return this;
            }
            ,listeners:{
                beforestatesave: {fn: this.onBeforeSaveState, scope: this}
                ,afterrender: function() {
                    var trigger = Ext.get('modx-leftbar-trigger');
                    if (this.collapsed) {
                        trigger.addClass('collapsed');
                    }
                    trigger.on('click', function() {
                        if (this.collapsed) {
                            this.expand(true);
                        } else {
                            this.collapse(true);
                        }
                    }, this);
                },
                collapse: function() {
                    var trigger = Ext.get('modx-leftbar-trigger');
                    trigger.addClass('collapsed');
                },
                expand: function() {
                    var trigger = Ext.get('modx-leftbar-trigger');
                    trigger.removeClass('collapsed');
                }
            }
        };
    }

    ,initPopper: function() {
        var el = this;
        var buttons = document.getElementById('modx-navbar').getElementsByClassName('top');
        var position = window.innerWidth <= 640 ? 'bottom' : 'right';
        for (var i = 0; i < buttons.length; i++) {
            var submenu = document.getElementById(buttons[i].id + '-submenu');
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
                            for (var i in data.offsets.popper) {
                                if (data.offsets.popper.hasOwnProperty(i)) {
                                    data.instance.popper.style[i] = !isNaN(parseFloat(data.offsets.popper[i]))
                                        ? data.offsets.popper[i] + 'px'
                                        : data.offsets.popper[i];
                                }
                                if (data.offsets.arrow.top !== '') {
                                    data.arrowElement.style.top = data.offsets.arrow.top + 'px';
                                }
                                if (data.offsets.arrow.left) {
                                    data.arrowElement.style.left = data.offsets.arrow.left + 'px';
                                }
                            }
                        }
                    },
                    preventOverflow: {
                        boundariesElement: document.getElementById('modx-container'),
                        priority: position === 'right'
                            ? ['bottom','top']
                            : ['left','right']
                    }
                }
            });
            buttons[i].onclick = function(e) {
                e.stopPropagation();
                el.showMenu(this);
            };
        }
        window.addEventListener('click', function() {
            el.hideMenu();
        });
    }

    ,showMenu: function(el) {
        var submenu = document.getElementById(el.id + '-submenu');
        if (submenu.classList.contains('active')) {
            submenu.classList.remove('active');
        } else {
            this.hideMenu();
            submenu.classList.add('active');
        }
    }
    ,hideMenu: function() {
        var submenus = document.getElementsByClassName('modx-subnav');
        for (var i = 0; i < submenus.length; i++) {
            submenus[i].classList.remove('active');
        }
    }

    /**
     * Convenient method to target the west region
     *
     * @returns {Ext.Component|void}
     */
    ,getLeftBar: function() {
        var nav = Ext.getCmp('modx-leftbar-tabpanel');
        if (nav) {
            return nav;
        }

        return null;
    }

    /**
     * Add the given item(s) to the west container
     *
     * @param {Object|Array} items
     */
    ,addToLeftBar: function(items) {
        var nav = this.getLeftBar();
        if (nav && items) {
            nav.add(items);
            this.onAfterLeftBarAdded(nav, items);
        }
    }
    /**
     * Method executed after some item(s) has been added to the west container
     *
     * @param {Ext.Component} nav The container
     * @param {Object|Array} items Added item(s)
     */
    ,onAfterLeftBarAdded: function(nav, items) {

    }


    /**
     * Set keyboard shortcuts
     */
    ,loadKeys: function() {
        Ext.KeyMap.prototype.stopEvent = true;
        var k = new Ext.KeyMap(Ext.get(document));
        // ctrl + shift + h : toggle left bar
        k.addBinding({
            key: Ext.EventObject.H
            ,ctrl: true
            ,shift: true
            ,fn: this.toggleLeftbar
            ,scope: this
            ,stopEvent: true
        });
        // ctrl + shift + n : new document
        k.addBinding({
            key: Ext.EventObject.N
            ,ctrl: true
            ,shift: true
            ,fn: function() {
                var t = Ext.getCmp('modx-resource-tree');
                if (t) { t.quickCreate(document,{},'modDocument','web',0); }
            }
            ,stopEvent: true
        });
        // ctrl + shift + u : clear cache
        k.addBinding({
            key: Ext.EventObject.U
            ,ctrl: true
            ,shift: true
            ,alt: false
            ,fn: MODx.clearCache
            ,scope: this
            ,stopEvent: true
        });

        this.fireEvent('loadKeyMap',{
            keymap: k
        });
    }
    /**
     * Wrapper method to refresh all available trees
     */
    ,refreshTrees: function() {
        var t;
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
    }
    // Why here & why assuming visible ??
    ,leftbarVisible: true
    /**
     * Toggle left bar
     */
    ,toggleLeftbar: function() {
        this.leftbarVisible ? this.hideLeftbar(true) : this.showLeftbar(true);
        // Toggle the left bar visibility
        this.leftbarVisible = !this.leftbarVisible;
    }
    /**
     * Hide the left bar
     *
     * @param {Boolean} [anim] Whether or not to animate the transition
     * @param {Boolean} [state] Whether or not to save the component's state
     */
    ,hideLeftbar: function(anim, state) {
        Ext.getCmp('modx-leftbar-tabs').collapse(anim);
        if (Ext.isBoolean(state)) {
            this.stateSave = state;
        }
    }
    /**
     * Show the left bar
     *
     * @param {Boolean} [anim] Whether or not to animate the transition
     */
    ,showLeftbar: function(anim) {
        Ext.getCmp('modx-leftbar-tabs').expand(anim);
    }
    /**
     * Actions performed before we save the component state
     *
     * @param {Ext.Component} component
     * @param {Object} state
     */
    ,onBeforeSaveState: function(component, state) {
        var collapsed = state.collapsed;
        if (collapsed && !this.stateSave) {
            // Stateful status changed to prevent saving the state
            this.stateSave = true;
            return false;
        }
        if (!collapsed) {
            var wrap = Ext.get('modx-leftbar').down('div');
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
    var _activeMenu = 'menu0';
    return {
        loadPage: function(action, parameters) {
            // Handles url, passed as first argument
            var parts = [];
            if (action) {
                if (isNaN(parseInt(action)) && (action.substr(0,1) == '?' || (action.substr(0, "index.php?".length) == 'index.php?'))) {
                    parts.push(action);
                } else {
                    parts.push('?a=' + action);
                }
            }
            if (parameters) {
                parts.push(parameters);
            }
            var url = parts.join('&');
            if (MODx.fireEvent('beforeLoadPage', url)) {
                var e = window.event;

                var middleMouseButtonClick = (e && (e.button === 4 || e.which === 2));
                var keyboardKeyPressed = (e && (e.button === 1 || e.ctrlKey === true || e.metaKey === true || e.shiftKey === true));
                if (middleMouseButtonClick || keyboardKeyPressed) {
                    // Middle mouse button click or keyboard key pressed,
                    // let the browser handle the way it should be opened (new tab/window)
                    return window.open(url);
                }

                location.href = url;
            }
            return false;
        }
        ,changeMenu: function(a,sm) {
            if (sm === _activeMenu) return false;

            Ext.get(sm).addClass('active');
            var om = Ext.get(_activeMenu);
            if (om) om.removeClass('active');
            _activeMenu = sm;
            return false;
        }
    }
}();

/* aliases for quicker reference */
MODx.loadPage = MODx.LayoutMgr.loadPage;
MODx.showDashboard = MODx.LayoutMgr.showDashboard;
MODx.hideDashboard = MODx.LayoutMgr.hideDashboard;
MODx.changeMenu = MODx.LayoutMgr.changeMenu;
