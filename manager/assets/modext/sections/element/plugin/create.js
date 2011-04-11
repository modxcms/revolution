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
        ,actions: {
            'new': MODx.action['element/plugin/create']
            ,edit: MODx.action['element/plugin/update']
            ,cancel: MODx.action['welcome']
        }
        ,buttons: [{
            process: 'create', text: _('save'), method: 'remote'
            ,checkDirty: true
            ,keys: [{
                key: MODx.config.keymap_save || 's'
                ,ctrl: true
            }]
        },'-',{
            process: 'cancel', text: _('cancel'), params: {a:MODx.action['welcome']}
        },'-',{
            text: _('help_ex')
            ,handler: MODx.loadHelpPane
        }]
        ,loadStay: true
        ,components: [{
            xtype: 'modx-panel-plugin'
            ,renderTo: 'modx-panel-plugin-div'
            ,plugin: 0
            ,record: config.record || {}
        }]
    });
    MODx.page.CreatePlugin.superclass.constructor.call(this,config);
};
Ext.extend(MODx.page.CreatePlugin,MODx.Component);
Ext.reg('modx-page-plugin-create',MODx.page.CreatePlugin);