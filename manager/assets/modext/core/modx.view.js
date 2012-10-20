/**
 * Abstract class for Ext.DataView creation in MODx
 * 
 * @class MODx.DataView
 * @extends Ext.DataView
 * @constructor
 * @param {Object} config An object of options.
 * @xtype modx-dataview
 */
MODx.DataView = function(config) {
    config = config || {};
    this._loadStore(config);
    
    Ext.applyIf(config.listeners || {},{
        'loadexception': {fn:this.onLoadException, scope: this}
        ,'beforeselect': {fn:function(view){ return view.store.getRange().length > 0;}}
        ,'contextmenu': {fn:this._showContextMenu, scope: this}
    });
    Ext.applyIf(config,{
        store: this.store
        ,singleSelect: true
        ,overClass: 'x-view-over'
        ,itemSelector: 'div.modx-pb-thumb-wrap'
        ,emptyText: '<div style="padding:10px;">'+_('file_err_filter')+'</div>'
    });
    MODx.DataView.superclass.constructor.call(this,config);
    this.config = config;
    this.cm = new Ext.menu.Menu();
};
Ext.extend(MODx.DataView,Ext.DataView,{
    lookup: {}
    
    ,onLoadException: function(){
        this.getEl().update('<div style="padding:10px;">'+_('data_err_load')+'</div>'); 
    }
    
    /**
     * Add context menu items to the dataview.
     * @param {Object, Array} items Either an Object config or array of Object configs.  
     */
    ,_addContextMenuItem: function(items) {
        var a = items, l = a.length;
        for(var i=0;i<l;i=i+1) {
            var options = a[i];
            
            if (options === '-') {
                this.cm.add('-');
                continue;
            }
            var h = Ext.emptyFn;
            if (options.handler) {
                h = eval(options.handler);
            } else {
                h = function(itm,e) {
                    var o = itm.options;
                    var id = this.cm.activeNode.id.split('_'); id = id[1];
                    var w = Ext.get('modx_content');
                    if (o.confirm) {
                        Ext.Msg.confirm('',o.confirm,function(e) {
                            if (e === 'yes') {
                                var a = Ext.urlEncode(o.params || {action: o.action});
                                var s = 'index.php?id='+id+'&'+a;
                                if (w === null) {
                                    location.href = s;
                                } else { w.dom.src = s; }
                            }
                        },this);
                    } else {
                        var a = Ext.urlEncode(o.params);
                        var s = 'index.php?id='+id+'&'+a;
                        if (w === null) {
                            location.href = s;
                        } else { w.dom.src = s; }
                    }
                };
            }
            this.cm.add({
                id: options.id
                ,text: options.text
                ,scope: this
                ,options: options
                ,handler: h
            });
        }
    }
    
    
    ,_loadStore: function(config) {
        this.store = new Ext.data.JsonStore({
            url: config.url
            ,baseParams: config.baseParams || { 
                action: 'getList'
                ,wctx: config.wctx || MODx.ctx
                ,dir: config.openTo || ''
                ,source: config.source || 0
            }
            ,root: config.root || 'results'
            ,fields: config.fields
            ,totalProperty: 'total'
            ,listeners: {
                'load': {fn:function(){ this.select(0); }, scope:this, single:true}
            }
        });
        this.store.load();
    }
    
    ,_showContextMenu: function(v,i,n,e) {
        e.preventDefault();
        var data = this.lookup[n.id];
        var m = this.cm;
        m.removeAll();
        if (data.menu) {
            this._addContextMenuItem(data.menu);
            m.show(n,'tl-c?');
        }
        m.activeNode = n;
    }
});
Ext.reg('modx-dataview',MODx.DataView);


Ext.namespace('MODx.browser');

MODx.Browser = function(config) {
    if (MODx.browserOpen && !config.multiple) return false;
    if (!config.multiple) MODx.browserOpen = true;
    
    config = config || {};
    Ext.applyIf(config,{
        onSelect: function(data) {}
        ,scope: this
        ,source: config.source || 1
        ,cls: 'modx-browser'
    });
    MODx.Browser.superclass.constructor.call(this,config);
    this.config = config;
    
    this.win = new MODx.browser.Window(config);
    this.win.reset();
};
Ext.extend(MODx.Browser,Ext.Component,{
    show: function(el) { if (this.win) { this.win.show(el); } }
    ,hide: function() { if (this.win) { this.win.hide(); } }

    ,setSource: function(source) {
        this.config.source = source;
        this.win.tree.config.baseParams.source = source;
        this.win.view.config.baseParams.source = source;
    }
    
});
Ext.reg('modx-browser',MODx.Browser);

