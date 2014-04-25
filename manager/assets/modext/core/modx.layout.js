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
    MODx.expandHelp = !Ext.isEmpty(MODx.config.inline_help);

    var sp = new MODx.HttpProvider();
    Ext.state.Manager.setProvider(sp);
    sp.initState(MODx.defaultState);

    var tabs = [];
    var showTree = false;
    if (MODx.perm.resource_tree) {
       tabs.push({
            title: _('resources')
            ,xtype: 'modx-tree-resource'
            ,id: 'modx-resource-tree'
        });
        showTree = true;
    }
    if (MODx.perm.element_tree) {
        tabs.push({
            title: _('elements')
            ,xtype: 'modx-tree-element'
            ,id: 'modx-tree-element'
        });
        showTree = true;
    }
    if (MODx.perm.file_tree) {
        tabs.push({
            title: _('files')
            ,xtype: 'modx-panel-filetree'
            ,id: 'modx-file-tree'
        });
        showTree = true;
    }
    var activeTab = 0;

    console.log('original tabs', tabs);

    var items = []
        ,icons = {
            'modx-resource-tree': 'sitemap'
            ,'modx-tree-element': 'bars'
            ,'modx-file-tree': 'folder-open'
        };

    Ext.each(tabs, function(tab, idx) {
        var wrap = {
            title: '<i class="icon icon-'+ (icons[tab.id] || 'asterisk') +'"></i><span class="title">' + tab.title +'</span>'
            ,stateId: 'modx-leftbar-tab-'+idx
//            ,collapsible: true
//            ,cls: 'menu-section'
//            ,titleCollapse: true
//            // Remove any "tool" markup
//            ,toolTemplate: new Ext.Template('')
        };
        tab.title = '';
        // Nullify toolbars
        tab.remoteToolbar = false;
        tab.tbar = {
            hidden: true
        };
        tab.useDefaultToolbar = false;
        wrap.items = tab;
        items.push(wrap);
    });
    console.log('transformed items/tabs', items);

    // Mimic an extra "menu"
    Ext.defer(function() {
        console.log('Extra menus should be added');
        var menu = Ext.getCmp('modx-leftbar-tabs')
            ,i = 0;

        while (i < 1) {
            menu.add({
                title: '<i class="icon icon-asterisk"></i><span class="title">Some extra row ['+ i +']</span>'
                ,collapsed: true
                ,items: [{
                    html: '<p>Just some random stuff</p>'
                    ,border: false
                }]
            });
            i += 1;
        }

        // Force rendering
        menu.doLayout();
    }, 5000, this);

    Ext.applyIf(config,{
         layout: 'border'
        ,id: 'modx-layout'
        ,saveState: true
        ,items: [{
            xtype: 'box'
            ,region: 'north'
            ,applyTo: 'modx-header'
            ,height: 43
        },{
             region: 'west'
            ,applyTo: 'modx-leftbar'
            ,id: 'modx-leftbar-tabs'
            ,split: true
            ,width: 310
            ,minSize: 288
            ,maxSize: 800
            ,autoScroll: true
            ,unstyled: true
            ,collapseMode: 'mini'
            ,useSplitTips: true
            ,monitorResize: true
            // Some tests with card layout to prevent rendering of all elements
//            ,layout: {
//                type: 'card'
//                ,deferredRender: true
//            }
//            ,activeItem: 0
            ,layout: 'anchor'
            // No wrapper (less DOM)
            ,items: items
            ,defaults: {
                collapsible: true
                ,collapsed: true
                ,cls: 'menu-section'
                ,titleCollapse: true
                // Remove any "tool" markup
                //,toolTemplate: new Ext.Template('')
                ,hideCollapseTool: true
            }
            // Wrapper (mostly to mimic the styles so far, and keep the ID)
//            ,items: [{
//                xtype: 'panel'
//                ,id: 'modx-leftbar-tabpanel'
//                ,cls: 'x-tab-panel-noborder'
//                ,items: items
//                ,defaults: {
//                    stateful: true
//                }
//            }]
            // Original tabs
//            ,items: [{
//                 xtype: 'modx-tabs'
//                ,plain: true
//                ,defaults: {
//                     autoScroll: true
//                    ,fitToFrame: true
//                }
//                ,id: 'modx-leftbar-tabpanel'
//                ,border: false
//                ,anchor: '100%'
//                ,activeTab: activeTab
//                ,stateful: true
//                ,stateId: 'modx-leftbar-tabs'
//                ,stateEvents: ['tabchange']
//                ,getState:function() {
//                    return {
//                        activeTab:this.items.indexOf(this.getActiveTab())
//                    };
//                }
//                ,items: tabs
//            }]
            ,listeners:{
                statesave: this.onStatesave
                ,scope: this
            }
        },{
            region: 'center'
            ,applyTo: 'modx-content'
            ,padding: '0 1px 0 0'
            ,bodyStyle: 'background-color:transparent;'
            ,id: 'modx-content'
            ,border: false
            ,autoScroll: true
        }/*,{
            region: 'south' // ya, you're going south alright
            ,applyTo: 'modx-footer'
            ,border: false
            ,id: 'modx-footer'
            ,html: '<p><b>' + MODx.config.site_name + '</b> ' + _('powered_by') + ' <a href="http://modx.com/?utm_source=revo&utm_medium=manager&utm_campaign=Revolution+Footer+Link" onclick="window.open(this.href); return false;" title="Visit the MODX website">MODX®</a></p>'
            ,bodyStyle: 'background-color:transparent;'
        }*/]
    });
    MODx.Layout.superclass.constructor.call(this,config);
    this.config = config;

    this.addEvents({
        'afterLayout': true
        ,'loadKeyMap': true
        ,'loadTabs': true
    });
    this.loadKeys();
    if (!showTree) {
        Ext.getCmp('modx-leftbar-tabs').collapse(false);
        Ext.get('modx-leftbar').hide();
        Ext.get('modx-leftbar-tabs-xcollapsed').setStyle('display','none');
    }
    this.fireEvent('afterLayout');
};
Ext.extend(MODx.Layout,Ext.Viewport,{
    loadKeys: function() {
        Ext.KeyMap.prototype.stopEvent = true;
        var k = new Ext.KeyMap(Ext.get(document));
        k.addBinding({
            key: Ext.EventObject.H
            ,ctrl: true
            ,shift: true
            ,fn: this.toggleLeftbar
            ,scope: this
            ,stopEvent: true
        });
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

    ,refreshTrees: function() {
        var t;
        t = Ext.getCmp('modx-resource-tree'); if (t) { t.refresh(); }
        t = Ext.getCmp('modx-element-tree'); if (t) { t.refresh(); }
        t = Ext.getCmp('modx-file-tree'); if (t) { t.refresh(); }
    }
    ,leftbarVisible: true
    ,toggleLeftbar: function() {
        this.leftbarVisible ? this.hideLeftbar(.3) : this.showLeftbar(.3);
        this.leftbarVisible = !this.leftbarVisible;
    }
    ,hideLeftbar: function(anim, state) {
        Ext.getCmp('modx-leftbar-tabs').collapse(anim);
        if(state != undefined){	this.saveState = state;	}
    }
    ,onStatesave: function(p, state){
        var panelState = state.collapsed;
        if (!panelState) {
            var wrap = Ext.get('modx-leftbar').down('div');
            if (!wrap.isVisible()) {
                // Set the "masking div" to visible
                wrap.setVisible(true);
                Ext.getCmp('modx-leftbar-tabpanel').expand(true);
            }
        }
        if(panelState && !this.saveState){
            Ext.state.Manager.set('modx-leftbar-tabs', {collapsed: false});
            this.saveState = true;
        }
    }
    ,showLeftbar: function(anim) {
        Ext.getCmp('modx-leftbar-tabs').expand(anim);
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
                if (action.substr(0,1) == '?' || (action.substr(0, "index.php?".length) == 'index.php?')) {
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
