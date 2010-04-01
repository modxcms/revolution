/**
 * Loads a grid of Packages.
 * 
 * @class MODx.grid.Package
 * @extends MODx.grid.Grid
 * @param {Object} config An object of options.
 * @xtype modx-grid-package
 */
MODx.grid.Package = function(config) {
    config = config || {};
    this.exp = new Ext.grid.RowExpander({
        tpl : new Ext.Template(
            '<p class="package-readme"><i>{readme}</i></p>'
        )
    });
    this.action = new Ext.ux.grid.RowActions({
         actions: [{
             iconIndex: 'iconaction'
            ,textIndex: 'textaction'
        }]
        ,widthSlope:125
    });
    this.action.on('action',function(g,r, a,ri) {
        this.menu.record = r.data;        
        switch (a) {
            case 'icon-install': this.install(this.action,{}); break;
            case 'icon-uninstall': this.uninstall(this.action,{}); break;
        }
    },this);
    
    var cols = [];
    cols.push(this.exp);
    cols.push({ header: _('name') ,dataIndex: 'name' });
    cols.push({ header: _('version') ,dataIndex: 'version' });
    cols.push({ header: _('release') ,dataIndex: 'release' });
    cols.push({ header: _('installed') ,dataIndex: 'installed' ,renderer: this._rins });
    if (MODx.config.auto_check_pkg_updates == 1) {
        cols.push({ header: _('updateable') ,dataIndex: 'updateable' ,renderer: this.rendYesNo });
    }
    cols.push({ header: _('provider') ,dataIndex: 'provider_name' });
    cols.push(this.action);
    
    Ext.applyIf(config,{
        title: _('packages')
        ,id: 'modx-grid-package'
        ,url: MODx.config.connectors_url+'workspace/packages.php'
        ,fields: ['signature','name','version','release','created','updated','installed','state','workspace'
                 ,'provider','provider_name','disabled','source','attributes','readme','menu'
                 ,'install','textaction','iconaction','updateable']
        ,plugins: [this.action,this.exp]
        ,pageSize: 10
        ,columns: cols
        ,primaryKey: 'signature'
        ,paging: true
        ,autosave: true
        ,tbar: [{
            text: _('package_add')
            ,handler: { xtype: 'modx-window-package-downloader' }
        },{
            text: _('download_extras')
            ,handler: this.loadMainProvider
        }]
        ,tools: [{
            id: 'plus'
            ,qtip: _('expand_all')
            ,handler: this.expandAll
            ,scope: this
        },{
            id: 'minus'
            ,hidden: true
            ,qtip: _('collapse_all')
            ,handler: this.collapseAll
            ,scope: this
        }]
    });
    MODx.grid.Package.superclass.constructor.call(this,config);
};
Ext.extend(MODx.grid.Package,MODx.grid.Grid,{
    console: null
    
    ,loadMainProvider: function(btn,e) {
        MODx.Ajax.request({
            url: MODx.config.connectors_url+'workspace/providers.php'
            ,params: {
                action: 'get'
                ,name: 'modxcms.com'
            }
            ,listeners: {
                'success':{fn:function(r) {
                    var p = r.object;
                    MODx.provider = p.id;
                    var x = 'modx-window-package-downloader';
                    if (!this.windows[x]) {
                        this.windows[x] = MODx.load({ 
                            xtype: x
                            ,showFirstPanel: false
                        });
                    }
                    this.windows[x].on('ready',function() {
                        var pd = Ext.getCmp('modx-window-package-downloader');
                        
                        var t = Ext.getCmp('modx-package-browser-tree');
                        if (t) {
                            t.getLoader().baseParams.provider = p.id;
                            t.refresh();
                            t.renderProviderInfo();
                            t.getRootNode().setText(p.name);
                        }
                        var g = Ext.getCmp('modx-package-browser-grid');
                        if (g) {
                            g.getStore().baseParams.provider = p.id;
                            g.getStore().removeAll();
                        }
                        
                        var v = Ext.getCmp('modx-package-browser-thumbs-view');
                        if (v) {
                            v.baseParams.provider = p.id;
                        }

                        pd.fireEvent('proceed','modx-pd-selpackage');
                        pd.setPosition(null,0);
                    },this,{single:true});
                    
                    this.windows[x].show(e.target);
                },scope:this}
            }
        });
    }
    
    ,viewPackage: function(btn,e) {
        location.href = 'index.php?a='+MODx.action['workspaces/package/view']+'&signature='+this.menu.record.signature;
    }
    
    ,update: function(btn,e) {        
        MODx.Ajax.request({
            url: this.config.url
            ,params: {
                action: 'update'
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
                                this.menu.record = o.a.result.object;
                                this.install(btn,e);
                            },scope:this}
                        }
                    });
                },scope:this}
            }
        });
    }
    
    ,_rins: function(d,c) {
        switch(d) {
            case '':
            case null:
                c.css = 'not-installed';
                return _('not_installed');
            default:
                c.css = '';
                return d;
        }
    }
    
    ,loadConsole: function(btn,topic) {
    	if (this.console === null) {
            this.console = MODx.load({
               xtype: 'modx-console'
               ,register: 'mgr'
               ,topic: topic
            });
        } else {
            this.console.setRegister('mgr',topic);
        }
        this.console.show(btn);
    }
    
    ,getConsole: function() {
        return this.console;
    }
    
    ,uninstall: function(btn,e) {
        this.loadWindow(btn,e,{
            xtype: 'modx-window-package-uninstall'
            ,listeners: {
                'success': {fn: function(va) { this._uninstall(this.menu.record,va,btn); },scope:this}
            }
        });
    }
    
    ,_uninstall: function(r,va,btn) {
        var r = this.menu.record;
        va = va || {};
        var topic = '/workspace/package/uninstall/'+r.signature+'/';
        this.loadConsole(btn,topic);
        Ext.apply(va,{
            action: 'uninstall'
            ,signature: r.signature
            ,register: 'mgr'
            ,topic: topic
        });
        
        MODx.Ajax.request({
            url: this.config.url
            ,params: va
            ,listeners: {
                'success': {fn:function(r) {
                    //this.console.fireEvent('complete');
                    Ext.Msg.hide();
                    this.refresh();
                    Ext.getCmp('modx-layout').refreshTrees();
                },scope:this}
                ,'failure': {fn:function(r) {
                    //this.console.fireEvent('complete');
                    Ext.Msg.hide();
                    this.refresh();
                },scope:this}
            }
        });
    }
    
    ,remove: function(btn,e) {
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
    
    ,install: function(btn,e,r) {
        this.loadWindow(btn,e,{
            xtype: 'modx-window-package-installer'
            ,listeners: {
                'finish': {fn: function(va) { this._install(this.menu.record,va); },scope:this}
            }
        });
    }
    
    ,_install: function(r,va) {
        var topic = '/workspace/package/install/'+r.signature+'/';
        this.loadConsole(Ext.getBody(),topic);
        Ext.apply(va,{
            action: 'install'
            ,signature: r.signature
            ,register: 'mgr'
            ,topic: topic
        });
        
        MODx.Ajax.request({
            url: this.config.url
            ,params: va
            ,listeners: {
                'success': {fn:function() {
                    Ext.getCmp('modx-window-package-installer').hide();
                    this.refresh();
                    //this.console.fireEvent('complete');
                    Ext.getCmp('modx-layout').refreshTrees();
                },scope:this}
                ,'failure': {fn:function() {
                    Ext.Msg.hide();
                    this.refresh();
                    //this.console.fireEvent('complete');
                },scope:this}
            }
        });
    }
});
Ext.reg('modx-grid-package',MODx.grid.Package);

