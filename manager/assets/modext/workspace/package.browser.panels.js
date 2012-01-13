/**
 * The package browser home card
 *
 * @class MODx.panel.PackageBrowserHome
 * @extends MODx.TemplatePanel
 * @param {Object} config An object of options.
 * @xtype modx-package-browser-home
 */
MODx.panel.PackageBrowserHome = function(config) {
    config = config || {};

	Ext.applyIf(config,{
		markup: '<tpl for=".">'
			+'<div class="one_half"><div class="x-panel-header"><span class="x-panel-header-text">'+_('most_popular')+'</span></div>'
				+'<ol class="x-panel-body">'
					+'<tpl for="topdownloaded">'
						+'<li>'
							+'<span class="highlighted">'+_('downloads_view')+'</span>'
							+'<button type="button" onclick="Ext.getCmp(\'modx-package-browser-tree\').searchFor(\'{name}\');">'
								+'<span class="ct">{#}</span>'
								+'{name}'
							+'</button>'
						+'</li>'
					+'</tpl>'
				+'</ol>'
			+'</div>'
			+'<div class="one_half last"><div class="x-panel-header"><span class="x-panel-header-text">'+_('newest_additions')+'</span></div>'
				+'<ol class="x-panel-body">'
					+'<tpl for="newest">'
						+'<li>'
							+'<span class="highlighted">{releasedon}</span>'
							+'<button type="button" onclick="Ext.getCmp(\'modx-package-browser-tree\').searchFor(\'{package_name}\');">'
								+'<span class="ct">{#}</span>'
								+'{name}'
							+'</button>'
						+'</li>'
					+'</tpl>'
				+'</ol>'
			+'</div>'
			+'<div class="stats">'
				+'<p>'+_('provider_total_packages')+': {packages}</p>'
				+'<p>'+_('provider_total_downloads')+': {downloads}</p>'
			+'</div>'
		+'</tpl>'
		,bodyCssClass: 'home-panel'
	});
	MODx.panel.PackageBrowserHome.superclass.constructor.call(this,config);
};
Ext.extend(MODx.panel.PackageBrowserHome,MODx.TemplatePanel,{
	activate: function(){
		Ext.getCmp('package-browser-card-container').getLayout().setActiveItem(this.id);
		var me = this;
		setTimeout(function(){
			me.doLayout();
		}, 500);
	}
});
Ext.reg('modx-package-browser-home',MODx.panel.PackageBrowserHome);

/**
 * The Repositories template panel
 *
 * @class MODx.panel.PackageBrowserRepositories
 * @extends MODx.TemplatePanel
 * @param {Object} config An object of options.
 * @xtype modx-package-browser-repositories
 */
MODx.panel.PackageBrowserRepositories = function(config) {
    config = config || {};
	Ext.applyIf(config,{
		markup: '<tpl for=".">'
			+'<div class="pbr-repository-view">'
				+'<h2>{name}</h2>'
				+'<p>{description}</p>'
			+'</div>'
		+'</tpl>'
		,bodyCssClass: 'home-panel'
	});
	MODx.panel.PackageBrowserRepositories.superclass.constructor.call(this,config);
};
Ext.extend(MODx.panel.PackageBrowserRepositories,MODx.TemplatePanel,{
	activate: function(){
		Ext.getCmp('package-browser-card-container').getLayout().setActiveItem(this.id);
	}
});
Ext.reg('modx-package-browser-repositories',MODx.panel.PackageBrowserRepositories);

/**
 * A base combobox for sorting options
 *
 * @class MODx.changeSortComboBox
 * @extends Ext.form.ComboBox
 * @param {Object} config An object of options.
 * @xtype modx-package-changesort-combobox
 */
MODx.changeSortComboBox = function(config) {
	config = config || {};

	Ext.applyIf(config,{
		store: new Ext.data.ArrayStore({
			fields: ['d','v']
			,data : [['','']
				,[_('alphabetically'),'alpha']
				,[_('most_downloads'),'downloads']
				,[_('newest_added'),'newest']
				,[_('top_rated'),'toprated']]
		})
		,displayField: 'd'
		,valueField: 'v'
		,width:280
		,mode: 'local'
		,forceSelection: true
		,emptyText: _('sort_by_dots')
		,editable: false
		,triggerAction: 'all'
		,typeAhead: false
		,selectOnFocus: false
	});
	MODx.changeSortComboBox.superclass.constructor.call(this,config);
};
Ext.extend(MODx.changeSortComboBox,Ext.form.ComboBox);
Ext.reg('modx-package-changesort-combobox',MODx.changeSortComboBox);

