/**
 * Loads the update static resource page
 * 
 * @class MODx.page.UpdateStatic
 * @extends MODx.Component
 * @param {Object} config An object of config properties
 * @xtype modx-page-static-update
 */
MODx.page.UpdateStatic = function(config) {
    config = config || {record:{}};
    config.record = config.record || {};
    Ext.apply(config.record,{
        'parent-cmb': config.record['parent']
    });
        
    Ext.applyIf(config,{
        url: MODx.config.connectors_url+'resource/index.php'
        ,which_editor: 'none'
        ,formpanel: 'modx-panel-resource'
        ,id: 'modx-page-update-resource'
        ,action: 'update'
        ,actions: {
            'new': 'resource/create'
            ,edit: 'resource/update'
            ,preview: 'resource/preview'
            ,cancel: 'welcome'
        }
        ,components: [{
            xtype: 'modx-panel-static'
            ,renderTo: 'modx-panel-static-div'
            ,resource: config.resource
            ,record: config.record || {}
            ,publish_document: config.publish_document
            ,access_permissions: config.access_permissions
            ,show_tvs: config.show_tvs
            ,url: config.url
        }]
        ,loadStay: true
        ,buttons: this.getButtons(config)
    });
    MODx.page.UpdateStatic.superclass.constructor.call(this,config);
};
Ext.extend(MODx.page.UpdateStatic,MODx.Component,{
    preview: function() {
        window.open(this.config.preview_url);
        return false;
    }
    
    ,duplicateResource: function(btn,e) {
        MODx.msg.confirm({
            text: _('resource_duplicate_confirm')
            ,url: MODx.config.connectors_url+'resource/index.php'
            ,params: {
                action: 'duplicate'
                ,id: this.config.resource
            }
            ,listeners: {
                success: {fn:function(r) {
                    location.href = '?a=resource/update&id='+r.object.id;
                },scope:this}
            }
        });
    }

    ,deleteResource: function(btn,e) {
        MODx.msg.confirm({
            text: _('resource_delete_confirm')
            ,url: MODx.config.connectors_url+'resource/index.php'
            ,params: {
                action: 'delete'
                ,id: this.config.resource
            }
            ,listeners: {
                success: {fn:function(r) {
                    location.href = '?a=resource/update&id='+r.object.id;
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
                    location.href = '?a=welcome';
                }
            },this);
        } else {
            MODx.releaseLock(MODx.request.id);
            location.href = '?a=welcome';
        }
    }
    ,getButtons: function(cfg) {
        var btns = [];
        if (cfg.canSave == 1) {
            btns.push({
                process: 'update'
                ,id: 'modx-abtn-save'
                ,text: _('save')
                ,method: 'remote'
                ,checkDirty: cfg.richtext || MODx.request.activeSave == 1 ? false : true
                ,keys: [{
                    key: MODx.config.keymap_save || 's'
                    ,ctrl: true
                }]
            });
            btns.push('-');
        } else {
            btns.push({
                text: cfg.lockedText || _('locked')
                ,handler: Ext.emptyFn
                ,disabled: true
                ,id: 'modx-abtn-locked'
            });
            btns.push('-');
        }
        if (cfg.canCreate == 1) {
            btns.push({
                process: 'duplicate'
                ,text: _('duplicate')
                ,handler: this.duplicateResource
                ,scope:this
                ,id: 'modx-abtn-duplicate'
            });
            btns.push('-');
        }
        if (cfg.canDelete == 1 && !cfg.locked) {
            btns.push({
                process: 'delete'
                ,text: _('delete')
                ,handler: this.deleteResource
                ,scope:this
                ,id: 'modx-abtn-delete'
            });
            btns.push('-');
        }
        btns.push({
            process: 'preview'
            ,text: _('view')
            ,handler: this.preview
            ,scope: this
            ,id: 'modx-abtn-preview'
        });
        btns.push('-');
        btns.push({
            process: 'cancel'
            ,text: _('cancel')
            ,handler: this.cancel
            ,scope: this
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
Ext.reg('modx-page-static-update',MODx.page.UpdateStatic);