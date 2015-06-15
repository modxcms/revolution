

MODx.browser.RTE = function(config) {
    config = config || {};
    this.ident = Ext.id();

    Ext.Ajax.defaultHeaders = {
        'modAuth': config.auth
    };
    Ext.Ajax.extraParams = {
        'HTTP_MODAUTH': config.auth
    };
    
    this.ident = 'modx-browser-'+Ext.id();
    this.view = MODx.load({
        xtype: 'modx-browser-view'
        ,onSelect: {fn: this.onSelect, scope: this}
        ,ident: this.ident
        ,source: config.source || MODx.config.default_media_source
        ,id: this.ident+'-view'
    });
    MODx.browserOpen = true;
    this.tree = MODx.load({
        xtype: 'modx-tree-directory'
        ,onUpload: function() { this.view.run(); }
        ,scope: this
        ,source: config.source || MODx.config.default_media_source
        ,useDefaultToolbar: false
        ,hideFiles: true
        ,openTo: config.openTo || ''
        ,ident: this.ident
        ,rootId: '/'
        ,rootName: _('files')
        ,rootVisible: true
        ,id: this.ident+'-tree'
        ,listeners: {
            'afterUpload': {fn:function() { this.view.run(); },scope:this}
            ,'changeSource': {fn:function(s) {
                this.config.source = s;
                this.view.config.source = s;
                this.view.baseParams.source = s;
                this.view.dir = '/';
                this.view.run();
            },scope:this}
            ,afterrender: {
                fn: function(tree) {
                    tree.root.expand();
                }
                ,scope: this
            }
        }
    });
    this.tree.on('click',function(node,e) {
        this.load(node.id);
    },this);
    
    Ext.applyIf(config,{
        title: _('modx_browser')
        ,layout: 'border'
        ,renderTo: document.body
        ,id: this.ident+'-viewport'
        
        ,onSelect: MODx.onBrowserReturn || function(data) {}
        ,items: [{
            id: this.ident+'-browser-tree'
            ,cls: 'modx-browser-tree'
            ,region: 'west'
            ,width: 250
            ,height: '100%'
            ,split: true
            ,items: this.tree
            ,autoScroll: true
        },{
            id: this.ident+'-browser-view'
            ,cls: 'modx-browser-view-ct'
            ,region: 'center'
            ,autoScroll: true
            ,width: 450
            ,items: this.view
            ,tbar: this.getToolbar()
        },{
            id: this.ident+'-img-detail-panel'
            ,cls: 'modx-browser-details-ct'
            ,region: 'east'
            ,split: true
            ,width: 200
            ,minWidth: 200
            ,maxWidth: 300
        },{
            id: this.ident+'-south'
            ,cls: 'modx-browser-buttons'
            ,region: 'south'
            ,split: false
            ,bbar: ['->',{
                id: this.ident+'-ok-btn'
                ,text: _('ok')
                ,handler: this.onSelect
                ,scope: this
                ,width: 200
            },{
                text: _('cancel')
                ,handler: this.closeWindow
                ,scope: this
                ,width: 200
            }]
        }]
    });
    MODx.browser.RTE.superclass.constructor.call(this,config);
    this.config = config;
};
Ext.extend(MODx.browser.RTE,Ext.Viewport,{

    filter : function(){
        var filter = Ext.getCmp('filter');
        this.view.store.filter('name', filter.getValue(),true);
        this.view.select(0);
    }
    
    ,setReturn: function(el) {
        this.returnEl = el;
    }
    
    ,load: function(dir) {
        dir = dir || '';
        var t = Ext.getCmp(this.ident+'-tree');
        if (t) {
            this.config.source = t.config.baseParams.source;
        }
        this.view.run({
            dir: dir
            ,wctx: MODx.ctx
            ,source: this.config.source || MODx.config.default_media_source
        });
    }
    
    ,sortStore: function(){
        var v = Ext.getCmp(this.ident+'sortSelect').getValue();
        this.view.store.sort(v, v == 'name' ? 'ASC' : 'DESC');
        this.view.select(0);
    }

    ,changeViewmode: function() {
        var v = Ext.getCmp(this.ident+'viewSelect').getValue();
        this.view.setTemplate(v);
        this.view.select(0);
    }
    
    ,reset: function(){
        if(this.rendered){
            Ext.getCmp('filter').reset();
            this.view.getEl().dom.scrollTop = 0;
        }
        this.view.store.clearFilter();
        this.view.select(0);
    }
    
    ,getToolbar: function() {
        return [{
            text: _('filter')+':'
        },{
            xtype: 'textfield'
            ,id: 'filter'
            ,selectOnFocus: true
            ,width: 200
            ,listeners: {
                'render': {fn:function(){
                    Ext.getCmp('filter').getEl().on('keyup', function(){
                        this.filter();
                    }, this, {buffer:500});
                }, scope:this}
            }
        }, ' ', {
            text: _('sort_by')+':'
        }, {
            id: this.ident+'sortSelect'
            ,xtype: 'combo'
            ,typeAhead: true
            ,triggerAction: 'all'
            ,width: 100
            ,editable: false
            ,mode: 'local'
            ,displayField: 'desc'
            ,valueField: 'name'
            ,lazyInit: false
            ,value: MODx.config.modx_browser_default_sort || 'name'
            ,store: new Ext.data.SimpleStore({
                fields: ['name', 'desc'],
                data : [['name',_('name')],['size',_('file_size')],['lastmod',_('last_modified')]]
            })
            ,listeners: {
                'select': {fn:this.sortStore, scope:this}
            }
        },{
            icon: MODx.config.template_url+'images/restyle/icons/refresh.png'
            ,cls: 'x-btn-icon'
            ,tooltip: {text: _('tree_refresh')}
            ,handler: function() { this.load(); }
            ,scope: this
        }, '-', {
            text: _('files_viewmode')+':'
            ,xtype: 'label'
        }, '-', {
            id: this.ident+'viewSelect'
            ,xtype: 'combo'
            ,typeAhead: false
            ,triggerAction: 'all'
            ,width: 100
            ,editable: false
            ,mode: 'local'
            ,displayField: 'desc'
            ,valueField: 'type'
            ,lazyInit: false
            ,value: MODx.config.modx_browser_default_viewmode || 'grid'
            ,store: new Ext.data.SimpleStore({
                fields: ['type', 'desc'],
                data : [['grid', _('files_viewmode_grid')],['list', _('files_viewmode_list')]]
            })
            ,listeners: {
                'select': {fn:this.changeViewmode, scope:this}
            }
        }];
    }
    
    ,onSelect: function(data) {
        var selNode = this.view.getSelectedNodes()[0];
        var callback = this.config.onSelect || this.onSelectHandler;
        var lookup = this.view.lookup;
        var scope = this.config.scope;
        if(selNode && callback) {
            data = lookup[selNode.id];
            Ext.callback(callback,scope || this,[data]);
            this.fireEvent('select',data);
            if (window.top.opener) {
                window.top.close();
                window.top.opener.focus();
            }
        }
    }
    
    ,onSelectHandler: function(data) {
        Ext.get(this.returnEl).dom.value = unescape(data.url);
    }

    ,closeWindow: function () {
        var callback = this.config.onSelect || this.onSelectHandler;
        var scope = this.config.scope;
        Ext.callback(callback,scope || this,[null]);
        this.fireEvent('select',null);
    }
});
Ext.reg('modx-browser-rte',MODx.browser.RTE);