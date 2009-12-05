/**
 * Loads the MODx Ext-driven Layout
 * 
 * @class MODx.Layout
 * @extends Ext.Viewport
 * @param {Object} config An object of config options.
 * @xtype modx-layout
 */
MODx.Layout = function(config){
    config = config || {};
    Ext.state.Manager.setProvider(new Ext.state.CookieProvider({
        expires: new Date(new Date().getTime()+(1000*60*60*24))
    }));
    Ext.BLANK_IMAGE_URL = MODx.config.manager_url+'assets/ext3/resources/images/default/s.gif';
    Ext.applyIf(config,{
        id: 'modx-layout'
        ,renderTo: 'modx-leftbar-content'
        ,width: '100%'
        ,autoHeight: true
        ,border: false
        ,unstyled: true
        ,monitorResize: true
        ,items: [{
            xtype: 'modx-tabs'
            ,id: 'modx-leftbar-tabs'
            ,width: '100%'
            ,plain: true
            ,defaults: {
                autoScroll: true
                ,fitToFrame: true
            }
            ,border: false
            ,deferredRender: false
            ,activeTab: 0
            ,stateful: true
            ,stateId: 'tabpanel'
            ,stateEvents: ['tabchange']
            ,getState:function() {
                return {activeTab:this.items.indexOf(this.getActiveTab())};
            }
            ,items: [{
                xtype: 'modx-tree-resource'
                ,title: _('resources')
                ,id: 'modx-resource-tree'
            },{
                xtype: 'modx-tree-element'
                ,title: _('elements')
                ,id: 'modx-element-tree'
            },{
                xtype: 'modx-tree-directory'
                ,title: _('files')
                ,id: 'modx-file-tree'
                ,hideFiles: false
            }]
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
    
    this.resizer = new Ext.Resizable('modx-leftbar', {
        handles: 'e'
        ,minWidth: 20
        ,minHeight: 100
        ,pinned: false
        ,animate: true
    });
    this.resizer.on('resize',function(r,w,h,e) {
        var wi = Ext.get('modx-body-tag').getWidth();
        Ext.getCmp('modx-leftbar-tabs').setWidth(w-10);        
        var ct = Ext.get('modx-content');
        ct.setWidth((wi-w)-20);
        ct.setStyle('float','left');        
    });
    
};
Ext.extend(MODx.Layout,Ext.Panel,{
    
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
                Ext.getCmp('modx-resource-tree').quickCreate(document,{},'modResource','web',0);
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
        Ext.getCmp('modx-resource-tree').refresh();
        Ext.getCmp('modx-element-tree').refresh();
        Ext.getCmp('modx-file-tree').refresh();
    }
    ,leftbarVisible: true
    ,toggleLeftbar: function() {
        this.leftbarVisible ? this.hideLeftbar(.3) : this.showLeftbar(.3);
        this.leftbarVisible = !this.leftbarVisible;
    }
    ,hideLeftbar: function(d) {
        this.cleanupContent(false);
        Ext.get('modx-leftbar').slideOut('l',{
            remove: false
            ,useDisplay: true
            ,duration: d || .1
        });
    }
    ,showLeftbar: function(d) {
        this.cleanupContent(true);
        Ext.get('modx-leftbar').slideIn('l',{
            remove: false
            ,useDisplay: true
            ,duration: d || .1
        });
    }
    ,cleanupContent: function(mode) {
        var c = Ext.get('modx-content');
        c.setStyle('width',mode ? '74%' : '98%');
        c.repaint();
        Ext.select('.x-portlet, .x-column-inner, .x-panel-body').each(function(el,ar,i) {
            el.setStyle('width','100%');
            el.repaint();
        },this);
        Ext.select('.x-portal-column').each(function(el,ar,i) {
            el.setStyle('width','97%');
            el.repaint();
        },this);
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
