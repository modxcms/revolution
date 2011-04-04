/**
 * Loads the TV creation page
 * 
 * @class MODx.page.CreateTV
 * @extends MODx.Component
 * @param {Object} config An object of config properties
 * @xtype modx-page-tv-create
 */
MODx.page.CreateTV = function(config) {
    config = config || {};
    Ext.applyIf(config,{
        formpanel: 'modx-panel-tv'
        ,actions: {
            'new': MODx.action['element/tv/create']
            ,edit: MODx.action['element/tv/update']
            ,cancel: MODx.action['welcome']
        }
        ,buttons: [{
            process: 'create'
            ,text: _('save')
            ,method: 'remote'
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
            xtype: 'modx-panel-tv'
            ,renderTo: 'modx-panel-tv-div'
            ,record: config.record || {}
        }]
    });
    MODx.page.CreateTV.superclass.constructor.call(this,config);
};
Ext.extend(MODx.page.CreateTV,MODx.Component);
Ext.reg('modx-page-tv-create',MODx.page.CreateTV);