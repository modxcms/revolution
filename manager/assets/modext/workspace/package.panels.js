/**
 * The panel who shows before package installation
 *
 * @class MODx.panel.PackageMetaPanel
 * @extends MODx.Tabs
 * @param {Object} config An object of options.
 * @xtype modx-package-metapanel
 */
MODx.panel.PackageMetaPanel = function(config) {
	config = config || {};

	Ext.applyIf(config,{
		cls: 'vertical-tabs-panel wrapped'
		// same as in parent class
		// ,headerCfg: { tag: 'div', cls: 'x-tab-panel-header vertical-tabs-header' }
		// ,bwrapCfg: { tag: 'div', cls: 'x-tab-panel-bwrap vertical-tabs-bwrap' }
		// ,defaults: {
		// 	bodyCssClass: 'vertical-tabs-body'
		// 	,autoScroll: true
		// 	,autoHeight: true
		// 	,autoWidth: true
		// }
		// ,layoutOnTabChange: true
		// ,listeners:{
			// tabchange: function(tb, pnl){
			// 	w = this.bwrap.getWidth();
			// 	this.body.setWidth(w);
			// 	this.doLayout();
			// }
			// ,scope: this
		// }
		,items: []
	});
	MODx.panel.PackageMetaPanel.superclass.constructor.call(this,config);
};
Ext.extend(MODx.panel.PackageMetaPanel,MODx.VerticalTabs,{
	updatePanel: function(meta, record){
		if(meta.changelog != undefined){
			this.addTab(_('changelog'), 'changelog', meta);
		}
        if(meta.requires != undefined){
            this.addDependenciesTab(_('dependencies'), 'dependencies', meta, record);
        }
		if(meta.readme != undefined){
			this.addTab(_('readme'), 'readme', meta);
		}
		if(meta.license != undefined){
			this.addTab(_('license'), 'license', meta);
		}
		this.setActiveTab(0);
		Ext.getCmp('modx-content').doLayout();
	}

	,addTab: function(title, id, data){
	    var tab = this.getItem(id);
	    if (!tab) {
            this.add({
                title: title
                ,xtype:'modx-template-panel'
                ,id: id +'-tab'
                ,markup: '{'+id+'}'
                ,bodyCssClass: 'meta-wrapper'
                ,listeners: {
                    afterrender: function() {
                        this.updateDetail( data );
                    }
                }
            });
        } else {
            tab.updateDetail(data);
        }
    }
    ,addDependenciesTab: function(title, id, data, pkgInfo){
        var tab = this.getItem(id);
        if (!tab) {
            this.add({
                title: title
                ,xtype:'modx-panel-package-dependencies'
                ,metaPanel: this
                ,pkgInfo: pkgInfo
                ,id: id +'-tab'
                ,height: '1000px'
                ,width: 500
            });
        } else {
            Ext.getCmp('modx-grid-package-dependencies').refresh();
        }
    }
});
Ext.reg('modx-package-meta-panel',MODx.panel.PackageMetaPanel);


/**
 * The panel who shows before package installation
 *
 * @class MODx.panel.PackageBeforeInstall
 * @extends MODx.panel.PackageMetaPanel
 * @param {Object} config An object of options.
 * @xtype modx-package-beforeinstall
 */
