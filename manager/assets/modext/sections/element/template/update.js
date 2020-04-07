/**
 * Loads the update template page
 *
 * @class MODx.page.UpdateTemplate
 * @extends MODx.Component
 * @param {Object} config An object of config properties
 * @xtype modx-page-template-update
 */
MODx.page.UpdateTemplate = function(config) {
    config = config || {};
    Ext.applyIf(config,{
        formpanel: 'modx-panel-template'
        ,buttons: this.getButtons(config)
        ,components: [{
            xtype: 'modx-panel-template'
            ,renderTo: 'modx-panel-template-div'
            ,template: config.id
            ,record: config.record || {}
        }]
    });
    MODx.page.UpdateTemplate.superclass.constructor.call(this,config);
};
Ext.extend(MODx.page.UpdateTemplate,MODx.Component, {
    duplicate: function(btn, e) {
        var rec = {
            id: this.record.id
            ,type: 'template'
            ,name: _('duplicate_of',{name: this.record.templatename})
            ,source: this.record.source
            ,static: this.record.static
            ,static_file: this.record.static_file
            ,category: this.record.category
        };
        var w = MODx.load({
            xtype: 'modx-window-element-duplicate'
            ,record: rec
            ,redirect: true
            ,listeners: {
                success: {
                    fn: function(r) {
                        var response = Ext.decode(r.a.response.responseText);
                        if (response.object.redirect) {
                            MODx.loadPage('element/'+ rec.type +'/update', 'id='+ response.object.id);
                        } else {
                            var t = Ext.getCmp('modx-tree-element');
                            if (t && t.rendered) {
                                t.refresh();
                            }
                        }
                    },scope:this}
                ,hide:{fn:function() {this.destroy();}}
            }
        });
        w.show(e.target);
    }
    ,delete: function(btn, e) {
        MODx.msg.confirm({
            text: _('template_delete_confirm')
            ,url: MODx.config.connector_url
            ,params: {
                action: 'Element/Template/Remove'
                ,id: this.record.id
            }
            ,listeners: {
                success: {
                    fn: function(r) {
                        MODx.loadPage('?');
                    },scope:this}
            }
        });
    }
    ,getButtons: function(config) {
        var config = config || {};

        var menu = [{
            text: _('duplicate') + ' <i class="icon icon-copy"></i>'
            ,id: 'modx-abtn-duplicate'
            ,handler: this.duplicate
            ,scope: this
        },{
            text: _('delete') + ' <i class="icon icon-trash-o"></i>'
            ,id: 'modx-abtn-delete'
            ,handler: this.delete
            ,scope: this
        },{
            text: _('help_ex') + ' <i class="icon icon-question-circle"></i>'
            ,id: 'modx-abtn-help'
            ,handler: MODx.loadHelpPane
        }]

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
                btn.fireEvent('arrowclick', btn, e);
                if (btn.arrowHandler) {
                    btn.arrowHandler.call(btn.scope || btn, btn, e);
                }
            }
            ,menu: {
                id: 'modx-abtn-menu-list'
                ,items: menu
            }
        },{
            text: _('cancel') + ' <i class="icon icon-times"></i>'
            ,id: 'modx-abtn-cancel'
        },{
            process: 'Element/Template/Update'
            ,text: _('save') + ' <i class="icon icon-check"></i>'
            ,id: 'modx-abtn-save'
            ,cls: 'primary-button'
            ,method: 'remote'
            // ,checkDirty: true
            ,keys: [{
                key: MODx.config.keymap_save || 's'
                ,ctrl: true
            }]
        }]

        return btns;
    }
});
Ext.reg('modx-page-template-update',MODx.page.UpdateTemplate);
