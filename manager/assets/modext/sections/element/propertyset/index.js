/**
 * Loads the property sets page
 * 
 * @class MODx.page.PropertySets
 * @extends MODx.Component
 * @param {Object} config An object of config properties
 * @xtype modx-page-property-sets
 */
MODx.page.PropertySets = function(config) {
    config = config || {};
    Ext.applyIf(config,{
        components: [{
            xtype: 'modx-panel-property-sets'
        }]
        ,buttons: [{
            text: _('help_ex')
            ,id: 'modx-abtn-help'
            ,handler: MODx.loadHelpPane
        }]
    });
    MODx.page.PropertySets.superclass.constructor.call(this,config);    
};
Ext.extend(MODx.page.PropertySets,MODx.Component);
Ext.reg('modx-page-property-sets',MODx.page.PropertySets);
