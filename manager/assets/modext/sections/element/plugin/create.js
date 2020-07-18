/**
 * Loads the create plugin page
 *
 * @class MODx.page.CreatePlugin
 * @extends MODx.Component
 * @param {Object} config An object of config properties
 * @xtype modx-page-plugin-create
 */
MODx.page.CreatePlugin = function(config) {
    config = config || {};
    Ext.applyIf(config,{
        formpanel: 'modx-panel-plugin'
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
            process: 'Element/Plugin/Create'
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
            xtype: 'modx-panel-plugin'
            ,renderTo: 'modx-panel-plugin-div'
            ,plugin: config.record.id || MODx.request.id
            ,record: config.record || {}
        }]
    });
    MODx.page.CreatePlugin.superclass.constructor.call(this,config);
};
Ext.extend(MODx.page.CreatePlugin,MODx.Component);
Ext.reg('modx-page-plugin-create',MODx.page.CreatePlugin);
