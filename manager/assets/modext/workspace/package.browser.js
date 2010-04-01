MODx.panel.PackageBrowser = function(config) {
    config = config || {};
    this.ident = config.ident || 'modx-pkgb-'+Ext.id();
    
    this.view = MODx.load({
        id: 'modx-package-browser-thumbs-view'
        ,xtype: 'modx-view-package-browser-thumbs'
        ,onSelect: {fn:function() { }, scope: this}
        ,containerScroll: true
        ,ident: this.ident
        ,style:'overflow:auto'
    });
    this.view.pagingBar = new Ext.PagingToolbar({
        pageSize: 10
        ,store: this.view.store
        ,displayInfo: true
        ,autoLoad: true
    });
    
    Ext.applyIf(config,{
        title: _('package_browser')
        ,id: 'modx-package-browser'
        ,cls: 'browser-win'
        ,layout: 'column'
        ,minWidth: 500
        ,minHeight: 350
        ,anchor: '97%'
        ,autoHeight: true
        ,modal: false
        ,hideMode: 'offsets'
        ,closeAction: 'hide'
        ,border: false
        ,autoScroll: true
        ,items: [{
            id: 'modx-package-browser-tree-panel'
            ,cls: 'browser-tree'
            ,region: 'west'
            ,width: 250
            ,items: [{
                xtype: 'modx-package-browser-tree'
                ,id: 'modx-package-browser-tree'
            }]
            ,autoScroll: true
            ,border: false
            ,hideMode: 'offsets'
        },{
            id: 'modx-package-browser-grid-panel'
            ,cls: 'browser-view'
            ,region: 'center'
            ,items: [{
                id: 'modx-package-browser-tag'
                ,border: false
            },{
                xtype: 'modx-package-browser-grid'
                ,id: 'modx-package-browser-grid'
                ,preventRender: true
            }]
            ,hidden: true
            ,border: false
            ,hideMode: 'offsets'
        },{
            id: 'modx-package-browser-view'
            ,cls: 'modx-pb-view-ct'
            ,region: 'center' 
            //,width: '75%'
            ,autoScroll: true
            ,autoHeight: true
            ,hidden: true
            ,html: ''
            ,border: false
            ,hideMode: 'offsets'
        },{
            id: 'modx-package-browser-thumbs'
            ,cls: 'modx-pb-view-ct'
            ,region: 'center'
            //,width: '55%'
            ,height: 450
            ,autoScroll: true
            ,border: false
            ,items: [{
                xtype: 'modx-combo-package-browser-sort'
                ,id: 'modx-combo-package-browser-sort'
                ,listeners: {
                    'select': {fn:this.view.sortBy, scope:this.view}
                }
            },this.view]
            ,bbar: [this.view.pagingBar]
            ,hidden: true
            ,hideMode: 'offsets'
        },{
            html: ''
            ,id: 'modx-package-browser-thumbs-detail'
            ,cls: 'modx-pb-details-ct'
            ,region: 'center'
            ,split: true
            ,autoScroll: true
            //,width: '20%'
            ,minWidth: 150
            ,maxWidth: 250
            ,height: 450
            ,hidden: true
            ,hideMode: 'offsets'
        }]
    });
    MODx.panel.PackageBrowser.superclass.constructor.call(this,config);
};
Ext.extend(MODx.panel.PackageBrowser,MODx.Panel,{
    hideDetails: function() {
        Ext.getCmp('modx-package-browser-view').hide();
        Ext.getCmp('modx-package-browser-grid-panel').show();
    }
});
Ext.reg('modx-package-browser',MODx.panel.PackageBrowser);

