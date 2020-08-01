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
        url: MODx.config.connector_url
        ,formpanel: 'modx-panel-resource'
        ,id: 'modx-page-update-resource'
        ,which_editor: 'none'
        ,action: 'Resource/Create'
    	,buttons: this.getButtons(config)
        ,components: [{
            xtype: config.panelXType || 'modx-panel-resource'
            ,renderTo: config.panelRenderTo || 'modx-panel-resource-div'
            ,resource: 0
            ,record: config.record
            ,publish_document: config.publish_document
            ,show_tvs: config.show_tvs
            ,mode: config.mode
            ,url: config.url
        }]
    });
    MODx.page.CreateResource.superclass.constructor.call(this,config);
};
Ext.extend(MODx.page.CreateResource,MODx.Component,{
    cancel: function(btn,e) {
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
        var menu = [{
            text: _('cancel') + ' <i class="icon icon-times"></i>'
            ,id: 'modx-abtn-cancel'
            ,handler: this.cancel
            ,scope: this
        },{
            text: _('help_ex') + ' <i class="icon icon-question-circle"></i>'
            ,id: 'modx-abtn-help'
            ,handler: MODx.loadHelpPane
        }];
        var save = {
            process: 'Resource/Create'
            ,reload: true
            ,text: _('save') + ' <i class="icon icon-check"></i>'
            ,id: 'modx-abtn-save'
            ,cls:'primary-button'
            ,method: 'remote'
            ,keys: [{
                key: MODx.config.keymap_save || 's'
                ,ctrl: true
            }]
        };

        var btns;
        if (config.canSave == 1) {
             btns = [{
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
            }, save];
        } else {
            btns = menu;
        }

        return btns;
    }
});
Ext.reg('modx-page-resource-create',MODx.page.CreateResource);
