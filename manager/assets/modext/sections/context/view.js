/**
 * @class MODx.page.ViewContext
 * @extends MODx.Component
 * @param {Object} config An object of configuration properties
 * @xtype page-context-view
 */
MODx.page.ViewContext = function(config) {
    config = config || {};
    Ext.applyIf(config,{
        form: 'context_data'
        ,actions: {
            'new': 'Context/Create'
            ,'edit': 'Context/Update'
            ,'delete': 'context/delete'
            ,'cancel': 'context/view'
        }
        ,buttons: this.getButtons()
    });
    MODx.page.ViewContext.superclass.constructor.call(this,config);
};
Ext.extend(MODx.page.ViewContext,MODx.Component,{
    getButtons: function(config) {
        var buttons = [{
            process: 'create'
            ,text: _('create')
            ,id: 'modx-abtn-new'
            ,params: {
                a: 'Context/Create'
            }
        },{
            process: 'edit'
            ,text: _('edit')
            ,id: 'modx-abtn-edit'
            ,params: {
                a: 'Context/Update'
                ,key: config.key
            }
        },{
            process: 'duplicate'
            ,text: _('duplicate')
            ,id: 'modx-abtn-duplicate'
            ,method: 'remote'
            ,confirm: _('context_duplicate_confirm')
        },{
            process: 'cancel'
            ,text: _('cancel')
            ,id: 'modx-abtn-cancel'
            ,params: {
                a: 'context'
            }
        }];
        if (config.key != 'web' && config.key != 'mgr') {
            buttons.push({
                process: 'delete'
                ,text: '<i class="icon icon-trash-o"></i>'
                ,id: 'modx-abtn-delete'
                ,method: 'remote'
                ,confirm: _('confirm_delete_context')
            });
        }
        buttons.push({
            text: '<i class="icon icon-question-circle"></i>'
            ,id: 'modx-abtn-help'
            ,handler: MODx.loadHelpPane
        });
        return buttons;
    }
});
Ext.reg('page-context-view',MODx.page.ViewContext);
