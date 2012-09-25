/**
 * Loads the create static resource page
 * 
 * @class MODx.page.CreateStatic
 * @extends MODx.Component
 * @param {Object} config An object of config properties
 * @xtype modx-page-static-create
 */
MODx.page.CreateStatic = function(config) {
    config = config || {};
    Ext.applyIf(config,{
        url: MODx.config.connectors_url+'resource/index.php'
        ,formpanel: 'modx-panel-resource'
        ,id: 'modx-page-update-resource'
        ,which_editor: 'none'
        ,action: 'create'
        ,actions: {
            'new': MODx.action['resource/create']
            ,edit: MODx.action['resource/update']
            ,cancel: MODx.action['welcome']
        }
        ,buttons: this.getButtons(config)
        ,loadStay: true
        ,components: [{
            xtype: 'modx-panel-static'
            ,renderTo: 'modx-panel-static-div'
            ,resource: 0
            ,record: config.record || {}
            ,publish_document: config.publish_document
            ,access_permissions: config.access_permissions
            ,show_tvs: config.show_tvs
            ,url: config.url
        }]
    });
    MODx.page.CreateStatic.superclass.constructor.call(this,config);
};
Ext.extend(MODx.page.CreateStatic,MODx.Component,{
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
            ,params: { a: MODx.action['welcome'] }
            ,id: 'modx-abtn-cancel'
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
Ext.reg('modx-page-static-create',MODx.page.CreateStatic);