MODx.tree.PackageBrowserTree = function(config) {
    config = config || {};
    Ext.applyIf(config,{
        id: 'modx-package-browser-tree'
        ,url: MODx.config.connectors_url+'workspace/packages-rest.php'
        ,baseParams: {
            action: 'getNodes'
            ,provider: MODx.provider
        }
        ,loaderConfig: {
            preloadChildren: false
        }
        ,stateful: false
        ,rootVisible: true
        ,enableDD: false
        ,root: {
            text: _('provider')
            ,nodeType: 'async'
            ,id: 'modx-package-browser-tree-root'
        }
        ,hideMode: 'visibility'
        ,tbar: [{
                icon: MODx.config.template_url+'images/restyle/icons/refresh.png'
                ,cls: 'x-btn-icon'
                ,scope: this
                ,tooltip: {text: _('tree_refresh')}
                ,handler: this.refresh
                ,hideMode: 'offsets'
            },{
                xtype: 'textfield'
                ,emptyText: _('search')
                ,name: 'search'
                ,id: 'modx-pbr-search-fld'
                ,hideMode: 'offsets'
                ,listeners: {
                    'change': {fn:this.search,scope:this}
                    ,'render': {fn: function(cmp) {
                        new Ext.KeyMap(cmp.getEl(), {
                            key: Ext.EventObject.ENTER
                            ,fn: function() { 
                                this.fireEvent('change',this.getValue());
                                this.blur();
                                return true; }
                            ,scope: cmp
                        });
                    },scope:this}
                }
            }]
    });
    MODx.tree.PackageBrowserTree.superclass.constructor.call(this,config);
    this.on('render',this.setupMask,this);
    this.on('click',this.onNodeClick,this);
    this.loadTpls();
    this.on('render',this.renderProviderInfo,this);
};
Ext.extend(MODx.tree.PackageBrowserTree,MODx.tree.Tree,{
    tpls: {}
    
    ,search: function(tf,newValue) {
        var nv = newValue || tf;

        var g = Ext.getCmp('modx-package-browser-grid');
        var s = g.getStore();
        s.removeAll();
        s.setBaseParam('query',nv);
        s.setBaseParam('tag','');
        s.load({
            params: {
                query: nv
                ,tag: ''
            }
        });
        g.getBottomToolbar().changePage(1);
        Ext.getCmp('modx-package-browser-view').hide();
        Ext.getCmp('modx-package-browser-thumbs').hide();
        Ext.getCmp('modx-package-browser-thumbs-detail').hide();
        Ext.getCmp('modx-package-browser-grid-panel').show();
        return true;
    }
    
    ,onNodeClick: function(n,e) {
        switch (n.attributes.type) {
            case 'repository':
                this.tpls.repository.overwrite('modx-package-browser-view',n.attributes.data);
                Ext.getCmp('modx-package-browser-grid-panel').hide();
                Ext.getCmp('modx-package-browser-thumbs').hide();
                Ext.getCmp('modx-package-browser-thumbs-detail').hide();
                Ext.getCmp('modx-package-browser-view').show();
                break;
            case 'tag':
                var tp = n.parentNode;
                if (tp && tp.attributes.data.templated == 1) {
                    var p = Ext.getCmp('modx-package-browser-thumbs-view');
                    p.store.baseParams.tag = n.attributes.data.id;
                    p.run();
                    
                    Ext.getCmp('modx-package-browser-grid-panel').hide();
                    Ext.getCmp('modx-package-browser-thumbs').show();
                    Ext.getCmp('modx-package-browser-thumbs-detail').show();
                } else {
                    this.loadPackagesFromTag(n.attributes.data);
                    this.tpls.tag.overwrite('modx-package-browser-tag',n.attributes.data);
                    
                    Ext.getCmp('modx-package-browser-thumbs').hide();
                    Ext.getCmp('modx-package-browser-thumbs-detail').hide();
                    Ext.getCmp('modx-package-browser-grid-panel').show();
                }
                Ext.getCmp('modx-package-browser-view').hide();
                break;
            default:
                this.tpls.home.overwrite('modx-package-browser-view',this.providerInfo);
                Ext.getCmp('modx-package-browser-thumbs').hide();
                Ext.getCmp('modx-package-browser-thumbs-detail').hide();
                Ext.getCmp('modx-package-browser-grid-panel').hide();
                Ext.getCmp('modx-package-browser-view').show();
                break;
        }
    }
    
    ,renderProviderInfo: function() {
        MODx.Ajax.request({
            url: this.config.url
            ,params: {
                action: 'getInfo'
                ,provider: MODx.provider
            }
            ,listeners: {
                'success': {fn:function(r) {
                    this.tpls.home.overwrite('modx-package-browser-view',r.object);
                    this.providerInfo = r.object;
                },scope:this}
            }
        });
        Ext.getCmp('modx-package-browser-grid-panel').hide();
        Ext.getCmp('modx-package-browser-view').show();
    }
    
    
    ,loadPackagesFromTag: function(tag) {
        if (!tag) return false;
        
        var g = Ext.getCmp('modx-package-browser-grid');
        var s = g.getStore();
        s.removeAll();
        s.setBaseParam('query','');
        s.setBaseParam('tag',tag.id);
        s.load({
            params: {
                tag: tag.id
            }
        });
        g.getBottomToolbar().changePage(1);
        Ext.getCmp('modx-pbr-search-fld').setValue('');
    }
    
    ,setProvider: function(p) {
        var m = this.getLoader().fullMask;
        if (!m) {
            m = this.setupMask();
        }
        m.show();
        this.provider = p;
        this.loadDataFromProvider();
    }
    
    ,loadTpls: function() {
        this.tpls.repository = new Ext.XTemplate(
            '<tpl for=".">'
                ,'<div class="pbr-repository-view">'
                ,'<h2>{name}</h2>'
                ,'<p>{description}</p>'
                ,'</div>'
            ,'</tpl>'
        );
        this.tpls.repository.compile();
        this.tpls.tag = new Ext.XTemplate(
            '<tpl for=".">'
                ,'<div class="pbr-tag-view">'
                ,'<h3>{name} '+_('packages')+'</h3>'
                ,'<p>'+_('packages_browse_msg')+'</p>'
                ,'</div>'
            ,'</tpl>'
        );
        this.tpls.tag.compile();
        this.tpls.home = new Ext.XTemplate(
            '<tpl for=".">'
                ,'<div class="pbr-provider-home">'
                ,'<h2>'+_('provider_home_title')+'</h2>'
                ,'<p>'+_('provider_home_msg')+'</p><br />'
                ,'<p>'+_('provider_total_packages')+': {packages}</p>'
                ,'<p>'+_('provider_total_downloads')+': {downloads}</p>'
                ,'<div class="pbr-provider-box"><h3>'+_('most_popular')+'</h3>'
                ,'<tpl for="topdownloaded">'
                    ,'<p>{#}. '
                    ,'<tpl if="this.isEmpty(url) == false"><a href="{url}{id}" target="_blank">{name}</a></tpl>'
                    ,'<tpl if="this.isEmpty(url) == true">{name}</tpl>'
                    ,' - {downloads}</p>'
                ,'</tpl></div>'
                ,'<div class="pbr-provider-box"><h3>'+_('newest_additions')+'</h3>'
                ,'<tpl for="newest">'
                    ,'<p>{#}. '
                    ,'<tpl if="this.isEmpty(url) == false"><a href="{url}{id}" target="_blank">{name}</a></tpl>'
                    ,'<tpl if="this.isEmpty(url) == true">{name}</tpl>'
                    ,' - {releasedon}</p>'
                ,'</tpl></div>'
                ,'<br class="clear" /></div>'
            ,'</tpl>'
        ,{
            isEmpty: function (v) {
                return (v == '' || v == null || v == undefined);
            }
        });
        this.tpls.home.compile();
        
        
    }
    
    ,setupMask: function() {
        var tl = this.getLoader();
        Ext.apply(tl,{fullMask : new Ext.LoadMask(this.getEl(),{msg:_('loading')}) });
        tl.fullMask.removeMask=false;
        tl.on({
            'load' : function(){this.fullMask.hide();}
            ,'loadexception' : function(){this.fullMask.hide();}
            ,'beforeload' : function(){this.fullMask.show();}
            ,scope : tl
        });
        return tl.fullMask;
    }
});
Ext.reg('modx-package-browser-tree',MODx.tree.PackageBrowserTree);


