MODx.tree.AsyncTreeNode = function(config) {
    config = config || {};
    config.id = config.id || Ext.id();
    Ext.applyIf(config,{


    });
    MODx.tree.AsyncTreeNode.superclass.constructor.call(this,config);

};
Ext.extend(MODx.tree.AsyncTreeNode,Ext.tree.AsyncTreeNode,{

});
Ext.reg('modx-tree-asynctreenode',MODx.tree.AsyncTreeNode);

