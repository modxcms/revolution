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
        ,emptyText: '<div style="padding:10px;">'+_('file_err_filter')+'</div>'
        ,closeAction: 'hide'
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
                                var s = '?id='+id+'&'+a;
                                if (w === null) {
                                    location.href = s;
                                } else { w.dom.src = s; }
                            }
                        },this);
                    } else {
                        var a = Ext.urlEncode(o.params);
                        var s = '?id='+id+'&'+a;
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
                action: 'browser/directory/getList'
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
        ,closeAction: 'hide'
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
        ,useDefaultToolbar: false
        ,listeners: {
            'afterUpload': {fn:function() { this.view.run(); },scope:this}
            ,'changeSource': {fn:function(s) {
                this.config.source = s;
                this.view.config.source = s;
                this.view.baseParams.source = s;
                this.view.dir = '/';
                this.view.run();
            },scope:this}
            ,'nodeclick': {fn:function(n,e) {
                n.select();
                e.preventDefault();
                e.stopPropagation();
                return false;
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
        title: _('modx_browser')+' ('+(MODx.ctx ? MODx.ctx : 'web')+')'
        ,cls: 'modx-browser-win'
        ,layout: 'border'
        ,minWidth: 500
        ,minHeight: 300
        ,width: '90%'
        ,height: Ext.getBody().getViewSize().height * 0.9
        ,modal: false
        ,closeAction: 'hide'
        ,border: false
        ,items: [{
            id: this.ident+'-browser-tree'
            ,cls: 'modx-browser-tree'
            ,region: 'west'
            ,width: 250
            ,height: '100%'
            ,items: this.tree
            ,autoScroll: true
            ,split: true
            ,border: false
        },{
            id: this.ident+'-browser-view'
            ,cls: 'modx-browser-view-ct'
            ,region: 'center'
            ,autoScroll: true
            //,width: 635
            ,border: false
            ,items: this.view
            ,tbar: this.getToolbar()
        },{
            id: this.ident+'-img-detail-panel'
            ,cls: 'modx-browser-details-ct'
            ,region: 'east'
            ,split: true
            ,border: false
            ,width: 250
        }]
        ,buttons: [{
            id: this.ident+'-cancel-btn'
            ,text: _('cancel')
            ,handler: this.close
            ,scope: this
        },{
            id: this.ident+'-ok-btn'
            ,text: _('ok')
            ,cls: 'primary-button'
            ,handler: this.onSelect
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
        this.sortStore();
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
            Ext.getCmp(this.ident+'filter').reset();
            this.view.getEl().dom.scrollTop = 0;
        }
        this.view.store.clearFilter();
        this.view.select(0);
    }

    ,getToolbar: function() {
        return [{
            text: _('filter')+':'
            ,xtype: 'label'
        },{
            xtype: 'textfield'
            ,id: this.ident+'filter'
            ,selectOnFocus: true
            ,width: 200
            ,listeners: {
                'render': {fn:function(){
                    Ext.getCmp(this.ident+'filter').getEl().on('keyup', function(){
                        this.filter();
                    }, this, {buffer:500});
                }, scope:this}
            }
        },{
            text: _('sort_by')+':'
            ,xtype: 'label'
        },{
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
        this.hide(this.config.animEl || null,function(){
            if(selNode && callback){
                var data = lookup[selNode.id];
                Ext.callback(callback,scope || this,[data]);
                this.fireEvent('select',data);
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
        url: MODx.config.connector_url
        ,id: this.ident
        ,fields: [
            {name: 'name', sortType: Ext.data.SortTypes.asUCString}
            ,'cls','url','relativeUrl','fullRelativeUrl','image','image_width','image_height','thumb','thumb_width','thumb_height','pathname','ext','disabled','preview'
            ,{name: 'size', type: 'float'}
            ,{name: 'lastmod', type: 'date', dateFormat: 'timestamp'}
            ,'menu'
        ]
        ,baseParams: {
            action: 'browser/directory/getfiles'
            ,prependPath: config.prependPath || null
            ,prependUrl: config.prependUrl || null
            ,source: config.source || 1
            // @todo: this overrides the media source configuration
            ,allowedFileTypes: config.allowedFileTypes || ''
            ,wctx: config.wctx || 'web'
            ,dir: config.openTo || ''
        }
        ,tpl: MODx.config.modx_browser_default_viewmode === 'list' ? this.templates.list : this.templates.thumb
        ,itemSelector: MODx.config.modx_browser_default_viewmode === 'list' ? 'div.modx-browser-list-item' : 'div.modx-browser-thumb-wrap'
        ,listeners: {
            'selectionchange': {fn:this.showDetails, scope:this, buffer:100}
            ,'dblclick': config.onSelect || {fn:Ext.emptyFn,scope:this}
            ,'render': {fn:this.sortStore, scope:this}
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
        if (typeof(this.dir) != 'object' && typeof(this.dir) != 'undefined') { d = this.dir; }
        MODx.msg.confirm({
            text: _('file_remove_confirm')
            ,url: MODx.config.connector_url
            ,params: {
                action: 'browser/file/remove'
                ,file: d+'/'+node.id
                ,source: this.config.source
                ,wctx: this.config.wctx || 'web'
            }
            ,listeners: {
                'success': {fn:function(r) {
                    this.run();
                },scope:this}
            }
        });
    }

    ,run: function(p) {
        p = p || {};
        if (p.dir) { this.dir = p.dir; }
        Ext.applyIf(p,{
            action: 'browser/directory/getFiles'
            ,dir: this.dir
            ,source: this.config.source || MODx.config.default_media_source
        });
        this.store.load({
            params: p
            ,callback: function() { this.refresh(); this.select(0); }
            ,scope: this
        });
    }

    ,setTemplate: function(tpl) {
        if (tpl === 'list') {
            this.tpl = this.templates.list;
            this.itemSelector = 'div.modx-browser-list-item';
        } else {
            this.tpl = this.templates.thumb;
            this.itemSelector = 'div.modx-browser-thumb-wrap';
        }
        this.refresh();
        this.select(0);
    }

    ,sortStore : function() {
        var v = MODx.config.modx_browser_default_sort || 'name'
        this.store.sort(v, v == 'name' ? 'ASC' : 'DESC');
        this.select(0);
    }

    ,showDetails : function(){
        var selNode = this.getSelectedNodes();
        var detailEl = Ext.getCmp(this.config.ident+'-img-detail-panel').body;
        var okBtn = Ext.getCmp(this.ident+'-ok-btn');
        if (selNode && selNode.length > 0) {
            selNode = selNode[0];
            if (okBtn) {
                okBtn.enable();
            }
            var data = this.lookup[selNode.id];
            detailEl.hide();
            this.templates.details.overwrite(detailEl, data);
            detailEl.slideIn('l', {stopFx:true,duration:'.2'});
        } else {
            if (okBtn) {
                okBtn.disable();
            }
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
        data.imageSizeString = data.preview != 0 ? data.image_width + "x" + data.image_height + "px": 0;
        data.dateString = !Ext.isEmpty(data.lastmod) ? new Date(data.lastmod).format(MODx.config.manager_date_format + " " + MODx.config.manager_time_format) : 0;
        this.lookup[data.name] = data;
        return data;
    }
    ,_initTemplates: function() {
        this.templates.thumb = new Ext.XTemplate(
            '<tpl for=".">'
                ,'<div class="modx-browser-thumb-wrap" id="{name}" title="{name}">'
                ,'  <div class="modx-browser-thumb">'
                ,'      <img src="{thumb}" title="{name}" />'
                ,'  </div>'
                ,'  <span>{shortName}</span>'
                ,'</div>'
            ,'</tpl>'
        );
        this.templates.thumb.compile();

        this.templates.list = new Ext.XTemplate(
            '<tpl for=".">'
                ,'<div class="modx-browser-list-item" id="{name}">'
                ,'  <span class="icon icon-file {cls}">'
                ,'      <span class="file-name">{name}</span>'
                ,'      <tpl if="sizeString !== 0">'
                ,'      <span class="file-size">{sizeString}</span>'
                ,'      </tpl>'
                ,'      <tpl if="imageSizeString !== 0">'
                ,'      <span class="image-size">{imageSizeString}</span>'
                ,'      </tpl>'
                ,'  </span>'
                ,'</div>'
            ,'</tpl>'
        );
        this.templates.list.compile();

        this.templates.details = new Ext.XTemplate(
            '<div class="details">'
            ,'  <tpl for=".">'
            ,'  <div class="modx-browser-detail-thumb">'
            ,'      <img src="{image}" alt="" onclick="Ext.getCmp(\''+this.ident+'\').showFullView(\'{name}\',\''+this.ident+'\'); return false;" />'
            ,'  </div>'
            ,'  <div class="modx-browser-details-info">'
            ,'      <b>'+_('file_name')+':</b>'
            ,'      <span>{name}</span>'
            ,'  <tpl if="sizeString !== 0">'
            ,'      <b>'+_('file_size')+':</b>'
            ,'      <span>{sizeString}</span>'
            ,'  </tpl>'
            ,'  <tpl if="imageSizeString !== 0">'
            ,'      <b>'+_('image_size')+':</b>'
            ,'      <span>{imageSizeString}</span>'
            ,'  </tpl>'
            ,'  <tpl if="dateString !== 0">'
            ,'      <b>'+_('last_modified')+':</b>'
            ,'      <span>{dateString}</span>'
            ,'  </tpl>'
            ,'  </div>'
            ,'  </tpl>'
            ,'</div>'
        );
        this.templates.details.compile();
    }
    ,showFullView: function(name,ident) {
        var data = this.lookup[name];
        if (!data) return;

        if (!this.fvWin) {
            this.fvWin = new Ext.Window({
                layout:'fit'
                ,width: 600
                ,height: 450
                ,closeAction:'hide'
                ,plain: true
                ,items: [{
                    id: this.ident+'modx-view-item-full'
                    ,cls: 'modx-browser-fullview'
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
        var w = data.image_width < 250 ? 250 : (data.image_width > 800 ? 800 : data.image_width);
        var h = data.image_height < 200 ? 200 : (data.image_height > 600 ? 600 : data.image_width);
        this.fvWin.setSize(w,h);
        this.fvWin.center();
        this.fvWin.setTitle(data.name);
        Ext.get(this.ident+'modx-view-item-full').update('<img src="'+data.image+'" alt="" class="modx-browser-fullview-img" onclick="Ext.getCmp(\''+ident+'\').fvWin.hide();" />');
    }
});
Ext.reg('modx-browser-view',MODx.browser.View);
