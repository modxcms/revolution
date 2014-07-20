/**
 * Loads the create resource page
 *
 * @class MODx.page.CreateSymLink
 * @extends MODx.Component
 * @param {Object} config An object of config properties
 * @xtype modx-page-symlink-create
 */
MODx.page.CreateSymLink = function(config) {
    config = config || {};
    Ext.applyIf(config,{
        url: MODx.config.connector_url
        ,formpanel: 'modx-panel-resource'
        ,id: 'modx-page-update-resource'
        ,which_editor: 'none'
        ,action: 'resource/create'
        ,buttons: this.getButtons(config)
        ,components: [{
            xtype: 'modx-panel-symlink'
            ,renderTo: 'modx-panel-symlink-div'
            ,resource: 0
            ,record: config.record || {}
            ,publish_document: config.publish_document
            ,access_permissions: config.access_permissions
            ,show_tvs: config.show_tvs
            ,url: config.url
        }]
    });
    MODx.page.CreateSymLink.superclass.constructor.call(this,config);
};
Ext.extend(MODx.page.CreateSymLink,MODx.Component,{
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
                // ,checkDirty: true
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
Ext.reg('modx-page-symlink-create',MODx.page.CreateSymLink);
