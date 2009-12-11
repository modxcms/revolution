MODx.panel.PackageBrowser = function(config) {
    config = config || {};
    this.ident = config.ident || 'modx-pkgb-'+Ext.id();
    Ext.applyIf(config,{
        title: 'Package Browser'
        ,id: 'modx-package-browser'
        ,cls: 'browser-win'
        ,layout: 'column'
        ,minWidth: 500
        ,minHeight: 300
        ,width: '90%'
        ,height: 500
        ,modal: false
        ,closeAction: 'hide'
        ,border: false
        ,items: [{
            id: this.ident+'-browser-tree'
            ,cls: 'browser-tree'
            ,region: 'west'
            ,width: '25%'
            ,items: [{
                xtype: 'modx-package-browser-tree'
            }]
            ,autoScroll: true
            ,border: false
        },{
            id: 'package-browser-grid'
            ,cls: 'browser-view'
            ,region: 'center'
            ,width: '75%'
            ,items: [{
                id: 'package-browser-tag'
                ,border: false
            },{
                xtype: 'modx-package-browser-grid'
                ,preventRender: true
            }]
            ,hidden: true
            ,border: false
        },{
            id: 'package-browser-view'
            ,cls: 'browser-view'
            ,region: 'east' 
            ,width: '75%'
            ,autoScroll: true
            ,autoHeight: true
            ,html: ''
            ,border: false
        }]
    });
    MODx.panel.PackageBrowser.superclass.constructor.call(this,config);
};
Ext.extend(MODx.panel.PackageBrowser,MODx.Panel,{
    hideDetails: function() {
        Ext.getCmp('package-browser-view').hide();
        Ext.getCmp('package-browser-grid').show();
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
        ,tbar: [{
            icon: MODx.config.template_url+'images/restyle/icons/refresh.png'
            ,cls: 'x-btn-icon'
            ,scope: this
            ,tooltip: {text: _('tree_refresh')}
            ,handler: this.refresh
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
    
    ,onNodeClick: function(n,e) {
        switch (n.attributes.type) {
            case 'repository':
                this.tpls.repository.overwrite('package-browser-view',n.attributes.data);
                Ext.getCmp('package-browser-grid').hide();
                Ext.getCmp('package-browser-view').show();
                break;
            case 'tag':
                this.loadPackagesFromTag(n.attributes.data);
                this.tpls.tag.overwrite('package-browser-tag',n.attributes.data);
                Ext.getCmp('package-browser-view').hide();
                Ext.getCmp('package-browser-grid').show();
                break;
            default:
                this.tpls.home.overwrite('package-browser-view',this.providerInfo);
                Ext.getCmp('package-browser-view').show();
                Ext.getCmp('package-browser-grid').hide();
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
                    this.tpls.home.overwrite('package-browser-view',r.object);
                    this.providerInfo = r.object;
                },scope:this}
            }
        });
        Ext.getCmp('package-browser-grid').hide();
        Ext.getCmp('package-browser-view').show();
    }
    
    
    ,loadPackagesFromTag: function(tag) {
        if (!tag) return false;
        
        var g = Ext.getCmp('modx-package-browser-grid');
        var s = g.getStore();
        s.removeAll();
        s.setBaseParam('tag',tag.id);
        s.load({
            params: {
                tag: tag.id
            }
        });
        g.getBottomToolbar().changePage(1);
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
                    ,'<p>{#}. {name} - {downloads}</p>'
                ,'</tpl></div>'
                ,'<div class="pbr-provider-box"><h3>'+_('newest_additions')+'</h3>'
                ,'<tpl for="newest">'
                    ,'<p>{#}. {name} - {releasedon}</p>'
                ,'</tpl></div>'
                ,'<br class="clear" /></div>'
            ,'</tpl>'
        );
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
    
    ,btnclick: function(g,rec,a,ri) {
        switch (a) {
            case 'package-details': this.details(g,rec,a,ri); break;
            case 'package-download': this.download(g,rec,a,ri); break;
        }
    }
    ,details: function(g,rec,a,ri) {
        this.menu.record = rec.data;
        /* do more details */        
        this.detailsTpl.overwrite('package-browser-view',rec.data);
        Ext.getCmp('package-browser-view').show();
        Ext.getCmp('package-browser-grid').hide();
    }
    ,download: function(g,rec,a,ri) {
        this.menu.record = rec.data;
        /* do download */
        MODx.Ajax.request({
            url: this.config.url
            ,params: {
                action: 'download'
                ,info: rec.data.location+'::'+rec.data.signature
                ,provider: this.provider
            }
            ,scope: this
            ,listeners: {
                'success': {fn:function(r) {
                    Ext.getCmp('modx-grid-package').refresh();
                    this.refresh();
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