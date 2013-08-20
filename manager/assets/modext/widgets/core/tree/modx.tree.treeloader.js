/**
 * Handles ajax loading of tree nodes.
 * Overrides native Ext implementation to allow
 * for in-node action tools
 *
 * @class MODx.tree.TreeLoader
 * @extends MODx.tree.Tree
 * @param {Object} config An object of options.
 * @xtype modx-tree-directory
 */
MODx.tree.TreeLoader = function(config) {
    config = config || {};
    config.id = config.id || Ext.id();
    Ext.applyIf(config,{

    });
    MODx.tree.TreeLoader.superclass.constructor.call(this,config);
};
Ext.extend(MODx.tree.TreeLoader,Ext.tree.TreeLoader,{

});
Ext.reg('modx-tree-treeloader',MODx.tree.TreeLoader);


