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
        ,action: 'Resource/GetNodes'
        ,baseParams: {
            nohref: true
        }
    });
    MODx.tree.SimpleResource.superclass.constructor.call(this,config);
};
Ext.extend(MODx.tree.SimpleResource, MODx.tree.Tree, {
    /**
     * Renders the item text without any special formatting. The Resource/GetNodes processor already protects against XSS.
     */
    renderItemText: function(item) {
        return item.text;
    }
});
Ext.reg('modx-tree-resource-simple', MODx.tree.SimpleResource);
