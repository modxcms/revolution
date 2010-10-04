MODx.window.PackageUpdate = function(config) {
    config = config || {};
    Ext.applyIf(config,{
        title: _('package_update')
        ,url: MODx.config.connectors_url+'workspace/packages.php'
        ,action: 'download'
        ,height: 400
        ,width: 400
        ,id: 'modx-window-package-update'
        ,saveBtnText: 'Update'
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
        },MODx.PanelSpacer,{
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