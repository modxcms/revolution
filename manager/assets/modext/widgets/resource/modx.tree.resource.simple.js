/**
 * Generates a Simplified Resource Tree in Ext
 * 
 * @class MODx.tree.SimpleResource
 * @extends MODx.tree.Tree
 * @param {Object} config An object of options.
 * @xtype modx-tree-resource-simple
 */
MODx.tree.SimpleResource = function(config) {
	config = config || {};
	Ext.applyIf(config,{
		root_id: '0'
		,root_name: _('resources')
		,enableDrag: true
		,enableDrop: true
        ,baseParams: {
            action: 'getNodes'
            ,nohref: true
        }
	});	
	MODx.tree.SimpleResource.superclass.constructor.call(this,config);
};
Ext.extend(MODx.tree.SimpleResource,MODx.tree.Tree);
Ext.reg('modx-tree-resource-simple',MODx.tree.SimpleResource);