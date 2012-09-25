/**
 * Loads the update plugin page
 * 
 * @class MODx.page.UpdatePlugin
 * @extends MODx.Component
 * @param {Object} config An object of config properties
 * @xtype modx-page-plugin-update
 */
MODx.page.UpdatePlugin = function(config) {
    config = config || {};
    Ext.applyIf(config,{
        formpanel: 'modx-panel-plugin'
        ,actions: {
            'new': 'element/plugin/create'
            ,edit: 'element/plugin/update'
            ,cancel: 'welcome'
        }
        ,buttons: [{
            process: 'update'
            ,text: _('save')
            ,method: 'remote'
            ,checkDirty: true
            ,keys: [{
                key: MODx.config.keymap_save || 's'
                ,ctrl: true
            }]
        },'-',{
            process: 'cancel'
            ,text: _('cancel')
            ,params: {a:'welcome'}
        },'-',{
            text: _('help_ex')
            ,handler: MODx.loadHelpPane
        }]
        ,loadStay: true
        ,components: [{
            xtype: 'modx-panel-plugin'
            ,renderTo: 'modx-panel-plugin-div'
            ,plugin: config.record.id || MODx.request.id
            ,record: config.record || {}
        }]
    });
    MODx.page.UpdatePlugin.superclass.constructor.call(this,config);
};
Ext.extend(MODx.page.UpdatePlugin,MODx.Component);
Ext.reg('modx-page-plugin-update',MODx.page.UpdatePlugin);