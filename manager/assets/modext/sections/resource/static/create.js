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
        ,formpanel: 'modx-panel-static'
        ,id: 'modx-page-update-resource'
        ,which_editor: 'none'
        ,actions: {
            'new': MODx.action['resource/staticresource/create']
            ,edit: MODx.action['resource/staticresource/update']
            ,cancel: MODx.action['welcome']
        }
        ,buttons: [{
            process: 'create'
            ,text: _('save')
            ,method: 'remote'
            ,checkDirty: true
            ,javascript: config.which_editor != 'none' ? "cleanupRTE('"+config.which_editor+"');" : ';'
            ,keys: [{
                key: 's'
                ,alt: true
                ,ctrl: true
            }]
        },{
            process: 'cancel'
            ,text: _('cancel')
            ,params: { a: MODx.action['welcome'] }
        }]
        ,loadStay: true
        ,components: [{
            xtype: 'modx-panel-static'
            ,renderTo: 'modx-panel-static-div'
            ,resource: 0
            ,record: {
                class_key: config.class_key
                ,context_key: config.context_key
                ,template: config.template
                ,parent: config.parent
            }
            ,publish_document: config.publish_document
            ,edit_doc_metatags: config.edit_doc_metatags
            ,access_permissions: config.access_permissions
        }]
    });
    MODx.page.CreateStatic.superclass.constructor.call(this,config);
};
Ext.extend(MODx.page.CreateStatic,MODx.Component);
Ext.reg('modx-page-static-create',MODx.page.CreateStatic);