MODx.page.Package = function(config) {
    config = config || {};
    Ext.applyIf(config,{
        formpanel: 'modx-panel-package'
        ,components: [{
            xtype: 'modx-panel-package'
            ,signature: MODx.request.signature
            ,package_name: MODx.request.package_name
        }]
        ,buttons: [{
            process: 'Workspace/Packages/Update'
            ,text: _('save')
            ,id: 'modx-abtn-save'
            ,cls: 'primary-button'
            ,method: 'remote'
            ,keys: [{
                key: MODx.config.keymap_save || 's'
                ,alt: true
                ,ctrl: true
            }]
        },{
            process: 'cancel'
            ,text: _('cancel')
            ,id: 'modx-abtn-cancel'
            ,handler: function() {
                MODx.loadPage('workspaces');
            }
        },{
            text: '<i class="icon icon-question-circle"></i>'
            ,id: 'modx-abtn-help'
            ,handler: MODx.loadHelpPane
        }]
    });
    MODx.page.Package.superclass.constructor.call(this,config);
};
Ext.extend(MODx.page.Package,MODx.Component);
Ext.reg('modx-page-package',MODx.page.Package);