/**
 * The package browser package grid card
 *
 * @class MODx.panel.PackageBrowserGrid
 * @extends MODx.grid.Grid
 * @param {Object} config An object of options.
 * @xtype modx-package-browser-grid
 */
MODx.grid.PackageBrowserGrid = function(config) {
	config = config || {};

	this.exp = new Ext.grid.RowExpander({
        tpl : new Ext.Template(
            '<p class="package-readme"><i>{description}</i></p>'
        )
    });

	this.mainColumnTpl = new Ext.XTemplate('<tpl for=".">'
		+'<h3 class="main-column{state:defaultValue("")}">{name}</h3>'
		+'<tpl if="actions !== null">'
			+'<ul class="actions">'
				+'<tpl for="actions">'
					+'<li><button type="button" class="controlBtn {className}">{text}</button></li>'
				+'</tpl>'
			+'</ul>'
		+'</tpl>'
	+'</tpl>', {
		compiled: true
	});

	Ext.applyIf(config,{
		id: 'modx-package-browser-grid'
        ,fields: ['id','version','release','signature','author','description','instructions','createdon','editedon','name'
                 ,'downloads','releasedon','screenshot','license','location','version-compiled'
                 ,'supports_db','minimum_supports','breaks_at','featured','audited','changelog'
                 ,'downloaded','dlaction-text','dlaction-icon']
        ,url: MODx.config.connectors_url+'workspace/packages-rest.php'
        ,baseParams: {
			provider: MODx.provider
			,action:'getList'
		}
        ,paging: true
        ,pageSize: 10
		,plugins: [this.exp]
		,showPerPage: false
		,columns: [this.exp,{
            header: _('name')
            ,dataIndex: 'name'
            ,width: 100
            ,sortable: true
			,id:'main'
			,renderer: { fn: this.mainColumnRenderer, scope: this }
        },{
            header: _('version')
            ,dataIndex: 'version-compiled'
            ,width: 100
			,fixed:true
			,id: 'meta-col'
        },{
            header: _('author')
            ,dataIndex: 'author'
            ,width: 100
			,fixed:true
			,id: 'text-col'
        },{
            header: _('released')
            ,dataIndex: 'releasedon'
            ,width: 140
			,fixed:true
			,id: 'info-col'
        },{
            header: _('downloads')
            ,dataIndex: 'downloads'
            ,width: 100
			,fixed:true
			,id: 'text-col'
        }]
		,tbar: [{
			xtype: 'button'
			,text: _('back_to_manager')
			,handler: function(){
				Ext.getCmp('modx-panel-packages').activate();
			}
		},'->',{
			xtype: 'modx-package-changesort-combobox'
			,id: 'modx-package-grid-changesort-combobox'
			,listeners: {
				'select': this.changeSort
				,'change': this.changeSort
				,scope: this
			}
        }]
	});
	MODx.grid.PackageBrowserGrid.superclass.constructor.call(this,config);
	this.on('click', this.onClick, this);
};
Ext.extend(MODx.grid.PackageBrowserGrid,MODx.grid.Grid,{
	// Actions handlers
	onClick: function(e){
		var t = e.getTarget();
		var elm = t.className.split(' ')[0];
		if(elm == 'controlBtn'){
			var act = t.className.split(' ')[1];
			var record = this.getSelectionModel().getSelected();
			switch (act) {
                case 'details':
                    this.onDetails(record.data);
                    break;
                case 'download':
					this.onDownload(record.data);
                    break;
				default:
					break;
            }
		}
	}

	,changeSort: function(cb,rec,idx) {
        var v = cb.getValue();
        var s = this.getStore();
        s.removeAll();
        s.setBaseParam('sorter',v);
        s.load();
    }

	,mainColumnRenderer:function (value, metaData, record, rowIndex, colIndex, store){
		var values = { name: value, actions: null };
		var h = [];
		h.push({ className:'details', text: _('view_details') });
		if(!record.data.downloaded){
			h.push({ className:'download green', text: _('download') });
		}
		values.actions = h;
		return this.mainColumnTpl.apply(values);
	}

	,onDownload: function(rec){
		var c = Ext.getCmp('modx-panel-packages-browser');
		c.showWait();
		Ext.Ajax.request({
			url : this.config.url
			,params : {
				action : 'download'
				,info : rec.location+'::'+rec.signature
				,provider : MODx.provider
			}
			,scope: this
			,success: function ( result, request ) {
				this.processResult( result.responseText );
			}
			,failure: function ( result, request) {
				Ext.MessageBox.alert('Failed', result.responseText);
				c.hideWait();
			}
		});
	}

	,processResult: function( response ){
		var data = Ext.util.JSON.decode( response );
		var me = this;
		if( data.success ){
			me.getStore().reload();
			Ext.getCmp('modx-package-grid').getStore().reload();
			Ext.getCmp('modx-panel-packages-browser').hideWait();
			me.updateBreadcrumbs(_('download_success'), true);
			setTimeout(function(){
				me.updateBreadcrumbs(_('list_of_packages_in_provider'));
			}, 5000);
		}
	}

	,onDetails: function(rec){
		Ext.getCmp('modx-package-browser-details').activate();
		Ext.getCmp('modx-package-browser-details').updateDetail(rec);
	}

	,activate: function(cat, query){
		if(cat != undefined){
			this.bdText = cat;
		}
		var msg = (!Ext.isEmpty(query) && !Ext.isObject(query)) ? _('search_results_for',{'query': query}) : _('packages_in_category');

		Ext.getCmp('package-browser-card-container').getLayout().setActiveItem(this.id);
		this.updateBreadcrumbs(msg);
	}

	,updateBreadcrumbs: function(msg, highlight){
		var bd = { text: msg };
        if(highlight){ bd.className = 'highlight'; }
		/* @TODO : lexiconify */
		bd.trail = [{
			text : _('package_browser')
			,pnl : 'modx-panel-packages-browser'
		},{
			text : this.bdText
		}];
		Ext.getCmp('packages-breadcrumbs').updateDetail(bd);
	}
});
Ext.reg('modx-package-browser-grid',MODx.grid.PackageBrowserGrid);

