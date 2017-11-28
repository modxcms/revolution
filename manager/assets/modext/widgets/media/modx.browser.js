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

MODx.browser.View = function(config) {
    config = config || {};
    this.ident = config.ident+'-view' || 'modx-browser-'+Ext.id()+'-view';

    this._initTemplates();

    Ext.applyIf(config,{
        url: MODx.config.connector_url
        ,id: this.ident
        ,fields: [
            {name: 'name', sortType: Ext.data.SortTypes.asUCString}
            ,'cls','url','relativeUrl','fullRelativeUrl','image','image_width','image_height','thumb','thumb_width','thumb_height','pathname','pathRelative','ext','disabled','preview'
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
            ,callback: function() {
                this.refresh();

                // reset the bottom filepath bar
                Ext.getCmp(this.ident+'-filepath').setValue('');

                this.select(0);
            }
            ,scope: this
        });
    }

    ,editFile: function(item,e) {
        var node = this.cm.activeNode;
        var data = this.lookup[node.id];
        MODx.loadPage('system/file/edit', 'file='+data.pathRelative+'&source='+this.config.source);
    }

    ,quickUpdateFile: function(item,e) {
        var node = this.cm.activeNode;
        var data = this.lookup[node.id];
        MODx.Ajax.request({
            url: MODx.config.connector_url
            ,params: {
                action: 'browser/file/get'
                ,file:  data.pathRelative
                ,wctx: MODx.ctx || ''
                ,source: this.config.source
            }
            ,listeners: {
                'success': {fn:function(response) {
                    var r = {
                        file: data.pathRelative
                        ,name: data.name
                        ,path: data.pathRelative
                        ,source: this.config.source
                        ,content: response.object.content
                    };
                    var w = MODx.load({
                        xtype: 'modx-window-file-quick-update'
                        ,record: r
                        ,listeners: {
                            'hide':{fn:function() {this.destroy();}}
                        }
                    });
                    w.show(e.target);
                },scope:this}
            }
        });
    }

    ,renameFile: function(item,e) {
        var node = this.cm.activeNode;
        var data = this.lookup[node.id];
        var r = {
            old_name: data.name
            ,name: data.name
            ,path: data.pathRelative
            ,source: this.config.source
        };
        var w = MODx.load({
            xtype: 'modx-window-file-rename'
            ,record: r
            ,listeners: {
                'success':{fn:function(r) {
                    this.config.tree.refreshParentNode();
                    this.run();
                },scope:this}
                ,'hide':{fn:function() {
                    this.destroy();}
                }
            }
        });
        w.show(e.target);
    }

    ,downloadFile: function(item,e) {
        var node = this.cm.activeNode;
        var data = this.lookup[node.id];
        MODx.Ajax.request({
            url: MODx.config.connector_url
            ,params: {
                action: 'browser/file/download'
                ,file: data.pathRelative
                ,wctx: MODx.ctx || ''
                ,source: this.config.source
            }
            ,listeners: {
                'success':{fn:function(r) {
                    if (!Ext.isEmpty(r.object.url)) {
                        location.href = MODx.config.connector_url+'?action=browser/file/download&download=1&file='+data.pathRelative+'&HTTP_MODAUTH='+MODx.siteId+'&source='+this.config.source+'&wctx='+MODx.ctx;
                    }
                },scope:this}
            }
        });
    }

    ,removeFile: function(item,e) {
        var node = this.cm.activeNode;
        var data = this.lookup[node.id];
        // var d = '';
        // if (typeof(this.dir) != 'object' && typeof(this.dir) != 'undefined') { d = this.dir; }
        MODx.msg.confirm({
            text: _('file_remove_confirm')
            ,url: MODx.config.connector_url
            ,params: {
                action: 'browser/file/remove'
                ,file: data.pathRelative
                ,source: this.config.source
                ,wctx: this.config.wctx || 'web'
            }
            ,listeners: {
                'success': {fn:function(r) {
                    this.config.tree.refreshParentNode();
                    this.run();
                },scope:this}
            }
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

    ,sortStore: function() {
        var v = MODx.config.modx_browser_default_sort || 'name'
        this.store.sort(v, v == 'name' ? 'ASC' : 'DESC');
        this.select(0);
    }

    ,showDetails: function() {
        var node = this.getSelectedNodes();
        var detailPanel = Ext.getCmp(this.config.ident+'-img-detail-panel').body;
        var okBtn = Ext.getCmp(this.ident+'-ok-btn');
        if (node && node.length > 0) {
            node = node[0];
            if (okBtn) {
                okBtn.enable();
            }
            var data = this.lookup[node.id];
            // sync the selected file in browser view and tree
            // we have to take care of the tree loosing sync after a file is deleted
            // and this.config.tree.getNodeById(data.pathRelative) being undefined
            if (this.config.tree.getNodeById(data.pathRelative)) {
                // this is necessary to prevent the whole tree from refreshing
                // e.g. like this we set the correct activeNode which is then used to determine the parent node
                this.config.tree.cm.activeNode = this.config.tree.getNodeById(data.pathRelative);
                // and this to have the visual syncing of selected items in browser view and tree
                this.config.tree.getSelectionModel().select(this.config.tree.getNodeById(data.pathRelative));
            }
            // keeps the bottom filepath bar in sync with the selected file
            Ext.getCmp(this.ident+'-filepath').setValue((data.fullRelativeUrl.indexOf('http') === -1 ? '/' : '')+data.fullRelativeUrl);

            detailPanel.hide();
            this.templates.details.overwrite(detailPanel, data);
            detailPanel.slideIn('l', {stopFx:true,duration:'.2'});
        } else {
            if (okBtn) {
                okBtn.disable();
            }
            detailPanel.update('');
        }
    }

    ,showFullView: function(name,ident) {
        var data = this.lookup[name];
        if (!data) return;

        if (!this.fvWin) {
            this.fvWin = new Ext.Window({
                layout:'fit'
                ,width: 600
                ,height: 450
                ,bodyStyle: 'padding: 0;'
                ,closeAction: 'hide'
                ,plain: true
                ,items: [{
                    id: this.ident+'modx-view-item-full'
                    ,cls: 'modx-browser-fullview'
                    ,html: ''
                }]
                ,buttons: [{
                    text: _('close')
                    ,cls: 'primary-button'
                    ,handler: function() { this.fvWin.hide(); }
                    ,scope: this
                }]
            });
        }
        this.fvWin.show();
        var ratio = data.image_width > 800 ? 800/data.image_width : 1;
        var w = data.image_width < 250 ? 250 : (data.image_width > 800 ? 800 : data.image_width);
        var hfit = (data.image_height*ratio)+this.fvWin.footer.dom.clientHeight+1+this.fvWin.header.dom.clientHeight+1; // +1 for the borders
        var h = data.image_height < 200 ? 200 : (data.image_height > 600 ? (hfit > 600 ? 600 : hfit) : data.image_height);
        this.fvWin.setSize(w,h);
        this.fvWin.center();
        this.fvWin.setTitle(data.name);
        Ext.get(this.ident+'modx-view-item-full').update('<img src="'+data.image+'" width="'+data.image_width+'" height="'+data.image_height+'" alt="'+data.name+'" title="'+data.name+'" class="modx-browser-fullview-img" onclick="Ext.getCmp(\''+ident+'\').fvWin.hide();" />');
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
        data.imageSizeString = data.imageSizeString === "xpx" ? 0 : data.imageSizeString;
        data.dateString = !Ext.isEmpty(data.lastmod) ? new Date(data.lastmod).format(MODx.config.manager_date_format + " " + MODx.config.manager_time_format) : 0;
        this.lookup[data.name] = data;
        return data;
    }
    ,_initTemplates: function() {
        this.templates.thumb = new Ext.XTemplate(
            '<tpl for=".">'
                ,'<div class="modx-browser-thumb-wrap" id="{name}" title="{name}">'
                ,'  <div class="modx-browser-thumb">'
                ,'      <img src="{thumb}" width="{thumb_width}" height="{thumb_height}" alt="{name}" title="{name}" />'
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
            ,'  <tpl if="preview === 1">'
            ,'      <div class="modx-browser-detail-thumb preview" onclick="Ext.getCmp(\''+this.ident+'\').showFullView(\'{name}\',\''+this.ident+'\'); return false;">'
            ,'          <img src="{image}" width="{image_width}" height="{image_height}" alt="{name}" title="{name}" />'
            ,'      </div>'
            ,'  </tpl>'
            ,'  <tpl if="preview === 0">'
            ,'      <div class="modx-browser-detail-thumb">'
            ,'          <img src="{image}" alt="" />'
            ,'      </div>'
            ,'  </tpl>'
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
});
Ext.reg('modx-browser-view',MODx.browser.View);

/**
 * This is the regular media browser window that opens when clicking on an image or file TV for example
 */
MODx.browser.Window = function(config) {
    config = config || {};

    this.ident = Ext.id();

    // Hide the "MODX Browser" toolbar button
    MODx.browserOpen = true;

    // Tree navigation
    this.tree = MODx.load({
        xtype: 'modx-tree-directory'
        ,onUpload: function() {
            this.view.run();
        }
        ,scope: this
        ,source: config.source || MODx.config.default_media_source
        ,hideFiles: config.hideFiles || MODx.config.modx_browser_tree_hide_files
        ,hideTooltips: config.hideTooltips || MODx.config.modx_browser_tree_hide_tooltips || true // by default do not request image preview tooltips in the media browser
        ,openTo: config.openTo || ''
        ,ident: this.ident
        ,rootId: config.rootId || '/'
        ,rootName: _('files')
        ,rootVisible: config.rootVisible == undefined || !Ext.isEmpty(config.rootId)
        ,id: this.ident+'-tree'
        ,hideSourceCombo: config.hideSourceCombo || false
        ,useDefaultToolbar: false
        ,listeners: {
            'afterUpload': {
                fn: function() {
                    this.view.run();
                }
                ,scope: this
            }
            ,'afterQuickCreate': {
                fn: function() {
                    this.view.run();
                }
                ,scope: this
            }
            ,'afterRename': {
                fn: function() {
                    this.view.run();
                }
                ,scope: this
            }
            ,'afterRemove': {
                fn: function() {
                    this.view.run();
                }
                ,scope: this
            }
            ,'changeSource': {
                fn: function(s) {
                    this.config.source = s;
                    this.view.config.source = s;
                    this.view.baseParams.source = s;
                    this.view.dir = '/';
                    this.view.run();
                }
                ,scope: this
            }
            ,'afterrender': {
                fn: function(tree) {
                    tree.root.expand();
                }
                ,scope: this
            }
            ,'beforeclick': {
                fn: function(node, e) {
                    // load the node/folder that is clicked on but prevent unnecessary requests when a file is clicked
                    if (!node.leaf) {
                        this.load(node.id);
                    } else {
                        // sync the selected item in the tree with the one in browser view
                        // the id of a browser view node in the store is the full absolute URL
                        // but there is a bug with urlAbsolute, see #11821 that's why we prepend a slash
                        this.view.select(this.view.store.indexOfId('/' + node.attributes.url));
                        // but instead load the container the file resides in if not already displayed
                        if (this.view.dir !== node.parentNode.id) {
                            this.load(node.parentNode.id);
                        }
                        return false;
                    }
                }
                ,scope: this
            }
        }
    });

    // DataView
    this.view = MODx.load({
        xtype: 'modx-browser-view'
        ,onSelect: {
            fn: this.onSelect
            ,scope: this
        }
        ,source: config.source || MODx.config.default_media_source
        ,allowedFileTypes: config.allowedFileTypes || ''
        ,wctx: config.wctx || 'web'
        ,openTo: config.openTo || ''
        ,ident: this.ident
        ,id: this.ident+'-view'
        ,tree: this.tree
    });

    Ext.applyIf(config,{
        title: _('modx_browser')+' ('+(MODx.ctx ? MODx.ctx : 'web')+')'
        ,cls: 'modx-browser modx-browser-window'
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
            ,bbar: this.getPathbar()
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

    /**
     * Filter the DataView results
     */
    ,filter : function() {
        var filter = Ext.getCmp(this.ident+'filter');
        this.view.store.filter('name', filter.getValue(), true);
        this.view.select(0);
    }


    /**
     * Load the given directory in the DataView
     *
     * @param {String} dir
     */
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

    /**
     * Sort the DataView results
     */
    ,sortStore: function(){
        var v = Ext.getCmp(this.ident+'sortSelect').getValue();
        this.view.store.sort(v, v == 'name' ? 'ASC' : 'DESC');
        this.view.select(0);
    }

    /**
     * Switch viewmode from grid to list and vice versa
     */
    ,changeViewmode: function() {
        var v = Ext.getCmp(this.ident+'viewSelect').getValue();
        this.view.setTemplate(v);
        this.view.select(0);
    }

    /**
     * Remove any filter applied to the DataView
     */
    ,reset: function() {
        if (this.rendered) {
            Ext.getCmp(this.ident+'filter').reset();
            this.view.getEl().dom.scrollTop = 0;
        }
        this.view.store.clearFilter();
        this.view.select(0);
    }

    /**
     * Get the browser view toolbar configuration
     *
     * @returns {Array}
     */
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
                'render': {
                    fn: function() {
                        Ext.getCmp(this.ident+'filter').getEl().on('keyup', function() {
                            this.filter();
                        }, this, {buffer: 500});
                    }
                    ,scope: this
                }
            }
        },{
            text: _('sort_by')+':'
            ,xtype: 'label'
        },{
            id: this.ident+'sortSelect'
            ,xtype: 'combo'
            ,typeAhead: true
            ,triggerAction: 'all'
            ,width: 130
            ,editable: false
            ,mode: 'local'
            ,displayField: 'desc'
            ,valueField: 'name'
            ,lazyInit: false
            ,value: MODx.config.modx_browser_default_sort || 'name'
            ,store: new Ext.data.SimpleStore({
                fields: ['name', 'desc'],
                data : [
                    ['name', _('name')]
                    ,['size', _('file_size')]
                    ,['lastmod', _('last_modified')]
                ]
            })
            ,listeners: {
                'select': {
                    fn: this.sortStore
                    ,scope: this
                }
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
                data : [
                    ['grid', _('files_viewmode_grid')]
                    ,['list', _('files_viewmode_list')]
                ]
            })
            ,listeners: {
                'select': {
                    fn: this.changeViewmode
                    ,scope: this
                }
            }
        }];
    }

    /**
     * Get the bottom filepath textfield in the browser view
     *
     * @returns {Array}
     */
    ,getPathbar: function() {
        return {
            cls: 'modx-browser-pathbbar'
            ,items: [{
                xtype: 'textfield'
                ,id: this.ident+'-filepath'
                ,cls: 'modx-browser-filepath'
                ,listeners: {
                    'focus': {
                        // select the filepath on focus
                        fn: function(el) {
                            // let the focus event stick first, needed for webkit primarily
                            setTimeout(function () {
                                var field = el.getEl().dom;

                                if (field.createTextRange) {
                                    var selRange = field.createTextRange();
                                    selRange.collapse(true);
                                    selRange.moveStart('character', 0);
                                    selRange.moveEnd('character', field.value.length);
                                    selRange.select();
                                } else if (field.setSelectionRange) {
                                    field.setSelectionRange(0, field.value.length);
                                } else if (field.selectionStart) {
                                    field.selectionStart = 0;
                                    field.selectionEnd = field.value.length;
                                }
                            }, 50);
                        }
                        ,scope: this
                    }
                }
            }]
        };
    }

    ,setReturn: function(el) {
        // @todo make sure this is never used
        console.log('MODx.Media#setReturn', el);
        this.returnEl = el;
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

/**
 * This is an attempt to extract the MODx.Browser.Window as a whole "component/page" found under Media > Media Browser
 *
 * @param {Object} config
 *
 * @extends Ext.Container
 * @xtype modx-media-view
 */
MODx.Media = function(config) {
    config = config || {};

    this.ident = config.ident || Ext.id();

    // Hide the "MODX Browser" toolbar button
    MODx.browserOpen = true;

    // Tree navigation
    this.tree = MODx.load({
        xtype: 'modx-tree-directory'
        ,onUpload: function() {
            this.view.run();
        }
        ,scope: this
        ,source: config.source || MODx.config.default_media_source
        ,hideFiles: config.hideFiles || MODx.config.modx_browser_tree_hide_files
        ,hideTooltips: config.hideTooltips || MODx.config.modx_browser_tree_hide_tooltips || true // by default do not request image preview tooltips in the media browser
        ,openTo: config.openTo || ''
        ,ident: this.ident
        ,rootId: config.rootId || '/'
        ,rootName: _('files')
        ,rootVisible: config.rootVisible == undefined || !Ext.isEmpty(config.rootId)
        ,id: this.ident+'-tree'
        ,hideSourceCombo: config.hideSourceCombo || false
        ,useDefaultToolbar: false
        ,listeners: {
            'afterUpload': {
                fn: function() {
                    this.view.run();
                }
                ,scope: this
            }
            ,'afterQuickCreate': {
                fn: function() {
                    this.view.run();
                }
                ,scope: this
            }
            ,'afterRename': {
                fn: function() {
                    this.view.run();
                }
                ,scope: this
            }
            ,'afterRemove': {
                fn: function() {
                    this.view.run();
                }
                ,scope: this
            }
            ,'changeSource': {
                fn: function(s) {
                    this.config.source = s;
                    this.view.config.source = s;
                    this.view.baseParams.source = s;
                    this.view.dir = '/';
                    this.view.run();
                }
                ,scope: this
            }
            ,'afterrender': {
                fn: function(tree) {
                    tree.root.expand();
                }
                ,scope: this
            }
            ,'beforeclick': {
                fn: function(node, e) {
                    // load the node/folder that is clicked on but prevent unnecessary requests when a file is clicked
                    if (!node.leaf) {
                        this.load(node.id);
                    } else {
                        // sync the selected item in the tree with the one in browser view
                        // the id of a browser view node in the store is the full absolute URL
                        // but there is a bug with urlAbsolute, see #11821 that's why we prepend a slash
                        this.view.select(this.view.store.indexOfId('/' + node.attributes.url));
                        // but instead load the container the file resides in if not already displayed
                        if (this.view.dir !== node.parentNode.id) {
                            this.load(node.parentNode.id);
                        }
                        return false;
                    }
                }
                ,scope: this
            }
        }
    });

    // DataView
    this.view = MODx.load({
        xtype: 'modx-browser-view'
        ,onSelect: {
            fn: this.onSelect
            ,scope: this
        }
        ,source: config.source || MODx.config.default_media_source
        ,allowedFileTypes: config.allowedFileTypes || ''
        ,wctx: config.wctx || 'web'
        ,openTo: config.openTo || ''
        ,ident: this.ident
        ,id: this.ident+'-view'
        ,tree: this.tree
    });

    Ext.applyIf(config, {
        cls: 'modx-browser modx-browser-panel container'
        ,layout: 'border'
        ,width: '98%'
        ,height: '95%'
        ,items: [{
            region: 'west'
            ,width: 250
            ,items: this.tree
            ,id: this.ident+'-browser-tree'
            ,cls: 'modx-browser-tree'
            ,autoScroll: true
            ,split: true
        },{
            region: 'center'
            ,layout: 'fit'
            ,items: this.view
            ,id: this.ident+'-browser-view'
            ,cls: 'modx-browser-view-ct'
            ,autoScroll: true
            ,border: false
            ,tbar: this.getToolbar()
            ,bbar: this.getPathbar()
        },{
            region: 'east'
            ,width: 250
            ,id: this.ident+'-img-detail-panel'
            ,cls: 'modx-browser-details-ct'
            ,split: true
            //,collapsed: true
        }]
    });
    MODx.Media.superclass.constructor.call(this, config);
    this.config = config;
};
Ext.extend(MODx.Media, Ext.Container, {
    returnEl: null

    /**
     * Filter the DataView results
     */
    ,filter : function() {
        var filter = Ext.getCmp(this.ident+'filter');
        this.view.store.filter('name', filter.getValue(), true);
        this.view.select(0);
    }

    /**
     * Load the given directory in the DataView
     *
     * @param {String} dir
     */
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

    /**
     * Sort the DataView results
     */
    ,sortStore: function(){
        var v = Ext.getCmp(this.ident+'sortSelect').getValue();
        this.view.store.sort(v, v == 'name' ? 'ASC' : 'DESC');
        this.view.select(0);
    }

    /**
     * Switch viewmode from grid to list and vice versa
     */
    ,changeViewmode: function() {
        var v = Ext.getCmp(this.ident+'viewSelect').getValue();
        this.view.setTemplate(v);
        this.view.select(0);
    }

    /**
     * Remove any filter applied to the DataView
     */
    ,reset: function() {
        if (this.rendered) {
            Ext.getCmp(this.ident+'filter').reset();
            this.view.getEl().dom.scrollTop = 0;
        }
        this.view.store.clearFilter();
        this.view.select(0);
    }

    /**
     * Get the browser view toolbar configuration
     *
     * @returns {Array}
     */
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
                'render': {
                    fn: function() {
                        Ext.getCmp(this.ident+'filter').getEl().on('keyup', function() {
                            this.filter();
                        }, this, {buffer: 500});
                    }
                    ,scope: this
                }
            }
        },{
            text: _('sort_by')+':'
            ,xtype: 'label'
        },{
            id: this.ident+'sortSelect'
            ,xtype: 'combo'
            ,typeAhead: true
            ,triggerAction: 'all'
            ,width: 130
            ,editable: false
            ,mode: 'local'
            ,displayField: 'desc'
            ,valueField: 'name'
            ,lazyInit: false
            ,value: MODx.config.modx_browser_default_sort || 'name'
            ,store: new Ext.data.SimpleStore({
                fields: ['name', 'desc'],
                data : [
                    ['name', _('name')]
                    ,['size', _('file_size')]
                    ,['lastmod', _('last_modified')]
                ]
            })
            ,listeners: {
                'select': {
                    fn: this.sortStore
                    ,scope: this
                }
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
                data : [
                    ['grid', _('files_viewmode_grid')]
                    ,['list', _('files_viewmode_list')]
                ]
            })
            ,listeners: {
                'select': {
                    fn: this.changeViewmode
                    ,scope: this
                }
            }
        }];
    }

    /**
     * Get the bottom filepath textfield in the browser view
     *
     * @returns {Array}
     */
    ,getPathbar: function() {
        return {
            cls: 'modx-browser-pathbbar'
            ,items: [{
                xtype: 'textfield'
                ,id: this.ident+'-filepath'
                ,cls: 'modx-browser-filepath'
                ,listeners: {
                    'focus': {
                        // select the filepath on focus
                        fn: function(el) {
                            // let the focus event stick first, needed for webkit primarily
                            setTimeout(function () {
                                var field = el.getEl().dom;

                                if (field.createTextRange) {
                                    var selRange = field.createTextRange();
                                    selRange.collapse(true);
                                    selRange.moveStart('character', 0);
                                    selRange.moveEnd('character', field.value.length);
                                    selRange.select();
                                } else if (field.setSelectionRange) {
                                    field.setSelectionRange(0, field.value.length);
                                } else if (field.selectionStart) {
                                    field.selectionStart = 0;
                                    field.selectionEnd = field.value.length;
                                }
                            }, 50);
                        }
                        ,scope: this
                    }
                }
            }]
        };
    }

    ,setReturn: function(el) {
        // @todo make sure this is never used
        console.log('MODx.Media#setReturn', el);
        this.returnEl = el;
    }

    ,onSelect: function(data) {
        // @todo make sure this is never used
        console.log('MODx.Media#onSelect', data);
        var selNode = this.view.getSelectedNodes()[0];
        var callback = this.config.onSelect || this.onSelectHandler;
        var lookup = this.view.lookup;
        var scope = this.config.scope;
        this.hide(this.config.animEl || null,function(){
            if (selNode && callback) {
                var data = lookup[selNode.id];
                Ext.callback(callback,scope || this,[data]);
                this.fireEvent('select',data);
            }
        },scope);
    }

    ,onSelectHandler: function(data) {
        // @todo make sure this is never used
        console.log('MODx.Media#onSelectHandler', data);
        Ext.get(this.returnEl).dom.value = unescape(data.url);
    }
});
Ext.reg('modx-media-view', MODx.Media);