MODx.browser.Window = function(config) {
    config = config || {};
    this.ident = Ext.id();
    this.view = MODx.load({
        xtype: 'modx-browser-view'
        ,onSelect: {fn: this.onSelect, scope: this}
        ,source: config.source || MODx.config.default_media_source
        ,allowedFileTypes: config.allowedFileTypes || ''
        ,wctx: config.wctx || 'web'
        ,openTo: config.openTo || ''
        ,ident: this.ident
        ,id: this.ident+'-view'
    });
    this.tree = MODx.load({
        xtype: 'modx-tree-directory'
        ,onUpload: function() { this.view.run(); }
        ,scope: this
        ,source: config.source || MODx.config.default_media_source
        ,hideFiles: config.hideFiles || false
        ,openTo: config.openTo || ''
        ,ident: this.ident
        ,rootId: config.rootId || '/'
        ,rootName: _('files')
        ,rootVisible: config.rootVisible == undefined || !Ext.isEmpty(config.rootId)
        ,id: this.ident+'-tree'
        ,hideSourceCombo: config.hideSourceCombo || false
        ,listeners: {
            'afterUpload': {fn:function() { this.view.run(); },scope:this}
            ,'changeSource': {fn:function(s) {
                this.config.source = s;
                this.view.config.source = s;
                this.view.baseParams.source = s;
                this.view.run();
            },scope:this}
            ,'nodeclick': {fn:function(n,e) {
                n.select();
                e.preventDefault();
                e.stopPropagation();
                return false;
            },scope:this}
        }
    });
    this.tree.on('click',function(node,e) {
        this.load(node.id);
    },this);
    
    Ext.applyIf(config,{
        title: _('modx_browser')+' ('+(MODx.ctx ? MODx.ctx : 'web')+')'
        ,cls: 'modx-pb-win'
        ,layout: 'border'
        ,minWidth: 500
        ,minHeight: 300
        ,width: '90%'
        ,height: 500
        ,modal: false
        ,closeAction: 'hide'
        ,border: false
        ,items: [{
            id: this.ident+'-browser-tree'
            ,cls: 'modx-pb-browser-tree'
            ,region: 'west'
            ,width: 250
            ,height: '100%'
            ,items: this.tree
            ,autoScroll: true
            ,split: true
            ,border: false
        },{
            id: this.ident+'-browser-view'
            ,cls: 'modx-pb-view-ct'
            ,region: 'center'
            ,autoScroll: true
            ,width: 450
            ,border: false
            ,items: this.view
            ,tbar: this.getToolbar()
        },{
            id: this.ident+'-img-detail-panel'
            ,cls: 'modx-pb-details-ct'
            ,region: 'east'
            ,split: true
            ,border: false
            ,width: 150
            ,minWidth: 150
            ,maxWidth: 250
        }]
        ,buttons: [{
            id: this.ident+'-ok-btn'
            ,text: _('ok')
            ,handler: this.onSelect
            ,scope: this
        },{
            id: this.ident+'-cancel-btn'
            ,text: _('cancel')
            ,handler: this.hide
            ,scope: this
        }]
        ,keys: {
            key: 27
            ,handler: this.hide
            ,scope: this
        }
    });
    MODx.browser.Window.superclass.constructor.call(this,config);
    this.config = config;
    this.addEvents({
        'select': true
    });
};
Ext.extend(MODx.browser.Window,Ext.Window,{
    returnEl: null
    
    ,filter : function(){
        var filter = Ext.getCmp(this.ident+'filter');
        this.view.store.filter('name', filter.getValue(),true);
        this.view.select(0);
    }
    
    ,setReturn: function(el) {
        this.returnEl = el;
    }
    
    ,load: function(dir) {
        dir = dir || (Ext.isEmpty(this.config.openTo) ? '' : this.config.openTo);
        this.view.run({
            dir: dir
            ,source: this.config.source
            ,allowedFileTypes: this.config.allowedFileTypes || ''
            ,wctx: this.config.wctx || 'web'
        });
        this.sortImages();
    }
    
    ,sortImages : function(){
        var v = Ext.getCmp(this.ident+'sortSelect').getValue();
        this.view.store.sort(v, v == 'name' ? 'asc' : 'desc');
        this.view.select(0);
    }
    
    ,reset: function(){
        if(this.rendered){
            Ext.getCmp(this.ident+'filter').reset();
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
            ,id: this.ident+'filter'
            ,selectOnFocus: true
            ,width: 100
            ,listeners: {
                'render': {fn:function(){
                    Ext.getCmp(this.ident+'filter').getEl().on('keyup', function(){
                        this.filter();
                    }, this, {buffer:500});
                }, scope:this}
            }
        }, ' ', '-', {
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
                'select': {fn:this.sortImages, scope:this}
            }
        }];
    }
    
    ,onSelect: function(data) {
        var selNode = this.view.getSelectedNodes()[0];
        var callback = this.config.onSelect || this.onSelectHandler;
        var lookup = this.view.lookup;
        var scope = this.config.scope;
        this.hide(this.config.animEl || null,function(){
            if(selNode && callback){
                var data = lookup[selNode.id];
                Ext.callback(callback,scope || this,[data]);
                this.fireEvent('select',data);
                if (window.top.opener) {
                    window.top.close();
                    window.top.opener.focus();
                }
            }
        },scope);
    }
    
    ,onSelectHandler: function(data) {
        Ext.get(this.returnEl).dom.value = unescape(data.url);
    }
});
Ext.reg('modx-browser-window',MODx.browser.Window);

