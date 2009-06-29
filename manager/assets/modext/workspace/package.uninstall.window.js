MODx.window.PackageUninstall = function(config) {
    config = config || {};
    Ext.applyIf(config,{
        title: _('package_uninstall')
        ,url: MODx.config.connectors_url+'workspace/packages.php'
        ,action: ''
        ,height: 400
        ,width: 500
        ,id: 'modx-window-package-uninstall'
        ,saveBtnText: _('uninstall')
        ,fields: [{
            html: _('preexisting_mode_select')
            ,border: false
            ,autoHeight: true
        },MODx.PanelSpacer,{
            xtype: 'radio'
            ,name: 'preexisting_mode'
            ,fieldLabel: _('preexisting_mode_preserve')
            ,boxLabel: _('preexisting_mode_preserve_desc')
            ,inputValue: 0
            ,checked: true
        },{
            xtype: 'radio'
            ,name: 'preexisting_mode'
            ,fieldLabel: _('preexisting_mode_remove')
            ,boxLabel: _('preexisting_mode_remove_desc')
            ,inputValue: 1
        },{
            xtype: 'radio'
            ,name: 'preexisting_mode'
            ,fieldLabel: _('preexisting_mode_restore')
            ,boxLabel: _('preexisting_mode_restore_desc')
            ,inputValue: 2
        }]
    });
    MODx.window.PackageUninstall.superclass.constructor.call(this,config);
};
Ext.extend(MODx.window.PackageUninstall,MODx.Window,{
    submit: function() {
        var va = this.fp.getForm().getValues();
        this.fireEvent('success',va);
        this.hide();
    }
});
Ext.reg('modx-window-package-uninstall',MODx.window.PackageUninstall);