/**
 * This is the popup window (not Ext.Window!) that opens when triggered from an RTE
 */
MODx.browser.RTE = function(config) {
    config = config || {};

    this.ident = config.ident || Ext.id();

    // Hide the "MODX Browser" toolbar button
    MODx.browserOpen = true;

    Ext.Ajax.defaultHeaders = {
        'modAuth': config.auth
    };

    Ext.Ajax.extraParams = {
        'HTTP_MODAUTH': config.auth
    };

    // Tree navigation
    this.tree = MODx.load({
        xtype: 'modx-tree-directory'
        ,onUpload: function() {
            this.view.run();
        }
        ,scope: this
        ,source: config.source || MODx.config.default_media_source
        ,hideFiles: config.hideFiles || MODx.config.modx_browser_tree_hide_files
        ,hideTooltips: config.hideTooltips || MODx.config.modx_browser_tree_hide_tooltips || true // by default do not request image preview tooltips in the media browser
        ,openTo: config.openTo || ''
        ,ident: this.ident
        ,rootId: config.rootId || '/'
        ,rootName: _('files')
        ,rootVisible: config.rootVisible == undefined || !Ext.isEmpty(config.rootId)
        ,id: this.ident+'-tree'
        ,hideSourceCombo: config.hideSourceCombo || false
        ,useDefaultToolbar: false
        ,listeners: {
            'afterUpload': {
                fn: function() {
                    this.view.run();
                }
                ,scope: this
            }
            ,'afterQuickCreate': {
                fn: function() {
                    this.view.run();
                }
                ,scope: this
            }
            ,'afterRename': {
                fn: function() {
                    this.view.run();
                }
                ,scope: this
            }
            ,'afterRemove': {
                fn: function() {
                    this.view.run();
                }
                ,scope: this
            }
            ,'changeSource': {
                fn: function(s) {
                    this.config.source = s;
                    this.view.config.source = s;
                    this.view.baseParams.source = s;
                    this.view.dir = '/';
                    this.view.run();
                }
                ,scope: this
            }
            ,'afterrender': {
                fn: function(tree) {
                    tree.root.expand();
                }
                ,scope: this
            }
            ,'beforeclick': {
                fn: function(node, e) {
                    // load the node/folder that is clicked on but prevent unnecessary requests when a file is clicked
                    if (!node.leaf) {
                        this.load(node.id);
                    } else {
                        // sync the selected item in the tree with the one in browser view
                        // the id of a browser view node in the store is the full absolute URL
                        // but there is a bug with urlAbsolute, see #11821 that's why we prepend a slash
                        this.view.select(this.view.store.indexOfId('/' + node.attributes.url));
                        // but instead load the container the file resides in if not already displayed
                        if (this.view.dir !== node.parentNode.id) {
                            this.load(node.parentNode.id);
                        }
                        return false;
                    }
                }
                ,scope: this
            }
        }
    });

    // DataView
    this.view = MODx.load({
        xtype: 'modx-browser-view'
        ,onSelect: {
            fn: this.onSelect
            ,scope: this
        }
        ,source: config.source || MODx.config.default_media_source
        ,allowedFileTypes: config.allowedFileTypes || ''
        ,wctx: config.wctx || 'web'
        ,openTo: config.openTo || ''
        ,ident: this.ident
        ,id: this.ident+'-view'
        ,tree: this.tree
    });

    Ext.applyIf(config,{
        title: _('modx_browser')
        ,cls: 'modx-browser modx-browser-rte'
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
            ,bbar: this.getPathbar()
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
            ,cls: 'modx-browser-rte-buttons'
            ,region: 'south'
            ,split: false
            ,bbar: ['->',{
                xtype: 'button'
                ,id: this.ident+'-cancel-btn'
                ,text: _('cancel')
                ,minWidth: 75
                ,handler: this.onCancel
                ,scope: this
                // ,width: 200
            },{
                xtype: 'button'
                ,id: this.ident+'-ok-btn'
                ,text: _('ok')
                ,cls: 'primary-button'
                ,minWidth: 75
                ,handler: this.onSelect
                ,scope: this
                // ,width: 200
            }]
        }]
    });
    MODx.browser.RTE.superclass.constructor.call(this,config);
    this.config = config;
};
Ext.extend(MODx.browser.RTE,Ext.Viewport,{
    returnEl: null

    /**
     * Filter the DataView results
     */
    ,filter : function() {
        var filter = Ext.getCmp(this.ident+'filter');
        this.view.store.filter('name', filter.getValue(), true);
        this.view.select(0);
    }

    /**
     * Load the given directory in the DataView
     *
     * @param {String} dir
     */
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

    /**
     * Sort the DataView results
     */
    ,sortStore: function(){
        var v = Ext.getCmp(this.ident+'sortSelect').getValue();
        this.view.store.sort(v, v == 'name' ? 'ASC' : 'DESC');
        this.view.select(0);
    }

    /**
     * Switch viewmode from grid to list and vice versa
     */
    ,changeViewmode: function() {
        var v = Ext.getCmp(this.ident+'viewSelect').getValue();
        this.view.setTemplate(v);
        this.view.select(0);
    }

    /**
     * Remove any filter applied to the DataView
     */
    ,reset: function() {
        if (this.rendered) {
            Ext.getCmp(this.ident+'filter').reset();
            this.view.getEl().dom.scrollTop = 0;
        }
        this.view.store.clearFilter();
        this.view.select(0);
    }

    /**
     * Get the browser view toolbar configuration
     *
     * @returns {Array}
     */
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
                'render': {
                    fn: function() {
                        Ext.getCmp(this.ident+'filter').getEl().on('keyup', function() {
                            this.filter();
                        }, this, {buffer: 500});
                    }
                    ,scope: this
                }
            }
        },{
            text: _('sort_by')+':'
            ,xtype: 'label'
        },{
            id: this.ident+'sortSelect'
            ,xtype: 'combo'
            ,typeAhead: true
            ,triggerAction: 'all'
            ,width: 130
            ,editable: false
            ,mode: 'local'
            ,displayField: 'desc'
            ,valueField: 'name'
            ,lazyInit: false
            ,value: MODx.config.modx_browser_default_sort || 'name'
            ,store: new Ext.data.SimpleStore({
                fields: ['name', 'desc'],
                data : [
                    ['name', _('name')]
                    ,['size', _('file_size')]
                    ,['lastmod', _('last_modified')]
                ]
            })
            ,listeners: {
                'select': {
                    fn: this.sortStore
                    ,scope: this
                }
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
                data : [
                    ['grid', _('files_viewmode_grid')]
                    ,['list', _('files_viewmode_list')]
                ]
            })
            ,listeners: {
                'select': {
                    fn: this.changeViewmode
                    ,scope: this
                }
            }
        }];
    }

    /**
     * Get the bottom filepath textfield in the browser view
     *
     * @returns {Array}
     */
    ,getPathbar: function() {
        return {
            cls: 'modx-browser-pathbbar'
            ,items: [{
                xtype: 'textfield'
                ,id: this.ident+'-filepath'
                ,cls: 'modx-browser-filepath'
                ,listeners: {
                    'focus': {
                        // select the filepath on focus
                        fn: function(el) {
                            // let the focus event stick first, needed for webkit primarily
                            setTimeout(function () {
                                var field = el.getEl().dom;

                                if (field.createTextRange) {
                                    var selRange = field.createTextRange();
                                    selRange.collapse(true);
                                    selRange.moveStart('character', 0);
                                    selRange.moveEnd('character', field.value.length);
                                    selRange.select();
                                } else if (field.setSelectionRange) {
                                    field.setSelectionRange(0, field.value.length);
                                } else if (field.selectionStart) {
                                    field.selectionStart = 0;
                                    field.selectionEnd = field.value.length;
                                }
                            }, 50);
                        }
                        ,scope: this
                    }
                }
            }]
        };
    }

    ,setReturn: function(el) {
        // @todo make sure this is never used
        console.log('MODx.Media#setReturn', el);
        this.returnEl = el;
    }

    ,onSelect: function(data) {
        var selNode = this.view.getSelectedNodes()[0];
        var callback = this.config.onSelect || this.onSelectHandler;
        var lookup = this.view.lookup;
        var scope = this.config.scope;
        if (callback) {
            data = (selNode) ? lookup[selNode.id] : null;
            Ext.callback(callback, scope || this, [data]);
            this.fireEvent('select', data);
            if (window.top.opener) {
                window.top.close();
                window.top.opener.focus();
            }
        }
    }

    ,onCancel: function() {
        var callback = this.config.onSelect || this.onSelectHandler;
        var scope = this.config.scope;
        Ext.callback(callback, scope || this, [null]);
        this.fireEvent('select', null);
        if (window.top.opener) {
            window.top.close();
            window.top.opener.focus();
        }
    }

    ,onSelectHandler: function(data) {
        Ext.get(this.returnEl).dom.value = unescape(data.url);
    }
});
Ext.reg('modx-browser-rte',MODx.browser.RTE);
