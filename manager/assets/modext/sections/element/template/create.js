/**
 * Loads the create template page
 * 
 * @class MODx.page.CreateTemplate
 * @extends MODx.Component
 * @param {Object} config An object of config properties
 * @xtype modx-page-template-create
 */
MODx.page.CreateTemplate = function(config) {
	config = config || {};
	Ext.applyIf(config,{
		formpanel: 'modx-panel-template'
		,actions: {
            'new': MODx.action['element/template/create']
            ,edit: MODx.action['element/template/update']
            ,cancel: MODx.action['welcome']
        }
		,buttons: [{
            process: 'create'
            ,text: _('save')
            ,method: 'remote'
            ,checkDirty: true
            ,keys: [{
                key: 's'
                ,alt: true
                ,ctrl: true
            }]
        },{
            process: 'cancel'
            ,text: _('cancel')
            ,params: {a:MODx.action['welcome']}
        }]
		,loadStay: true
        ,components: [{
            xtype: 'modx-panel-template'
            ,renderTo: 'modx-panel-template'
            ,template: 0
            ,category: config.category || 0
            ,name: ''
            ,baseParams: { action: 'create', category: MODx.request.category }
        }]
	});
	MODx.page.CreateTemplate.superclass.constructor.call(this,config);
};
Ext.extend(MODx.page.CreateTemplate,MODx.Component);
Ext.reg('modx-page-template-create',MODx.page.CreateTemplate);