/**
 * The package browser detail card
 *
 * @class MODx.PackageBrowserWaitWindow
 * @extends Ext.Window
 * @param {Object} config An object of options.
 * @xtype modx-package-browser-details
 */
MODx.PackageBrowserWaitWindow = function(config) {
	config = config || {};

	Ext.applyIf(config,{
		html: '<p class="wait">'+_('downloading')+'</p>'
		,bodyCssClass: 'centered'
		,modal: true
		,title: _('please_wait')
		,border: false
		,closable: false
		,width: 400
	});
	MODx.PackageBrowserWaitWindow.superclass.constructor.call(this,config);
}
Ext.extend(MODx.PackageBrowserWaitWindow,Ext.Window);

/**
 * The package browser detail card
 *
 * @class MODx.panel.PackageBrowserDetails
 * @extends MODx.Panel
 * @param {Object} config An object of options.
 * @xtype modx-package-browser-details
 */
MODx.panel.PackageBrowserDetails = function(config) {
    config = config || {};

	Ext.applyIf(config,{
		layout: 'column'
		,border: false
		,autoHeight: true
		,items:[{
			xtype: 'modx-template-panel'
			,id: 'modx-package-browser-details-main'
			,columnWidth: 1
			,markup: '<div class="details">'
				+'<tpl for=".">'
					+'<tpl if="description">'
						+'<div class="item">'
							+'<h4>'+_('description')+'</h4>'
							+'{description}'
						+'</div>'
					+'</tpl>'
					+'<tpl if="instructions">'
						+'<div class="item">'
							+'<h4>'+_('instructions')+'</h4>'
							+'{instructions}'
						+'</div>'
					+'</tpl>'
					+'<tpl if="changelog">'
						+'<div class="item">'
							+'<h4>'+_('changelog')+'</h4>'
							+'{changelog}'
						+'</div>'
					+'</tpl>'
				+'</tpl>'
			+'</div>'
		},{
			xtype: 'modx-template-panel'
			,id: 'modx-package-browser-details-aside'
			,cls: 'aside-details'
			,width: 250
			,markup: '<div class="details">'
				+'<tpl for=".">'
					+'<div class="selected">'
						+'<tpl if="screenshot">'
							+'<a href="{screenshot}" title="'+_('package_preview_view')+'" alt="'+_('package_preview_view')+'" class="lightbox" />'
								+'<img src="{screenshot}" alt="{name}" />'
							+'</a>'
						+'</tpl>'
						+'<h5>{name} {version-compiled}</h5>'
						+'<tpl if="!downloaded">'
							+'<button class="inline-button green" onclick="Ext.getCmp(\'modx-package-browser-details\').downloadPackage(\'{id}\'); return false;"/>'+_('download')+'</button>'
						+'</tpl>'
						+'<tpl if="downloaded">'
							+'<div class="downloaded">'
								+_('package_already_downloaded')
							+'</div>'
						+'</tpl>'
					+'</div>'
					+'<div class="infos description">'
						+'<h4>Information</h4>'
						+'<ul>'
							+'<li>'
								+'<span class="infoname">'+_('author')+':</span>'
								+'<span class="infovalue">{author}</span>'
							+'</li>'
							+'<li>'
								+'<span class="infoname">'+_('released_on')+':</span>'
								+'<span class="infovalue">{releasedon}</span>'
							+'</li>'
							+'<li>'
								+'<span class="infoname">'+_('minimum_supports')+':</span>'
								+'<span class="infovalue">{minimum_supports:defaultValue("--")}</span>'
							+'</li>'
							+'<li>'
								+'<span class="infoname">'+_('breaks_at')+':</span>'
								+'<span class="infovalue">{breaks_at:defaultValue("--")}</span>'
							+'</li>'
							+'<li>'
								+'<span class="infoname">'+_('supports_db')+':</span>'
								+'<span class="infovalue">{supports_db:defaultValue("--")}</span>'
							+'</li>'
							+'<li>'
								+'<span class="infoname">'+_('downloads')+':</span>'
								+'<span class="infovalue">{downloads}</span>'
							+'</li>'
							+'<li>'
								+'<span class="infoname">'+_('license')+':</span>'
								+'<span class="infovalue">{license:defaultValue("--")}</span>'
							+'</li>'
						+'</ul>'
					+'</div>'
				+'</tpl>'
			+'</div>'
		}]
		,tbar: [{
			xtype: 'button'
			,text: _('back_to_browser')
			,handler: this.onBackToPackageBrowserGrid
			,scope: this
		}]
	});
	MODx.panel.PackageBrowserDetails.superclass.constructor.call(this,config);
};
Ext.extend(MODx.panel.PackageBrowserDetails,MODx.Panel,{
	activate: function(){
		Ext.getCmp('package-browser-card-container').getLayout().setActiveItem(this.id);
	}

	,updateBreadcrumbs: function(msg, highlight){
		var bd = { text: msg };
        if(highlight){ bd.className = 'highlight'; }
		bd.trail = [{
			text : _('package_browser')
			,pnl : 'modx-panel-packages-browser'
		},{
			text : _('package_details')
		}];
		Ext.getCmp('packages-breadcrumbs').updateDetail(bd);
	}

	,updateDetail: function(rec, text){
		this.updateBreadcrumbs(_('package_details')+': '+ rec.name +' '+ rec['version-compiled']);
		Ext.getCmp('modx-package-browser-details-main').updateDetail(rec);
		Ext.getCmp('modx-package-browser-details-aside').updateDetail(rec);
	}

	,onBackToPackageBrowserGrid: function(btn,e){
		Ext.getCmp('modx-package-browser-grid').activate();
	}

	,downloadPackage: function(btn, e){
		grid = Ext.getCmp('modx-package-browser-grid');
		var record = grid.getSelectionModel().getSelected();
		grid.activate();
		grid.onDownload(record.data);
	}
});
Ext.reg('modx-package-browser-details',MODx.panel.PackageBrowserDetails);

