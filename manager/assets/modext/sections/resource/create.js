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
        url: MODx.config.connectors_url+'resource/index.php'
        ,formpanel: 'modx-panel-resource'
        ,id: 'modx-page-update-resource'
        ,which_editor: 'none'
        ,action: 'create'
    	,actions: {
            'new': 'resource/create'
            ,edit: 'resource/update'
            ,cancel: 'welcome'
        }
    	,buttons: this.getButtons(config)
    	,loadStay: true
        ,components: [{
            xtype: config.panelXType || 'modx-panel-resource'
            ,renderTo: config.panelRenderTo || 'modx-panel-resource-div'
            ,resource: 0
            ,record: config.record
            ,access_permissions: config.access_permissions
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
                process: 'create'
                ,id: 'modx-abtn-save'
                ,text: _('save')
                ,method: 'remote'
                ,checkDirty: true
                ,keys: [{
                    key: MODx.config.keymap_save || 's'
                    ,ctrl: true
                }]
            });
            btns.push('-');
        }
        btns.push({
            process: 'cancel'
            ,text: _('cancel')
            ,id: 'modx-abtn-cancel'
            ,params: { a: 'welcome' }
        });
        btns.push('-');
        btns.push({
            text: _('help_ex')
            ,handler: MODx.loadHelpPane
            ,id: 'modx-abtn-help'
        });
        return btns;
    }
});
Ext.reg('modx-page-resource-create',MODx.page.CreateResource);