/**
 * Loads the update snippet page
 *
 * @class MODx.page.UpdateSnippet
 * @extends MODx.Component
 * @param {Object} config An object of config properties
 * @xtype modx-page-snippet-update
 */
MODx.page.UpdateSnippet = function(config) {
    config = config || {};
    Ext.applyIf(config,{
        formpanel: 'modx-panel-snippet'
        ,buttons: [{
            process: 'element/snippet/update'
            ,text: _('save')
            ,id: 'modx-abtn-save'
            ,cls: 'primary-button'
            ,method: 'remote'
            // ,checkDirty: true
            ,keys: [{
                key: MODx.config.keymap_save || 's'
                ,ctrl: true
            }]
        },{
            text: _('delete')
            ,id: 'modx-abtn-delete'
            ,handler: this.delete
            ,scope: this
        },{
            text: _('duplicate')
            ,id: 'modx-abtn-duplicate'
            ,handler: this.duplicate
            ,scope: this
        },{
            text: _('cancel')
            ,id: 'modx-abtn-cancel'
        },{
            text: _('help_ex')
            ,id: 'modx-abtn-help'
            ,handler: MODx.loadHelpPane
        }]
        ,components: [{
            xtype: 'modx-panel-snippet'
            ,renderTo: 'modx-panel-snippet-div'
            ,snippet: config.record.id || MODx.request.id
            ,record: config.record || {}
        }]
    });
    MODx.page.UpdateSnippet.superclass.constructor.call(this,config);
};
Ext.extend(MODx.page.UpdateSnippet,MODx.Component, {
    duplicate: function(btn, e) {
        var rec = {
            id: this.record.id
            ,type: 'snippet'
            ,name: _('duplicate_of',{name: this.record.name})
        };
        var w = MODx.load({
            xtype: 'modx-window-element-duplicate'
            ,record: rec
            ,listeners: {
                success: {
                    fn: function(r) {
                        var response = Ext.decode(r.a.response.responseText);
                        MODx.loadPage('element/'+ rec.type +'/update', 'id='+ response.object.id);
                    },scope:this}
                ,hide:{fn:function() {this.destroy();}}
            }
        });
        w.show(e.target);
    }
    ,delete: function(btn, e) {
        MODx.msg.confirm({
            text: _('snippet_delete_confirm')
            ,url: MODx.config.connector_url
            ,params: {
                action: 'element/snippet/remove'
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
});
Ext.reg('modx-page-snippet-update',MODx.page.UpdateSnippet);
