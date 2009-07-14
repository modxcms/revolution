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
    Ext.BLANK_IMAGE_URL = MODx.config.manager_url+'assets/ext3/resources/images/default/s.gif';
    Ext.applyIf(config,{
        id: 'modx-layout'
        ,renderTo: 'modx-accordion-content'
        ,minSize: 100
        ,minHeight: 300
        ,maxHeight: 400
        ,split: true
        ,collapsible: true
        ,hideBorders: true
        ,resizable: true
        ,stateful: false
        ,autoHeight: true
        ,layout: 'accordion'
        ,border: false
        ,layoutConfig: { 
            animate: true
            ,autoScroll: true
            ,titleCollapse: true
            ,fill: true
        }
        ,defaults: {
            autoScroll: true
            ,fitToFrame: true
            ,autoHeight: true
            ,titleCollapse: true
            ,maxHeight: 450
            ,height: 450
            ,cls: 'modx-accordion-panel'
        }
        ,items: this.setupAccordion()
    });
        
    MODx.Layout.superclass.constructor.call(this,config);
    this.config = config;
    
    this.addEvents({
        'afterLayout': true
        ,'loadKeyMap': true
        ,'loadAccordion': true
    });
    this.loadKeys();
    this.fireEvent('afterLayout');
};
Ext.extend(MODx.Layout,Ext.Panel,{
    
    loadKeys: function() {
        Ext.KeyMap.prototype.stopEvent = true;
        var k = new Ext.KeyMap(Ext.get(document));        
        k.addBinding({
            key: Ext.EventObject.H
            ,ctrl: true
            ,shift: true
            ,fn: this.toggleAccordion
            ,scope: this
            ,stopEvent: true
        });
        k.addBinding({
            key: Ext.EventObject.N
            ,ctrl: true
            ,shift: true
            ,fn: function() {
                Ext.getCmp('modx_resource_tree').quickCreate(document,{},'modResource','web',0);
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
        Ext.getCmp('modx_resource_tree').refresh();
        Ext.getCmp('modx_element_tree').refresh();
        Ext.getCmp('modx_file_tree').refresh();
    }
    
    ,setupAccordion: function() {
        var it = [];
        var lps = MODx.loadAccordionPanels();
        if (lps.length > 0) {
            for(var x=0;x<lps.length;x=x+1) {
                it.push(lps[x]);
            }
        }
        it.push({
            xtype: 'modx-tree-resource'
            ,title: _('web_resources')
            ,resizeEl: 'modx_resource_tree'
            ,id: 'modx_resource_tree'
        });
        it.push({
            xtype: 'modx-tree-element'
            ,title: _('content_elements')
            ,resizeEl: 'modx_element_tree'
            ,id: 'modx_element_tree'
        });
        it.push({
            xtype: 'modx-tree-directory'
            ,title: _('files')
            ,resizeEl: 'modx_file_tree'
            ,id: 'modx_file_tree'
            ,hideFiles: false
        });
        
        if (MODx.onAccordionLoad) {
            it = MODx.onAccordionLoad(it);
        }
        
        return it;
    }
    ,accordionVisible: true
    ,toggleAccordion: function() {
        this.accordionVisible ? this.removeAccordion(.3) : this.showAccordion(.3);
        this.accordionVisible = !this.accordionVisible;
    }
    ,removeAccordion: function(d) {
        this.cleanupContent(false);
        Ext.get('modx-accordion').slideOut('l',{
            remove: false
            ,useDisplay: true
            ,duration: d || .1
        });
    }
    ,showAccordion: function(d) {
        this.cleanupContent(true);
        Ext.get('modx-accordion').slideIn('l',{
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
