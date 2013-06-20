/**
 * Loads the create template page
 * 
 * @class MODx.page.CreateTemplate
 * @extends MODx.Component
 * @param {Object} config An object of config properties
 * @xtype modx-page-template-create
 */
MODx.page.CreateTemplate = function(config) {
    config = config || {};
    Ext.applyIf(config,{
        formpanel: 'modx-panel-template'
        ,actions: {
            'new': 'element/template/create'
            ,'edit': 'element/template/update'
            ,'cancel': 'welcome'
        }
        ,buttons: [{
            process: 'element/template/create'
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
            xtype: 'modx-panel-template'
            ,renderTo: 'modx-panel-template-div'
            ,template: 0
            ,record: config.record || {}
        }]
    });
    MODx.page.CreateTemplate.superclass.constructor.call(this,config);
};
Ext.extend(MODx.page.CreateTemplate,MODx.Component);
Ext.reg('modx-page-template-create',MODx.page.CreateTemplate);