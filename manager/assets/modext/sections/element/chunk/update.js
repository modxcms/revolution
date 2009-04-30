/**
 * Loads the chunk update page
 * 
 * @class MODx.page.UpdateChunk
 * @extends MODx.Component
 * @param {Object} config An object of config properties
 * @xtype modx-page-chunk-update
 */
MODx.page.UpdateChunk = function(config) {
	config = config || {};
	Ext.applyIf(config,{
	   formpanel: 'modx-panel-chunk'
	   ,actions: {
            'new': MODx.action['element/chunk/create']
            ,edit: MODx.action['element/chunk/update']
            ,cancel: MODx.action['welcome']
        }
        ,buttons: [{
            process: 'update'
            ,text: _('save')
            ,method: 'remote'
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
            xtype: 'modx-panel-chunk'
            ,renderTo: 'modx-panel-chunk'
            ,chunk: config.id
            ,name: config.name
        }]
	});
	MODx.page.UpdateChunk.superclass.constructor.call(this,config);
};
Ext.extend(MODx.page.UpdateChunk,MODx.Component);
Ext.reg('modx-page-chunk-update',MODx.page.UpdateChunk);