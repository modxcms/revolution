/**
 * @class MODx.page.Namespaces
 * @extends MODx.Component
 * @param {Object} config An object of configuration properties
 * @xtype modx-page-namespaces
 */
MODx.page.Namespaces = function(config) {
    config = config || {};
    Ext.applyIf(config,{
        components: [{
            xtype: 'modx-panel-namespaces'
        }]
        ,buttons: [{
            text: _('help_ex')
            ,handler: MODx.loadHelpPane
        }]
    });
    MODx.page.Namespaces.superclass.constructor.call(this,config);
};
Ext.extend(MODx.page.Namespaces,MODx.Component);
Ext.reg('modx-page-namespaces',MODx.page.Namespaces);
