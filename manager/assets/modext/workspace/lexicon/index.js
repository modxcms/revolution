/**
 * @class MODx.page.LexiconManagement
 * @extends MODx.Component
 * @param {Object} config An object of configuration properties
 * @xtype modx-page-lexicon-management
 */
MODx.page.LexiconManagement = function(config) {
    config = config || {};
    Ext.applyIf(config,{
        components: [{
            xtype: 'modx-panel-lexicon'
        }]
        ,buttons: [{
            text: '<i class="icon icon-question-circle"></i>'
            ,handler: MODx.loadHelpPane
        }]
    });
    MODx.page.LexiconManagement.superclass.constructor.call(this,config);
};
Ext.extend(MODx.page.LexiconManagement,MODx.Component);
Ext.reg('modx-page-lexicon-management',MODx.page.LexiconManagement);