/**
 * Loads the resource data page
 * 
 * @class MODx.page.ResourceData
 * @extends MODx.Component
 * @param {Object} config An object of config properties
 * @xtype modx-page-resource-data
 */
MODx.page.ResourceData = function(config) {
	config = config || {};
	Ext.applyIf(config,{
		form: 'modx-resource-data'
		,actions: {
            'new': MODx.action['resource/create']
            ,edit: MODx.action['resource/update']
            ,cancel: MODx.action['welcome']
        }
        ,buttons: [{
            process: 'edit'
            ,text: _('edit')
            ,params: { a: MODx.action['resource/update'] }
        },'-',/*{
            process: 'duplicate'
            ,text: _('duplicate')
            ,method: 'remote'
            ,confirm: _('resource_duplicate_confirm')
        },{
            process: 'delete'
            ,text: _('delete')
            ,method: 'remote'
            ,confirm: _('resource_delete_confirm')
            ,refresh: {
            	tree: 'modx_resource_tree'
            	,node: config.ctx+'_'+config.id
            }
        },'-',*/{
            process: 'cancel'
            ,text: _('cancel')
            ,params: { a: MODx.action['welcome'] }
        }]
        ,components: [{
            xtype: 'modx-panel-resource-data'
            ,renderTo: 'modx-panel-resource-data'
            ,resource: config.id
            ,context: config.ctx
            ,class_key: config.class_key
            ,pagetitle: config.pagetitle
        }]
	});
	MODx.page.ResourceData.superclass.constructor.call(this,config);	
};
Ext.extend(MODx.page.ResourceData,MODx.Component);
Ext.reg('modx-page-resource-data',MODx.page.ResourceData);