/**
 * @class MODx.window.RemovePackage
 * @extends MODx.Window
 * @param {Object} config An object of configuration parameters
 * @xtype modx-window-package-remove
 */
MODx.window.RemovePackage = function(config) {
    config = config || {};
    Ext.applyIf(config,{
        title: _('package_remove')
        ,url: MODx.config.connectors_url+'workspace/packages.php'
        ,baseParams: {
            action: 'uninstall'
        }
        ,defaults: { border: false }
        ,fields: [{
            xtype: 'hidden'
            ,name: 'signature'
            ,id: 'modx-rpack-signature'
            ,value: config.signature
        },{
            html: _('package_remove_confirm')
        },MODx.PanelSpacer,{
            html: _('package_remove_force_desc') 
            ,border: false
        },MODx.PanelSpacer,{
            xtype: 'checkbox'
            ,name: 'force'
            ,boxLabel: _('package_remove_force')
            ,id: 'modx-rpack-force'
            ,labelSeparator: ''
            ,inputValue: 'true'
        }]
        ,saveBtnText: _('package_remove')
    });
    MODx.window.RemovePackage.superclass.constructor.call(this,config);
};
Ext.extend(MODx.window.RemovePackage,MODx.Window,{
    submit: function() {
        var r = this.config.record;
        if (this.fp.getForm().isValid()) {            
            Ext.getCmp('modx-grid-package').loadConsole(Ext.getBody(),r.topic);
            this.fp.getForm().baseParams = {
                action: 'remove'
                ,signature: r.signature
                ,register: 'mgr'
                ,topic: r.topic
                ,force: Ext.getCmp('modx-rpack-force').getValue()
            };
            
            this.fp.getForm().submit({ 
                waitMsg: _('saving')
                ,scope: this
                ,failure: function(frm,a) {
                    this.fireEvent('failure',frm,a);
                    var g = Ext.getCmp('modx-grid-package');
                    g.getConsole().fireEvent('complete');
                    g.refresh();
                    Ext.Msg.hide();
                    this.hide();
                }
                ,success: function(frm,a) {
                    this.fireEvent('success',{f:frm,a:a});
                    var g = Ext.getCmp('modx-grid-package');
                    g.getConsole().fireEvent('complete');
                    g.refresh();
                    Ext.Msg.hide();
                    this.hide();
                }
            });
        }
    }
});
Ext.reg('modx-window-package-remove',MODx.window.RemovePackage);