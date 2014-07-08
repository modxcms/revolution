/**
 * Loads the groups and roles page
 * 
 * @class MODx.page.ResourceGroups
 * @extends MODx.Component
 * @param {Object} config An object of config properties
 * @xtype modx-page-resource-groups
 */
MODx.page.ResourceGroups = function(config) {
    config = config || {};
    Ext.applyIf(config,{
        components: [{
            xtype: 'modx-panel-resource-groups'
        }]
        ,buttons: [{
            text: _('help_ex')
            ,id: 'modx-abtn-help'
            ,handler: MODx.loadHelpPane
        }]
    });
    MODx.page.ResourceGroups.superclass.constructor.call(this,config);
};
Ext.extend(MODx.page.ResourceGroups,MODx.Component);
Ext.reg('modx-page-resource-groups',MODx.page.ResourceGroups);
