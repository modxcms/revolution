/**
 * @class MODx.page.UpdateContext
 * @extends MODx.Component
 * @param {Object} config An object of config properties
 * @xtype modx-page-context-update
 */
MODx.page.UpdateContext = function(config) {
    config = config || {};
    Ext.applyIf(config,{
        formpanel: 'modx-panel-context'
        ,actions: {
            'new': 'context/create'
            ,edit: 'context/update'
            ,'delete': 'context/delete'
            ,cancel: 'context/view'
        }
        ,buttons: [{
            process: 'context/update'
            ,text: _('save')
            ,id: 'modx-abtn-save'
            ,cls:'primary-button'
            ,method: 'remote'
            // ,checkDirty: true
            ,keys: [{
                key: MODx.config.keymap_save || "s"
                ,ctrl: true
            }]
        },{
            text: _('duplicate')
            ,id: 'modx-abtn-duplicate'
            ,handler: this.duplicateContext
            ,scope: this
            ,hidden: !MODx.perm.new_context
        },{
            process: 'cancel'
            ,text: _('cancel')
            ,id: 'modx-abtn-cancel'
            ,params: {
                a: 'context'
            }
        },{
            text: _('help_ex')
            ,id: 'modx-abtn-help'
            ,handler: MODx.loadHelpPane
        }]
        ,components: [{
            xtype: 'modx-panel-context'
            ,context: MODx.request.key
        }]
    });
    MODx.page.UpdateContext.superclass.constructor.call(this,config);
};
Ext.extend(MODx.page.UpdateContext,MODx.Component, {
    duplicateContext: function(e) {
        var r = {
            key: MODx.request.key
            ,newkey: ''
        };
        var w = MODx.load({
            xtype: 'modx-window-context-duplicate'
            ,record: r
            ,listeners: {
                'success': {fn:function() {
                    var tree = Ext.getCmp('modx-resource-tree');
                    if (tree) {
                        tree.refresh();
                    }
                },scope:this}
            }
        });
        w.show();
    }
});
Ext.reg('modx-page-context-update',MODx.page.UpdateContext);