/**
 * The view panel for the template browser
 *
 * @class MODx.PackageBrowserThumbsView
 * @extends MODx.DataView
 * @param {Object} config An object of options.
 * @xtype modx-package-browser-thumbs-view
 */
MODx.PackageBrowserThumbsView = function(config) {
    config = config || {};

    this._initTemplates();
    Ext.applyIf(config,{
        url: MODx.config.connectors_url+'workspace/packages-rest.php'
        ,fields: ['id','version','release','signature','author','description','instructions','createdon','editedon','name'
                 ,'downloads','releasedon','screenshot','license','supports','location','version-compiled'
                 ,'downloaded','dlaction-text','dlaction-icon']
        ,baseParams: {
            action: 'getList'
            ,provider: MODx.provider
        }
        ,tpl: this.templates.thumb
        ,listeners: {
            'dblclick': {fn: this.onDblClick ,scope:this }
        }
        ,prepareData: this.formatData.createDelegate(this)
		,overClass:'x-view-over'
		,selectedClass:'selected'
		,itemSelector: 'div.thumb-wrapper'
		,loadingText : '<div class="empty-msg"><h4>'+_('loading')+'</h4></div>'
		,emptyText : '<div class="empty-msg">' + this.emptyText + '</div>'|| '<div class="empty-msg"><h4>'+_('package_update_err_provider_empty')+'</h4></div>'
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
        this.store.load({
            params: v
			// /* Fix layout after the store's loaded */
			,callback: function(rec, options, success){
				setTimeout(function(){
					Ext.getCmp('modx-content').doLayout();
				}, 500);
			}
        });
    }

    ,sortBy: function(sel) {
        this.store.baseParams.sorter = sel.getValue();
        this.run();
        return true;
    }

    ,sortDir: function(sel) {
        this.store.baseParams.dir = sel.getValue();
        this.run();
    }

    ,showDetails : function(){
        var selNode = this.getSelectedNodes();
        if(selNode && selNode.length > 0){
            selNode = selNode[0];
            var data = this.lookup[selNode.id];
            if (data) { Ext.getCmp('modx-package-browser-view').updateDetail(data); }
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
		this.templates.thumb = new Ext.XTemplate('<tpl for=".">'
			+'<div class="thumb-wrapper <tpl if="downloaded">pbr-thumb-downloaded</tpl>" id="modx-package-thumb-{id}">'
				+'<div class="thumb">'
					+'<tpl if="screenshot.length == 0">'
							+'<span class="no-preview">'+_('no_preview')+'</span>'
					+'</tpl>'
					+'<tpl if="screenshot">'
						+'<img src="{screenshot}" title="{name}" alt="{name}" />'
					+'</tpl>'
				+'</div>'
				+'<span class="name">{shortName}</span>'
				+'<span class="downloads">{downloads} '+_('downloads')+'</span>'
				+'<tpl if="downloaded"><span class="downloaded">'+_('downloaded')+'</span></tpl>'
			+'</div>'
		+'</tpl>', {
			compiled: true
		});
    }

    ,download: function(id) {
        var data = this.lookup['modx-package-thumb-'+id];
        if (!data) return false;
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
});
Ext.reg('modx-package-browser-thumbs-view',MODx.PackageBrowserThumbsView);

/**
 * The package browser detail panel
 *
 * @class MODx.panel.PackageBrowserView
 * @extends MODx.Panel
 * @param {Object} config An object of options.
 * @xtype modx-package-browser-view
 */
MODx.panel.PackageBrowserView = function(config) {
    config = config || {};
	this.ident = config.ident || 'modx-pkgb-'+Ext.id();
	this.view = MODx.load({
        id: 'modx-package-browser-thumbs-view'
		,xtype: 'modx-package-browser-thumbs-view'
		,containerScroll: true
		,ident: this.ident
		,border: false
    });

	Ext.applyIf(config,{
		layout: 'column'
		,xtype: 'panel'
		,url: MODx.config.connectors_url+'workspace/packages-rest.php'
		,tbar: [{
			xtype: 'button'
			,text: _('back_to_browser')
			,handler: function(){
				Ext.getCmp('modx-panel-packages').activate();
			}
		},'->',{
			xtype:'modx-package-changesort-combobox'
			,id: 'modx-package-browser-changesort-combobox'
			,listeners: {
				'select': function(cb){
					var v = cb.getValue();
					var s = Ext.getCmp('modx-package-browser-thumbs-view').getStore();
					s.removeAll();
					s.setBaseParam('sorter',v);
					s.load();
				}
			}
		}]
		,border: false
		,autoHeight: true
		,items:[{
			items: this.view
			,border: false
			,bbar: new Ext.PagingToolbar({
				pageSize: 10
				,store: this.view.store
				,displayInfo: true
				,autoLoad: true
			})
			,columnWidth: 1
		},{
			xtype: 'modx-template-panel'
			,id: 'modx-package-browser-template-aside'
			,cls: 'aside-details'
			,width: 280
			,startingText: _('template_select_desc')
			,markup: '<div class="details">'
				+'<tpl for=".">'
					+'<div class="selected">'
						+'<tpl if="screenshot.length == 0">'
								+'<span class="no-preview">'+_('no_preview')+'</span>'
						+'</tpl>'
						+'<tpl if="screenshot">'
							+'<a href="{screenshot}" title="'+_('template_preview_view')+'" alt="'+_('template_preview_view')+'" class="lightbox" />'
								+'<img src="{screenshot}" alt="{name}" />'
							+'</a>'
						+'</tpl>'
						+'<h5>{name} {version-compiled}</h5>'
						+'<tpl if="!downloaded">'
							+'<button class="inline-button green" onclick="Ext.getCmp(\'modx-package-browser-view\').download(\'{id}\'); return false;"/>'+_('download')+'</button>'
						+'</tpl>'
						+'<tpl if="downloaded">'
							+'<div class="downloaded">'
								+_('template_already_downloaded')
							+'</div>'
						+'</tpl>'
					+'</div>'
					+'<div class="description">'
						+'<h4>'+_('description')+'</h4>'
						+'{description}'
					+'</div>'
					+'<div class="infos">'
						+'<h4>'+_('information')+'</h4>'
						+'<ul>'
							+'<li>'
								+'<span class="infoname">'+_('author')+':</span>'
								+'<span class="infovalue">{author}</span>'
							+'</li>'
							+'<li>'
								+'<span class="infoname">'+_('released_on')+':</span>'
								+'<span class="infovalue">{releasedon}</span>'
							+'</li>'
							+'<li>'
								+'<span class="infoname">'+_('minimum_supports')+':</span>'
								+'<span class="infovalue">{supports:defaultValue("--")}</span>'
							+'</li>'
							+'<li>'
								+'<span class="infoname">'+_('downloads')+':</span>'
								+'<span class="infovalue">{downloads}</span>'
							+'</li>'
							+'<li>'
								+'<span class="infoname">'+_('license')+':</span>'
								+'<span class="infovalue">{license}</span>'
							+'</li>'
						+'</ul>'
					+'</div>'
				+'</tpl>'
			+'</div>'
		}]
	});
	MODx.panel.PackageBrowserView.superclass.constructor.call(this,config);
};
Ext.extend(MODx.panel.PackageBrowserView,MODx.Panel,{
	activate: function(cat){
		if(cat != undefined){
			this.bdText = cat;
		}
		Ext.getCmp('package-browser-card-container').getLayout().setActiveItem(this.id);
		this.updateBreadcrumbs(_('viewing_templates_available'));
		Ext.getCmp('modx-package-browser-template-aside').reset();
	}

	,updateBreadcrumbs: function(msg, highlight){
		var bd = { text: msg };
        if(highlight){ bd.className = 'highlight'; }
		bd.trail = [{
			text : _('package_browser')
			,pnl : 'modx-panel-packages-browser'
		},{
			text : this.bdText
		}];
		Ext.getCmp('packages-breadcrumbs').updateDetail(bd);
	}

	,updateDetail: function(rec){
		Ext.getCmp('modx-package-browser-template-aside').updateDetail(rec);
	}

	,download: function(id){
		var record = this.view.lookup['modx-package-thumb-'+id];
		var c = Ext.getCmp('modx-panel-packages-browser');
		if(!record) return false;

		c.showWait();
		var me = this;

		MODx.Ajax.request({
            url: this.url
            ,params: {
                action: 'download'
                ,info: record.location+'::'+record.signature
                ,provider: MODx.provider || 1
            }
            ,scope: this
            ,listeners: {
                'success': {fn:function(r) {
                    this.view.run();
					c.hideWait();
					this.updateBreadcrumbs(_('download_success'), true);
					setTimeout(function(){
						me.updateBreadcrumbs(_('templates_in_category'));
					}, 5000);
                },scope:this}
            }
        });
	}
});
Ext.reg('modx-package-browser-view',MODx.panel.PackageBrowserView);