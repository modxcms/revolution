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

    processResponse : function(response, node, callback, scope){
        var json = response.responseText;
        if (typeof(json) === 'string') {
            json = Ext.decode(json);
        }

        if (json['results'] !== undefined) {
            response.responseText = Ext.encode(json['results']);
        } else if (json['object'] !== undefined) {
            response.responseText = Ext.encode(json['object']);
        }
        if (json['success'] !== undefined && json['message'] !== undefined) {
            if (json['success'] == false) {
                if (typeof(json['message']) === 'object') {
                    var msg = [];
                    for (var i in json['message']) {
                        if (json['message'].hasOwnProperty(i)) {
                            msg.push(json['message'][i]);
                        }
                    }
                    json['message'] = msg.join("\n");
                }
                MODx.msg.alert(_('alert'), json['message']);
            }
        }

        Ext.tree.TreeLoader.prototype.processResponse.call(this, response, node, callback, scope);
    },

});
Ext.reg('modx-tree-treeloader',MODx.tree.TreeLoader);


