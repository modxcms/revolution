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
	    ,actions: {
            'new': MODx.action['element/chunk/create']
            ,edit: MODx.action['element/chunk/update']
            ,cancel: MODx.action['welcome']
        }
        ,buttons: [{
            process: 'create'
            ,text: _('save')
            ,method: 'remote'
            ,keys: [{
                key: 's'
                ,alt: true
                ,ctrl: true
            }]
        },{
            process: 'cancel', text: _('cancel'), params: {a:MODx.action['welcome']}
        }]
        ,loadStay: true
        ,components: [{
            xtype: 'modx-panel-chunk'
            ,renderTo: 'modx-panel-chunk'
            ,chunk: 0
            ,category: config.category || 0
            ,name: ''
            ,baseParams: { action: 'create', category: MODx.request.category }
        }]
	});
	MODx.page.CreateChunk.superclass.constructor.call(this,config);
};
Ext.extend(MODx.page.CreateChunk,MODx.Component);
Ext.reg('modx-page-chunk-create',MODx.page.CreateChunk);