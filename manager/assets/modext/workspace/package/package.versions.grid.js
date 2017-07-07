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
            action: 'workspace/packages/version/getList'
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
                action: 'workspace/packages/version/remove'
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
});
Ext.reg('modx-grid-package-versions',MODx.grid.PackageVersions);
