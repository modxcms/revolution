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
            'new': 'context/create'
            ,'edit': 'context/update'
            ,'delete': 'context/delete'
            ,'cancel': 'context/view'
        }
        ,buttons: this.getButtons()
	});
	MODx.page.ViewContext.superclass.constructor.call(this,config);
};
Ext.extend(MODx.page.ViewContext,MODx.Component,{	
	getButtons: function(config) {
		var b = [];
	    b.push({
	        process: 'create'
	        ,text: _('new')
	        ,id: 'modx-abtn-new'
	        ,params: {
	            a: 'context/create'
	        }
	    },{
	        process: 'edit'
	        ,text: _('edit')
	        ,id: 'modx-abtn-edit'
	        ,params: {
	            a: 'context/update'
	            ,key: config.key
	        }
	    },{
	        process: 'duplicate'
	        ,text: _('duplicate')
	        ,id: 'modx-abtn-duplicate'
	        ,method: 'remote'
	        ,confirm: _('context_duplicate_confirm')
	    });
		if (config.key != 'web' && config.key != 'mgr') {
			b.push({
				process: 'delete'
				,text: _('delete')
				,id: 'modx-abtn-delete'
				,method: 'remote'
				,confirm: _('confirm_delete_context')
			});
		}
		b.push({
	        process: 'cancel'
	        ,text: _('cancel')
	        ,id: 'modx-abtn-cancel'
	        ,params: {
	            a: 'context'
	        }
	    });
        b.push({
            text: _('help_ex')
            ,id: 'modx-abtn-help'
            ,handler: MODx.loadHelpPane
        });
	    return b;
	}
});
Ext.reg('page-context-view',MODx.page.ViewContext);