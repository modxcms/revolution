/**
 * Loads the chunk create page
 *
 * @class MODx.page.CreateChunk
 * @extends MODx.Component
 * @param {Object} config An object of config properties
 * @xtype modx-page-chunk-create
 */
MODx.page.CreateChunk = function(config) {
    config = config || {};
    Ext.applyIf(config,{
        formpanel: 'modx-panel-chunk'
        ,buttons: [{
            process: 'Element/Chunk/Create'
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
            text: _('cancel')
            ,id: 'modx-abtn-cancel'
            ,handler: function() {
                MODx.loadPage('?');
            }
        },{
            text: '<i class="icon icon-question-circle"></i>'
            ,id: 'modx-abtn-help'
            ,handler: MODx.loadHelpPane
        }]
        ,components: [{
            xtype: 'modx-panel-chunk'
            ,renderTo: 'modx-panel-chunk-div'
            ,chunk: config.record.id || MODx.request.id
            ,record: config.record || {}
        }]
    });
    MODx.page.CreateChunk.superclass.constructor.call(this,config);
};
Ext.extend(MODx.page.CreateChunk,MODx.Component);
Ext.reg('modx-page-chunk-create',MODx.page.CreateChunk);