MODx.grid.PackageBrowserGrid = function(config) {
    config = config || {};
    this.exp = new Ext.grid.RowExpander({
        tpl : new Ext.Template(
            '<p class="package-readme"><i>{description}</i></p>'
        )
    });
    this.action = new Ext.ux.grid.RowActions({
         actions: [{
             iconCls: 'package-details'
            ,text: _('details')
        },{
             iconIndex: 'dlaction-icon'
            ,textIndex: 'dlaction-text'
        }]
        ,widthSlope:125
    });
    this.action.on('action',this.btnclick,this);
    Ext.applyIf(config,{
        id: 'modx-package-browser-grid'
        ,fields: ['id','version','release','signature','author','description','instructions','createdon','editedon','name'
                 ,'downloads','releasedon','screenshot','license','supports','location','version-compiled'
                 ,'downloaded','dlaction-text','dlaction-icon']
        ,url: MODx.config.connectors_url+'workspace/packages-rest.php'
        ,baseParams: {
            action: 'getList'
        }
        ,paging: true
        ,pageSize: 10
        ,plugins: [this.action,this.exp]
        ,hideMode: 'offsets'
        ,columns: [this.exp,{
            header: _('name')
            ,dataIndex: 'name'
            ,width: 100
            ,sortable: true
        },{
            header: _('version')
            ,dataIndex: 'version-compiled'
            ,width: 60
        },{
            header: _('author')
            ,dataIndex: 'author'
            ,width: 80
        },{
            header: _('released')
            ,dataIndex: 'releasedon'
            ,width: 80
        },{
            header: _('downloads')
            ,dataIndex: 'downloads'
            ,width: 80
        },this.action]
        ,tbar: [{
            xtype: 'modx-combo-package-browser-sort'
            ,id: 'modx-combo-package-browser-sort'
            ,hideMode: 'offsets'
            ,listeners: {
                'select': {fn:function(cb,rec,idx) {
                    var v = cb.getValue();
                    var s = this.getStore();
                    s.removeAll();
                    s.setBaseParam('sorter',v);
                    s.load({
                        params: {
                            sorter: v
                        }
                    });
                    this.getBottomToolbar().changePage(1);
                },scope:this}
            }
        }]
    });
    MODx.grid.PackageBrowserGrid.superclass.constructor.call(this,config);
    this.loadTpls();
    this.getStore().on('load',function() {
    
        Ext.getCmp('modx-window-package-downloader').syncSize();
        Ext.getCmp('modx-window-package-downloader').doLayout();
    },this);
};
Ext.extend(MODx.grid.PackageBrowserGrid,MODx.grid.Grid,{    
    loadTpls: function() {
        this.detailsTpl = new Ext.XTemplate(
            '<div class="pb-details" style="padding: 1em;">'
            ,'<tpl for=".">'
                ,'<div class="pb-mi-content">'
                ,'<div class="pbr-details-right">'
                    ,'<div class="ux-row-action" onclick="Ext.getCmp(\'modx-package-browser\').hideDetails(); return false;">'
                        ,'<div class="ux-row-action-item ux-row-action-text"><span>'+_('back_txt')+'</span></div>'
                    ,'</div>'
                    ,'<tpl if="screenshot"><img src="{screenshot}" alt="" width="200" height="134" /></tpl>'
                ,'</div>'
                ,'<h3>{name}</h3>'
                ,'<tpl if="author">'
                    ,'<i>'+_('by')+' {author}</i><br />'
                ,'</tpl>'
                ,'<span>'+_('released_on')+': {releasedon}</span><br />'
                ,'<span>'+_('license')+': {license}</span><br />'
                ,'<span>'+_('downloads')+': {downloads}</span><br />'
                ,'<span>'+_('supports')+': {supports}</span><br />'
                ,'<br /><h4>'+_('description')+'</h4>'
                ,'<p>{description}</p>'
                ,'<tpl if="instructions">'
                    ,'<br /><h4>'+_('installation_instructions')+'</h4>'
                    ,'<p>{instructions}</p>'
                ,'</tpl>'
                ,'<tpl if="downloaded">'
                    ,'<br /><p class="green bold">'+_('already_downloaded')+'</p>'
                ,'</tpl>'
                ,'</div>'
            ,'</tpl></div>'
        );
        this.detailsTpl.compile();
    }
    
    ,btnclick: function(g,rec,a,ri,ci) {
        switch (a) {
            case 'package-details': this.details(g,rec,a,ri); break;
            case 'package-download':
                if (!rec.downloading || rec.downloading == undefined) {
                    var btns = Ext.query('.package-download');
                    Ext.each(btns,function(btn) { btn.style.opacity = '0.5'; });
                    rec.downloading = true;
                    this.download(g,rec,a,ri);
                }
            break;
        }
    }
    ,details: function(g,rec,a,ri) {
        this.menu.record = rec.data;
        /* do more details */        
        this.detailsTpl.overwrite('modx-package-browser-view',rec.data);
        Ext.getCmp('modx-package-browser-view').show();
        Ext.getCmp('modx-package-browser-grid-panel').hide();
    }
    ,download: function(g,rec,a,ri) {
        this.menu.record = rec.data;
        /* do download */
        MODx.Ajax.request({
            url: this.config.url
            ,params: {
                action: 'download'
                ,info: rec.data.location+'::'+rec.data.signature
                ,provider: MODx.provider
            }
            ,scope: this
            ,listeners: {
                'success': {fn:function(r) {
                    Ext.getCmp('modx-grid-package').refresh();
                    this.refresh();
                    rec.downloading = false;
                    var btns = Ext.query('.package-download');
                    Ext.each(btns,function(btn) { btn.style.opacity = '1.0'; });
                },scope:this}
                ,'failure': {fn:function(r) {
                    rec.downloading = false;
                    var btns = Ext.query('.package-download');
                    Ext.each(btns,function(btn) { btn.style.opacity = '1.0'; });
                },scope:this}
            }
        });
    }
});
Ext.reg('modx-package-browser-grid',MODx.grid.PackageBrowserGrid);

