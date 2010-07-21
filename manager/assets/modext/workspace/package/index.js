Ext.onReady(function() {
    MODx.load({ xtype: 'modx-page-package' });
});

MODx.page.Package = function(config) {
    config = config || {};
    Ext.applyIf(config,{
        formpanel: 'modx-panel-package'
        ,components: [{
            xtype: 'modx-panel-package'
            ,renderTo: 'modx-panel-package-div'
            ,signature: MODx.request.signature
        }]
        ,buttons: [{
            process: 'update'
            ,text: _('save')
            ,method: 'remote'
            ,checkDirty: true
            ,keys: [{
                key: MODx.config.keymap_save || 's'
                ,alt: true
                ,ctrl: true
            }]
        },'-',{
            process: 'cancel'
            ,text: _('cancel')
            ,handler: function() {
                location.href= '?a='+MODx.action['workspaces'];
            }
        },'-',{
            text: _('help_ex')
            ,handler: MODx.loadHelpPane
        }]
    });
    MODx.page.Package.superclass.constructor.call(this,config);
};
Ext.extend(MODx.page.Package,MODx.Component);
Ext.reg('modx-page-package',MODx.page.Package);