MODx.panel.PackageBeforeInstall = function(config) {
    config = config || {};
	this.setupOptions = null;

	Ext.applyIf(config,{});
	MODx.panel.PackageBeforeInstall.superclass.constructor.call(this,config);
};
Ext.extend(MODx.panel.PackageBeforeInstall, MODx.panel.PackageMetaPanel,{
	activate: function(){
		Ext.getCmp(this.ownerCt.id).getLayout().setActiveItem(this.id);
		this.removeAll();
	}

	,updateBreadcrumbs: function(msg, rec){
        var bc = Ext.getCmp('packages-breadcrumbs');
        var bd = bc.getData();

        bd.text = msg;

        bd.trail.shift();

        if (bd.trail.length > 0) {
            bd.trail[bd.trail.length - 1].install = true;
        }

        /* Get the package name. By default it's stored in data.name,
         but in case of package update, the name is stored in data.package_name. */
        var name = rec.data.name;
        if (name === undefined) {
            name = rec.data.package_name
        }

        var newBcItem = {
            text : _('install') + ' ' + name
            ,rec: rec
        };

		bd.trail.push(newBcItem);

		bc.updateDetail(bd);
	}

	,updatePanel: function(meta, record){
        var installBtn = Ext.getCmp('package-install-btn');
        var setupoptionsBtn = Ext.getCmp('package-show-setupoptions-btn');
		this.updateBreadcrumbs(_('license_agreement_desc'), record);
        Ext.getCmp('package-list-reset').show();
        installBtn.hide().signature = '';
        setupoptionsBtn.hide();
		if(meta.changelog != undefined){
			this.addTab(_('changelog'), 'changelog', meta);
		}
        if(meta.requires != undefined){
            this.addDependenciesTab('Dependencies', 'dependencies', meta, record);
        } else {
            setupoptionsBtn.enable().setText(_('setup_options')).syncSize();
            installBtn.enable().setText(_('continue')).syncSize();
        }
		if(meta.readme != undefined){
			this.addTab(_('readme'), 'readme', meta);
		}
		if(meta.license != undefined){
			this.addTab(_('license'), 'license', meta);
		}

		if(meta['setup-options'] != null && meta['setup-options'] != ''){
            setupoptionsBtn.show().signature = record.data.signature;
			this.setupOptions = meta['setup-options'];
		} else {
            installBtn.show().signature = record.data.signature;
		}
		this.setActiveTab(0);
		Ext.getCmp('modx-content').doLayout();
	}

	,getOptions: function(){
		return this.setupOptions;
	}
});
Ext.reg('modx-package-beforeinstall',MODx.panel.PackageBeforeInstall);

/**
 * The panel to view package detail
 *
 * @class MODx.panel.PackageDetails
 * @extends MODx.Panel
 * @param {Object} config An object of options.
 * @xtype modx-package-browser-details
 */
MODx.panel.PackageDetails = function(config) {
    config = config || {};

	Ext.applyIf(config,{
		layout: 'column'
		,border: false
		,autoHeight: true
		,items:[{
			xtype: 'modx-template-panel'
			,id: 'modx-package-details-metas'
			,columnWidth: 1
			,markup: '<div class="details">'
				+'<tpl for=".">'
					+'<tpl if="readme">'
						+'<div class="item">'
							+'<h4>'+_('readme')+'</h4>'
							+'{readme}'
						+'</div>'
					+'</tpl>'
					+'<tpl if="changelog">'
						+'<div class="item">'
							+'<h4>'+_('changelog')+'</h4>'
							+'{changelog}'
						+'</div>'
					+'</tpl>'
					+'<tpl if="license">'
						+'<div class="item">'
							+'<h4>'+_('license')+'</h4>'
							+'{license}'
						+'</div>'
					+'</tpl>'
				+'</tpl>'
			+'</div>'
		},{
			xtype: 'modx-template-panel'
			,id: 'modx-package-details-aside'
			,cls: 'aside-details'
			,width: 250
			,markup: '<div class="details">'
				+'<tpl for=".">'
					+'<div class="selected">'
						+'<h5>{package_name}</h5>'
					+'</div>'
					+'<div class="infos description">'
						+'<h4>'+_('information')+'</h4>'
						+'<ul>'
							+'<li>'
								+'<span class="infoname">'+_('signature')+':</span>'
								+'<span class="infovalue">{signature}</span>'
							+'</li>'
							+'<li>'
								+'<span class="infoname">'+_('uploaded_on')+':</span>'
								+'<span class="infovalue">{created}</span>'
							+'</li>'
							+'<li>'
								+'<span class="infoname">'+_('installed')+':</span>'
								+'<span class="infovalue">{installed}</span>'
							+'</li>'
							+'<li>'
								+'<span class="infoname">'+_('last_updated')+':</span>'
								+'<span class="infovalue">{updated}</span>'
							+'</li>'
							+'<li>'
								+'<span class="infoname">'+_('provider')+':</span>'
								+'<span class="infovalue">{provider}</span>'
							+'</li>'
						+'</ul>'
					+'</div>'
				+'</tpl>'
			+'</div>'
		}]
	});
	MODx.panel.PackageDetails.superclass.constructor.call(this,config);
};
Ext.extend(MODx.panel.PackageDetails,MODx.Panel,{
	activate: function(){
		Ext.getCmp(this.ownerCt.id).getLayout().setActiveItem(this.id);
	}

	,updateBreadcrumbs: function(msg){
		Ext.getCmp('packages-breadcrumbs').updateDetail({
			 text : msg
			,trail : [{	text : _('package_details') }]
		});
	}

	,updateDetail: function(rec){
		this.updateBreadcrumbs(_('package_details_for',{'package': rec.package_name}));
		Ext.getCmp('modx-package-details-metas').updateDetail(rec);
		Ext.getCmp('modx-package-details-aside').updateDetail(rec);
	}
});
Ext.reg('modx-package-details',MODx.panel.PackageDetails);

