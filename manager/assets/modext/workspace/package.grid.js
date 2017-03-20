/**
 * Loads a grid of Packages.
 *
 * @class MODx.grid.Package
 * @extends MODx.grid.Grid
 * @param {Object} config An object of options.
 * @xtype modx-package-grid
 */
MODx.grid.Package = function(config) {
    config = config || {};
    this.exp = new Ext.grid.RowExpander({
        tpl : new Ext.XTemplate(
            '<p class="package-readme"><i>{readme}</i></p>'
        )
    });

	/* Package name + action button renderer */
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

    var cols = [];
    cols.push(this.exp);
    cols.push({ header: _('name') ,dataIndex: 'name', id:'main',renderer: { fn: this.mainColumnRenderer, scope: this } });
    cols.push({ header: _('version') ,dataIndex: 'version', id: 'meta-col', fixed:true, width:120, renderer: function (v, md, record) { return v + '-' + record.data.release;} });
    cols.push({ header: _('installed') ,dataIndex: 'installed', id: 'info-col', fixed:true, width: 160 ,renderer: this.dateColumnRenderer });
    cols.push({ header: _('provider') ,dataIndex: 'provider_name', id: 'text-col', fixed:true, width:120 });

    var dlbtn;
    if (MODx.curlEnabled) {
        dlbtn = {
            text: _('download_extras')
			,xtype: 'splitbutton'
			,cls:'primary-button'
            ,handler: this.onDownloadMoreExtra
			,menu: {
				items:[{
					text: _('provider_select')
					,handler: this.changeProvider
					,scope: this
				},{
					text: _('package_search_local_title')
					,handler: this.searchLocal
					,scope: this
				},{
                    text: _('transport_package_upload')
                    ,handler: this.uploadTransportPackage
                    ,scope: this
                }]
			}
        };
    } else {
        dlbtn = {
            text: _('package_search_local_title')
            ,xtype: 'splitbutton'
            ,handler: this.searchLocal
            ,scope: this
            ,menu: {
                items: [{
                    text: _('transport_package_upload')
                    ,handler: this.uploadTransportPackage
                    ,scope: this
                }]
            }
        };
    }

    Ext.applyIf(config,{
        title: _('packages')
        ,id: 'modx-package-grid'
        ,url: MODx.config.connector_url
        ,action: 'workspace/packages/getlist'
        ,fields: ['signature','name','version','release','created','updated','installed','state','workspace'
                 ,'provider','provider_name','disabled','source','attributes','readme','menu'
                 ,'install','textaction','iconaction','updateable']
        ,plugins: [this.exp]
        ,pageSize: Math.min(parseInt(MODx.config.default_per_page), 25)
        ,columns: cols
        ,primaryKey: 'signature'
        ,paging: true
        ,autosave: true
        ,tbar: [dlbtn,'->',{
            xtype: 'textfield'
            ,name: 'search'
            ,id: 'modx-package-search'
            ,cls: 'x-form-filter'
            ,emptyText: _('search_ellipsis')
            ,listeners: {
                'change': {fn: this.search, scope: this}
                ,'render': {fn: function(pnl) {
                    new Ext.KeyMap(pnl.getEl(), {
                        key: Ext.EventObject.ENTER
                        ,fn: this.blur
                        ,scope: pnl
                    });
                },scope:this}
            }
        },{
            xtype: 'button'
            ,id: 'modx-package-filter-clear'
            ,cls: 'x-form-filter-clear'
            ,text: _('filter_clear')
            ,listeners: {
                'click': {fn: this.clearFilter, scope: this}
            }
        }]
    });
    MODx.grid.Package.superclass.constructor.call(this,config);
    this.on('render',function() {
        this.getView().mainBody.update('<div class="x-grid-empty">' + _('loading') + '</div>');
    },this);
	this.on('click', this.onClick, this);
};
Ext.extend(MODx.grid.Package,MODx.grid.Grid,{
	console: null

    ,activate: function() {
        var west = Ext.getCmp('modx-leftbar-tabs')
            ,stateId = 'modx-leftbar-tabs';
        if (west && west.stateId) {
            stateId = west.stateId;
        }
        var state = Ext.state.Manager.get(stateId);
        if (state && state.collapsed === false) {
            // Panel was not collapsed before, lets restore it
            Ext.getCmp('modx-layout').showLeftbar();
        }
        Ext.getCmp('modx-panel-packages').getLayout().setActiveItem(this.id);
        this.updateBreadcrumbs(_('packages_desc'));
    }

	,updateBreadcrumbs: function(msg, highlight){
		msg = Ext.getCmp('packages-breadcrumbs').desc;
        if(highlight){
			msg.text = msg;
			msg.className = 'highlight';
		}
		Ext.getCmp('packages-breadcrumbs').reset(msg);
	}

	,search: function(tf,newValue,oldValue) {
        var nv = newValue || tf;
        this.getStore().baseParams.search = Ext.isEmpty(nv) || Ext.isObject(nv) ? '' : nv;
        this.getBottomToolbar().changePage(1);
        //this.refresh();
        return true;
    }

    ,clearFilter: function() {
    	this.getStore().baseParams = {
            action: 'workspace/packages/getList'
    	};
        Ext.getCmp('modx-package-search').reset();
    	this.getBottomToolbar().changePage(1);
        //this.refresh();
    }


	/* Main column renderer */
	,mainColumnRenderer:function (value, metaData, record, rowIndex, colIndex, store){
		var rec = record.data;
		var state = (rec.installed !== null) ? ' installed' : ' not-installed';
		var values = { name: value, state: state, actions: null };

		var h = [];
		if(rec.installed !== null) {
			h.push({ className:'uninstall', text: rec.textaction });
			h.push({ className:'reinstall', text: _('package_reinstall_action_button') });
		} else {
            h.push({ className:'install primary-button', text: rec.textaction });
        }
        if (rec.updateable) {
            h.push({ className:'update orange', text: _('package_update_action_button') });
        } else {
            if( rec.provider != 0 ){
                h.push({ className:'checkupdate', text: _('package_check_for_updates') });
            }
        }
        h.push({ className:'remove', text: _('package_remove_action_button') });
        h.push({ className:'details', text: _('view_details') });
		values.actions = h;
		return this.mainColumnTpl.apply(values);
	}

    ,dateColumnRenderer: function(d,c) {
        switch(d) {
            case '':
            case null:
                c.css = 'not-installed';
                return _('not_installed');
            default:
                c.css = '';
                return _('installed_on',{'time':d});
        }
    }

	,onClick: function(e){
		var t = e.getTarget();
		var elm = t.className.split(' ')[0];
		if(elm == 'controlBtn'){
			var act = t.className.split(' ')[1];
			var record = this.getSelectionModel().getSelected();
			this.menu.record = record.data;
			switch (act) {
                case 'remove':
                    this.remove(record, e);
                    break;
                case 'install':
                case 'reinstall':
					this.install(record);
                    break;
                case 'uninstall':
					this.uninstall(record, e);
                    break;
				case 'update':
				case 'checkupdate':
                    this.update(record, e);
                    break;
				case 'details':
                    this.viewPackage(record, e);
                    break;
				default:
					break;
            }
		}
	}

	/* Install a package */
	,install: function( record ){
		Ext.Ajax.request({
			url : MODx.config.connector_url
			,params : {
				action : 'workspace/packages/getAttribute'
				,attributes: 'license,readme,changelog,setup-options,requires'
				,signature: record.data.signature
			}
			,method: 'GET'
			,scope: this
			,success: function ( result, request ) {
				this.processResult( result.responseText, record );
			}
			,failure: function ( result, request) {
				Ext.MessageBox.alert(_('failed'), result.responseText);
			}
		});
	}

	/* Go through the install process */
	,processResult: function( response, record ){
		var data = Ext.util.JSON.decode( response );

		if ( data.object.license !== null && data.object.readme !== null && data.object.changelog !== null ){
			/* Show license/changelog panel */
			p = Ext.getCmp('modx-package-beforeinstall');
			p.activate();
			p.updatePanel( data.object, record );
		}
		else if ( data.object['setup-options'] !== null ) {
			/* No license/changelog, show setup-options */
            Ext.getCmp('package-show-setupoptions-btn').signature = record.data.signature;
			Ext.getCmp('modx-panel-packages').onSetupOptions();
		} else {
			/* No license/changelog, no setup-options, install directly */
            Ext.getCmp('package-install-btn').signature = record.data.signature;
			Ext.getCmp('modx-panel-packages').install();
		}
	}

	/* Launch Package Browser */
	,onDownloadMoreExtra: function(btn,e){
	    MODx.provider = MODx.defaultProvider;
		Ext.getCmp('modx-panel-packages-browser').activate();
	}

	,changeProvider: function(btn, e){
		this.loadWindow(btn,e,{
            xtype: 'modx-package-changeprovider'
        });
	}

    /**
     * Open a window allowing user to upload a transport package directly
     */
    ,uploadTransportPackage: function(btn,e){
        if (!this.uploader) {
            this.uploader = new MODx.util.MultiUploadDialog.Dialog({
                url: MODx.config.connector_url
                ,base_params: {
                    action: 'workspace/packages/upload'
                    ,wctx: MODx.ctx || ''
                    ,source: MODx.config.default_media_source
                    ,path: MODx.config.core_path+'packages/'
                }
                ,permitted_extensions: ['zip']
                ,cls: 'ext-ux-uploaddialog-dialog modx-upload-window'
            });
            this.uploader.on('hide',function(){
                this.searchLocalWithoutPrompt();
            },this);
            this.uploader.on('close',function(){
                this.searchLocalWithoutPrompt();
            },this);
        }
        this.uploader.base_params.source = 1;
        this.uploader.show(btn);
    }

	,searchLocal: function() {
        MODx.msg.confirm({
           title: _('package_search_local_title')
           ,text: _('package_search_local_confirm')
           ,url: MODx.config.connector_url
           ,params: {
                action: 'workspace/packages/scanLocal'
           }
           ,listeners: {
                'success':{fn:function(r) {
                    this.getStore().reload();
                },scope:this}
           }
        });
    }

    /**
     * Scan for new packages, without the pointless & annoying confirmation box
     */
    ,searchLocalWithoutPrompt: function(){
        MODx.Ajax.request({
            url: MODx.config.connector_url
            ,params: {
                action: 'workspace/packages/scanLocal'
            }
            ,listeners: {
                'success':{fn:function(r) {
                    this.getStore().reload();
                },scope:this}
            }
        })
    }

	/* Go to package details @TODO : Stay on the same page */
    ,viewPackage: function(btn,e) {
        MODx.loadPage('workspaces/package/view', 'signature='+this.menu.record.signature+'&package_name='+this.menu.record.name);
    }

	/* Search for a package update - only for installed package */
    ,update: function(btn,e) {
        MODx.Ajax.request({
            url: this.config.url
            ,params: {
                action: 'workspace/packages/update-remote'
                ,signature: this.menu.record.signature
            }
            ,listeners: {
                'success': {fn:function(r) {
                    this.loadWindow(btn,e,{
                        xtype: 'modx-window-package-update'
                        ,packages: r.object
                        ,record: this.menu.record
                        ,force: true
                        ,listeners: {
                            'success': {fn: function(o) {
                                this.refresh();
                                this.menu.record = {data:o.a.result.object};
                                this.install(this.menu.record);
                            },scope:this}
                        }
                    });
                },scope:this}
                ,'failure': {fn: function(r) {
                    MODx.msg.alert(_('package_update'),r.message);
                    return false;
                },scope:this}
            }
        });
    }

	/* Uninstall a package */
	,uninstall: function(btn,e) {
        this.loadWindow(btn,e,{
            xtype: 'modx-window-package-uninstall'
            ,listeners: {
                success: {
                    fn: function(va) {
                        this._uninstall(this.menu.record,va,btn);
                    }
                    ,scope: this
                }
            }
        });
    }

    ,_uninstall: function(r,va,btn) {
        r = this.menu.record;
        va = va || {};
        var topic = '/workspace/package/uninstall/'+r.signature+'/';
        this.loadConsole(btn,topic);
        Ext.apply(va,{
            action: 'workspace/packages/uninstall'
            ,signature: r.signature
            ,register: 'mgr'
            ,topic: topic
        });

        MODx.Ajax.request({
            url: this.config.url
            ,params: va
            ,listeners: {
                'success': {fn:function(r) {
                    Ext.Msg.hide();
                    this.refresh();
                    Ext.getCmp('modx-layout').refreshTrees();
                },scope:this}
                ,'failure': {fn:function(r) {
                    Ext.Msg.hide();
                    this.refresh();
                },scope:this}
            }
        });
    }

	/* Remove a package entirely */
    ,remove: function(btn,e) {
        if (this.destroying) {
            return MODx.grid.Package.superclass.remove.apply(this, arguments);
        }
    	var r = this.menu.record;
        var topic = '/workspace/package/remove/'+r.signature+'/';

        this.loadWindow(btn,e,{
            xtype: 'modx-window-package-remove'
            ,record: {
                signature: r.signature
                ,topic: topic
                ,register: 'mgr'
            }
        });
    }

	/* Load the console */
    ,loadConsole: function(btn,topic) {
        this.console = MODx.load({
           xtype: 'modx-console'
           ,register: 'mgr'
           ,topic: topic
        });
        this.console.show(btn);
    }

    ,getConsole: function() {
        return this.console;
    }
});
Ext.reg('modx-package-grid',MODx.grid.Package);

