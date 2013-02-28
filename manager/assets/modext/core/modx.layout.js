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
            ,xtype: 'modx-tree-directory'
            ,id: 'modx-file-tree'
        });
        showTree = true;
    }
    var activeTab = 0;

    Ext.applyIf(config,{
         layout: 'border'
        ,id: 'modx-layout'
        ,saveState: true
        ,items: [{
            xtype: 'box'
            ,region: 'north'
            ,applyTo: 'modx-header'
            ,height: 92
        },{
             region: 'west'
            ,applyTo: 'modx-leftbar'
            ,id: 'modx-leftbar-tabs'
            ,split: true
            ,width: 310
            ,minSize: 150
            ,maxSize: 800
            ,autoScroll: true
            ,unstyled: true
            ,collapseMode: 'mini'
            ,useSplitTips: true
            ,monitorResize: true
            ,layout: 'anchor'
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
                ,stateId: 'modx-leftbar-tabs'
                ,stateEvents: ['tabchange']
                ,getState:function() {
                    return {
                        activeTab:this.items.indexOf(this.getActiveTab())
                    };
                }
                ,items: tabs
            }]
            ,listeners:{
                statesave: this.onStatesave
                ,scope: this
            }
        },{
            region: 'center'
            ,applyTo: 'modx-content'
            ,id: 'modx-content'
            ,border: false
            ,autoScroll: true
            ,padding: '0 1px 0 0'
            ,bodyStyle: 'background-color:transparent;'
        }]
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
Ext.reg('modx-layout',MODx.Layout);

/**
 * Handles layout functions. In module format for easier privitization.
 * @class MODx.LayoutMgr
 */
MODx.LayoutMgr = function() {
    var _activeMenu = 'menu0';
    return {
        loadPage: function(action, parameters) {
            var url = '';
            // Handles url, passed as first argument
            if (isNaN(action)) {
                url = action;
            } else {
                var parts = [];
                if (action) {
                    parts.push('a=' + action);
                }
                if (parameters) {
                    parts.push(parameters);
                }
                url = '?' + parts.join('&');
            }
            if (MODx.fireEvent('beforeLoadPage', url)) {
                location.href = url;
            }
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
