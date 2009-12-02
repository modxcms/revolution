Ext.onReady(function() {
	MODx.load({
	   xtype: 'page-context-view'
	   ,key: MODx.request.key
	});
});

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
            'new': MODx.action['context/create']
            ,edit: MODx.action['context/update']
            ,'delete': MODx.action['context/delete']
            ,cancel: MODx.action['context/view']
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
	        ,params: {
	            a: MODx.action['context/create']
	        }
	    },{
	        process: 'edit'
	        ,text: _('edit')
	        ,params: {
	            a: 'context/update'
	            ,key: config.key
	        }
	    },'-',{
	        process: 'duplicate'
	        ,text: _('duplicate')
	        ,method: 'remote'
	        ,confirm: _('context_duplicate_confirm')
	    });
		if (config.key != 'web' && config.key != 'mgr') {
			b.push({
				process: 'delete',
				text: _('delete'),
				method: 'remote',
				confirm: _('confirm_delete_context')
			});
		}
		b.push('-',{
	        process: 'cancel'
	        ,text: _('cancel')
	        ,params: {
	            a: MODx.action['context']
	        }
	    });
        b.push('-',{
            text: _('help_ex')
            ,handler: MODx.loadHelpPane
        });
	    return b;
	}
});
Ext.reg('page-context-view',MODx.page.ViewContext);