MODx.window.PackageUpdate = function(config) {
    config = config || {};
    Ext.applyIf(config,{
        title: _('package_update')
        ,url: MODx.config.connector_url
        ,action: 'workspace/packages/rest/download'
        // ,height: 400
        // ,width: 400
        ,id: 'modx-window-package-update'
        ,saveBtnText: _('update')
        ,fields: this.setupOptions(config.packages,config.record)
    });
    MODx.window.PackageUpdate.superclass.constructor.call(this,config);
    this.on('hide',function() { this.destroy(); },this);
};
Ext.extend(MODx.window.PackageUpdate,MODx.Window,{
    setupOptions: function(ps,rec) {
        var items = [{
            html: _('package_update_to_version')
            ,border: false
        },{
            xtype: 'hidden'
            ,name: 'provider'
            ,value: Ext.isDefined(rec.provider) ? rec.provider : MODx.provider
        }];

        for (var i=0;i<ps.length;i=i+1) {
            var pkg = ps[i];
            items.push({
                xtype: 'radio'
                ,name: 'info'
                ,boxLabel: pkg.signature
                ,hideLabel: true
                ,description: pkg.description
                ,inputValue: pkg.info
                ,labelSeparator: ''
                ,checked: i == 0
            });

        }
        return items;
    }
});
Ext.reg('modx-window-package-update',MODx.window.PackageUpdate);
