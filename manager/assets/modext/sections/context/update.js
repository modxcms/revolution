Ext.onReady(function() {
    MODx.load({
        xtype: 'modx-page-context-update'
        ,context: MODx.request.key
    });
});

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
            process: 'update'
            ,text: _('save')
            ,method: 'remote'
            ,checkDirty: true
            ,keys: [{
                key: MODx.config.keymap_save || "s"
                ,ctrl: true
            }]
        },'-',{
            process: 'cancel'
            ,text: _('cancel')
            ,params: {
                a: 'context'
            }
        },'-',{
            text: _('help_ex')
            ,handler: MODx.loadHelpPane
        }]
        ,components: [{
            xtype: 'modx-panel-context'
            ,renderTo: 'modx-panel-context-div'
            ,context: config.context
        }]
    });
    MODx.page.UpdateContext.superclass.constructor.call(this,config);
};
Ext.extend(MODx.page.UpdateContext,MODx.Component);
Ext.reg('modx-page-context-update',MODx.page.UpdateContext);