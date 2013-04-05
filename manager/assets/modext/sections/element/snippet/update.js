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
        ,actions: {
            'new': MODx.action['element/snippet/create']
            ,edit: MODx.action['element/snippet/update']
            ,cancel: MODx.action['welcome']
        }
        ,buttons: [{
            process: 'update'
            ,text: _('save')
            ,method: 'remote'
            ,checkDirty: true
            ,keys: [{
                key: MODx.config.keymap_save || 's'
                ,ctrl: true
            }]
        },'-',{
            text: _('duplicate')
            ,handler: this.duplicate
            ,scope: this
        },'-',{
            process: 'cancel'
            ,text: _('cancel')
            ,params:{a:MODx.action['welcome']}
        },'-',{
            text: _('help_ex')
            ,handler: MODx.loadHelpPane
        }]
        ,loadStay: true
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
                        MODx.loadPage(MODx.action['element/'+ rec.type +'/update'], 'id='+ response.object.id);
                    },scope:this}
                ,hide:{fn:function() {this.destroy();}}
            }
        });
        w.show(e.target);
    }
});
Ext.reg('modx-page-snippet-update',MODx.page.UpdateSnippet);