/**
 * Loads the TV update page
 *
 * @class MODx.page.UpdateTV
 * @extends MODx.Component
 * @param {Object} config An object of config properties
 * @xtype modx-page-tv-update
 */
MODx.page.UpdateTV = function(config) {
	config = config || {};
	Ext.applyIf(config,{
		formpanel: 'modx-panel-tv'
        ,buttons: [{
            process: 'element/tv/update'
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
            xtype: 'modx-panel-tv'
            ,renderTo: 'modx-panel-tv-div'
            ,tv: config.record.id || MODx.request.id
            ,record: config.record || {}
        }]
    });
    MODx.page.UpdateTV.superclass.constructor.call(this,config);
};
Ext.extend(MODx.page.UpdateTV,MODx.Component, {
    duplicate: function(btn, e) {
        var rec = {
            id: this.record.id
            ,type: 'tv'
            ,name: _('duplicate_of',{name: this.record.name})
            ,caption: _('duplicate_of',{name: this.record.caption})
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
});
Ext.reg('modx-page-tv-update',MODx.page.UpdateTV);
