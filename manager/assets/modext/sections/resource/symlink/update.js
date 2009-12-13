/**
 * Loads the update symlink resource page
 * 
 * @class MODx.page.UpdateSymLink
 * @extends MODx.Component
 * @param {Object} config An object of config properties
 * @xtype modx-page-symlink-update
 */
MODx.page.UpdateSymLink = function(config) {
    config = config || {};
        
    Ext.applyIf(config,{
        url: MODx.config.connectors_url+'resource/index.php'
        ,which_editor: 'none'
        ,formpanel: 'modx-panel-symlink'
        ,id: 'modx-page-update-resource'
        ,actions: {
            'new': MODx.action['resource/symlink/create']
            ,edit: MODx.action['resource/symlink/update']
            ,preview: MODx.action['resource/preview']
            ,cancel: MODx.action['welcome']
        }
        ,components: [{
            xtype: 'modx-panel-symlink'
            ,renderTo: 'modx-panel-symlink-div'
            ,resource: config.resource
            ,record: {
                class_key: config.class_key
                ,context_key: config.context_key
                ,template: config.template
            }
            ,publish_document: config.publish_document
            ,edit_doc_metatags: config.edit_doc_metatags
            ,access_permissions: config.access_permissions
        }]
    	,loadStay: true
        ,buttons: [{
            process: 'update'
            ,javascript: config.which_editor != 'none' ? "cleanupRTE('"+config.which_editor+"');" : ';'
            ,text: _('save')
            ,method: 'remote'
            ,checkDirty: true
            ,keys: [{
                key: 's'
                ,alt: true
                ,ctrl: true
            }]
        }
        ,'-'
        ,{
            process: 'duplicate'
            ,text: _('duplicate')
            ,handler: this.duplicate
            ,scope:this
        }
        ,'-'
        ,{
            process: 'preview'
            ,text: _('preview')
            ,handler: this.preview
            ,scope: this
        },'-',{
            process: 'cancel'
            ,text: _('cancel')
            ,handler: this.cancel
            ,scope: this
        },'-',{
            text: _('help_ex')
            ,handler: MODx.loadHelpPane
        }]
    });
    MODx.page.UpdateSymLink.superclass.constructor.call(this,config);
};
Ext.extend(MODx.page.UpdateSymLink,MODx.Component,{
    preview: function() {
        window.open(this.config.preview_url);
        return false;
    }
    
    ,duplicate: function(btn,e) {
        MODx.msg.confirm({
            text: _('resource_duplicate_confirm')
            ,url: MODx.config.connectors_url+'resource/index.php'
            ,params: {
                action: 'duplicate'
                ,id: this.config.resource
            }
            ,listeners: {
                success: {fn:function(r) {
                    location.href = '?a='+MODx.action['resource/update']+'&id='+r.object.id;
                },scope:this}
            }
        });
    }

    ,cancel: function(btn,e) {
        var fp = Ext.getCmp(this.config.formpanel);
        if (fp && fp.isDirty()) {
            Ext.Msg.confirm(_('warning'),_('resource_cancel_dirty_confirm'),function(e) {
                if (e == 'yes') {
                    MODx.releaseLock(MODx.request.id);
                    MODx.sleep(400);
                    location.href = '?a='+MODx.action['welcome'];                    
                }
            },this);
        } else {
            MODx.releaseLock(MODx.request.id);
        };
    }
});
Ext.reg('modx-page-symlink-update',MODx.page.UpdateSymLink);