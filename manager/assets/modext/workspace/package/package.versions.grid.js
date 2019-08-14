MODx.grid.PackageVersions = function(config) {
    config = config || {};
    this.exp = new Ext.grid.RowExpander({
        tpl : new Ext.Template(
            '<p class="package-readme"><i>{readme}</i></p>'
        )
    });
    Ext.applyIf(config,{
        title: _('packages')
        ,id: 'modx-grid-package-versions'
        ,url: MODx.config.connector_url
        ,baseParams: {
            action: 'Workspace/Packages/Version/GetList'
            ,signature: config.signature
            ,package_name: MODx.request.package_name
        }
        ,fields: ['signature','name','version','release','created','updated','installed','state'
                 ,'workspace','provider','provider_name','disabled','source'
                 ,'readme','menu']
        ,plugins: [this.exp]
        ,pageSize: 20
        ,columns: [this.exp,{
              header: _('name') ,dataIndex: 'name' }
           ,{ header: _('version') ,dataIndex: 'version' }
           ,{ header: _('release') ,dataIndex: 'release' }
            ,{ header: _('installed') ,dataIndex: 'installed' ,renderer: this._rins }
            ,{
                header: _('provider')
                ,dataIndex: 'provider_name'
                ,editable: false
            }]
        ,primaryKey: 'signature'
        ,paging: true
        ,autosave: true
        ,tbar: [{
            text: _('package_versions_purge')
            ,handler: this.purgePackageVersions
        }]
    });
    MODx.grid.PackageVersions.superclass.constructor.call(this,config);
};
Ext.extend(MODx.grid.PackageVersions,MODx.grid.Grid,{

    _rins: function(d,c) {
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

    ,removePriorVersion: function(btn,e) {
        var r = this.menu.record;
        MODx.msg.confirm({
            title: _('package_version_remove')
            ,text: _('package_version_remove_confirm')
            ,url: this.config.url
            ,params: {
                action: 'Workspace/Packages/Version/Remove'
                ,signature: r.signature
            }
            ,listeners: {
                'success': {fn:function() {
                    if (this.fireEvent('afterRemoveRow',r)) {
                        this.removeActiveRow(r);
                    }
                },scope:this}
            }
        });
    }

    /* Purge old package versions */
    ,purgePackageVersions: function(btn,e) {
        var topic = '/Workspace/Packages/Purge/';

        this.loadWindow(btn,e,{
            xtype: 'modx-window-package-versions-purge'
            ,record: {
                packagename: this.config.package_name
                ,topic: topic
                ,register: 'mgr'
            }
            ,listeners: {
                success: {fn: function(o) {
                    this.refresh();
                },scope:this}
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
Ext.reg('modx-grid-package-versions',MODx.grid.PackageVersions);

/**
 * @class MODx.window.PurgePackageVersions
 * @extends MODx.Window
 * @param {Object} config An object of configuration parameters
 * @xtype modx-window-package-versions-purge
 */
MODx.window.PurgePackageVersions = function(config) {
    config = config || {};
    Ext.applyIf(config,{
        title: _('package_versions_purge')
        ,url: MODx.config.connector_url
        ,baseParams: {
            action: 'Workspace/Packages/Purge'
        }
        ,cls: 'modx-confirm'
        ,defaults: { border: false }
        ,fields: [{
            xtype: 'hidden'
            ,name: 'packagename'
            ,id: 'modx-ppack-package_name'
            ,value: config.packagename
        },{
            html: _('package_versions_purge_confirm')
        }]
        ,saveBtnText: _('package_versions_purge')
    });
    MODx.window.PurgePackageVersions.superclass.constructor.call(this,config);
};
Ext.extend(MODx.window.PurgePackageVersions,MODx.Window,{
    submit: function() {
        var r = this.config.record;
        if (this.fp.getForm().isValid()) {
            Ext.getCmp('modx-grid-package-versions').loadConsole(Ext.getBody(),r.topic);
            this.fp.getForm().baseParams = {
                action: 'Workspace/Packages/Purge'
                ,register: 'mgr'
                ,topic: r.topic
            };

            this.fp.getForm().submit({
                waitMsg: _('saving')
                ,scope: this
                ,failure: function(frm,a) {
                    this.fireEvent('failure',frm,a);
                    var g = Ext.getCmp('modx-grid-package-versions');
                    g.getConsole().fireEvent('complete');
                    g.refresh();
                    Ext.Msg.hide();
                    this.hide();
                }
                ,success: function(frm,a) {
                    this.fireEvent('success',{f:frm,a:a});
                    var g = Ext.getCmp('modx-grid-package-versions');
                    g.getConsole().fireEvent('complete');
                    g.refresh();
                    Ext.Msg.hide();
                    this.hide();
                }
            });
        }
    }
});
Ext.reg('modx-window-package-versions-purge',MODx.window.PurgePackageVersions);
