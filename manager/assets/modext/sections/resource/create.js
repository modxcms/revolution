/**
 * Loads the create resource page
 *
 * @class MODx.page.CreateResource
 * @extends MODx.Component
 * @param {Object} config An object of config properties
 * @xtype modx-page-resource-create
 */
MODx.page.CreateResource = function(config) {
    config = config || {};
    Ext.applyIf(config,{
        url: MODx.config.connector_url
        ,formpanel: 'modx-panel-resource'
        ,id: 'modx-page-update-resource'
        ,which_editor: 'none'
        ,action: 'resource/create'
    	,buttons: this.getButtons(config)
        ,components: [{
            xtype: config.panelXType || 'modx-panel-resource'
            ,renderTo: config.panelRenderTo || 'modx-panel-resource-div'
            ,resource: 0
            ,record: config.record
            ,publish_document: config.publish_document
            ,show_tvs: config.show_tvs
            ,mode: config.mode
            ,url: config.url
        }]
    });
    MODx.page.CreateResource.superclass.constructor.call(this,config);
};
Ext.extend(MODx.page.CreateResource,MODx.Component,{
    getButtons: function(cfg) {
        var btns = [];
        if (cfg.canSave == 1) {
            btns.push({
                process: 'resource/create'
                ,reload: true
                ,text: _('save')
                ,id: 'modx-abtn-save'
                ,cls:'primary-button'
                ,method: 'remote'
                //,checkDirty: true
                ,keys: [{
                    key: MODx.config.keymap_save || 's'
                    ,ctrl: true
                }]
            });

        }
        btns.push({
            text: _('cancel')
            ,id: 'modx-abtn-cancel'
        });
        btns.push({
            text: _('help_ex')
            ,id: 'modx-abtn-help'
            ,handler: MODx.loadHelpPane
        });
        return btns;
    }
});
Ext.reg('modx-page-resource-create',MODx.page.CreateResource);
