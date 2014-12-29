/**
 * Loads the resource update page
 *
 * @class MODx.page.UpdateResource
 * @extends MODx.Component
 * @param {Object} config An object of config properties
 * @xtype modx-page-resource-update
 */
MODx.page.UpdateResource = function(config) {
    config = config || {record:{}};
    config.record = config.record || {};
    Ext.apply(config.record,{
        'parent-cmb': config.record['parent']
    });
    Ext.applyIf(config,{
        url: MODx.config.connector_url
        ,which_editor: 'none'
        ,formpanel: 'modx-panel-resource'
        ,id: 'modx-page-update-resource'
        ,action: 'resource/update'
        ,components: [{
            xtype: config.panelXType || 'modx-panel-resource'
            ,renderTo: config.panelRenderTo || 'modx-panel-resource-div'
            ,resource: config.resource
            ,record: config.record || {}
            ,publish_document: config.publish_document
            ,show_tvs: config.show_tvs
            ,mode: config.mode
            ,url: config.url
        }]
        ,buttons: this.getButtons(config)
    });
    MODx.page.UpdateResource.superclass.constructor.call(this,config);
    if (!Ext.isIE) {
        Ext.EventManager.on(window, 'beforeunload',function(e) {
            MODx.releaseLock(this.config.resource);
            MODx.sleep(400);
            return false;
        }, this);
    }
    new Ext.KeyMap(Ext.getBody(), {
        key: 'p'
        ,alt: true
        ,ctrl: true
        ,fn: this.preview
        ,scope: this
    });
};
Ext.extend(MODx.page.UpdateResource,MODx.Component,{
    preview: function() {
        window.open(this.config.preview_url);
        return false;
    }

    ,duplicateResource: function(btn,e) {
        MODx.msg.confirm({
            text: _('resource_duplicate_confirm')
            ,url: MODx.config.connector_url
            ,params: {
                action: 'resource/duplicate'
                ,id: this.config.resource
            }
            ,listeners: {
                success: {fn:function(r) {
                    MODx.loadPage('resource/update', 'id='+r.object.id);
                },scope:this}
            }
        });
    }

    ,deleteResource: function(btn,e) {
        MODx.msg.confirm({
            text: _('resource_delete_confirm')
            ,url: MODx.config.connector_url
            ,params: {
                action: 'resource/delete'
                ,id: this.config.resource
            }
            ,listeners: {
                success: {fn:function(r) {
                    MODx.loadPage('resource/update', 'id='+r.object.id);
                },scope:this}
            }
        });
    }

    ,cancel: function(btn,e) {
        var fp = Ext.getCmp(this.config.formpanel);
        if (fp && fp.isDirty()) {
            Ext.Msg.confirm(_('warning'),_('resource_cancel_dirty_confirm'),function(e) {
                if (e == 'yes') {
                    fp.warnUnsavedChanges = false;
                    MODx.releaseLock(MODx.request.id);
                    MODx.sleep(400);
                    MODx.loadPage('?');
                }
            },this);
        } else {
            MODx.releaseLock(MODx.request.id);
            MODx.loadPage('?');
        }
    }

    ,getButtons: function(cfg) {
        var btns = [];
        if (cfg.canSave == 1) {
            btns.push({
                process: 'resource/update'
                ,text: _('save')
                ,id: 'modx-abtn-save'
                ,cls: 'primary-button'
                ,method: 'remote'
                //,checkDirty: MODx.request.reload ? false : true
                ,keys: [{
                    key: MODx.config.keymap_save || 's'
                    ,ctrl: true
                }]
            });
        } else if (cfg.locked) {
            btns.push({
                text: cfg.lockedText || _('locked')
                ,id: 'modx-abtn-locked'
                ,handler: Ext.emptyFn
                ,disabled: true
            });
        }
        if (cfg.canDuplicate == 1 && (cfg.record.parent !== parseInt(MODx.config.tree_root_id) || cfg.canCreateRoot == 1)) {
            btns.push({
                text: _('duplicate')
                ,id: 'modx-abtn-duplicate'
                ,handler: this.duplicateResource
                ,scope:this
            });
        }
        if (cfg.canDelete == 1 && !cfg.locked) {
            btns.push({
                text: _('delete')
                ,id: 'modx-abtn-delete'
                ,handler: this.deleteResource
                ,scope:this
            });
        }
        btns.push({
            text: _('view')
            ,id: 'modx-abtn-preview'
            ,handler: this.preview
            ,scope: this
        });
        btns.push({
            text: _('cancel')
            ,id: 'modx-abtn-cancel'
            ,handler: this.cancel
            ,scope: this
        });
        btns.push({
            text: _('help_ex')
            ,id: 'modx-abtn-help'
            ,handler: MODx.loadHelpPane
        });
        return btns;
    }
});
Ext.reg('modx-page-resource-update',MODx.page.UpdateResource);
