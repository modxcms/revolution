/**
 * Loads the create snippet page
 *
 * @class MODx.page.CreateSnippet
 * @extends MODx.Component
 * @param {Object} config An object of config properties
 * @xtype modx-page-snippet-create
 */
MODx.page.CreateSnippet = function(config) {
    config = config || {};
    Ext.applyIf(config,{
        formpanel: 'modx-panel-snippet'
        ,buttons: [{
            process: 'Element/Snippet/Create'
            ,reload: true
            ,text: _('save')
            ,id: 'modx-abtn-save'
            ,cls: 'primary-button'
            ,method: 'remote'
            ,keys: [{
                key: MODx.config.keymap_save || 's'
                ,ctrl: true
            }]
        },{
            text: _('cancel')
            ,id: 'modx-abtn-cancel'
            ,handler: function() {
                MODx.loadPage('?');
            }
        },{
            text: '<i class="icon icon-question-circle"></i>'
            ,id: 'modx-abtn-help'
            ,handler: MODx.loadHelpPane
        }]
        ,components: [{
            xtype: 'modx-panel-snippet'
            ,renderTo: 'modx-panel-snippet-div'
            ,snippet: 0
            ,record: config.record || {}
        }]
    });
    MODx.page.CreateSnippet.superclass.constructor.call(this,config);
};
Ext.extend(MODx.page.CreateSnippet,MODx.Component);
Ext.reg('modx-page-snippet-create',MODx.page.CreateSnippet);
