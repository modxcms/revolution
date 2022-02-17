MODx.page.CreateSource = function(config) {
    config = config || {};
    Ext.applyIf(config,{
        formpanel: 'modx-panel-source'
        ,actions: {
            'new': 'Source/Create'
            ,edit: 'Source/Update'
            ,cancel: 'source'
        }
        ,buttons: [{
            process: 'Source/Create'
            ,reload: true
            ,text: _('save')
            ,id: 'modx-abtn-save'
            ,cls: 'primary-button'
            ,method: 'remote'
            ,keys: [{
                key: MODx.config.keymap_save || 's'
                ,ctrl: true
            }]
        },{
            process: 'cancel'
            ,text: _('cancel')
            ,id: 'modx-abtn-cancel'
            ,handler: function() {
                MODx.loadPage('source');
            }
        },{
            text: '<i class="icon icon-question-circle"></i>'
            ,id: 'modx-abtn-help'
            ,handler: MODx.loadHelpPane
        }]
        ,components: [{
            xtype: 'modx-panel-source'
            ,record: config.record || {}
            ,defaultProperties: config.defaultProperties || {}
        }]
    });
    MODx.page.CreateSource.superclass.constructor.call(this,config);
};
Ext.extend(MODx.page.CreateSource,MODx.Component);
Ext.reg('modx-page-source-create',MODx.page.CreateSource);
