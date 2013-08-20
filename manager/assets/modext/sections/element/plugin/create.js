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
            'new': 'element/plugin/create'
            ,'edit': 'element/plugin/update'
            ,'cancel': 'welcome'
        }
        ,buttons: [{
            process: 'element/plugin/create'
            ,text: _('save')
            ,method: 'remote'
            ,checkDirty: true
            ,reload: true
            ,keys: [{
                key: MODx.config.keymap_save || 's'
                ,ctrl: true
            }]
        },'-',{
            process: 'welcome'
            ,text: _('cancel')
            ,params: {a:'welcome'}
        },'-',{
            text: _('help_ex')
            ,handler: MODx.loadHelpPane
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