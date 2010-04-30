/**
 * Loads the update weblink resource page
 * 
 * @class MODx.page.UpdateWebLink
 * @extends MODx.Component
 * @param {Object} config An object of config properties
 * @xtype modx-page-weblink-update
 */
MODx.page.UpdateWebLink = function(config) {
    config = config || {};
        
    Ext.applyIf(config,{
        url: MODx.config.connectors_url+'resource/index.php'
        ,which_editor: 'none'
        ,formpanel: 'modx-panel-resource'
        ,id: 'modx-page-update-resource'
        ,actions: {
            'new': MODx.action['resource/create']
            ,edit: MODx.action['resource/update']
            ,preview: MODx.action['resource/preview']
            ,cancel: MODx.action['welcome']
        }
        ,components: [{
            xtype: 'modx-panel-weblink'
            ,renderTo: 'modx-panel-weblink-div'
            ,resource: config.resource
            ,record: {
                class_key: config.class_key
                ,context_key: config.context_key
                ,template: config.template
            }
            ,publish_document: config.publish_document
            ,access_permissions: config.access_permissions
        }]
    	,loadStay: true
        ,buttons: this.getButtons(config)
    });
    MODx.page.UpdateWebLink.superclass.constructor.call(this,config);
};
Ext.extend(MODx.page.UpdateWebLink,MODx.Component,{
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
    
    ,getButtons: function(cfg) {
        var btns = [];
        if (cfg.canSave == 1) {
            btns.push({
                process: 'update'
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
        if (cfg.canCreate == 1) {
            btns.push({
                process: 'duplicate'
                ,text: _('duplicate')
                ,handler: this.duplicate
                ,scope:this
            });
            btns.push('-');
        }
        btns.push({
            process: 'preview'
            ,text: _('preview')
            ,handler: this.preview
            ,scope: this
        });
        btns.push('-');
        btns.push({
            process: 'cancel'
            ,text: _('cancel')
            ,handler: this.cancel
            ,scope: this
        });
        btns.push('-');
        btns.push({
            text: _('help_ex')
            ,handler: MODx.loadHelpPane
        });
        return btns;
    }
});
Ext.reg('modx-page-weblink-update',MODx.page.UpdateWebLink);