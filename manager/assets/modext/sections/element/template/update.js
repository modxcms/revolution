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
        ,buttons: [{
            process: 'element/template/update'
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
Ext.reg('modx-page-template-update',MODx.page.UpdateTemplate);
