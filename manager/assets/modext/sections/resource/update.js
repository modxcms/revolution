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
        ,action: 'Resource/Update'
        ,components: [{
            xtype: config.panelXType || 'modx-panel-resource'
            ,renderTo: config.panelRenderTo || 'modx-panel-resource-div'
            ,resource: config.resource
            ,record: config.record || {}
            ,publish_document: config.publish_document
            ,show_tvs: config.show_tvs
            ,mode: config.mode
            ,url: config.url
            ,canDelete: config.canDelete
            ,locked: config.locked
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
        var t = Ext.getCmp('modx-resource-tree');
        var id = this.config.resource;
        var node = t.getNodeById(this.config.record.context_key + '_' + id);

        var r = {
            resource: id
            ,pagetitle: this.config.record.pagetitle
            ,hasChildren: false
            ,is_folder: this.config.record.isfolder
        };

        if (node) {
            r.pagetitle = node.ui.textNode.innerText;
            r.hasChildren = node.attributes.hasChildren;
            r.childCount = node.attributes.childCount;
            r.is_folder = node.getUI().hasClass('folder');
        }
        var w = MODx.load({
            xtype: 'modx-window-resource-duplicate'
            ,resource: id
            ,pagetitle: r.pagetitle
            ,hasChildren: r.hasChildren
            ,childCount: r.childCount
            ,redirect: true
            ,listeners: {
                success: {fn:function(r) {
                    var response = Ext.decode(r.a.response.responseText);
                    if (response.object.redirect) {
                        MODx.loadPage('resource/update', 'id='+response.object.id);
                    } else if (node) {
                        node.parentNode.attributes.childCount = parseInt(node.parentNode.attributes.childCount) + 1;
                        t.refreshNode(node.id);
                    }
                },scope:this}
            }
        });
        w.setValues(r);
        w.show(e.target);
    }

    ,deleteResource: function(btn,e) {
        MODx.msg.confirm({
            title: this.config.record.pagetitle ? _('resource_delete') + ' ' + this.config.record.pagetitle + ' (' + this.config.resource + ')' : _('resource_delete')
            ,text: _('resource_delete_confirm')
            ,url: MODx.config.connector_url
            ,params: {
                action: 'Resource/Delete'
                ,id: this.config.resource
            }
            ,listeners: {
                success: {fn:function(r) {
                    var panel = Ext.getCmp('modx-panel-resource');
                    if (panel) {
                        panel.handlePreview(true);
                        panel.handleDeleted(true);
                    }
                },scope:this}
            }
        });
    }

    ,unDeleteResource: function(btn,e) {
        MODx.Ajax.request({
            url: MODx.config.connector_url
            ,params: {
                action: 'Resource/Undelete'
                ,id: this.config.resource
            }
            ,listeners: {
                success: {fn:function(r) {
                    var panel = Ext.getCmp('modx-panel-resource');
                    if (panel) {
                        panel.handlePreview(false);
                        panel.handleDeleted(false);
                    }
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

    ,getButtons: function(config) {
        var menu = [];
        if (config.canDuplicate == 1 && (config.record.parent !== parseInt(MODx.config.tree_root_id) || config.canCreateRoot == 1)) {
            menu.push({
                text: _('duplicate') + ' <i class="icon icon-copy"></i>'
                ,id: 'modx-abtn-duplicate'
                ,handler: this.duplicateResource
                ,scope: this
            });
        }
        if (config.canDelete == 1 && !config.locked) {
            menu.push({
                text:  _('undelete') + ' <i class="icon icon-repeat"></i>'
                ,id: 'modx-abtn-undelete'
                ,handler: this.unDeleteResource
                ,hidden: !config.record.deleted
                ,scope: this
            });
            menu.push({
                text: _('delete') + ' <i class="icon icon-trash-o"></i>'
                ,id: 'modx-abtn-delete'
                ,handler: this.deleteResource
                ,hidden: config.record.deleted
                ,scope: this
            });
        }
        menu.push({
            text: _('cancel') + ' <i class="icon icon-times"></i>'
            ,id: 'modx-abtn-cancel'
            ,handler: this.cancel
            ,scope: this
        });
        menu.push({
            text: _('help_ex') + ' <i class="icon icon-question-circle"></i>'
            ,id: 'modx-abtn-help'
            ,handler: MODx.loadHelpPane
        });

        var btns = [{
            text: '<i class="icon icon-ellipsis-h"></i>'
            ,id: 'modx-abtn-menu'
            ,xtype: 'splitbutton'
            ,split: false
            ,arrowSelector: false
            ,handler: function(btn, e) {
                if (!btn.menu.isVisible() && !btn.ignoreNextClick) {
                    btn.showMenu();
                }
                btn.fireEvent("arrowclick", btn, e);
                if (btn.arrowHandler) {
                    btn.arrowHandler.call(btn.scope || btn, btn, e);
                }
            }
            ,menu: {
                id: 'modx-abtn-menu-list'
                ,items: menu
            }
        }];

        btns.push({
            text: (config.lockedText || _('locked')) + ' <i class="icon icon-lock"></i>'
            ,id: 'modx-abtn-locked'
            ,handler: Ext.emptyFn
            ,hidden: (config.canSave == 1)
            ,disabled: true
        });

        btns.push({
            text: _('view') + ' <i class="icon icon-eye"></i>'
            ,id: 'modx-abtn-preview'
            ,handler: this.preview
            ,hidden: config.record.deleted
            ,scope: this
        });

        btns.push({
            process: 'Resource/Update'
            ,text: _('save') + ' <i class="icon icon-check"></i>'
            ,id: 'modx-abtn-save'
            ,cls: 'primary-button'
            ,method: 'remote'
            ,hidden: !(config.canSave == 1)
            //,checkDirty: MODx.request.reload ? false : true
            ,keys: [{
                key: MODx.config.keymap_save || 's'
                ,ctrl: true
            }]
        });

        return btns;
    }
});
Ext.reg('modx-page-resource-update',MODx.page.UpdateResource);
