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

    var tabs = [];
    if (MODx.perm.resource_tree) {
       tabs.push({
            xtype: 'modx-tree-resource'
            ,title: _('resources')
            ,id: 'modx-resource-tree'
        });
    }
    if (MODx.perm.element_tree) {
        tabs.push({
            xtype: 'modx-tree-element'
            ,title: _('elements')
            ,id: 'modx-element-tree'
        });
    }
    if (MODx.perm.file_tree) {
        tabs.push({
            xtype: 'modx-tree-directory'
            ,title: _('files')
            ,id: 'modx-file-tree'
            ,hideFiles: false
        });
    }

    Ext.applyIf(config,{
         layout: 'border'
        ,id: 'modx-layout'
        ,items: [{
             region: 'west'
            ,applyTo: 'modx-leftbar'
            ,id: 'modx-leftbar-tabs'
            ,split: true
            ,width: 310
            ,minSize: 150
            ,maxSize: 800
            ,autoHeight: true
            ,unstyled: true
            ,collapseMode: 'mini'
            ,useSplitTips: true
            ,monitorResize: true
            ,forceLayout: true
            ,stateful: false
            ,items: [{
                 xtype: 'modx-tabs'
                ,plain: true
                ,forceLayout: true
                ,deferredRender: false
                ,defaults: {
                     autoScroll: true
                    ,fitToFrame: true
                }
                ,border: false
                ,activeTab: 0
                ,stateful: true
                ,stateId: 'modx-leftbar-state'
                ,stateEvents: ['tabchange']
                ,getState:function() {
                    return {
                        activeTab:this.items.indexOf(this.getActiveTab())
                    };
                }
                ,items: tabs
            }]
        },{
            region: 'center'
            ,applyTo: 'modx-content'
            ,id: 'modx-content'
            ,border: false
            ,autoHeight: true
            ,margins: '0 30 0 15'
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
                if (t) { t.quickCreate(document,{},'modResource','web',0); }
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
    ,hideLeftbar: function(d) {
        Ext.getCmp('modx-leftbar-tabs').collapse();
    }
    ,showLeftbar: function(d) {
        Ext.getCmp('modx-leftbar-tabs').expand();
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
        loadPage: function(a,p) {
            location.href = '?a='+a+'&'+(p || '');
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
