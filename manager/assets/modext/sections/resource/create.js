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
    	,actions: {
            'new': MODx.action['resource/create']
            ,edit: MODx.action['resource/update']
            ,cancel: MODx.action['welcome']
        }
    	,buttons: this.getButtons(config)
    	,loadStay: true
        ,components: [{
            xtype: 'modx-panel-resource'
            ,renderTo: 'modx-panel-resource-div'
            ,resource: 0
            ,record: {
                context_key: MODx.request.context_key || 'web'
                ,template: config.template
                ,'parent': config['parent']
                ,'parent-cmb': config['parent']
                ,class_key: config.class_key
                ,content_type: config.content_type
                ,richtext: config.richtext
            }
            ,access_permissions: config.access_permissions
            ,publish_document: config.publish_document
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
                ,text: _('save')
                ,method: 'remote'
                ,checkDirty: cfg.richtext ? false : true
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
Ext.reg('modx-page-resource-create',MODx.page.CreateResource);