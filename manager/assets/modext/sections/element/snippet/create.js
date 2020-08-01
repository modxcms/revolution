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
            text: '<i class="icon icon-ellipsis-h"></i>'
            ,id: 'modx-abtn-menu'
            ,xtype: 'splitbutton'
            ,split: false
            ,arrowSelector: false
            ,handler: function(btn, e) {
                if (!btn.menu.isVisible() && !btn.ignoreNextClick) {
                    btn.showMenu();
                }
                btn.fireEvent('arrowclick', btn, e);
                if (btn.arrowHandler) {
                    btn.arrowHandler.call(btn.scope || btn, btn, e);
                }
            }
            ,menu: {
                id: 'modx-abtn-menu-list'
                ,items: [{
                    text: _('cancel') + ' <i class="icon icon-times"></i>'
                    ,id: 'modx-abtn-cancel'
                    ,handler: function() {
                        MODx.loadPage('?');
                    }
                },{
                    text: _('help_ex') + ' <i class="icon icon-question-circle"></i>'
                    ,id: 'modx-abtn-help'
                    ,handler: MODx.loadHelpPane
                }]
            }
        },{
            process: 'Element/Snippet/Create'
            ,reload: true
            ,text: _('save') + ' <i class="icon icon-check"></i>'
            ,id: 'modx-abtn-save'
            ,cls: 'primary-button'
            ,method: 'remote'
            ,keys: [{
                key: MODx.config.keymap_save || 's'
                ,ctrl: true
            }]
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
