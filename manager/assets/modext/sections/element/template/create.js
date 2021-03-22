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
        ,buttons: [{
            process: 'Element/Template/Create'
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
