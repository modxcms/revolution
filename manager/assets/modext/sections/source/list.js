/**
 * Loads the sources page
 *
 * @class MODx.page.Sources
 * @extends MODx.Component
 * @param {Object} config An object of config properties
 * @xtype modx-page-sources
 */
MODx.page.Sources = function(config) {
    config = config || {};
    Ext.applyIf(config,{
        components: [{
            xtype: 'modx-panel-sources'
        }]
        ,buttons: [{
            text: '<i class="icon icon-question-circle"></i>'
            ,id: 'modx-abtn-help'
            ,handler: MODx.loadHelpPane
        }]
    });
    MODx.page.Sources.superclass.constructor.call(this,config);
};
Ext.extend(MODx.page.Sources,MODx.Component);
Ext.reg('modx-page-sources',MODx.page.Sources);