MODx.browser.View = function(config) {
    config = config || {};
    this.ident = config.ident+'-view' || 'modx-browser-'+Ext.id()+'-view';
    
    this._initTemplates();
    Ext.applyIf(config,{
        url: MODx.config.connectors_url+'browser/directory.php'
        ,id: this.ident
        ,fields: [
            'name','cls','url','relativeUrl','fullRelativeUrl','image','image_width','image_height','thumb','thumb_width','thumb_height','pathname','ext','disabled'
            ,{name:'size', type: 'float'}
            ,{name:'lastmod', type:'date', dateFormat:'timestamp'}
            ,'menu'
        ]
        ,baseParams: { 
            action: 'getFiles'
            ,prependPath: config.prependPath || null
            ,prependUrl: config.prependUrl || null
            ,source: config.source || 1
            ,allowedFileTypes: config.allowedFileTypes || ''
            ,wctx: config.wctx || 'web'
            ,dir: config.openTo || ''
        }
        ,sortInfo: {
            field: MODx.config.modx_browser_default_sort || 'name'
            ,direction: 'ASC'
        }
        ,tpl: this.templates.thumb
        ,listeners: {
            'selectionchange': {fn:this.showDetails, scope:this, buffer:100}
            ,'dblclick': config.onSelect || {fn:Ext.emptyFn,scope:this}
        }
        ,prepareData: this.formatData.createDelegate(this)
    });
    MODx.browser.View.superclass.constructor.call(this,config);
};
Ext.extend(MODx.browser.View,MODx.DataView,{
    templates: {}
    
    ,removeFile: function(item,e) {
        var node = this.cm.activeNode;
        var data = this.lookup[node.id];
        var d = '';
        if (typeof(this.dir) != 'object') { d = this.dir; }
        MODx.msg.confirm({
            text: _('file_remove_confirm')
            ,url: MODx.config.connectors_url+'browser/file.php'
            ,params: {
                action: 'remove'
                ,file: d+'/'+node.id
                ,source: this.config.source
                ,wctx: this.config.wctx || 'web'
            }
            ,listeners: {
                'success': {fn:function(r) {
                    this.run({ ctx: MODx.ctx });
                },scope:this}
            }
        });
    }
    
    ,run: function(p) {
        p = p || {};
        if (p.dir) { this.dir = p.dir; }
        Ext.applyIf(p,{
            action: 'getFiles'
            ,dir: this.dir
            ,source: this.config.source || MODx.config.default_media_source
        });
        this.store.load({
            params: p
            ,callback: function() { this.refresh(); }
            ,scope: this
        });
    }
    
    ,showDetails : function(){
        var selNode = this.getSelectedNodes();
        var detailEl = Ext.getCmp(this.config.ident+'-img-detail-panel').body;
        if(selNode && selNode.length > 0){
            selNode = selNode[0];
            Ext.getCmp(this.ident+'-ok-btn').enable();
            var data = this.lookup[selNode.id];
            detailEl.hide();
            this.templates.details.overwrite(detailEl, data);
            detailEl.slideIn('l', {stopFx:true,duration:'.2'});
        }else{
            Ext.getCmp(this.config.ident+'-ok-btn').disable();
            detailEl.update('');
        }
    }
    ,formatData: function(data) {
        var formatSize = function(size){
            if(size < 1024) {
                return size + " bytes";
            } else {
                return (Math.round(((size*10) / 1024))/10) + " KB";
            }
        };
        data.shortName = Ext.util.Format.ellipsis(data.name,18);
        data.sizeString = data.size != 0 ? formatSize(data.size) : 0;
        data.dateString = !Ext.isEmpty(data.lastmod) ? new Date(data.lastmod).format("m/d/Y g:i a") : 0;
        this.lookup[data.name] = data;
        return data;
    }
    ,_initTemplates: function() {
        this.templates.thumb = new Ext.XTemplate(
            '<tpl for=".">'
                ,'<div class="modx-pb-thumb-wrap" id="{name}">'
                ,'<div class="modx-pb-thumb"><img src="{thumb}" title="{name}" width="90" height="90" /></div>'
                ,'<span>{shortName}</span></div>'
            ,'</tpl>'
        );
        this.templates.thumb.compile();
        
        this.templates.details = new Ext.XTemplate(
            '<div class="details">'
            ,'<tpl for=".">'
                ,'<div class="modx-pb-detail-thumb"><img src="{thumb}" alt="" width="80" height="60" onclick="Ext.getCmp(\''+this.ident+'\').showFullView(\'{name}\',\''+this.ident+'\'); return false;" /></div>'
                ,'<div class="modx-pb-details-info">'
                ,'<b>'+_('file_name')+':</b>'
                ,'<span>{name}</span>'
                ,'<tpl if="this.isEmpty(sizeString) == false">'
                    ,'<b>'+_('file_size')+':</b>'
                    ,'<span>{sizeString}</span>'
                ,'</tpl>'
                ,'<tpl if="this.isEmpty(dateString) == false">'
                    ,'<b>'+_('last_modified')+':</b>'
                    ,'<span>{dateString}</span></div>'
                ,'</tpl>'
            ,'</tpl>'
            ,'</div>'
        ,{
            isEmpty: function (v) {
                return (v == '' || v == null || v == undefined || v === 0);
            }
        });
        this.templates.details.compile(); 
    }
    ,showFullView: function(name,ident) {
        var data = this.lookup[name];
        if (!data) return false;
        
        if (!this.fvWin) {
            this.fvWin = new Ext.Window({
                layout:'fit'
                ,width: 600
                ,height: 450
                ,closeAction:'hide'
                ,plain: true
                ,items: [{
                    id: this.ident+'modx-view-item-full'
                    ,cls: 'modx-pb-fullview'
                    ,html: ''
                }]
                ,buttons: [{
                    text: _('close')
                    ,handler: function() { this.fvWin.hide(); }
                    ,scope: this
                }]
            });
        }
        this.fvWin.show();
        var w = data.image_width < 250 ? 250 : data.image_width;
        var h = data.image_height < 200 ? 200 : data.image_height;
        this.fvWin.setSize(w,h);
        this.fvWin.center();
        this.fvWin.setTitle(data.name);
        Ext.get(this.ident+'modx-view-item-full').update('<img src="'+data.image+'" alt="" class="modx-pb-fullview-img" onclick="Ext.getCmp(\''+ident+'\').fvWin.hide();" />');
    }
});
Ext.reg('modx-browser-view',MODx.browser.View);
