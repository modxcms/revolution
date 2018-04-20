/**
 * The packages list main container
 *
 * @class MODx.panel.Packages
 * @extends MODx.Panel
 * @param {Object} config An object of options.
 * @xtype modx-panel-packages
 */
MODx.panel.Packages = function(config) {
    config = config || {};

	Ext.applyIf(config,{
		layout:'card'
		,border:false
		,layoutConfig:{ deferredRender: true }
		,defaults:{
			autoHeight: true
			,autoWidth: true
			,border: false
		}
		,activeItem: 0
		,items:[{
			xtype:'modx-package-grid'
			,id:'modx-package-grid'
			,bodyCssClass: 'grid-with-buttons'
		},{
			xtype: 'modx-package-beforeinstall'
			,id:'modx-package-beforeinstall'
		},{
			xtype: 'modx-package-details'
			,id:'modx-package-details'
			,bodyCssClass: 'modx-template-detail'
		}]
		,buttons: [{
			text: _('cancel')
			,id:'package-list-reset'
			,hidden: true
			,handler: function(btn, e){
                var bc = Ext.getCmp('packages-breadcrumbs');
                var last = bc.data.trail[bc.data.trail.length - 2];
                if (last != undefined && last.rec != undefined) {
                    bc.data.trail.pop();
                    bc.data.trail.pop();
                    bc.data.trail.shift();
                    bc.updateDetail(bc.data);

                    var grid = Ext.getCmp('modx-package-grid');
                    grid.install(last.rec);
                    return;
                }
				Ext.getCmp('modx-panel-packages').activate();
			}
			,scope: this
		},{
			text: _('continue')
			,id:'package-install-btn'
			,cls:'primary-button'
			,hidden: true
			,handler: this.install
            ,disabled: false
			,scope: this
			,autoWidth: true
			,autoHeight: true
		},{
			text: _('setup_options')
			,id:'package-show-setupoptions-btn'
			,cls:'primary-button'
			,hidden: true
			,handler: this.onSetupOptions
			,scope: this
			,autoWidth: true
			,autoHeight: true
		}]
	});
	MODx.panel.Packages.superclass.constructor.call(this,config);
};
Ext.extend(MODx.panel.Packages,MODx.Panel,{
    activate: function() {
        Ext.getCmp('card-container').getLayout().setActiveItem(this.id);
        Ext.getCmp('modx-package-grid').activate();
        Ext.each(this.buttons, function(btn){ Ext.getCmp(btn.id).hide(); });
    }

    /**
	 *
     * @param va ExtJS instance of the button that was clicked
     * @param event The Ext.EventObjectImpl from clicking the button
     * @param options Object containing the setup options if available, or undefined.
     * @returns {boolean}
     */
	,install: function(va, event, options){
    	options = options || {};
        var r;
        var g = Ext.getCmp('modx-package-grid');
        if (!g) return false;

        // Set the signature from the button config (set in MODx.panel.PackageBeforeInstall.updatePanel)
        if (va.signature != undefined && va.signature != '') {
            r = {signature: va.signature};
        } else {
            r = g.menu.record.data ? g.menu.record.data : g.menu.record;
        }

        // Load up the installation console
		var topic = '/workspace/package/install/'+r.signature+'/';
        g.loadConsole(Ext.getBody(),topic);

        // Grab the params to send to the install processor
        var params = {
            action: 'workspace/packages/install'
            ,signature: r.signature
            ,register: 'mgr'
            ,topic: topic
        };
        // Include the setup options that were provided from MODx.window.SetupOptions.install
        Ext.apply(params, options);

        // Trigger the actual installation
        MODx.Ajax.request({
            url: MODx.config.connector_url
            ,params: params
            ,listeners: {
                'success': {fn:function() {
                    var bc = Ext.getCmp('packages-breadcrumbs');
                    var trail= bc.data.trail;
                    trail.pop();

                    if (trail.length > 1) {
                        last = trail[trail.length - 1];

                        if (last != undefined && last.rec != undefined) {
                            bc.data.trail.pop();
                            bc.data.trail.shift();
                            bc.updateDetail(bc.data);

                            var grid = Ext.getCmp('modx-package-grid');
                            grid.install(last.rec);
                            return;
                        }
                    }

                    this.activate();
					Ext.getCmp('modx-package-grid').refresh();
                },scope:this}
                ,'failure': {fn:function() {
                    this.activate();
                },scope:this}
            }
        });

        return true;
	}

	,onSetupOptions: function(btn){
		if(this.win == undefined){
			this.win = new MODx.window.SetupOptions({
				id: 'modx-window-setupoptions'
				,signature: btn.signature || ''
			});
		} else {
			this.win.signature = btn.signature || '';
		}
		this.win.show(btn);
		var opts = Ext.getCmp('modx-package-beforeinstall').getOptions();
		this.win.fetch(opts);
	}
});
Ext.reg('modx-panel-packages',MODx.panel.Packages);

/**
 * The package browser container
 *
 * @class MODx.panel.PackagesBrowser
 * @extends MODx.Panel
 * @param {Object} config An object of options.
 * @xtype modx-panel-packages-browser
 */
MODx.panel.PackagesBrowser = function(config) {
    config = config || {};

	Ext.applyIf(config,{
		layout: 'column'
		,border: false
		,items:[{
			xtype: 'modx-package-browser-tree'
			,id: 'modx-package-browser-tree'
			,width: 250
		},{
			layout:'card'
			,columnWidth: 1
			,defaults:{ border: false }
			,border:false
			,id:'package-browser-card-container'
			,layoutConfig:{ deferredRender:true }
			,items:[{
				xtype:'modx-package-browser-home'
				,id:'modx-package-browser-home'
			},{
				xtype: 'modx-package-browser-repositories'
				,id: 'modx-package-browser-repositories'
			},{
				xtype:'modx-package-browser-grid'
				,id:'modx-package-browser-grid'
				,bodyCssClass: 'grid-with-buttons'
			},{
				xtype: 'modx-package-browser-details'
				,id: 'modx-package-browser-details'
				,bodyCssClass: 'modx-template-detail'
			},{
				xtype: 'modx-package-browser-view'
				,id: 'modx-package-browser-view'
				,bodyCssClass: 'modx-template-detail'
			}]
		}]
	});
	MODx.panel.PackagesBrowser.superclass.constructor.call(this,config);
};
Ext.extend(MODx.panel.PackagesBrowser,MODx.Panel,{
	activate: function(){
		Ext.getCmp('modx-layout').hideLeftbar(true, false);
		Ext.getCmp('card-container').getLayout().setActiveItem(this.id);
		Ext.getCmp('modx-package-browser-home').activate();
		this.updateBreadcrumbs(_('provider_home_msg'));
	}

	,updateBreadcrumbs: function(msg, highlight){
		var bd = { text: msg };
        if(highlight){ bd.className = 'highlight'; }

		bd.trail = [{ text : _('package_browser') }];
		Ext.getCmp('packages-breadcrumbs').updateDetail(bd);
	}

    ,showWait: function(){
        if (!this.wait) {
            this.wait = new MODx.PackageBrowserWaitWindow();
        }
        this.wait.show();
    }

    ,hideWait: function() {
        if (this.wait) {
            this.wait.destroy();
            delete this.wait;
        }
    }
});
Ext.reg('modx-panel-packages-browser',MODx.panel.PackagesBrowser);
