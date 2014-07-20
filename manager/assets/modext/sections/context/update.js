/**
 * @class MODx.page.UpdateContext
 * @extends MODx.Component
 * @param {Object} config An object of config properties
 * @xtype modx-page-context-update
 */
MODx.page.UpdateContext = function(config) {
    config = config || {};
    Ext.applyIf(config,{
        formpanel: 'modx-panel-context'
        ,actions: {
            'new': 'context/create'
            ,edit: 'context/update'
            ,'delete': 'context/delete'
            ,cancel: 'context/view'
        }
        ,buttons: [{
            process: 'context/update'
            ,text: _('save')
            ,id: 'modx-abtn-save'
            ,cls:'primary-button'
            ,method: 'remote'
            // ,checkDirty: true
            ,keys: [{
                key: MODx.config.keymap_save || "s"
                ,ctrl: true
            }]
        },{
            process: 'cancel'
            ,text: _('cancel')
            ,id: 'modx-abtn-cancel'
            ,params: {
                a: 'context'
            }
        },{
            text: _('help_ex')
            ,id: 'modx-abtn-help'
            ,handler: MODx.loadHelpPane
        }]
        ,components: [{
            xtype: 'modx-panel-context'
            ,context: MODx.request.key
        }]
    });
    MODx.page.UpdateContext.superclass.constructor.call(this,config);
};
Ext.extend(MODx.page.UpdateContext,MODx.Component);
Ext.reg('modx-page-context-update',MODx.page.UpdateContext);
