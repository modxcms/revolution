/**
 * Loads the actions page
 *
 * @class MODx.page.Actions
 * @extends MODx.Component
 * @param {Object} config An object of config properties
 * @xtype modx-page-actions
 */
MODx.page.Actions = function(config) {
    config = config || {};
    Ext.applyIf(config,{
        components: [{
            xtype: 'modx-panel-actions'
        }]
        ,buttons: [{
            text: '<i class="icon icon-question-circle"></i>'
            ,id: 'modx-abtn-help'
            ,handler: MODx.loadHelpPane
        }]
    });
    MODx.page.Actions.superclass.constructor.call(this,config);
};
Ext.extend(MODx.page.Actions,MODx.Component);
Ext.reg('modx-page-actions',MODx.page.Actions);
