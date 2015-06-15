/**
 * Loads the content type management page
 *
 * @class MODx.page.ContentType
 * @extends MODx.Component
 * @param {Object} config An object of config properties
 * @xtype modx-page-content-type
 */
MODx.page.ContentType = function(config) {
    config = config || {};
    Ext.applyIf(config,{
        formpanel: 'modx-panel-content-type'
        ,buttons: [{
            text: _('cancel')
            ,id: 'modx-abtn-cancel'
        },{
            text: _('help_ex')
            ,id: 'modx-abtn-help'
            ,handler: MODx.loadHelpPane
        }]
        ,components: [{
            xtype: 'modx-panel-content-type'
            ,title: ''
        }]
    });
    MODx.page.ContentType.superclass.constructor.call(this,config);
};
Ext.extend(MODx.page.ContentType,MODx.Component);
Ext.reg('modx-page-content-type',MODx.page.ContentType);
