Ext.onReady(function() {
    MODx.add('modx-page-workspace');
});

/**
 * Loads the MODx Workspace environment
 * 
 * @class MODx.page.Workspace
 * @extends MODx.Component
 * @param {Object} config An object of config properties
 * @xtype modx-page-workspace
 */
MODx.page.Workspace = function(config) {
    config = config || {};
    Ext.applyIf(config,{
        components: [{
            xtype: 'modx-panel-workspace'
        }]
        ,buttons: [{
            text: _('help_ex')
            ,handler: MODx.loadHelpPane
        }]
    });
    MODx.page.Workspace.superclass.constructor.call(this,config);
    Ext.Ajax.timeout = 0;
};
Ext.extend(MODx.page.Workspace,MODx.Component);
Ext.reg('modx-page-workspace',MODx.page.Workspace);