MODx.panel.PackageDependencies = function(config) {
    config = config || {};
    Ext.apply(config,{
        border: false
        ,baseCls: 'modx-formpanel'
//        ,cls: 'container'
        ,cls: 'auto-width'
        ,bodyCssClass: 'vertical-tabs-body auto-width auto-height'
        ,items: [{
            html: _('dependencies')
            ,xtype: 'modx-header'
        },{
            xtype: 'modx-grid-package-dependencies'
            ,preventRender: true
            ,metaPanel: config.metaPanel
            ,pkgInfo: config.pkgInfo
            ,dependenciesPanel: this
            ,cls: 'main-wrapper'
        }]
    });
    MODx.panel.PackageDependencies.superclass.constructor.call(this,config);
};
Ext.extend(MODx.panel.PackageDependencies,MODx.Panel);
Ext.reg('modx-panel-package-dependencies',MODx.panel.PackageDependencies);

MODx.grid.PackageDependencies = function(config) {
    config = config || {};

    var cols = [];
    cols.push({ header: _('name') ,dataIndex: 'name', id:'main',renderer: { fn: this.mainColumnRenderer, scope: this } });
    cols.push({ header: _('constraints') ,dataIndex: 'constraints', id: 'meta-col', fixed:true, width:160 });
    cols.push({ header: _('installed') ,dataIndex: 'installed', id: 'info-col', fixed:true, width: 160 ,renderer: this.installColumnRenderer });

    Ext.applyIf(config,{
        id: 'modx-grid-package-dependencies'
        ,baseParams: {
            action: 'workspace/packages/getdependencies'
            ,signature: config.pkgInfo.data.signature
        }
        ,fields: ['name', 'constraints', 'installed', 'parentSignature', 'signature', 'downloaded', 'actions']
        ,paging: false
        ,loadMask: true
        ,tbar: []
        ,columns: cols
    });
    MODx.grid.PackageDependencies.superclass.constructor.call(this,config);

    this.store.on('load', function () {
        if (!this.checkDependencies()) {
            Ext.getCmp('package-show-setupoptions-btn').disable().setText(_('install_dependencies_first')).syncSize();
            Ext.getCmp('package-install-btn').disable().setText(_('install_dependencies_first')).syncSize();
        }
    }, this);
};
Ext.extend(MODx.grid.PackageDependencies,MODx.grid.Package, {
    mainColumnRenderer:function (value, metaData, record, rowIndex, colIndex, store){
        var rec = record.data;
        var state = (rec.installed !== null) ? ' installed' : ' not-installed';
        var values = { name: value, state: state, actions: null };

        var h = [];
        if(rec.downloaded == false && rec.installed == false) {
            h.push({ className:'download primary', text: _('download') });
        } else {
            if(rec.installed == false) {
                h.push({ className:'install primary', text: _('install') });
            }
        }

        values.actions = h;
        return this.mainColumnTpl.apply(values);
    }

    ,installColumnRenderer: function(d,c) {
        switch(d) {
            case '':
            case false:
                c.css = 'not-installed';
                return _('not_installed');
            default:
                c.css = '';
                return _('installed');
        }
    }

    ,downloadPackage: function(rec) {
        this.loadMask.show();
        Ext.Ajax.request({
            url : MODx.config.connector_url
            ,params : {
                action : 'workspace/packages/dependency/download'
                ,signature: rec.data.parentSignature
                ,name: rec.data.name
                ,constraints: rec.data.constraints
            }
            ,method: 'GET'
            ,scope: this
            ,success: function ( result, request ) {
                this.store.reload();
            }
            ,failure: function ( result, request) {
                this.loadMask.hide();
                Ext.MessageBox.alert(_('failed'), result.responseText);
            }
        });
    }

    ,onClick: function(e){
        var t = e.getTarget();
        var elm = t.className.split(' ')[0];
        if(elm == 'controlBtn'){
            var act = t.className.split(' ')[1];
            var record = this.getSelectionModel().getSelected();
            this.menu.record = record.data;
            switch (act) {
                case 'install':
                case 'reinstall':
                    this.install(record);
                    break;
                case 'download':
                    this.downloadPackage(record);
                    break;
                default:
                    break;
            }
        }
    }

    ,checkDependencies: function() {
        var installed = this.store.collect('installed', true);

        return installed.indexOf(false) == -1;
    }
});
Ext.reg('modx-grid-package-dependencies',MODx.grid.PackageDependencies);
