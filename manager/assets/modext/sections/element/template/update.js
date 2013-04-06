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
		,actions: {
            'new': MODx.action['element/template/create']
            ,edit: MODx.action['element/template/update']
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
            ,params: {a:MODx.action['welcome']}
        },'-',{
            text: _('help_ex')
            ,handler: MODx.loadHelpPane
        }]
        ,loadStay: true
        ,components: [{
            xtype: 'modx-panel-template'
            ,renderTo: 'modx-panel-template-div'
            ,template: config.id
            ,record: config.record || {}
            ,baseParams: { action: 'update' ,id: config.id }
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
                        MODx.loadPage(MODx.action['element/'+ rec.type +'/update'], 'id='+ response.object.id);
                    },scope:this}
                ,hide:{fn:function() {this.destroy();}}
            }
        });
        w.show(e.target);
    }
});
Ext.reg('modx-page-template-update',MODx.page.UpdateTemplate);