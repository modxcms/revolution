/**
 * Loads the resource data page
 *
 * @class MODx.page.ResourceData
 * @extends MODx.Component
 * @param {Object} config An object of config properties
 * @xtype modx-page-resource-data
 */
MODx.page.ResourceData = function(config) {
    config = config || {};

    Ext.applyIf(config,{
        form: 'modx-resource-data'
        ,buttons: this.getButtons(config)
        ,components: [{
            xtype: 'modx-panel-resource-data'
            ,resource: config.record.id
            ,context: config.record.context_key
            ,class_key: config.record.class_key
            ,pagetitle: config.record.pagetitle
            ,record: config.record
            ,border: false
            ,url: config.url
            ,canEdit: config.canEdit
        }]
    });
    MODx.page.ResourceData.superclass.constructor.call(this,config);
};
Ext.extend(MODx.page.ResourceData,MODx.Component,{
    preview: function() {
        window.open(this.config.record.preview_url);
        return false;
    }

    ,editResource: function() {
        MODx.loadPage('resource/update', 'id='+this.config.record.id);
    }

    ,cancel: function() {
        MODx.loadPage('?');
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
        var edit = {
            text: _('edit') + ' <i class="icon icon-edit"></i>'
            ,id: 'modx-abtn-edit'
            ,cls: 'primary-button'
            ,hidden: !config.canEdit
            ,handler: this.editResource
            ,scope: this
        };

        var btns;
        if (config.canEdit == 1) {
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
            }, edit];
        } else {
            btns = menu;
        }

        return btns;
    }

});
Ext.reg('modx-page-resource-data',MODx.page.ResourceData);
