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
        ,actions: {
            'new': MODx.action['resource/symlink/create']
            ,edit: MODx.action['resource/symlink/update']
            ,preview: MODx.action['resource/preview']
            ,cancel: MODx.action['welcome']
        }
        ,components: [{
            xtype: 'modx-panel-symlink'
            ,renderTo: 'modx-panel-symlink-div'
            ,resource: config.id
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
        },{
            process: 'cancel'
            ,text: _('cancel')
            ,handler: this.cancel
            ,scope: this
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
                ,id: this.config.id
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
        if (fp != 'undefined' && fp.isDirty()) {
            MODx.msg.confirm({
                text: _('resource_cancel_dirty_confirm')
                ,url: MODx.config.connectors_url+'resource/locks.php'
                ,params: {
                    action: 'release'
                    ,id: this.config.id
                }
                ,listeners: {
                    success: {fn:function(r) {
                        location.href = '?a='+MODx.action['welcome'];
                    },scope:this}
                }
            });
        } else {
            MODx.Ajax.request({
                url: MODx.config.connectors_url+'resource/locks.php'
                ,params: {
                    action: 'release'
                    ,id: this.config.id
                }
                ,listeners: {
                    success: {fn:function(r) {
                        location.href = '?a='+MODx.action['welcome'];
                    },scope:this}
                }
            });
        }
    }
});
Ext.reg('modx-page-symlink-update',MODx.page.UpdateSymLink);