MODx.combo.PackageBrowserSort = function(config) {
    config = config || {};
    Ext.applyIf(config,{
        xtype: 'combo'
        ,store: new Ext.data.ArrayStore({
            fields: ['d','v']
            ,data : [['','']
                ,[_('alphabetically'),'alpha']
                ,[_('most_downloads'),'downloads']
                ,[_('newest_added'),'newest']
                ,[_('top_rated'),'toprated']]
        })
        ,displayField: 'd'
        ,valueField: 'v'
        ,listWidth: 200
        ,mode: 'local'
        ,forceSelection: true
        ,emptyText: _('sort_by_dots')
        ,editable: false
        ,triggerAction: 'all'
        ,typeAhead: false
        ,selectOnFocus: true
    });
    MODx.combo.PackageBrowserSort.superclass.constructor.call(this,config);
};
Ext.extend(MODx.combo.PackageBrowserSort,Ext.form.ComboBox)
Ext.reg('modx-combo-package-browser-sort',MODx.combo.PackageBrowserSort);


MODx.PackageBrowserThumbsView = function(config) {
    config = config || {};
    
    this._initTemplates();
    Ext.applyIf(config,{
        url: MODx.config.connectors_url+'workspace/packages-rest.php'
        ,fields: ['id','version','release','signature','author','description','instructions','createdon','editedon','name'
                 ,'downloads','releasedon','screenshot','license','supports','location','version-compiled'
                 ,'downloaded','dlaction-text','dlaction-icon']
        ,ident: 'scsv'
        ,id: 'modx-package-browser-thumbs-view'
        ,baseParams: { 
            action: 'getList'
            ,provider: MODx.provider
        }
        ,loadingText: _('loading')
        ,tpl: this.templates.thumb
        ,listeners: {
            'dblclick': {fn: this.onDblClick ,scope:this }
        }
        ,prepareData: this.formatData.createDelegate(this)
    });
    MODx.PackageBrowserThumbsView.superclass.constructor.call(this,config);
    this.on('selectionchange',this.showDetails,this,{buffer: 100});
};
Ext.extend(MODx.PackageBrowserThumbsView,MODx.DataView,{
    templates: {}
    
    ,run: function(p) {
        var v = {};
        Ext.applyIf(v,this.store.baseParams);
        Ext.applyIf(v,p);
        this.pagingBar.changePage(1);
        this.store.load({
            params: v
        });
    }
        
    ,sortBy: function(sel) {
        var v = sel.getValue();
        this.store.baseParams.sorter = v;
        this.run();
        return true;
    }
    
    ,sortDir: function(sel) {
        var v = sel.getValue();
        this.store.baseParams.dir = v;
        this.run();
    }
    
    ,showDetails : function(){
        var selNode = this.getSelectedNodes();
        var detailEl = Ext.getCmp('modx-package-browser-thumbs-detail').body;
        if(selNode && selNode.length > 0){
            selNode = selNode[0];
            var data = this.lookup[selNode.id];
            if (data) {
                detailEl.hide();
                this.templates.details.overwrite(detailEl, data);
                detailEl.slideIn('l', {stopFx:true,duration:'.2'});
            }
        }else{
            detailEl.update('');
        }
    }
    
    ,formatData: function(data) {
        var formatSize = function(data){
            if(data.size < 1024) {
                return data.size + " bytes";
            } else {
                return (Math.round(((data.size*10) / 1024))/10) + " KB";
            }
        };
        data.shortName = Ext.util.Format.ellipsis(data.name, 16);
        data.sizeString = formatSize(data);
        data.releasedon = new Date(data.releasedon).format("m/d/Y g:i a");
        this.lookup['modx-package-thumb-'+data.id] = data;
        return data;
    }
    ,_initTemplates: function() {
        this.templates.thumb = new Ext.XTemplate(
            '<tpl for=".">'
                ,'<div class="modx-pb-thumb-wrap <tpl if="downloaded">pbr-thumb-downloaded</tpl>" id="modx-package-thumb-{id}">'
                    ,'<div class="modx-pb-thumb">'
                        ,'<img src="{screenshot}" title="{name}" width="90" height="90" />'
                    ,'</div>'
                    ,'<span>{shortName}</span>'
                    ,'<span>{downloads} '+_('downloads')+'</span>'
                    ,'<tpl if="downloaded"><span class="green">'+_('downloaded')+'</span></tpl>'              
                ,'</div>'
            ,'</tpl>'
        );

        this.templates.thumb.compile();
                
        this.templates.details = new Ext.XTemplate(
            '<div class="details">'
            ,'<tpl for=".">'
                ,'<div class="modx-pb-detail-thumb">'
                    ,'<img src="{screenshot}" alt="{name}" width="80" height="60" onclick="Ext.getCmp(\'modx-package-browser-thumbs-view\').showScreenshot(\'{id}\'); return false;" />'
                ,'</div>'
                ,'<div class="modx-pb-details-info">'
                    ,'<h4>{name} {version-compiled}</h4><br />'
                    ,'<p>{description}</p><br />'
                    ,'<b>'+_('author')+':</b>'
                    ,'<span>{author}</span>'
                    ,'<b>'+_('released_on')+':</b><span>{releasedon}</span>'
                    ,'<span>{downloads} '+_('downloads')+'</span>'                    
                    ,'<b>'+_('supports')+':</b><span>{supports}</span>'
                    ,'<b>'+_('license')+':</b><span>{license}</span>'
                    
                    ,'<tpl if="!downloaded">'
                        ,'<div class="ux-row-action" onclick="Ext.getCmp(\'modx-package-browser-thumbs-view\').download(\'{id}\'); return false;">'
                            ,'<div class="ux-row-action-item ux-row-action-text"><span>'+_('download')+'</span></div>'
                        ,'</div>'
                    ,'</tpl>'
                    ,'<tpl if="downloaded">'
                        ,'<div class="ux-row-action pbr-thumb-downloaded">'
                            ,'<div class="ux-row-action-item ux-row-action-text"><span>'+_('downloaded')+'</span></div>'
                        ,'</div>'
                    ,'</tpl>'
                ,'</div>'
            ,'</tpl>'
            ,'</div>'
        );
        this.templates.details.compile(); 
    }
    ,download: function(id) {
        var data = this.lookup['modx-package-thumb-'+id];
        if (!data) return false;
        /* do download */
        MODx.Ajax.request({
            url: this.config.url
            ,params: {
                action: 'download'
                ,info: data.location+'::'+data.signature
                ,provider: MODx.provider || 1
            }
            ,scope: this
            ,listeners: {
                'success': {fn:function(r) {
                    this.run();
                },scope:this}
            }
        });
    }
    ,showScreenshot: function(id) {
        var data = this.lookup['modx-package-thumb-'+id];
        if (!data) return false;
        
        if (!this.ssWin) {
            this.ssWin = new Ext.Window({
                layout:'fit'
                ,width: 600
                ,height: 450
                ,closeAction:'hide'
                ,plain: true
                ,items: [{
                    id: 'modx-package-thumb-ss'
                    ,html: ''
                }]
                ,buttons: [{
                    text: _('close')
                    ,handler: function() { this.ssWin.hide(); }
                    ,scope: this
                }]
            });
        }
        this.ssWin.show();
        Ext.get('modx-package-thumb-ss').update('<img src="'+data.screenshot+'" width="600" height="400" alt="" onclick="Ext.getCmp(\'modx-package-browser-thumbs-view\').ssWin.hide();" />');
    }
});
Ext.reg('modx-view-package-browser-thumbs',MODx.PackageBrowserThumbsView);