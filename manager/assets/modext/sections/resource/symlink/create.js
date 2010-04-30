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
        url: MODx.config.connectors_url+'resource/index.php'
        ,formpanel: 'modx-panel-resource'
        ,id: 'modx-page-update-resource'
        ,which_editor: 'none'
        ,actions: {
            'new': MODx.action['resource/symlink/create']
            ,edit: MODx.action['resource/symlink/update']
            ,cancel: MODx.action['welcome']
        }
        ,buttons: this.getButtons(config)
        ,loadStay: true
        ,components: [{
            xtype: 'modx-panel-symlink'
            ,renderTo: 'modx-panel-symlink-div'
            ,resource: 0
            ,record: {
                class_key: config.class_key
                ,context_key: config.context_key
                ,template: config.template
                ,parent: config.parent
            }
            ,publish_document: config.publish_document
            ,access_permissions: config.access_permissions
        }]
    });
    MODx.page.CreateSymLink.superclass.constructor.call(this,config);
};
Ext.extend(MODx.page.CreateSymLink,MODx.Component,{
    getButtons: function(cfg) {
        var btns = [];
        if (cfg.canSave == 1) {
            btns.push({
                process: 'create'
                ,text: _('save')
                ,method: 'remote'
                ,checkDirty: true
                ,keys: [{
                    key: 's'
                    ,alt: true
                    ,ctrl: true
                }]
            });
            btns.push('-');
        }
        btns.push({
            process: 'cancel'
            ,text: _('cancel')
            ,params: { a: MODx.action['welcome'] }
        });
        btns.push('-');
        btns.push({
            text: _('help_ex')
            ,handler: MODx.loadHelpPane
        });
        return btns;
    }
});
Ext.reg('modx-page-symlink-create',MODx.page.CreateSymLink);